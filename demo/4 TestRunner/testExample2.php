<?php
class testExample2 implements IExpect\ITest{

	public function run(IExpect\Assertion $I){

		$I->expect('a')->equals(strtolower('A'));
		$I->expect(1)->equals(1);
		$I->expect(null)->hasValue(2);
		$I->expect(1)->not()->equals(2);
		
	}	
}