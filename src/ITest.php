<?php
namespace IExpect;

interface ITest{
	function run(Assertion $assertion);
}