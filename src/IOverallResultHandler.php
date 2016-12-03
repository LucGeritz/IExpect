<?php
namespace Tigrez\IExpect;

interface IOverallResultHandler{
	
	function incPassed();
	function incFailed();
	
}