<?php
/**
* This is the test of IExpect itself.
* It is *not* an example of what a IEXpect testrunner should look like since in this script
* we'll let some tests fail on purpose. For good examples look into the src\demo folder!
*  
* @author Luc Geritz<luc@tigrez.nl>
*/
include '..\vendor\autoload.php';

define('OK', true);
define('NOK', false);

Tigrez\IExpect\Logo::show();

// Some classes to use in tests
class TestClass{
	public function double($int){
		return $int * 2;
	}
}

class Descendant extends TestClass{
	public function yeah(){
		return 'yeah';
	}
}

interface TestInterface{
	function quantadruple($num);
}

class InterfaceUser implements TestInterface{
	function quantadruple($num){
		// do something
	}
}

class InterfaceUserDescendant extends InterfaceUser{
	private $r='';	
}

/**
* an adhoc testrunner to test the iexpect itself
*/
class TestIEX{

	private $result = true;
	
	private function check($expect,$result){
		if($expect!=$result){
			echo "\nTestIEX Expectation not met";
			// normally the assertion::getOverallResult would suffice to give a end result for all expect() calls
			// but here we let tests fail on purpose to test iexpect so we sort of added a layer of expectations 
			// which you normally wouldn't.
			$this->result = false;
		}	
	}
	
	private function testContains($I){

		$s="Pandabears eat bamboo";

		// find text it contains
		$res = $I->expect($s)->contains("bear");
		$this->check(OK,$res);
		// negate
		$res = $I->expect($s)->not()->contains("bear");
		$this->check(NOK,$res);
		
		// find text it does not contain (case sensitive)
		$res = $I->expect($s)->contains("panda");
		$this->check(NOK,$res);
		// negate
		$res = $I->expect($s)->not()->contains("panda");
		$this->check(OK,$res);
		
		// case sensitivity off, should find panda
		$res = $I->expect($s)->caseInsensitive()->contains("panda");
		$this->check(OK,$res);						
		// negate
		$res = $I->expect($s)->caseInsensitive()->not()->contains("panda");
		$this->check(NOK,$res);			
		
		$res = $I->expect(12)->contains('panda');			
		$this->check(NOK,$res);			
		
	}

	private function testIsFile($I){
	
		// I expect this script  to be a file
		$res = $I->expect(__FILE__)->isFile();
		$this->check(OK,$res);			
		
		// negate
		$res = $I->expect(__FILE__)->not()->isFile();
		$this->check(NOK,$res);			
			
	}	
	
	private function testHasValue($I){
		
		$arr = [4,5,"x"];

		// search value in array that exists
		$res = $I->expect($arr)->hasValue(5);
		$this->check(OK,$res);

		// negate
		$res = $I->expect($arr)->not()->hasValue(5);
		$this->check(NOK,$res);

		// search value in array that not exists
		$res = $I->expect($arr)->hasValue('havenot');
		$this->check(NOK,$res);

		// negate 
		$res = $I->expect($arr)->not()->hasValue('havenot');
		$this->check(OK,$res);

		$arr = ['aaa'=>'djklf', 'b4'=>3245];

		// search value in array with different case but set case insensitivity
		$res = $I->expect($arr)->caseInsensitive()->hasValue('djkLf');
		$this->check(OK,$res);

		// negation of previous
		$res = $I->expect($arr)->caseInsensitive()->not()->hasValue('djkLf');
		$this->check(NOK,$res);

		// search value in array with different case but do not set case insensitivity
		$res = $I->expect($arr)->hasValue('djkLf');
		$this->check(NOK,$res);
		
		// negation of previous
		$res = $I->expect($arr)->not()->hasValue('djkLf');
		$this->check(OK,$res);

		// search value in array, set case insensitivity but ignored because search for numeric
		$res = $I->expect($arr)->caseInsensitive()->hasValue(3245);
		$this->check(OK,$res);

		// expression not array should always return NOK
		$res = $I->expect(14)->caseInsensitive()->hasValue(3245);
		$this->check(NOK,$res);
		
		// expression not array should always return NOK
		$res = $I->expect("sss")->caseInsensitive()->hasValue("sss");
		$this->check(NOK,$res);

		// negate
		$res = $I->expect("sss")->caseInsensitive()->not()->hasValue("sss");
		$this->check(OK,$res);
		
	}	

	private function testHasKey($I){
		$arr = ['city'=>'Apeldoorn', 'country'=>'Netherlands' ];

		// existing key
		$res = $I->expect($arr)->hasKey('city');
		$this->check(OK,$res);

		// negate
		$res = $I->expect($arr)->not()->hasKey('city');
		$this->check(NOK,$res);

		// should not confuse key with value
		$res = $I->expect($arr)->hasKey('Apeldoorn');
		$this->check(NOK,$res);
		
		// negate
		$res = $I->expect($arr)->not()->hasKey('Apeldoorn');
		$this->check(OK,$res);

		// non existing key
		$res = $I->expect($arr)->hasKey('xxxxx');
		$this->check(NOK,$res);

		// negate 
		$res = $I->expect($arr)->not()->hasKey('xxxxx');
		$this->check(OK,$res);

		// keys are always case sensitive (setting case to insensitive is ignored)
		$res = $I->expect($arr)->caseInsensitive()->hasKey('cItY');
		$this->check(NOK,$res);
		
		// negate 
		$res = $I->expect($arr)->caseInsensitive()->not()->hasKey('cItY');
		$this->check(OK,$res);

		// non array expression never finds key
		$res = $I->expect(23)->hasKey(23);
		$this->check(NOK,$res);
		
		// negate
		$res = $I->expect(23)->not()->hasKey(23);
		$this->check(OK,$res);
		
	}	
	
	private function testHasKeyValue($I){

		// search existing key value
		$res = $I->expect($arr)->hasKeyValue('city','Apeldoorn');
		$this->check(OK,$res);
		
		// negate
		$res = $I->expect($arr)->not()->hasKeyValue('city','Apeldoorn');
		$this->check(NOK,$res);

		// key always case sensitive
		$res = $I->expect($arr)->hasKeyValue('citY','Apeldoorn');
		$this->check(NOK,$res);

		// negate
		$res = $I->expect($arr)->not()->hasKeyValue('citY','Apeldoorn');
		$this->check(OK,$res);

		// but value can be case insensitive
		$res = $I->expect($arr)->caseInsensitive()->hasKeyValue('city','apeLdOOrn');
		$this->check(OK,$res);

		// non array expression never finds key
		$res = $I->expect(23)->hasKeyValue(23, 'dummy');
		$this->check(NOK,$res);
		
		// negate
		$res = $I->expect(23)->not()->hasKeyValue(23,'dummy');
		$this->check(OK,$res);
		
	}
	
	private function testEquals($I){
		
		$x = 5;
		$y = 6;

		// compare variable against literal
		$res = $I->expect($x)->equals(5);
		$this->check(OK,$res);
		// negate 
		$res = $I->expect($x)->not()->equals(5);
		$this->check(NOK,$res);
		
		// check if not equal to
		$res = $I->expect($x)->not()->equals(4);
		$this->check(OK,$res);
		
		// variation with variables
		$res = $I->expect($x)->equals($y);
		$this->check(NOK,$res);

		// with methods
		$xx = new TestClass();
		$res = $I->expect($xx->double($x))->equals(10);
		$this->check(OK,$res);
		// negate
		$res = $I->expect($xx->double($x))->not()->equals(10); // this text is shown as well
		$this->check(NOK,$res);
		
		// some more variations
		$arr1 = [1,2,3,4,5];
		$arr2 = [1,2,3,4,5];

		$res = $I->expect(sizeof($arr1))->equals(sizeof($arr2));
		$this->check(OK,$res);
		//negate
		$res = $I->expect(sizeof($arr1))->not()->equals(sizeof($arr2));
		$this->check(NOK,$res);

		// case (in)sensitivity
		$res = $I->expect('iexpect')->equals('IexpeCt');
		$this->check(NOK,$res);
		$res = $I->expect('iexpect')->caseInsensitive()->equals('IexpeCt');
		$this->check(OK,$res);
		// negate
		$res = $I->expect('iexpect')->not()->equals('IexpeCt');
		$this->check(OK,$res);
		$res = $I->expect('iexpect')->not()->caseInsensitive()->equals('IexpeCt');
		$this->check(NOK,$res);
		
		// cmp non strings to strings, should not crash or so, just return false
		$res = $I->expect(33)->equals('IexpeCt');
		$this->check(NOK,$res);
		$res = $I->expect(33)->equals(null);
		$this->check(NOK,$res);
		$res = $I->expect(33)->not()->equals('IexpeCt');
		$this->check(OK,$res);
		$res = $I->expect(33)->not()->equals(null);
		$this->check(OK,$res);
		
		// .. same with case insensitive
		$res = $I->expect(33)->caseInsensitive()->equals('IexpeCt');
		$this->check(NOK,$res);
		$res = $I->expect('xxx')->caseInsensitive()->equals(33);
		$this->check(NOK,$res);
		// negate
		$res = $I->expect(33)->caseInsensitive()->not()->equals('IexpeCt');
		$this->check(OK,$res);
		$res = $I->expect('xxx')->caseInsensitive()->not()->equals(33);
		$this->check(OK,$res);
				
	}
	
	private function testIsNull($I){
		
		$res = $I->expect(null)->isNull();
		$this->check(OK,$res);
		
		// negate
		$res = $I->expect(12)->isNull();
		$this->check(NOK,$res);
		
		
		$res = $I->expect(false)->isNull(); // I expect false is null
		$this->check(NOK,$res);            // No it isn't
		$res = $I->expect('')->isNull();  // I expect '' is  null
		$this->check(NOK,$res);          // No it isn't

		// negate
		$res = $I->expect(false)->not()->isNull();
		$this->check(OK,$res);
		$res = $I->expect('')->not()->isNull();
		$this->check(OK,$res);
		
	}

	private function testIsTruthy($I){
		
		// some truthy values
		$res = $I->expect('LLL')->isTruthy();
		$this->check(OK,$res);
		$res = $I->expect(13)->isTruthy();
		$this->check(OK,$res);
		$res = $I->expect(true)->isTruthy();
		$this->check(OK,$res);
		$res = $I->expect(array(13))->isTruthy();
		$this->check(OK,$res);
		
		// negates
		$res = $I->expect('LLL')->not()->isTruthy();
		$this->check(NOK,$res);
		$res = $I->expect(13)->not()->isTruthy();
		$this->check(NOK,$res);
		$res = $I->expect(true)->not()->isTruthy();
		$this->check(NOK,$res);
		$res = $I->expect(array(13))->not()->isTruthy();
		$this->check(NOK,$res);
		
	}
	
	private function testIsA($I){
		
		$x = new TestClass();
		// check class
		$res = $I->expect($x)->isA('TestClass');
		$this->check(OK,$res);
		// negate
		$res = $I->expect($x)->not()->isA('TestClass');
		$this->check(NOK,$res);

		// check some class it isnt
		$res = $I->expect($x)->isA('OtherClass');
		$this->check(NOK,$res);
		// negate
		$res = $I->expect($x)->not()->isA('OtherClass');
		$this->check(OK,$res);
				
		// some tests on a Descendant
		$x = new Descendant();
		$res = $I->expect($x)->isA('Descendant');
		$this->check(OK,$res);
		$res = $I->expect($x)->isA('TestClass');
		$this->check(OK,$res);
		//negate
		$res = $I->expect($x)->not()->isA('Descendant');
		$this->check(NOK,$res);
		$res = $I->expect($x)->not()->isA('TestClass');
		$this->check(NOK,$res);

		// some tests on interface 
		$x = new InterfaceUser();
		$res = $I->expect($x)->isA('TestInterface');		
		$this->check(OK,$res);
		$res = $I->expect($x)->isA('InterfaceUser');		
		$this->check(OK,$res);
		// negate
		$x = new InterfaceUser();
		$res = $I->expect($x)->not()->isA('TestInterface');		
		$this->check(NOK,$res);
		$res = $I->expect($x)->not()->isA('InterfaceUser');		
		$this->check(NOK,$res);
		
		// interface + descendant
		$x = new InterfaceUserDescendant();
		$res = $I->expect($x)->isA('TestInterface');		
		$this->check(OK,$res);
		$res = $I->expect($x)->isA('InterfaceUser');		
		$this->check(OK,$res);
		$res = $I->expect($x)->isA('InterfaceUserDescendant');		
		$this->check(OK,$res);
		$res = $I->expect($x)->isA('TestClass');		
		$this->check(NOK,$res);
		
		// negate
		$res = $I->expect($x)->not()->isA('TestInterface');		
		$this->check(NOK,$res);
		$res = $I->expect($x)->not()->isA('InterfaceUser');		
		$this->check(NOK,$res);
		$res = $I->expect($x)->not()->isA('InterfaceUserDescendant');		
		$this->check(NOK,$res);
		$res = $I->expect($x)->not()->isA('TestClass');		
		$this->check(OK,$res);
		
		
		
				
	}	
	
	private function testIsFalsy($I){
		
		// some falsy values
		$res = $I->expect(0)->isFalsy();
		$this->check(OK,$res);
		$res = $I->expect(null)->isFalsy();
		$this->check(OK,$res);
		$res = $I->expect(array())->isFalsy();
		$this->check(OK,$res);
		$res = $I->expect('')->isFalsy();
		$this->check(OK,$res);
		$res = $I->expect('0')->isFalsy();
		$this->check(OK,$res);
		$res = $I->expect(false)->isFalsy();
		$this->check(OK,$res);

		// the negates
		$res = $I->expect(0)->not()->isFalsy();
		$this->check(NOK,$res);
		$res = $I->expect(null)->not()->isFalsy();
		$this->check(NOK,$res);
		$res = $I->expect(array())->not()->isFalsy();
		$this->check(NOK,$res);
		$res = $I->expect('')->not()->isFalsy();
		$this->check(NOK,$res);
		$res = $I->expect('0')->not()->isFalsy();
		$this->check(NOK,$res);
		$res = $I->expect(false)->not()->isFalsy();
		$this->check(NOK,$res);

	}	
	
	public function getResult(){

		return $this->result;	

	}
	
	public function run(){

		$assertion = new Tigrez\IExpect\Assertion();
		
		$this->testEquals($assertion);
		$this->testContains($assertion);
		$this->testHasValue($assertion);
		$this->testHasKey($assertion);
		$this->testIsNull($assertion);
		$this->testIsFalsy($assertion);
		$this->testIsTruthy($assertion);		
		$this->testIsA($assertion);
		$this->testIsFile($assertion);
		
		echo "\ntests  : ".$assertion->getTests();
		echo "\npassed : ".$assertion->getPassed();
		echo "\nfailed : ".$assertion->getFailed();
		echo "\noverall: ".($assertion->getOverallResult() ? "passed" : "failed");

		return $this->result;
	}
	
}
//----------------------------

$testrunner = new TestIEX();

echo "\nThe NOKs you see might be on purpose, only regard the result at the end!\n";

if($testrunner->run()){
	echo "\n\n :) All Expectations met";
}
else{
	echo "\n\n :( Not all expectations met!";
}

