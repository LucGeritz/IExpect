<?php
class Stock{

	private $stock;
	
	
	// get current stock
	public function getStock(){
		return $this->stock;
	}
	
	// add to stock
	public function buy($amount){
		$this->stock+= $amount;
	}
	
	// take from stock
	public function sell($amount){
		
		$this->stock-= $amount;	
	}
	
	// initialize stock
	public function __construct($amount=0){
		
		$this->stock = $amount;
	}
	
	
}

