<?php
namespace Tigrez\IExpect;

class Assertion implements IOverallResultHandler{
	
	private $expectationClass;
	private $resultHandler;
	private $overallResultHandler;
	private $passed;
	private $failed;
	private $overallResult = true;

	public function getPassed(){
		return $this->passed;
	}
	
	public function getFailed(){
		return $this->failed;	
	}
	
	public function getTests(){
		return $this->failed + $this->passed;
	}
	
	public function getOverallResult(){
		return $this->failed == 0;
	}

	public function setOverallResultHandler(IOverallResultHandler $handler){
		$this->overallResultHandler = $handler;
	}
	
	public function setResultHandler(IResultHandler $handler){
		$this->resultHandler = $handler;
	}

	public function setExpectationClass($class){
		$this->expectationClass = $class;
	}
	
	/**
	* Prepare an expectation.
	* @param mixed expr expression
	* 
	* @return Expectation an initialized expectation
	*/	
	public function expect($expr){
		
		$backtrace=debug_backtrace(false); 
        
		$exp = new $this->expectationClass($expr);
		$this->resultHandler->setOverallResultHandler($this);
		$exp->setResultHandler($this->resultHandler);
		$exp->setCaller(new Caller($backtrace[0]['file'],$backtrace[0]['line']));
		
		return $exp;				
		
	}
	
	public function __construct(){
		
		// default resultshower can be overridden! before each expect()
		$this->resultHandler = new ResultHandler();
		// default overallResultHandler is this object itself, can be overridden 
		$this->overallResultHandler = $this;
		// default expectation is the Expectation base class
		$this->expectationClass = 'Tigrez\IExpect\Expectation';
		$this->passed = 0;
		$this->failed = 0;
	}

	// IOverallResultHandler implementation
	public function incPassed(){
		$this->passed++;		
	}
	
	public function incFailed(){
		$this->failed++;
	}
	
}