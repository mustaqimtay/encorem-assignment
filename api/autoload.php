<?php
include_once('inc/phpSettings.php');
include_once('inc/database/cdb.php');
include_once('inc/functions.php');

spl_autoload_register(function ($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filename = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    if (is_readable($filename)) {
    
        require_once($filename);
    }
});
?>