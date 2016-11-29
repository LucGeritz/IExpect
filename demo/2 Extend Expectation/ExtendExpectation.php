<?php
/**
* example how to add your own check
* 
*   The way to do this is by extending the Expectation class
*   
*   check-type of methods have to 
*   - do the check, maybe taking the setting of $this->caseSensitive in account 
*   - modify the result (to make not() work and future result modifiers)
*   - pass the modified result to the finalize() method of the injected resulthandler. This handles the output and statistics
*   - return the modified result 
*    
*/
include('../../vendor/autoload.php');

class MoreExpectation extends Tigrez\IExpect\Expectation{

	/**
	* check: does assertion expression start with an X
	* 
	* @return
	*/
	public function startsWithX(){

		$extra = ''; // some extra info might appear to be neccesary
			
		if(is_string($this->expression)){
			// if we want to take case insensitivity in account we must 
			// do it ourself like I did here..
			if($this->caseSensitive){
				$ok = (strpos($this->expression, 'X')===0);
			}
			else{
				$ok = (strpos(strtolower($this->expression), 'x')===0);
			}
		}
		else{
			$ok = false;
			$extra = 'Assertion expression is not a string';
		}
		
		// all checks should have their result passed through modifyresult
		$ok = $this->modifyResult($ok);
		
		// all checks should call the finalize to update statistics and to show result
		$this->resultHandler->finalize($ok, $this->caller);
		
		// all checks should return the modified result
		return $ok;
	}
		
}

$I = new Tigrez\IExpect\Assertion();
// to use our own extension we have to tell the assertion like so:
$I->setExpectationClass('MoreExpectation');

// I Expect 'Hello' not to start with capital X
$I->expect('Hello')->not()->startsWithX();
// I Expect 'xylophone' not to start with capital X
$I->expect('xylophone')->not()->startsWithX();
// I Expect 'Xylophone' to start with capital x
$I->expect('Xylophone')->startsWithX();
// I Expect 'hello' not to start with a case insensitive X
$I->expect('Hello')->caseInSensitive()->not()->startsWithX();
// I Expect 'xerxes' to start with a case insensitive X
$I->expect('xerxes')->caseInSensitive()->startsWithX();
// I Expect 'Xerxes' to start with a case insensitive X
$I->expect('Xerxes')->caseInSensitive()->startsWithX();



