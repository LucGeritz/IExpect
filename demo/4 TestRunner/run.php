<?php

include ('/../../src/Autoloader.php');

$runner = new Tigrez\IExpect\Runner(dirname(__FILE__).'/run.cfg');
	
if($runner->start()){

	echo "\n\nSummary";
	echo "\ntests  : ".$runner->getTests();
	echo "\npassed : ".$runner->getPassed();
	echo "\nfailed : ".$runner->getFailed();
	echo "\noverall: ".($runner->getResult() ? "passed" : "failed");
	exit($runner->getResult() ? 0 : 1);

}
else{
	
	$configFile = $runner->getConfigFile();
	echo "\nConfig file $configFile not found or has incorrect content";
	exit(1);

}
