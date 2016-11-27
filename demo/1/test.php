<?php
include('Stock.php');
include('../../src/Autoloader.php');

// In IExpect we talk to an Assertion
// I like to use $I because it makes the syntax look like 'I Expect 3*2 equals 6' :)
$I = new IExpect\Assertion();


// Let's test the Stock class 

// I expect if no initial amount is given it will be 0
$stock = new Stock();
$I->expect($stock->getStock())->equals(0);

// I expect I can pass to the constructor an initial amount
$stock = new Stock(10);
$I->expect($stock->getStock())->equals(10);

// I expect if I buy 5 the stock will be increased by 5 and becomes 15
$stock->buy(5);
$I->expect($stock->getStock())->equals(15);

// I expect if I sell 8 the stock will be decreased by 8 and becomes 7
$stock->sell(8);
$I->expect($stock->getStock())->equals(7);

// final results are provided by the assertion
echo "\n\nTest: ".$I->getTests()." Passed ".$I->getPassed()." Failed ".$I->getFailed();
echo "\nOveral: ".$I->getOverallResult();














