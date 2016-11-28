<?php
namespace IExpect;

/**
* Represents the expectation
* 
* In function synopses I use the following terminology
* unmodified result: the result of an comparison before result-modifiers are applied. At the time of writing there's only one, not()
* assertion expression: the expression loaded in the assertion. This is $this->expression. In the example $I->expect($x)->equals(12) $x is the assertion expression   
* 
* @author Luc Geritz<luc@tigrez.nl>
*/
class Expectation{
	
	protected $expression = false;
	protected $not = false;
	protected $resultHandler = null;
	protected $caller = null;
	protected $caseSensitive = true;	
	
	/**
	* takes use of 'not' in account and maybe more in future
	* @param boolean $ok preliminary result
	* 
	* @return boolean final result
	*/
	protected function modifyResult($ok){
		if($this->not) $ok = !$ok;
		return $ok;	
	}

	protected function show($ok){
		
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
	* set case sensitivity to false for string comparison
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
	
	/**
	* Check if expression is equal
	* takes case insensitive in account? yes if both sides of expression are string
	*
	* @param mixed $expr 
	* 
	* @return unmodified true if passed expression is same as assertion expression
	*/
	public function equals($expr){
		
		$ok = $expr === $this->expression;
		if((!$this->caseSensitive) && is_string($this->expression) && is_string($expr)){
			$ok = (strtolower($expr) == strtolower($this->expression));			
		}
				
		$ok = $this->modifyResult($ok);
		$this->resultHandler->finalize($ok, $this->caller);
		return $ok;
	}
	
	/**
	* Check for substring in string
	* if the assertion expression is not a string unmodified result will always be false
	* takes case insensitive in account? yes
	* @param mixed $value the value to search for in assertion expression
	* 
	* @return boolean unmodified true
	*/
	public function contains($str){
		
		$extra='';
		$expr = $this->expression;
		
		if(is_string($expr)){
			
			if(!$this->caseSensitive){
				$expr = strtolower($this->expression);	
				$str = strtolower($str);
			}
		}
		else{
			$ok = false;
			$extra = 'Assertion expression is not a string';
		}
		$ok = $this->modifyResult(strpos($expr,$str) !== false);
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
	}
	
	/**
	* check: has array a given value.
	* if the assertion expression is not an array unmodified result will always be false
	* takes case insensitive in account? yes
	* 
	* @param mixed $value the value to check existance of
	* 
	* @return boolean unmodified true
	*/
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
			$extra = 'Note: Assertion expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
		
	}
	
	/**
	* check: has array given key.
	* if the assertion expression is not an array unmodified result will always be false
	* takes case insensitive in account? no
	* @param mixed $key the key to check existance of
	* 
	* @return boolean unmodified true
	*/
	public function hasKey($key){
		
		$extra = '';
		
		if(is_array($this->expression)){
			$ok = $this->modifyResult(array_key_exists($key,$this->expression));
		}
		else{
			$ok = $this->modifyResult(false);
			$extra = 'Note: Assertion expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
	}
	
	/**
	* Check: has array given key with given value.
	* if the assertion expression is not an array unmodified result will always be false
	* takes case insensitive in account? key no, value yes
	* @param mixed $key the key to check existance of
	* @param mixed $val the value to check in array
	* 
	* @return boolean unmodified true if array has key with value, false either key not there or key there but value different
	*/
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
			$extra = 'Note: Assertion expression is not an array';
		}
		
		$this->resultHandler->finalize($ok, $this->caller, $extra);
		
		return $ok;
	}
	
	public function __construct($expr){
		
		$this->expression = $expr;
		
	}
}