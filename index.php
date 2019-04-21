<?php

use Hea\Router\Router;
use Hea\Router\Request;

// Session start
session_start();

// Add helper
if (file_exists('src/helpers.php')) {
    require_once 'src/helpers.php';
}
// Init autoloader
spl_autoload_register('autoloader');

// Init env variables
init_env_file();

// Init router
$router = new Router(new Request);
if (file_exists('route.php')) {
    require_once 'route.php';
}
