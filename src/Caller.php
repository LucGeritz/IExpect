<?php
namespace Tigrez\IExpect;

class Caller{
	
	private $line;
	private $file;

	public function getLine(){
		return $this->line;
	}	
	
	public function getFile(){
		return $this->file;
	}
	
	public function __construct($file,$line){
		$this->line = $line;
		$this->file = $file;
	}
	
}
