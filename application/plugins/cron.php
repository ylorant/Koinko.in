<?php
namespace Plugin;
use \DateTime;

class Cron
{
	private static $instance;
	
	public function __construct($configfile = './cron.ini')
	{
		self::getTab($configfile);
	}
	
	public static function s_getTab($configfile = './cron.ini')
	{
		if(self::$instance == null)
			self::$instance = new CronTab($configfile);
		
		return self::$instance;
	}
	
	public static function s_setTab($instance)
	{
		self::$instance = $instance;
	}
	
	public static function __callStatic($name, $args)
	{
		if($name != 'getTab' && $name != 'setTab')
		{
			$self = self::$instance;
			return call_user_func_array(array($self, $name), $args);
		}
		else
			return self::{'s_'.$name}($args[0]);
	}
}

class CronTab
{
	private $config;
	private $data = array();
	
	public function __construct($configfile = './cron.ini')
	{
		$this->config = $configfile;
		
		$this->load();
	}
	
	public function add(CronTask $task)
	{
		$this->data[$task->name] = $task;
		$this->save();
	}
	
	public function save()
	{
		$file = fopen($this->config, 'w+');
		
		foreach($this->data as $task)
			fputs($file, serialize($task)."\n");
		
		fclose($file);
		return true;
	}
	
	public function load()
	{
		if(!is_file($this->config))
			return;
		
		$file = explode("\n", trim(file_get_contents($this->config)));
		$data = array();
		foreach($file as $task)
		{
			if(!empty($task))
			{
				$data = unserialize($task);
				$this->data[$data->name] = $data;
			}
		}
	}
	
	public function execute($database = null)
	{
		$now = new DateTime();
		
		foreach($this->data as $el)
		{
			if($el->time <= $now)
			{
				$el->call($database);
				unset($this->data[$el->name]);
				$this->save();
			}
		}
	}
}

class CronTask
{
	private $callback;
	private $name;
	private $time;
	private $parameters;
	
	public function __construct($time, $callback, $parameters = array(), $name = null)
	{
		if($name === null)
			$name = uniqid();
		
		if(!is_array($parameters))
			$parameters = array($parameters);
		
		if(!is_a($time, 'DateTime'))
		{
			$timeObject = new DateTime();
			
			if(is_numeric($time))
				$timeObject->setTimestamp($time);
			else
				$timeObject->setTime($time);
			
			$time = $timeObject;
		}
		
		$this->callback = $callback;
		$this->time = $time;
		$this->name = $name;
		$this->parameters = $parameters;
	}
	
	public function __get($name)
	{
		if(isset($this->$name))
			return $this->$name;
		else
			return NULL;
	}
	
	public function call()
	{
		call_user_func_array($this->callback, $this->parameters);
	}
}

class SQLCronTask extends CronTask
{
	private $query;
	
	public function __construct($time, $query, $parameters = array(), $callback = null, $name = null)
	{
		parent::__construct($time, $callback, $parameters, $name);
		$this->query = $query;
	}
	
	public function call($database = null)
	{
		$query = $database->prepare($this->query);
		$query->execute($parameters);
		
		if($this->callback != null)
			call_user_func_array($this->callback, $query->fetchAll);
	}
}
