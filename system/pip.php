<?php
namespace Controller;
use \Exception, \Debug;
use \View\View;

function pip()
{
	global $config;
    
    // Set our defaults
    $controller = $config['default_controller'];
    $action = 'index';
    $url = '';
    
	// Get request url and script url
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
    
	// Get our url path and trim the / of the left and the right
	if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
	
	define('REQUESTED_PAGE', $url);
	
	//         //
	// Routing //
	//         //
	
    if(isset($config['routes']) && !empty($config['routes']))
    {
		foreach($config['routes'] as $route)
		{
			if($route->match($url))
			{
				$call = explode('::', $route->mapping());
				
				if(strpos($call[0], 'Controller\\') === FALSE)
					$call[0] = '\\Controller\\'.$call[0];
				
				$class = $call[0];
				$func = $call[1];
				$obj = new $class();
				
				if($route->callback !== null)
				{
					$cb = $route->callback;
					$cb($obj);
				}
				
				call_user_func_array(array($obj, $func), $route->matches);
				die();
			}
		}
	}
    
    // Error handling (404)
	$controller = $config['error_controller'];
	$action = 'index';
	
	// Create object and call method
	$obj = new $controller;
    call_user_func_array(array( $obj, $action), $route->matches);
    
    die();
}

?>
