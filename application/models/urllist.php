<?php
namespace Model;
use \Iterator;
use \Countable;

class URLList extends Model implements Iterator, Countable
{
	private $list = array();
	
	public function getFromIP($ip)
	{
		$this->prepare("SELECT id, url, keyword, ip, clicks FROM urls WHERE ip = ?");
		$this->execute($ip);
		
		$data = $this->fetchAll();
		
		if(empty($data))
			return 0;
		else
		{
			$this->list = array();
			foreach($data as $line)
				$this->list[] = new URL($line);
			
			return count($this->list);
		}
	}
	
	/*
	 * Iterator interface implementation functions
	 */
	public function current() { return current($this->list); }
	public function count() { return count($this->list); }
	public function key() { return key($this->list); }
	public function next() { next($this->list); }
	public function rewind() { reset($this->list); }
	public function valid() { return current($this->list) !== FALSE; }
	public function toArray() { return $this->list; }
}
