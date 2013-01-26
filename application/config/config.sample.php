<?php 

// Base URL including trailing slash, but without domain (e.g. for http://localhost/ use "/", and for http://localhost/koinkoin/ use "/koinkoin/")
$config['base_url'] = '/'; 

$config['default_controller'] = 'Controller\\Main'; // Default controller to load
$config['default_controller_file'] = 'main.php'; // Default controller file to load
$config['error_controller'] = 'Controller\\Error'; // Controller used for errors (e.g. 404, 500 etc)
$config['error_controller_file'] = 'error.php'; // Controller file used for errors (e.g. 404, 500 etc)
$config['exception_handler'] = 'exception'; //Exception handler method (from error_controller)
$config['debug'] = true; //Debug activation
$config['hash_salt'] = ''; //Hash salt for passwords
$config['google_phishing_api_key'] = ''; //Key for Google Phishing Lookup API
$config['url_prefix'] = "http://koinko.in/";

// Routes
// These reproduce the way of routing addresses coming from older versions of PIP
$config['routes'] = array(
	new Route(Route::TYPE_STATIC, '', 'Main::index'),
	new Route(Route::TYPE_STATIC, 'home', 'Main::index'),
	new Route(Route::TYPE_STATIC, 'shorten', 'Main::shorten'),
	new Route(Route::TYPE_STATIC, 'mylinks', 'Main::mylinks'),
	new Route(Route::TYPE_DYNAMIC, array('{string}'), 'Main::redirect'),
);

define('DB_ENGINE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', '');
define('DB_PW', '');
define('DB_DBNAME', '');
?>
