<?php
class testExample6 implements IExpect\ITest{

	public function run(IExpect\Assertion $I){

		$I->expect(true)->equals(true);
		$I->expect(1)->equals(1);
		$I->expect(2)->equals(2);
		$I->expect(1)->not()->equals(2);
		
	}	
}