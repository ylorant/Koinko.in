<?php
namespace Model;

class ModelNoDB
{
	public function __construct()
	{
		call_user_func_array(array($this, '__init'), func_get_args());
	}
	
	public function __init()
	{
		
	}
	
	public static function serialize($object)
	{
		$data = array();
		foreach($object as $name => $value)
		{
			if($name[0] != '_')
			{
				if(is_object($value))
					$data[$name] = 'object/'.get_class($value).':'.Model::serialize($value);
				else
					$data[$name] = $value;
			}
		}
		
		return serialize($data);
	}
	
	public static function unserialize($data, $object)
	{
		foreach(unserialize($data) as $name => $value)
		{
			if(strpos($value,'/'))
			{
				$parts = explode(':', $value, 2);
				$parts[0] = explode('/', $parts[0], 2);
				if($parts[0][0] == 'object')
				{
					$objName = $parts[0][1];
					$value = new $objName();
					Model::unserialize($parts[1], $value);
				}
			}
			
			$object->$name = $value;
		}
		
		return $object;
	}
	
	public function to_bool($val)
	{
	    return !!$val;
	}
	
	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}
	
	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}
	
	public function to_datetime($val)
	{
	    return date('Y-m-d H:i:s', $val);
	}
}

?>
