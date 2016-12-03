<?php
namespace Tigrez\IExpect;

interface ITest{
	function run(Assertion $assertion);
}