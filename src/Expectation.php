<?php
namespace IExpect;

/**
* Represents the expression 
*/
class Expectation{
	
	private $expression = false;
	private $not = false;
	private $resultHandler = null;
	private $caller = null;
	private $caseSensitive = true;	
	
	/**
	* takes use of 'not' in account and maybe more in future
	* @param boolean $ok preliminary result
	* 
	* @return boolean final result
	*/
	private function modifyResult($ok){
		if($this->not) $ok = !$ok;
		return $ok;	
	}

	private function show($ok){
		
		if($this->resultHandler){
			$this->setResultHandler($ok, $this->caller);
		}		
		else{
			throw new Exception('no IResultHandler injected in Expectation');
		}
		
	}
	
	
	public function setCaller(Caller $caller){
		$this->caller = $caller;
	}
	
	public function setResultHandler(IResultHandler $rs){
		$this->resultHandler = $rs;
	}
	
	/**
	* resultmodifier: negates result of expression
	*/
	public function not(){
		$this->not = true;
		return $this;
	}
	/**
	* resultmodifier: set case insensitivity for string comparison
	*/
	public function caseInSensitive(){
		
		$this->caseSensitive=false;
		return $this;	
		
	}	
	
	public function isNull(){
		
		$ok = $this->modifyResult(is_null($this->expression));
		
		$this->resultHandler->finalize($ok, $this->caller);
		return $ok;
		
	}

	public function isA($className){
	
		$ok = $this->modifyResult(is_a($this->expression,$className));
		
		$this->resultHandler->finalize($ok, $this->caller);
		
		return $ok; 		
	
	}
	
	public function isFalsy(){
		
		$ok = !$this->expression ? true : false;
		$ok = $this->modifyResult($ok);
		
		$this->resultHandler->finalize($ok, $this->caller);
		return $ok;
		
	}
	
	public function isTruthy(){
		
		$ok = $this->expression ? true : false;
		$ok = $this->modifyResult($ok);
		
		$this->resultHandler->finalize($ok, $this->caller);
		return $ok;
	}
	
	public function equals($expr){
		$ok = $expr === $this->expression;
		if($this->not) $ok = !$ok;
		$this->resultHandler->finalize($ok, $this->caller);
		return $ok;
	}
	
	public function contains($str){
		
		$expr = $this->expression;
		
		if(!$this->caseSensitive){
			$expr = strtolower($this->expression);	
			$str = strtolower($str);
		}
		
		$ok = $this->modifyResult(strpos($expr,$str) !== false);
		
		$this->resultHandler->finalize($ok, $this->caller);
		
		return $ok;
	}
	
	public function hasValue($val){
		
		$extra=''; 
		
		if(is_string($val)){
			if(!$this->caseSensitive){
				$val = strtolower($val);
			}
		}
		
		if(is_array($this->expression)){
			$ok = $this->modifyResult(in_array($val,$this->expression));
		}
		else{
			$ok = $this->modifyResult(false);
			$extra = 'Note: Expectation expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
		
	}
	
	// caseInsensitive does not work on key
	public function hasKey($key){
		
		$extra = '';
		
		if(is_array($this->expression)){
			$ok = $this->modifyResult(array_key_exists($key,$this->expression));
		}
		else{
			$ok = $this->modifyResult(false);
			$extra = 'Note: Expectation expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
	}
	
	// caseInsensitive does not work on key, does on value
	public function hasKeyValue($key, $val){
		
		$extra = '';
		
		if(is_array($this->expression)){
			$ok = false;
			if(array_key_exists($key, $this->expression)){
				$arrval = $this->expression[$key];
				if(!$this->caseSensitive){
					$arrval = strtolower($arrval);
					$val = strtolower($val);
				}
				$ok= $arrval === $val;
			}
			$ok = $this->modifyResult($ok);
		}
		else{
			$ok = $this->modifyResult(false);
			$extra = 'Note: Exceptation expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
	}
	
	public function __construct($expr){
		
		$this->expression = $expr;
		
	}
}