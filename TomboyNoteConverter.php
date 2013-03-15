<?php
/**
 * Tomboy Notes Converter - convert .note to .txt
 * 
 * @author Siyang Bi
 */
$tomboyFolder = '/Users/bigmonkey/tomboy';
$targetFolder = '/Users/bigmonkey/sissy';

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath($tomboyFolder)));

$objects = new RegexIterator($objects, '/^.+\.note$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($objects as $name => $object) {
    convertNote($name, $targetFolder);
}


function convertNote($file, $targetFolder)
{
    $xmlFile = file_get_contents($file) or die('Cant open note');
    $xml = simplexml_load_string($xmlFile);

    $title = '';
    $content = '';

    foreach ($xml as $node => $value) {
        if ($node == 'title') {
            $title = str_replace("/", "-", $value);
        }

        if ($node == 'text') {
            foreach ($xml->text->children() as $k => $v) {
                if ($k == 'note-content') {
                    $content = $v;
                    break;
                }
            }
        }
    }

    if ($title) {
        file_put_contents(realpath($targetFolder) . '/' . $title . '.txt', $content);
    } else {
        if ($content) {
            file_put_contents(realpath($targetFolder) . '/unknown.txt', $content);
        } else {
            echo 'Skip to convert file ' . $file . "\n\n";
        }

    }
}
