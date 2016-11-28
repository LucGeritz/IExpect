<?php
namespace IExpect;

class Runner{
	
	private $configFile;
	private $initOk = false;
	private $params;
	private $testFiles=array();
	private $assertion;

	private function gatherTestFiles(){
		
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
	
	private function init(){
		
		$ok = false;	
		
		if(file_exists($this->configFile)){
			$this->params = include($this->configFile);
			
			if(isset($this->params['paths'])){
				
				if(!isset($this->params['mask'])){
					$this->paths['mask'] = 'test*.php';
				}
				
				$ok = $this->gatherTestFiles();
	
			}	
		}
		
		return $ok;
	}
	
	public function getConfigFile(){
		return $this->configFile;	
	}
	
	/**
	* Add an additional autoloader, maybe to locate the 
	* classes your testclasses test
	* 
	* @param function $function the autoload function
	*/
	public function addAutoloader($function){
		
		spl_autoload_register($function);		
	
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
				
		if($this->init()){
		
			$this->assertion = new Assertion();
		
			foreach($this->testFiles as $file){
			
				require($file);
			
				$class = basename($file, '.php');
			
				$test = new $class();
				$test->run($this->assertion);
		
			}
			
			return true;
						
		}
		else{
			return false;
		}
	}
	
	
	public function __construct($configFile = 'Runner.cfg.php'){
		$this->configFile = $configFile;		
	}
	
}
	
	
		
