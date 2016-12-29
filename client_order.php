<?PHP
	require 'data_access_object.php';
	require 'salesOrderCart.php';
	$dao=new DAO();
	$pageSecurity=6;
	$dao->checkLogin();
	if (isset($_POST['update']) && isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			if(isset($_SESSION['existing_order'])){
				$i=0;
				$dao->updateClientOrderById($entry_date,$_SESSION['existing_order']);
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
		header('Location:client_statement.php');
	}
	if (isset($_POST['order']) && isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0){
		$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
	$lastId=$dao->saveSalesOrder($entry_date,$_SESSION['log_user']);
	$i=0;
	foreach($_POST['product'] as $value) {
	if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
		$dao->saveClientOrderDetails($lastId,$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i],$_POST['amount'][$i],
		$_POST['discount'][$i],$_POST['buying_price'][$i],$_POST['profit'][$i]);
	}
	$i++;
	}
	unset($_SESSION['salesOrder']);
	unset($_SESSION['salesOrderUpdated']);
	header('Location:client_statement.php');
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
		  url:"client_order.php?add_cart="+ui.item.value,
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
	 url:"client_order.php?set_date="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".quantity").change(function(){
	var totalAmount=0;
	var quantityId=$(this).attr("id");
	var id=quantityId.substring(quantityId.indexOf("_")+1);
	document.getElementById("profit_"+id).value=parseInt($(this).val())*parseInt($("#bp_"+id).val());
	var profit=parseInt($(this).val())*parseInt($("#bp_"+id).val());
	var discount=parseFloat(0.5)*profit;
	var lineAmount=parseInt($(this).val())*parseInt($("#price_"+id).val())-discount;
	document.getElementById("discount_"+id).value=discount;
	document.getElementById("amount_"+id).value=lineAmount;
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"client_order.php?update_cart="+id+"&quantity="+value,
	 success:function(result){
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
});
	</script>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu($_SESSION['tab_no']);
    ?>
  	<div id="menu_main">
			<a href="products.php">Products</a>
      <a href="client_statement.php">Statement</a>
			<a href="client_order" id="item_selected">Order</a>
      </div>
			<?php
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
				if(isset($_REQUEST['clear_order'])){
					unset($_SESSION['salesOrder']);
				}
			if (!isset($_SESSION['salesOrder'])){
				 $_SESSION['salesOrder'] = new Cart();
			}
			if (!isset($_SESSION['salesOrderUpdated'])){
				$_SESSION['salesOrderUpdated']=0;
			}
			if(isset($_GET['SelectedOrder'])){
				$_SESSION['existing_order']=$_GET['SelectedOrder'];
				if($_SESSION['salesOrderUpdated']==0){
					$_SESSION['salesOrder'] = new Cart();
					$salesOrder=$dao->getSalesOrderById($_GET['SelectedOrder']);
					$orderProducts=$dao->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
					$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
					$_SESSION['salesOrder']->deliveryStarted=$salesOrder['delivery_started'];
					foreach($orderProducts as $product){
					$_SESSION['salesOrder']->add_to_cart($product['id'],$product['quantity'],$product['description'],$product['price'],$product['discount'],$product['quantity_delivered'],0,0,0,$product['buying_price'],$product['name'],-1);
				}
			}
			}
			if (isset($_GET['Delete']))
			{
				$_SESSION['salesOrder']->remove_from_cart($_GET['Delete']);
			}
			if (isset($_GET['update_cart']))
			{
				$quantity=$dao->sanitize($_GET['quantity']);
				$_SESSION['salesOrder']->update_cart($_GET['update_cart'],$quantity);
				$_SESSION['salesOrderUpdated']=1;
			}
			if (isset($_GET['set_date']))
			{
				$date=$dao->sanitize($_GET['set_date']);
				$_SESSION['salesOrder']->setDeliveryDate($date);
				$_SESSION['salesOrderUpdated']=1;
			}
			$hasPendingOrder=$dao->getClientOrderingValidity($_SESSION['log_user']);
			if($hasPendingOrder['count']>0 && !isset($_SESSION['existing_order'])){
					echo '<div class="alert alert-danger">
						<strong>Please clear payment for pending invoice before creating a new one</strong>
					</div>';
			}
			else{
			echo '<form class="form-signin" method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="sales_order_cart_form">';
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$product=$dao->getInventoryItemByName($SearchString);
				$AlreadyOnThisCart =0;
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
							$_SESSION['salesOrder']->add_to_cart($product['id'],1,$product['description'],$product['selling_price'],$discount,0,0,0,0,$product['buying_price'],$product['name']-1);
						}
				}//end of if(isset($_POST['add_cart]))
				echo '</form>';
			 ?>
			 <div class="col-sm-3 col-md-2 pull-right" style="margin-bottom:10px;">
 				<a href="products.php" class="btn btn-default btn-primary">View Products</a>
 			</div>
			<div class="pull-right" style="margin-bottom:10px;">
				<form class="navbar-form">
			 <button name="clear_order" type="submit" class="glyphicon glyphicon-refresh"></button>
		 </form>
		 </div>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Order Products</h2>
        <label for="entry_date">Date You Want Delivery</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['salesOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
				<?php
					if (count($_SESSION['salesOrder']->LineItems)>0)
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
				<input type="hidden" name="buying_price[]" id="bp_<?php echo $order->LineNumber ?>" value="<?php echo $itemProfit ?>"/>
				<input type="hidden" name="profit[]" id="profit_<?php echo $order->LineNumber ?>" value="<?php echo $lineProfit?>"/>
				<td><input type="text"  class="form-control drug" placeholder="Product"
 				name="name[]" id="drug_0" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->name ?>"
 				required readonly/>
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
				 <td><input type="text"  class="form-control" placeholder="Quantity Already Delivered"
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
				 if($_SESSION['salesOrder']->deliveryStarted==0){
				echo "<td><a href='".$_SERVER['PHP_SELF']."?"."Delete=".$order->LineNumber ."'>
				<span class='glyphicon glyphicon-trash'></span></a></td>";
				}
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
				<label for="total">Total Amount</label>
				<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
				</div>
				<div style="clear:both;"></div>';
				?>
				<br><br>
				<?php
				if(isset($_SESSION['existing_order']) && $_SESSION['salesOrder']->deliveryStarted==0){
				echo	'<button type="submit" name="update" id="update" class="btn btn-lg btn-primary"
					style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Update Order</button>';
				}
				elseif (count($_SESSION['salesOrder']->LineItems)>0 && !isset($_SESSION['existing_order'])
				&& $_SESSION['salesOrder']->deliveryStarted==0)
				{
        echo '<button type="submit" name="order" id="order" class="btn btn-lg btn-primary"
				style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Order Products</button>';
				 }
      echo '</form>';
		}
	 }
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
  </body>
  </html>
