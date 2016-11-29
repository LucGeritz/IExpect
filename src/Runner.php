<?php
namespace Tigrez\IExpect;
/**
*  Class to run multiple tests in given folders
*  @author Luc Geritz<luc@tigrez.nl>
*/
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
	
	/**
	* return name config file.
	* @return string name of config file used
	*/
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
	
	/**
	* get number of total tests done
	* @return int nr of tests
	*/	
	public function getTests(){
		return $this->assertion->getTests();
	} 
	/**
	* get number of passed tests
	* @return int nr of passed tests
	*/	
	public function getPassed(){
		return $this->assertion->getPassed();
		
	}	
	/**
	* get number of failed tests
	* @return int nr of failed tests
	*/	
	public function getFailed(){
		return $this->assertion->getFailed();
		
	}
	
	/**
	* get overall result
	* @return boolean true 0 tests failed, false >0 tests failed.
	*/
	public function getResult(){
		return $this->assertion->getOverallResult();
	}
	
	/**
	* start test run
	* @return boolean true all tests ran, false init error
	*/
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
	
	
		
