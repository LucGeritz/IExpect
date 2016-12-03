<?php
class testExample3 implements Tigrez\IExpect\ITest{

	public function run(Tigrez\IExpect\Assertion $I){

		$I->expect(true)->equals(true);
		$I->expect(1)->equals(1);
		$I->expect(2)->equals(2);
		$I->expect(1)->not()->equals(2);
		
	}	
}