<?php
namespace Controller;
use \View\View;
use \Model\URL, \Model\URLList;
use \Plugin\JSON;
use \Plugin\Security;
use \Debug;

class Main extends Controller
{
	public function index($error = null)
	{
		$template = new View('main');
		$template->render();
	}
	
	public function shorten()
	{
		global $config;
		
		$json = new JSON();
		$json->result = false;
		
		if(empty($_POST["url"]))
			$json->message = "You did not specify an URL to shorten";
		elseif(!filter_var($_POST["url"], FILTER_VALIDATE_URL) || strpos($_POST["url"], "http://koinko.in") === 0 || strpos($_POST["url"], "http://www.koinko.in") === 0)
			$json->message = "This is not a valid URL.";
		else
		{
			if(!Security::checkURL($_POST["url"]))
				$json->message = "This URL has been detected as a dangerous URL by our system.";
			else
			{
				$url = new URL();
				if(!$url->loadFromURL($_POST['url']))
				{
					$keyword = null;
					do
					{
						for($i = 0; $i < 5; $i++)
							$keyword .= substr(str_shuffle("01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1);
						$url->id = -1;
						$url->loadFromKeyword($keyword);
					}
					while($url->id != -1);
					
					$url->keyword = $keyword;
					$url->url = $_POST["url"];
					$url->ip = $this->getRealIPAddress();
					$url->save();
				}
				
				$json->url = $config['url_prefix'].$url->keyword;
				$json->result = true;
			}
		}
		
		Debug::show($json);
		
		//Debug::exception(new Exception("Oops !"));
		
		Debug::terminate();
		if($config['debug'])
			$json->debug = (string) Debug::getInstance();
		
		echo $json;
	}
	
	public function redirect($url)
	{
		$urlModel = new URL();
		
		$this->HTTPReturnCode(301);
		Debug::terminate();
		if($urlModel->loadFromKeyword($url))
		{
			$urlModel->clicks++;
			$urlModel->save();
			header('Location: '. $urlModel->url);
		}
		else
			header('Location: home');
		
	}
	
	public function mylinks()
	{
		$json = new JSON();
		$json->result = true;
		
		$urlList = new URLList();
		$urlList->getFromIP($this->getRealIPAddress());
		
		$view = new View('mylinks');
		$view->set('list', $urlList->toArray());
		$json->html = $view->render(false);
		
		Debug::terminate();
		if($config['debug'])
			$json->debug = (string) Debug::getInstance();
		
		echo $json;
	}
	
	private function getRealIPAddress()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if(!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(!empty($_SERVER['HTTP_X_FORWARDED']))
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		else if(!empty($_SERVER['HTTP_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(!empty($_SERVER['HTTP_FORWARDED']))
			$ip = $_SERVER['HTTP_FORWARDED'];
		
		return $ip;
	}
	
	private function incrementURLIndex()
	{
		global $config;
		
		$url = trim(file_get_contents($config['last_url_file']));
		
		$found = false;
		$i = -1;
		while(!$found)
		{
			$char = ord(substr($url, $i, 1));
			
			$char++;
			if($char == 58)
			{
				$char = 65;
				$found = true;
			}
			elseif($char == 91)
			{
				$char = 97;
				$found = true;
			}
			elseif($char == 123)
				$char = 48;
			else
				$found = true;
			
			if(strlen($url) + $i >= 0)
				$url[strlen($url) + $i] = chr($char);
			else
			{
				$url = chr($char). $url;
				$found = true;
			}
			
			$i--;
		}
		
		file_put_contents($config['last_url_file'], $url);
		
		return $url;
	}
}

?>
