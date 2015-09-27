<?php

declare(strict_types = 1);

// phpLINQ
spl_autoload_register(function($className) {
    $classFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                          str_replace("\\", DIRECTORY_SEPARATOR, $className) .
                          '.php');

    if (false !== $classFile) {
        require_once $classFile;
    }
});

// tests
require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestCaseBase.php';
