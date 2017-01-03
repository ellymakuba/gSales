<?php
Class Cart {
	var $LineItems;
	var $total;
	var $LineCounter;
	var $ItemsOrdered;
	var $orderDate;
	var $PhoneNo;
	var $Email;
	Var $OrderNo;
	var $deliveryStarted;
	var $allDelivariesMade;
	var $cashPayment;
	function Cart(){
		$this->LineItems = array();
		$this->total=0;
		$this->ItemsOrdered=0;
		$this->LineCounter=0;
		$this->deliveryStarted=0;
		$this->cashPayment=0;
	}
	function add_to_cart($productID,$Qty,$Descr,$Price,$Disc,$quantityDelivered,$requested,$payment,$paid,$bP,$name,$batch,
	$tax,$discP,$LineNumber=-1){
		if (isset($productID) AND $productID!="" AND isset($Qty)){
			if ($Price<0){
				$Price=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new LineDetails($LineNumber,$productID,$Descr,$Qty,$Price,$Disc,$quantityDelivered,$requested,
			$payment,$paid,$bP,$name,$batch,$tax,$discP);
			$this->ItemsOrdered++;
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}
	function setDeliveryDate($date){
		$this->orderDate=$date;
	}
	function update_cart($UpdateLineNumber,$Qty,$total){
		$this->LineItems[$UpdateLineNumber]->Quantity= $Qty;
		$this->LineItems[$UpdateLineNumber]->total= $total;
	}
	function update_paid($UpdateLineNumber,$payment){
		$this->LineItems[$UpdateLineNumber]->payment= $payment;
	}
	function update_discount($UpdateLineNumber,$disc){
		$this->LineItems[$UpdateLineNumber]->Discount= $disc;
	}
	function remove_from_cart($LineNumber){
		if (!isset($LineNumber) || $LineNumber=='' || $LineNumber < 0){
			return;
		}
		unset($this->LineItems[$LineNumber]);
		$this->ItemsOrdered--;
	}//remove_from_cart()
} /* end of cart class defintion */
Class LineDetails {
	Var $LineNumber;
	Var $productID;
	Var $ItemDescription;
	Var $Quantity;
	Var $Price;
	Var $Discount;
	Var $Units;
	Var $cost;
	Var $ItemDue;
	Var $POLine;
	var $quantityDelivered;
	var $requested;
	var $payment;
	var $paid;
	var $buyingPrice;
	var $name;
	var $batch;
	var $tax;
	var $percentageDiscount;

	function LineDetails ($LineNumber,$productID,$Descr,$Qty,$Prc,$Disc,$quantityDelivered,$requested,$payment,$paid,$bP,$name,
	$batch,$tax,$discP){
		$this->LineNumber = $LineNumber;
		$this->productID =$productID;
		$this->ItemDescription = $Descr;
		$this->Quantity = $Qty;
		$this->Price = $Prc;
		$this->Discount= $Disc;
		$this->quantityDelivered=$quantityDelivered;
		$this->requested=$requested;
		$this->payment=$payment;
		$this->paid=$paid;
		$this->buyingPrice=$bP;
		$this->name=$name;
		$this->batch=$batch;
		$this->tax=$tax;
		$this->percentageDiscount=$discP;
	} //end constructor function for LineDetails
}
?>
