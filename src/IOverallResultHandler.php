<?php
namespace IExpect;

interface IOverallResultHandler{
	
	function incPassed();
	function incFailed();
	
}