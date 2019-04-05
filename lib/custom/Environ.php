<?php

// Set up some Docker related constants
define('DOCKER_HOST', '127.0.0.1');
define('DB_SERVICE', 'mysql');
define('WEB_SERVICE', 'apache2');
define('APP_SERVICE', 'workspace');
define('MAIL_SERVICE', 'maildev');

define('GIT_ROOT', getcwd());

define('LARADOCK_ROOT', 'laravel' . DIRECTORY_SEPARATOR . 'laradock');
define('LARADOCK_USER', 'laradock');

$GLOBALS['dirstack'] = [];



/*
TODO set any defines from the docke-compose config
try {
  $config = parse_ini_file(LARADOCK_ROOT . DIRECTORY_SEPARATOR . '.env');
} catch(Exception $e) {
    printf("\nCould not process the .env file. Please ensure a valid docker-compose compatible .env file exists in the same directory as the Phakefile. (error - %s, callstack - %s)",
      $e->getMessage(),
      $e->getTraceAsString());
}
*/
