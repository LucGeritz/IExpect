<?php
// Include this file if you don't use IExpect as a composer package   
spl_autoload_register(

	function ($class) {
		
		$baseDir = dirname(__FILE__).'/';
		$baseNamespace = 'Tigrez\IExpect'; 
		
		if(strpos($class,$baseNamespace)!==0) return; // not my class
			$class = trim(str_replace($baseNamespace, '',$class),'\\');
			$file=$baseDir.$class.'.php';
			$file=str_replace('\\','/',$file);
			if(!file_exists($file)) return;
			require($file);
		}
		
	);
