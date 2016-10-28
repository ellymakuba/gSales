<?php
Class ProductCart {
	var $LineItems;
	var $LineCounter;
	var $category;
	function ProductCart(){
		$this->LineItems = array();
		$this->LineCounter=0;
		$this->category='All Categories';
	}
	function add_to_cart($productID,$Descr,$Price,$pic,$name,$LineNumber=-1){
		if (isset($productID) AND $productID!="" ){
			if ($Price<0){
				$Price=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new ProductLineDetails($LineNumber,$productID,$Descr,$Price,$pic,$name);
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}
	function setCategory($category){
		$this->category=$category;
	}
} /* end of cart class defintion */
Class ProductLineDetails {
	Var $LineNumber;
	Var $productID;
	Var $ItemDescription;
	Var $Price;
	var $photo;
	var $name;

	function ProductLineDetails ($LineNumber,$productID,$Descr,$Prc,$pic,$name){
		$this->LineNumber = $LineNumber;
		$this->productID =$productID;
		$this->ItemDescription = $Descr;
		$this->Price = $Prc;
		$this->photo=$pic;
		$this->name=$name;
	} //end constructor function for LineDetails
}
?>
