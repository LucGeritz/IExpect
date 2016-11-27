<?php
namespace IExpect;

/**
* Default implementation for IResult
*/
class ResultHandler implements IResultHandler{
	
	private $overallResultHandler;
	
	private function getSrcLine(Caller $caller){

		$lines = file($caller->getFile());
		$line = $lines[$caller->getLine()-1];	
		$line=str_replace("\n","",$line);
		return $line;
		
	}	
	
	private function prefix(Caller $caller){
		$str = "\n %s Line %4d";
		return sprintf($str,basename($caller->getFile(),'.php'), $caller->getLine());
		
	}

	// IResultHandler implementation
	public function setOverallResultHandler(IOverallResultHandler $handler){
		$this->overallResultHandler = $handler;	
		
	}
	
	public function finalize($ok, Caller $caller, $extra=''){
		
		$prefix = $this->prefix($caller);
		
		if($ok){
			$msg = $prefix . ' OK';
		}
		else{
			$line = $this->getSrcLine($caller);
			$msg = $prefix . " NOK **** ".trim($line);
		}
	
		echo $msg.' '.$extra;
		
		if($ok) {
			$this->overallResultHandler->incPassed();
		}
		else{
			$this->overallResultHandler->incFailed();
		}
	}	
}