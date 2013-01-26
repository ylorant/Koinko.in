<?php

class Debug
{
	private static $instance = null;
	private $variables = array();
	private $exceptions = array();
	private $terminated = false;
	
	public static function getInstance()
	{
		if(empty(self::$instance))
			self::$instance = new self();
		
		return self::$instance;
	}
	
	public static function __callStatic($name, array $arguments)
	{
		if(method_exists('Debug', 's_'.$name))
		{
			$name = 's_'.$name;
			$self = self::getInstance();
			return call_user_func_array(array($self, $name), $arguments);
		}
	}
	
	public function s_show($var, $name = null)
	{
		if($name === null)
			$this->variables[] = $var;
		else
			$this->variables[$name] = $var;
	}
	
	public function s_exception($e)
	{
		$this->exceptions[] = $e;
	}
	
	public function s_countExceptions()
	{
		return count($this->exceptions);
	}
	
	public function s_countVariables()
	{
		return count($this->exceptions);
	}
	
	public function s_terminated()
	{
		return $this->terminated;
	}
	
	public function s_terminate()
	{
		$this->terminated = true;
	}
	
	public function __toString()
	{
		
		$str =	" --- Application debug trace ---\n\n".
				"+------------+\n".
				"| Variables: |\n".
				"+------------+\n\n";
		
		if(!empty($this->variables))
		{
			ob_start();
			foreach($this->variables as $k => $var)
			{
				if(!is_int($k))
				{
					echo '['.$k."]:\n\t";
					
					$str .= ob_get_contents();
					ob_clean();
					echo var_dump($var);
					$str .= trim(str_replace("\n", "\n\t", ob_get_contents()));
					ob_clean();
				}
				else
					echo var_dump($var);
				
				echo "\n\n";
			}
			$str .= ob_get_clean();
		}
		else
			$str .= "None.\n";
		
		$str .=	"\n".
				"+-------------+\n".
				"| Exceptions: |\n".
				"+-------------+\n\n";
		
		if(!empty($this->exceptions))
		{
			foreach($this->exceptions as $var)
				$str .= str_replace("\n", "\n|\t", $var)."\n+----------\n\n";
		}
		else
			$str .= "None.\n";
		
		return $str;
	}
}
