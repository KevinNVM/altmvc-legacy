<?php

function requireAllFileInFolder($folder)
{
    $folder = substr($folder, -1) === '/'
        ? $folder :
        $folder .= '/';

    $controllersFolder = $folder;
    $dir = opendir($controllersFolder);

    while ($file = readdir($dir)) {
        // Exclude directories and only include PHP files
        if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
            require_once $controllersFolder . $file;
        }
    }

    closedir($dir);
}


// Require all of the neccesary classes for the App

requireAllFileInFolder(dirname(__FILE__) . '/../database');
requireAllFileInFolder(dirname(__FILE__) . '/../core');
requireAllFileInFolder(dirname(__FILE__) . '/../app/Controllers');