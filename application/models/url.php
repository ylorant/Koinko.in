<?php
namespace Model;

class URL extends Model
{
	public $id = -1;
	public $keyword = '';
	public $url = '';
	public $clicks = 0;
	public $ip = '';
	
	//Virtual property
	public $user; //Bound to user object
	
	public function __init($data = null)
	{
		if(is_int($data))
			$this->load($data);
		elseif(is_array($data))
		{
			foreach($data as $key => $value)
				$this->$key = $value;
		}
	}
	
	public function __set($key, $value)
	{
		if(isset($this->$key))
			$this->$key = $value;
	}
	
	public function load($id)
	{
		$this->prepare('SELECT id, url, keyword, ip, clicks FROM urls WHERE id = ?');
		$this->execute($id);
		
		$data = $this->fetch();
		
		if(!empty($data))
		{
			$this->__init($data);
			return true;
		}
		else
			return false;
	}
	
	public function loadFromKeyword($keyword)
	{
		$this->prepare('SELECT id, url, keyword, ip, clicks FROM urls WHERE keyword = ?');
		$this->execute($keyword);
		
		$data = $this->fetch();
		
		if(!empty($data))
		{
			$this->__init($data);
			return true;
		}
		else
			return false;
	}
	
	public function loadFromURL($url)
	{
		$this->prepare('SELECT id, url, keyword, ip, clicks FROM urls WHERE url = ?');
		$this->execute($url);
		
		$data = $this->fetch();
		
		if(!empty($data))
		{
			$this->__init($data);
			return true;
		}
		else
			return false;
	}
	
	public function save()
	{
		if($this->id != -1)
			return $this->update();
		else
			return $this->create();
	}
	
	public function create()
	{
		$this->prepare('INSERT INTO urls (url, keyword, ip) VALUES(?, ?, ?)');
		$ret = $this->execute($this->url, $this->keyword, $this->ip);
		
		return $ret;
	}
	
	public function update()
	{
		$this->prepare('UPDATE urls SET url = ?, keyword = ?, ip = ?, clicks = ? WHERE id = ?');
		$ret = $this->execute($this->url, $this->keyword, $this->ip, $this->clicks, $this->id);
		
		return $ret;
	}
}

?>
