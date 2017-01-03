<?php
Class PurchaseOrderCart {
	var $LineItems;
	var $total;
	var $LineCounter;
	var $ItemsOrdered;
	var $orderDate;
	var $PhoneNo;
	var $Email;
	Var $OrderNo;
	var $supplier;
	function PurchaseOrderCart(){
		$this->LineItems = array();
		$this->total=0;
		$this->ItemsOrdered=0;
		$this->LineCounter=0;
	}
	public function getSupplier(){
		return $this->supplier;
	}
	public function setSupplier($supplier){
		$this->supplier=$supplier;
	}
	function add_to_cart($productID,$number,$Descr,$Price,$exp,$batch,$discount,$bonus,$units,$LineNumber=-1){
		if (isset($productID) AND $productID!="" AND isset($number)){
			if ($Price<0){
				$Price=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new PurchaseOrderLineDetails($LineNumber,$productID,$Descr,$number,$Price,$exp,$batch,$discount,$bonus,$units);
			$this->ItemsOrdered++;
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}
	function setDeliveryDate($date){
		$this->orderDate=$date;
	}
	function update_cart($UpdateLineNumber,$number){
		$this->LineItems[$UpdateLineNumber]->number= $number;
	}
	function update_discount($UpdateLineNumber,$disc){
		$this->LineItems[$UpdateLineNumber]->discount= $disc;
	}
	function update_bonus($UpdateLineNumber,$bonus){
		$this->LineItems[$UpdateLineNumber]->bonus= $bonus;
	}
	function setLineItemExpiryDate($UpdateLineNumber,$exp){
		$this->LineItems[$UpdateLineNumber]->expiryDate= $exp;
	}
	function setLineItemBatchNo($UpdateLineNumber,$batch){
		$this->LineItems[$UpdateLineNumber]->batchNo= $batch;
	}
	function remove_from_cart($LineNumber){
		if (!isset($LineNumber) || $LineNumber=='' || $LineNumber < 0){
			return;
		}
		unset($this->LineItems[$LineNumber]);
		$this->ItemsOrdered--;
	}//remove_from_cart()
} /* end of cart class defintion */
Class PurchaseOrderLineDetails {
	Var $LineNumber;
	Var $productID;
	Var $ItemDescription;
	Var $number;
	Var $Price;
	Var $POLine;
	var $expiryDate;
	var $batchNo;
	var $discount;
	var $bonus;
	var $units;

	function PurchaseOrderLineDetails($LineNumber,$productID,$Descr,$number,$Prc,$exp,$batch,$discount,$bonus,$units){
		$this->LineNumber = $LineNumber;
		$this->productID =$productID;
		$this->ItemDescription = $Descr;
		$this->number= $number;
		$this->Price = $Prc;
		$this->expiryDate=$exp;
		$this->batchNo=$batch;
		$this->discount=$discount;
		$this->bonus=$bonus;
		$this->units=$units;
	}
}
?>
