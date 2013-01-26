<?php
namespace Controller;
use \Debug;
use \Model\Exception as ExceptionModel;
use \View\View;
use \Exception\NotFoundException;

class Error extends Controller {
	
	function index()
	{
		$this->error404();
	}
	
	function error404()
	{
		Debug::exception(new NotFoundException(REQUESTED_PAGE));
		
		//We load the main page.
		$main = new Main();
		$main->index();
	}
    
    /*
     * This function is called when an exception is NOT catched and hereby terminates the script.
     * Here, we log the error and show a customizable error output (using a view).
     * 
     */
    function exception($e)
    {
		Debug::exception($e);
		
		$this->HTTPReturnCode(500);
		$view = new View('error');
		$view->member = null;
		$view->render();
		return true;
	}
}

?>
