<?PHP
	require 'data_access_object.php';
	require 'salesOrderCart.php';
	$dao=new DAO();
	$pageSecurity=6;
	$dao->checkLogin();
	/*if (isset($_POST['update']) && isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			if(isset($_SESSION['existing_order'])){
				$i=0;
				$dao->updateClientOrderById($entry_date,$_SESSION['existing_order'],$_SESSION['log_user']);
				$dao->removeSalesOderDetailsByOrderId($_SESSION['existing_order']);
				foreach($_POST['product'] as $value){
					if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
						$dao->saveClientOrderDetails($_SESSION['existing_order'],$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i],
						$_POST['amount'][$i],$_POST['discount'][$i],$_POST['buying_price'][$i],$_POST['profit'][$i]);
					}
			 	$i++;
		 		}
				unset($_SESSION['existing_order']);
				unset($_SESSION['salesOrder']);
				unset($_SESSION['salesOrderUpdated']);
			}
		header('Location:client_invoice_list.php');
	}*/

	if (isset($_POST['sale']) && isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0){
		$allowSale=0;
		if(($_POST['total']<$_POST['paid'] || $_POST['total']==$_POST['paid']) && isset($_POST['paid'])){
			$allowSale=1;
		}
		if($allowSale==1){
		$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
		$lastId=$dao->saveSalesOrder($entry_date,0,$_SESSION['log_user'],$_SESSION['log_location']['id']);
		$i=0;
		foreach($_POST['product'] as $value) {
		if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
			$dao->saveClientOrderDetails($lastId,$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i],$_POST['amount'][$i],
			$_POST['discount'][$i],$_POST['buying_price'][$i],$_POST['profit'][$i],$_POST['batch'][$i],$_POST['vat'][$i]);

			$dao->subtractStockFromInventory($_POST['product'][$i],$_POST['batch'][$i],$_POST['quantity'][$i],
			$_SESSION['log_location']['id']);
		}
		$i++;
		}
		unset($_SESSION['salesOrder']);
		unset($_SESSION['salesOrderUpdated']);
		header('Location:receipt.php?SelectedOrder='.$lastId.'&total='.$_POST['total'].'&paid='.$_POST['paid']);
	}
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
		$(document).on("click", "#product_search", function() {
		$(this).autocomplete({
	 		source:function(request,response){
	 	$.getJSON("searchInventory.php?product="+request.term+"&location="+$("#location").val(),function(result){
		 response($.map(result,function(item){
		 	return{
			id:item.id,
			value:item.name+'-('+item.batch+')-'+item.expiry,
			cost:item.cost,
			stock:item.stock,
			display:item.name,
			batch:item.batch
			}
		 }))
		})
	 },
	 select:function(event,ui){
		 $.ajax({
	    type:"GET",
		  url:"cash_sale.php?add_cart="+ui.item.id+"&batch="+ui.item.batch+"&location="+$("#location").val(),
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
	 url:"cash_sale.php?set_date="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".quantity").change(function(){
	var totalAmount=0;
	var discount_sum=0;
	var quantityId=$(this).attr("id");
	var id=quantityId.substring(quantityId.indexOf("_")+1);
	document.getElementById("profit_"+id).value=parseInt($(this).val())*parseInt($("#bp_"+id).val());
	var discount=parseInt($(this).val())*parseFloat($("#discount_percentage_"+id).val());
	discount=parseInt(discount);

	var lineAmount=parseInt($(this).val())*parseInt($("#price_"+id).val())-discount;
	document.getElementById("amount_"+id).value=lineAmount;
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"cash_sale.php?update_cart="+id+"&quantity="+value+"&total="+totalAmount,
	 success:function(result){
		 $.ajax({
		  type:"GET",
		  url:"cash_sale.php?update_discount="+id+"&discount="+discount,
		  success:function(result){
		 	 document.getElementById("discount_"+id).value=discount;
		 	 $(".discount").each(function(){
		 		discount_sum +=Number($(this).val());
		 	});
		 	document.getElementById("discount_sum").value=discount_sum;
		  }
		 });
	 }
 });
 document.getElementById("total").value=totalAmount;
});
$(".discount").change(function(){
	var totalAmount=0;
	var discount_sum=0;
	var numberId=$(this).attr("id");
	var id=numberId.substring(numberId.indexOf("_")+1);
	var amount_before_discount=parseInt($("#quantity_"+id).val())*parseInt($("#price_"+id).val());
	document.getElementById("amount_"+id).value=amount_before_discount-parseInt($(this).val());

	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"cash_sale.php?update_discount="+id+"&discount="+value,
	 success:function(result){
		 $(".discount").each(function(){
		  discount_sum +=Number($(this).val());
		 });
		 document.getElementById("discount_sum").value=discount_sum;
		 $(".amount").each(function(){
		 	totalAmount +=Number($(this).val());
		 });
	 }
 });
	document.getElementById("total").value=totalAmount;
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
document.getElementById("total").value=tAmount;
var discount_sum=0;
$(".discount").each(function(){
	discount_sum +=Number($(this).val());
});
document.getElementById("discount_sum").value=discount_sum;
$("#paid").change(function(){
	document.getElementById("balance").value=parseInt($(this).val())-parseInt($("#total").val());
});
});
	</script>
  </head>
  <body class="container">
    <?PHP
		$_SESSION['tab_no']=1;
		$dao->includeMenu($_SESSION['tab_no']);
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
				if(isset($_REQUEST['clear_order'])){
					unset($_SESSION['salesOrder']);
				}
			if (!isset($_SESSION['salesOrder'])){
				 $_SESSION['salesOrder'] = new Cart();
				 $_SESSION['salesOrder']->orderDate=date('m/d/Y');
				 $_SESSION['salesOrderUpdated']=0;
			}
			if(isset($_GET['SelectedOrder'])){
				if($_SESSION['salesOrderUpdated']==0){
					$_SESSION['salesOrder'] = new Cart();
					$salesOrder=$dao->getSalesOrderById($_GET['SelectedOrder']);
					$orderProducts=$dao->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
					$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
				foreach($orderProducts as $product){
					$_SESSION['salesOrder']->add_to_cart($product['id'],$product['quantity'],$product['description'],$product['price'],$product['discount'],
					0,0,0,0,$product['buying_price'],$product['name'],$product['batch_no'],0,-1);
				}
			}
		}
			if (isset($_GET['Delete']))
			{
				$_SESSION['salesOrder']->remove_from_cart($_GET['Delete']);
			}
			if (isset($_GET['update_cart']))
			{
				$_SESSION['salesOrder']->update_cart($_GET['update_cart'],$_GET['quantity'],$_GET['total']);
				$_SESSION['salesOrderUpdated']=1;
			}
			if (isset($_GET['set_date']))
			{
				$_SESSION['salesOrder']->setDeliveryDate($_GET['set_date']);
				$_SESSION['salesOrderUpdated']=1;
			}
			if (isset($_GET['update_discount']) && isset($_SESSION['salesOrder']))
			{
				$discount=$dao->sanitize($_GET['discount']);
				$_SESSION['salesOrder']->update_discount($_GET['update_discount'],$discount);
				$_SESSION['salesOrderUpdated']=1;
			}

			echo '<form method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="sales_order_cart_form">';
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$productBatch=$dao->getInventoryItemByNameAndBatch($SearchString,$_GET['batch'],$_GET['location']);
				$AlreadyOnThisCart =0;
				if (count($_SESSION['salesOrder']->LineItems)>0){
					   foreach ($_SESSION['salesOrder']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['salesOrder']->LineCounter;
						    if ($OrderItem->productID ==$productBatch['id'] && $OrderItem->batch ==$productBatch['batch_no'])
								 {
							     $AlreadyOnThisCart = 1;
									 $dao->showMessage("product already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$profit=$productBatch['selling_price']-$productBatch['buying_price'];
							$discount=$productBatch['discount']*$productBatch['selling_price'];
							$discount=(int)$discount;
							$_SESSION['salesOrder']->add_to_cart($productBatch['id'],1,$productBatch['description'],$productBatch['selling_price'],
							$discount,0,0,0,0,$productBatch['buying_price'],$productBatch['name'],$productBatch['batch_no'],0,$productBatch['discount'],-1);
						}
				}//end of if(isset($_POST['add_cart]))
				echo '</form>';
			 ?>
			 <div class="pull-right" style="margin-bottom:10px;">
 				<form class="navbar-form">
 			 <button name="clear_order" type="submit" class="glyphicon glyphicon-refresh"></button>
 		 </form>
 		 </div>
			 <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
         <h2 class="form-signin-heading">Cash Sale</h2>
				 <input type="hidden" name="location" id="location" value="<?php echo $_SESSION['log_location']['id']; ?>"/>
         <label for="entry_date">Date</label>
         <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['salesOrder']->orderDate; ?>"
 					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
 				<?php
 					if (count($_SESSION['salesOrder']->LineItems)>0)
 					{
 				?>
 				<table id="sales_order_table" class="table-condensed">
 					<thead>
             <tr>
 							<th style="width:25%;">Product</th>
							<th>Batch</th>
 							<th>Quantity</th>
 							<th>Price</th>
 							<th>Discount</th>
							<th>VAT</th>
 							<th>Amount</th>
 							<?php if($_SESSION['salesOrder']->deliveryStarted==0){
 							echo '<th></th>';
 						} ?>
 						</tr>
           </head>
         <tbody>
 					<?php
 					foreach ($_SESSION['salesOrder']->LineItems as $order)
 					{
 						$itemProfit=$order->Price-$order->buyingPrice;
 						$lineProfit=($order->Price-$order->buyingPrice)*$order->Quantity;
 					?>
         <tr id="<?php echo $order->LineNumber ?>">
         <input type="hidden" name="product[]"  value="<?php echo $order->productID ?>"/>
 				<input type="hidden" name="buying_price[]" id="bp_<?php echo $order->LineNumber ?>" value="<?php echo $order->buyingPrice ?>"/>
 				<input type="hidden" name="profit[]" id="profit_<?php echo $order->LineNumber ?>" value="<?php echo $lineProfit?>"/>
 				<td><input type="text"  class="form-control drug" placeholder="Product"
  				name="name[]" id="drug_0" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->name ?>"
  				required readonly/>
  				 </td>
					 <td><input type="text"  class="form-control batch" placeholder="Batch"
					 	name="batch[]" id="batch_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
						value="<?php echo $order->batch ?>" required readonly=""/>
					 	 </td>
 				 <?php if($_SESSION['salesOrder']->deliveryStarted==0){ ?>
 				 <td><input type="text"  class="form-control quantity" placeholder="Quantity Ordered"
  					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
 					 value="<?php echo $order->Quantity ?>" required=""/></td>
 			   <?php } else{ ?>
 					<td><input type="text"  class="form-control quantity" placeholder="Quantity Ordered"
 						name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
 						value="<?php echo $order->Quantity ?>" required="" readonly=""/></td>
 		     <?php }?>
 				 <td><input type="text"  class="form-control" placeholder="Price"
  					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Price ?>" readonly/>
  				 </td>
 				 <td><input type="text"  class="form-control discount" placeholder="Discount"
  					 name="discount[]" id="discount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Discount ?>"
 					 required="" />
  				 </td>
					 <input type="hidden" name="discount_percentage[]" id="discount_percentage_<?php echo $order->LineNumber ?>"
					 value="<?php echo $order->percentageDiscount ?>"/>
 				 <td><input type="text"  class="form-control vat" placeholder="VAT"
  					 name="vat[]" id="vat_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
						 required="" value="<?php echo $order->tax ?>" readonly=""/>
  				 </td>
					 <td><input type="text"  class="form-control amount" placeholder="Amount"
	  					 name="amount[]" id="amount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
							 required="" readonly=""/>
	  			</td>
 				 <?php
				 echo "<td><a href='".$_SERVER['PHP_SELF']."?"."Delete=".$order->LineNumber ."'>
				<span class='glyphicon glyphicon-trash'></span></a></td>";
 				echo '</tr>';
 			 }?>
         </tbody>
         </table>
				 <div class="form-inline" style="float:right;">
					 <label for="paid">Cash</label>
					 <input type="number" class="form-control" id="paid" name="paid" required="" min="<?php echo $_SESSION['salesOrder']->total ?>"/>
					<label for="total">Total</label>
					<input type="number" class="form-control" name="total" id="total" value="<?php echo $_SESSION['salesOrder']->total ?>" readonly=""/>
					<label for="total">Discount</label>
	 				<input type="number" class="form-control" name="discount_sum" id="discount_sum" value="0" readonly=""/>
					<label for="total">Balance</label>
					<input type="number" class="form-control" id="balance" name="balance" readonly=""/>
				</div>
				<div style="clear: both;"></div>
 				<?php }
				echo '<br><input type="text" class="form-control" name="product_search" id="product_search"
				placeholder="Type three characters to display product" />
				<div id="result"></div>';
         echo '<button type="submit" name="sale" id="order" class="btn btn-lg btn-primary"
 				style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Sale</button>';
       echo '</form>';
		}
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}

			require 'footer.php';?>
  </body>
  </html>
