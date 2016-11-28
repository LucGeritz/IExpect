<?php
// A test class should implement ITest
// Typically it has a 1:1 relation with a class you want to unit test

class testExample1 implements IExpect\ITest{


	// The only method ITest forces you to implement is run()	
	public function run(IExpect\Assertion $I){

		$I->expect(true)->equals(true);
		$I->expect(1)->equals(1);
		$I->expect(2)->equals(2);
		$I->expect(1)->not()->equals(2);
		
	}	
}