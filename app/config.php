<?php
require_once 'vendor/autoload.php';

session_start();


// URL
define("PORT", "7879");
define('URL', 'http://simple-cart-php.test/');
define('BASEPATH', 'http://simple-cart-php.test/');

// PATH FOLDERS
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', getcwd() . DS);
define('APP', ROOT . 'app' . DS);
define('INCLUDES', ROOT . 'includes' . DS);
define('VIEWS', ROOT . 'views' . DS);

define('ASSETS', URL . 'assets/');
define('CSS', ASSETS . 'css/');
define('IMAGES', ASSETS . 'images/');
define('JS', ASSETS . 'js/');
define('PLUGINS', ASSETS . 'plugins/');

// include functions
require_once APP . 'functions.php';
