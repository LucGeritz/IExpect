<?php
namespace IExpect;

class Runner{
	
	private $configFile;
	private $initOk = false;
	private $params;
	private $testFiles=array();
	private $assertion;
	
	public function getConfigFile(){
		return $this->configFile;	
	}
	
	/**
	* Add an additional autoloader, maybe to locate the classes your testclasses test
	* @param function $function the autoload function
	*/
	public function addAutoloader($function){
		
		spl_autoload_register($function);		
	
	}
	
	public function wasInitOk(){
		return $this->initOk;
	}

	public function gatherTestFiles(){
		
		$ok=false;
		
		if(is_array($this->params['paths'])){
			foreach($this->params['paths'] as $path){
				
				$path = trim($path,'/').'/';
				
				$files = glob($path.$this->params['mask']);
				
				foreach($files as $file){
					$this->testFiles[] = $file;
				}	
			
			}
			$ok =true;			
			
		}
		return $ok; 	
	}	
	
	
	public function getTests(){
		return $this->assertion->getTests();
	} 
	
	public function getPassed(){
		return $this->assertion->getPassed();
		
	}	
	
	public function getFailed(){
		return $this->assertion->getFailed();
		
	}
	
	public function getResult(){
		return $this->assertion->getOverallResult();
		
	}
	
	public function start(){
		
		$this->assertion = new Assertion();
		
		foreach($this->testFiles as $file){
			
			require($file);
			
			$class = basename($file, '.php');
			
			$test = new $class();
			$test->run($this->assertion);
		
		}
						
	}
	
	public function __construct($configFile){
	
		if(empty($configFile)){
			$configFile = 'Runner.cfg.php'; 		
		}
		
		if(file_exists($configFile)){
	
			$this->params = include($configFile);
			
			if(isset($this->params['paths'])){
				
				if(!isset($this->params['mask'])){
					$this->paths['mask'] = 'test*.php';
				}
				
				$this->initOk = $this->gatherTestFiles();
	
			}	
		}
	}
	
}
	
	
		
