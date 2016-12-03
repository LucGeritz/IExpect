<?php
namespace Tigrez\IExpect;

interface IResultHandler{
	
	// finalize should show the result and maybe report back to owner the result
	function finalize($ok, Caller $caller, $extra = '');
	function setOverallResultHandler(IOverallResultHandler $handler);	
	
}