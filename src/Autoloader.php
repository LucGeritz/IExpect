<?php

spl_autoload_register(

	function ($class) {
		
		$baseDir = dirname(__FILE__).'/';
		$baseNamespace = 'IExpect'; 
		
		if(strpos($class,$baseNamespace)!==0) return; // not my class
			$class = trim(str_replace($baseNamespace, '',$class),'\\');
			$file=$baseDir.$class.'.php';
			$file=str_replace('\\','/',$file);
			if(!file_exists($file)) return;
			require($file);
		}
		
	);
