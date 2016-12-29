<?PHP
	require 'data_access_object.php';
	require 'salesOrderCart.php';
	$pageSecurity=4;
	$dao=new DAO();
	$dao->checkLogin();
	if (isset($_POST['dispatch'])){
		if(isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0 && isset($_SESSION['existing_order'])){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			$i=0;
			$dao->productDeliveryProcessStarted($_SESSION['existing_order']);
			foreach($_POST['product'] as $value) {
			$totalDelivered=$_POST['quantity_delivered'][$i]+$_POST['quantity'][$i];
			$dao->dispatchSalesOrderProducts($_SESSION['existing_order'],$totalDelivered,$_POST['product'][$i],$_POST['payment'][$i]);
			$dao->deductStockFromInventory($_POST['quantity'][$i],$_POST['product'][$i]);
			$i++;
			}
			if($_SESSION['salesOrder']->allDelivariesMade==0){
				$allDelivariesMade=$dao->allDeliveriesMadeForSalesOrder($_SESSION['existing_order']);
				if($allDelivariesMade['count']==0){
					$dao->completeDelivery($_SESSION['existing_order']);
				}
			}
			$allPaymentsMade=$dao->allPaymentMadeForSalesOrder($_SESSION['existing_order']);
			if($allPaymentsMade['count']==0){
				$dao->clearSalesOrder($_SESSION['existing_order']);
			}
			unset($_SESSION['salesOrder']);
			unset($_SESSION['existing_order']);
			unset($_SESSION['salesOrderUpdated']);
			unset($_SESSION['updateOrder']);
			unset($_SESSION['client']);
			header('Location:manage_orders.php');
	}
	}
	if(isset($_POST['update'])){
		$i=0;
		$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
		$dao->updateClientOrderById($entry_date,$_SESSION['updateOrder']);
		foreach($_POST['product'] as $value) {
			if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
				$productExists=$dao->productAlreadyOnOrder($_SESSION['updateOrder'],$_POST['product'][$i]);
				if($productExists['product_id']==$_POST['product'][$i]){
					$dao->updateClientOrderDetails($_SESSION['updateOrder'],$_POST['product'][$i],$_POST['quantity'][$i],
					$_POST['discount'][$i],$_POST['amount'][$i]);
				}
				else{
				$dao->saveClientOrderDetails($_SESSION['updateOrder'],$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i]
				,$_POST['amount'][$i],$_POST['discount'][$i]);
			}
			}
		$i++;
		}
		unset($_SESSION['salesOrder']);
		unset($_SESSION['existing_order']);
		unset($_SESSION['salesOrderUpdated']);
		unset($_SESSION['updateOrder']);
		unset($_SESSION['client']);
		header('Location:manage_orders.php');
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Sales Order',0) ?>
	<script>
	$(document).ready(function(){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10) {
		    dd='0'+dd
		}
		if(mm<10) {
		    mm='0'+mm
		}
		today = mm+'/'+dd+'/'+yyyy;

    $(document).on('focus','.myDate', function() {
      var $jthis = $(this);
      if(!$jthis.data('datepicker')) {
        $jthis.removeClass("hasDatepicker");
        $jthis.datepicker();
        $jthis.datepicker("show");
      }
    });
		$(document).on("click", "#product_search", function() {
		$(this).autocomplete({
	 		source:function(request,response){
	 	$.getJSON("searchInventory.php?term="+request.term,function(result){
		 response($.map(result,function(item){
		 	return{
			id:item.id,
			value:item.name,
			cost:item.cost,
			stock:item.stock
			}
		 }))
		})
	 },
	 select:function(event,ui){
		 $.ajax({
	    type:"GET",
		  url:"sales_order.php?add_cart="+ui.item.value,
		  async:false,
		  success:function(result){
		  $("#sales_order_cart_form").submit();
		  }
		});
	 },
	 minLength:3,
	  messages: {
	        noResults: '',
	        results: function() {}
	    }
	  });
		return false;
	});
$("#entry_date").datepicker();
$("#entry_date").change(function(){
	$.ajax({
	 type:"GET",
	 url:"sales_order.php?set_date="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".quantity").change(function(){
	var totalAmount=0;
	var quantityId=$(this).attr("id");
	var id=quantityId.substring(quantityId.indexOf("_")+1);
	document.getElementById("amount_"+id).value=parseInt($(this).val())*parseInt($("#price_"+id).val());
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"sales_order.php?update_cart="+id+"&quantity="+value,
	 success:function(result){
	 }
 });
	document.getElementById("total").value=totalAmount;
	document.getElementById("payment_"+id).max=document.getElementById("amount_"+id).value;
});
$(document).on("click", "#save", function(e) {
		bootbox.confirmation("Are you sure?", function() {
		});
});
var tAmount=0;
var $lineTotal=0;
$(".amount").each(function(){
	$lineTotal=0;
	var amountId=$(this).attr("id");
	var id=amountId.substring(amountId.indexOf("_")+1);
	$lineTotal=parseInt($("#quantity_"+id).val())*parseInt($("#price_"+id).val())-parseInt($("#discount_"+id).val());
	document.getElementById("amount_"+id).value=$lineTotal;
	tAmount +=$lineTotal;
});
var tPaid=0;
$(".pai").each(function(){
	tPaid+=Number($(this).val());
});
document.getElementById("total_paid").value=tPaid;
document.getElementById("total").value=tAmount;
var discount_sum=0;
$(".discount").each(function(){
	discount_sum +=Number($(this).val());
});
document.getElementById("discount_sum").value=discount_sum;
$('.payment').change(function(){
	var paidId=$(this).attr("id");
	var id=paidId.substring(paidId.indexOf("_")+1);
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"sales_order.php?update_paid="+id+"&amount_paid="+value,
	 success:function(result){
	 }
 });
	var tPaid=0;
	$('.payment').each(function(){
		tPaid+=Number($(this).val());
	})
	document.getElementById("total_paid").value=tPaid;
})
});
	</script>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu($_SESSION['tab_no']);
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
			echo '<form class="form-signin" method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="sales_order_cart_form">';
			if(!isset($_SESSION['salesOrder'])){
				$_SESSION['salesOrder'] = new Cart();
			}
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$product=$dao->getInventoryItemByName($SearchString);
				$AlreadyOnThisCart =0;
				$quantity=1;
				if (count($_SESSION['salesOrder']->LineItems)>0){
						 foreach ($_SESSION['salesOrder']->LineItems AS $OrderItem)
								{
								$LineNumber = $_SESSION['salesOrder']->LineCounter;
								if ($OrderItem->productID ==$product['id'])
								 {
									 $AlreadyOnThisCart = 1;
									 $dao->showMessage("product already added on this list");
									}
							 }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$profit=$product['selling_price']-$product['buying_price'];
							$discount=0.5*$profit;
							$_SESSION['salesOrder']->add_to_cart($product['id'],$quantity,$product['name'],$product['selling_price'],$discount,0,0,0,0,-1);
						}
				}//end of if(isset($_POST['add_cart]))
				echo '</form>';
				if (isset($_GET['Delete']))
				{
					$_SESSION['salesOrder']->remove_from_cart($_GET['Delete']);
				}
			if(!isset($_SESSION['salesOrderUpdated'])){
				$_SESSION['salesOrderUpdated']=0;
			}
			if (isset($_GET['update_cart']) )
			{
				$_SESSION['salesOrder']->update_cart($_GET['update_cart'],$_GET['quantity']);
				$_SESSION['salesOrderUpdated']=1;
			}
			if (isset($_GET['update_paid']) )
			{
				$_SESSION['salesOrder']->update_paid($_GET['update_paid'],$_GET['amount_paid']);
				$_SESSION['salesOrderUpdated']=1;
			}
				if(isset($_GET['SelectedOrder'])){
					unset($_SESSION['updateOrder']);
					$client_username=$dao->getClientByOrderId($_GET['SelectedOrder']);
					$_SESSION['client']=$client_username['client'];
					$_SESSION['existing_order']=$_GET['SelectedOrder'];
					if($_SESSION['salesOrderUpdated']==0){
						$_SESSION['salesOrder'] = new Cart();
						$salesOrder=$dao->getSalesOrderById($_GET['SelectedOrder']);
						$orderProducts=$dao->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
						$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
						$_SESSION['salesOrder']->allDelivariesMade=$salesOrder['complete_delivery'];
						foreach($orderProducts as $product){
						$_SESSION['salesOrder']->add_to_cart($product['id'],0,$product['name'],$product['price'],$product['discount'],$product['quantity_delivered'],
						$product['quantity'],0,$product['payment'],$product['buying_price'],$product['name'],-1);
						}
				}
				}
				if(isset($_GET['updateOrder'])){
					unset($_SESSION['existing_order']);
					$client_username=$dao->getClientByOrderId($_GET['updateOrder']);
					$_SESSION['client']=$client_username['client'];
					$_SESSION['updateOrder']=$_GET['updateOrder'];
					if($_SESSION['salesOrderUpdated']==0){
						$_SESSION['salesOrder'] = new Cart();
						$salesOrder=$dao->getSalesOrderById($_GET['updateOrder']);
						$orderProducts=$dao->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
						$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
						foreach($orderProducts as $product){
						$_SESSION['salesOrder']->add_to_cart($product['id'],$product['quantity'],$product['name'],$product['price'],$product['discount'],
						$product['quantity_delivered'],$product['quantity'],0,$product['payment'],$product['buying_price'],$product['name'],-1);
						}
				}
				}
			 ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading"><?php echo $_SESSION['client'].' Order';?></h2>
        <label for="entry_date">Delivery Date</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['salesOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
				<?php
					if (count($_SESSION['salesOrder']->LineItems)>0 && isset($_SESSION['existing_order']))
					{
				?>
				<table id="sales_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr>
							<th>Product</th>
							<th>Quantity Requested</th>
							<th>Delivered</th>
							<th>Dispatch</th>
							<th>Price</th>
							<th>Discount</th>
							<th>Cost</th>
							<th>Paid</th>
							<th>Balance</th>
							<th>Payment</th>
						</tr>
          </head>
        <tbody>
					<?php
					foreach ($_SESSION['salesOrder']->LineItems as $order)
					{
						$amountRequired=$order->requested*$order->Price;
						$maximumPayment=($amountRequired-$order->paid)-$order->Discount;
					?>
        <tr>
        <input type="hidden"  class="form-control" placeholder="Product" name="product[]"  value="<?php echo $order->productID ?>"/>
				<td><input type="text"  class="form-control drug" placeholder="Product"
 				name="name[]"  style="margin-right:20px;margin-top:10px;" value="<?php echo $order->ItemDescription ?>"
 				required readonly/>
 				 </td>
				 <td><input type="number"  class="form-control" placeholder="Quantity Requested"
 					 name="requested[]" id="requested_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					  value="<?php echo $order->requested ?>" required="" readonly=""/>
 				 </td>
				 <td><input type="number"  class="form-control" placeholder="Quantity Already Delivered"
 					 name="quantity_delivered[]" id="quantity_delivered_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->quantityDelivered ?>" required="" readonly=""/>
 				 </td>
				 <?php $maximumDispatch=$order->requested-$order->quantityDelivered;
				 if($order->requested==$order->quantityDelivered){?>
					 <td><input type="number"  class="form-control quantity" placeholder="Quantity"  max="<?php echo $maximumDispatch ?>"
   					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
  					 value="<?php echo $order->Quantity ?>" required="" readonly=""/>
   				 </td>
				 <?php }else{ ?>
					 <td><input type="number"  class="form-control quantity" placeholder="Quantity"  max="<?php echo $maximumDispatch ?>"
							name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
						value="<?php echo $order->Quantity ?>" required="" />
						</td>
				 <?php } ?>
				 <td><input type="text"  class="form-control" placeholder="Price"
 					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->Price ?>" readonly/>
 				 </td>
				 <td><input type="text"  class="form-control discount" placeholder="Discount"
 					 name="discount[]" id="discount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Discount ?>"
					 required="" readonly=""/>
 				 </td>
				 <td><input type="text"  class="form-control amount" placeholder="Amount" name="amount[]"
					 id="amount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required="" readonly=""/>
 				 </td>
				 <td><input type="number"  class="form-control" value="<?php echo $order->paid ?>"
 					 name="paid[]"  style="margin-right:20px;margin-top:10px;" readonly=""/>
 				 </td>
				 <td><input type="number"  class="form-control" value="<?php echo $maximumPayment ?>"
 					  style="margin-right:20px;margin-top:10px;" readonly=""/>
 				 </td>
				 <?php if($amountRequired==$order->paid){ ?>
					 <td><input type="number"  class="form-control payment" value="<?php echo $order->payment ?>" max="<?php echo $maximumPayment ?>"
					 	name="payment[]" id="payment_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required="" readonly/>
					 </td>
				 <?php }
				 else{ ?>
					 <td><input type="number"  class="form-control payment" value="<?php echo $order->payment ?>" max="<?php echo $maximumPayment ?>"
					 	name="payment[]" id="payment_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required=""/>
					 </td>
				 <?php }
				echo '</tr>';
			 }?>
        </tbody>
        </table>
				<?php }
					if (count($_SESSION['salesOrder']->LineItems)>0 && isset($_SESSION['updateOrder']))
					{
				?>
				<table id="sales_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Delivered</th>
							<th>Price</th>
							<th>Discount</th>
							<th>Amount</th>
						</tr>
          </head>
        <tbody>
					<?php
					foreach ($_SESSION['salesOrder']->LineItems as $order)
					{
					?>
					<tr>
	        <input type="hidden"  class="form-control" placeholder="Product" name="product[]"  value="<?php echo $order->productID ?>"/>
					<td><input type="text"  class="form-control drug" placeholder="Product"
	 				name="name[]"  style="margin-right:20px;margin-top:10px;" value="<?php echo $order->ItemDescription ?>"
	 				required readonly/>
	 				 </td>
					 <td><input type="number"  class="form-control quantity" placeholder="Quantity"  min="<?php echo $order->quantityDelivered ?>"
	 					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
						 value="<?php echo $order->Quantity ?>" required="" />
	 				 </td>
					 <td><input type="number"  class="form-control" placeholder="Quantity Already Delivered"
	 					 name="quantity_delivered[]" id="quantity_delivered_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
						 value="<?php echo $order->quantityDelivered ?>" required="" readonly=""/>
	 				 </td>
					 <td><input type="text"  class="form-control" placeholder="Price"
	 					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Price ?>" readonly/>
	 				 </td>
					 <td><input type="text"  class="form-control discount" placeholder="Discount"
	 					 name="discount[]" id="discount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Discount ?>"
						 required="" readonly=""/>
	 				 </td>
					 <td><input type="text"  class="form-control amount" placeholder="Amount"
	 					 name="amount[]" id="amount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required="" readonly=""/>
	 				 </td>
					 <?php
					echo '</tr>';
			 }?>
        </tbody>
        </table>
				<?php }
				echo '<div class="form-inline" style="float:right;">
				<label for="total">Discount Sum</label>
				<input type="number" class="form-control" name="discount_sum" id="discount_sum" value="0" readonly=""/>
			  </div>
				<div style="clear:both;"></div>';
				echo '<div class="form-inline" style="float:right;">
				<label for="total">Balance</label>
				<input type="number" class="form-control" name="balance" id="balance" value="0" readonly=""/>
				</div>
				<div class="form-inline" style="float:right;">
				<label for="total">Total Amount</label>
				<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
				</div>
				<div class="form-inline" style="float:right;">
				<label for="total">Total Payment</label>
				<input type="number" class="form-control" name="total_paid" id="total_paid" value="0" readonly=""/>
				</div>
				<div style="clear:both;"></div>';
				if(!isset($_SESSION['existing_order'])){
				echo '<br><input type="text" class="form-control" name="product_search" id="product_search"
				placeholder="Type three characters to display product" />
				<div id="result"></div>';
			}
				echo '<br><br>';
				if(isset($_SESSION['updateOrder'])){
				echo '<button type="submit" name="update" id="order" class="btn btn-lg btn-primary"
					style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Update Order</button>';
				}
				else{
				echo '<button type="submit" name="dispatch" id="order" class="btn btn-lg btn-primary"
						style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Dispatch Order</button>';
					}
				?>
      </form>
			<?php }
			else{
				echo '<div class="alert alert-danger">
					<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
				</div>';
			}
			require 'footer.php';?>
  </body>
  </html>
