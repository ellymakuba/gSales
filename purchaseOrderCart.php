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
	function add_to_cart($productID,$Qty,$Descr,$Price,$exp,$batch,$LineNumber=-1){
		if (isset($productID) AND $productID!="" AND isset($Qty)){
			if ($Price<0){
				$Price=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new PurchaseOrderLineDetails($LineNumber,$productID,$Descr,$Qty,$Price,$exp,$batch);
			$this->ItemsOrdered++;
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}
	function setDeliveryDate($date){
		$this->orderDate=$date;
	}
	function update_cart($UpdateLineNumber,$Qty){
		$this->LineItems[$UpdateLineNumber]->Quantity= $Qty;
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
	Var $Quantity;
	Var $Price;
	Var $Units;
	Var $POLine;
	var $expiryDate;
	var $batchNo;

	function PurchaseOrderLineDetails($LineNumber,$productID,$Descr,$Qty,$Prc,$exp,$batch){
		$this->LineNumber = $LineNumber;
		$this->productID =$productID;
		$this->ItemDescription = $Descr;
		$this->Quantity = $Qty;
		$this->Price = $Prc;
		$this->expiryDate=$exp;
		$this->batchNo=$batch;
	} //end constructor function for LineDetails
}
?>
