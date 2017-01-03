<?PHP
	require 'data_access_object.php';
	require 'purchaseOrderCart.php';
	$dao=new DAO();
	$pageSecurity=3;
	$dao->checkLogin();
	if (isset($_POST['receive'])){
		if(isset($_POST['entry_date']) && isset($_POST['supplier'])){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			$lastId=$dao->savePurchaseOrder($entry_date,$_POST['supplier'],$_SESSION['log_location']['id']);
		$i=0;
		foreach($_POST['product'] as $value){
			if(isset($_POST['product'][$i]) && isset($_POST['number'][$i]) && isset($_POST['expiry_date'][$i]) && isset($_POST['batch_no'][$i])){
				$expiry_date=date('Y-m-d',strtotime($_POST['expiry_date'][$i]));
				$dao->savePurchaseOrderDetails($lastId,$_POST['product'][$i],$expiry_date,$_POST['batch_no'][$i],
				$_POST['number'][$i],$_POST['units'][$i],$_POST['price'][$i],$_POST['discount'][$i],$_POST['amount'][$i],$_POST['bonus'][$i]);
				$productExists=$dao->checkIfProductBatchExistsInInventory($_POST['product'][$i],$_POST['batch_no'][$i]);
				$number=$_POST['number'][$i]+$_POST['bonus'][$i];
				$quantity=$_POST['units'][$i]*$number;
				$discount=$_POST['discount'][$i]/$_POST['amount'][$i];
				if($productExists['count']>0){
					$dao->addStockToInventory($productExists['product_id'],$productExists['batch_no'],$quantity,$_POST['bonus'][$i],$discount);
				}
				else{
					$dao->saveInventory($_POST['product'][$i],$quantity,$expiry_date,$_POST['batch_no'][$i],$_POST['bonus'][$i],
					$_SESSION['log_location']['id'],$discount);
			}
			}
			$i++;
		}
		unset($_SESSION['purchaseOrder']);
		unset($_SESSION['existing_order']);
		unset($_SESSION['purchaseOrderUpdated']);
		header("Location:manage_purchase_order.php");
	}
	}
/*	if(isset($_POST['update']) && isset($_SESSION['existing_order'])){
		$i=0;
		if(isset($_POST['entry_date']) && isset($_POST['supplier'])){
		$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
		foreach($_POST['product'] as $value){
			if(isset($_POST['product'][$i]) && isset($_POST['number'][$i]) && isset($_POST['batch_no'][$i])){
				$productExists=$dao->checkIfProductExistsInInventory($_POST['number'][$i]);
				if(count($productExists)>0){
					$dao->updatePurchaseOrder($_SESSION['existing_order'],$_POST['product'][$i],$_POST['number'][$i],$_POST['batch_no'][$i]);
				}
			}
			$i++;
		}
	}
	unset($_SESSION['purchaseOrder']);
	unset($_SESSION['existing_order']);
	unset($_SESSION['purchaseOrderUpdated']);
	header("Location:manage_purchase_order.php");
} */
  ?>
  <html>
  <?PHP $dao->includeHead('Purchase Order',0) ?>
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

    $(document).on('focus','.myDate', function(){
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
	 	$.getJSON("autocomplete_product.php?term="+request.term,function(result){
		 response($.map(result,function(item){
		 	return{
			id:item.id,
			value:item.name,
			cost:item.cost
			}
		 }))
		})
	 },
	 select:function(event,ui){
		 $.ajax({
	    type:"GET",
		  url:"purchase_order.php?add_cart="+ui.item.value,
		  async:false,
		  success:function(result){
		  $("#purchase_order_cart_form").submit();
		  }
		});
	 },
	 minLength:3,
	  messages: {
	      noResults: '',
	      results: function(){}
	    }
	  });
		return false;
	});
$("#entry_date").datepicker();
$("#entry_date").change(function(){
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_date="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".batchNo").change(function(){
	var extract=$(this).attr("id");
	var id=extract.substring(extract.indexOf("_")+1);
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_batch="+id+"&batchNo="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".mydate").change(function(){
	var extract=$(this).attr("id");
	var id=extract.substring(extract.indexOf("_")+1);
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_expiry_date="+id+"&expiry_date="+value,
	 async:false,
	 success:function(result){}
 });
});
$("#supplier").change(function(){
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_supplier="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".number").change(function(){
	var totalAmount=0;
	var numberId=$(this).attr("id");
	var id=numberId.substring(numberId.indexOf("_")+1);
	var amount_before_discount=parseInt($(this).val())*parseInt($("#price_"+id).val());
	document.getElementById("amount_"+id).value=amount_before_discount-parseInt($("#discount_"+id).val());
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?update_cart="+id+"&number="+value,
	 success:function(result){
	 }
 });
	document.getElementById("total").value=totalAmount;
});
$(".discount").change(function(){
	var totalAmount=0;
	var numberId=$(this).attr("id");
	var id=numberId.substring(numberId.indexOf("_")+1);
	var amount_before_discount=parseInt($("#number_"+id).val())*parseInt($("#price_"+id).val());
	document.getElementById("amount_"+id).value=amount_before_discount-parseInt($(this).val());
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?update_discount="+id+"&discount="+value,
	 success:function(result){
	 }
 });
	document.getElementById("total").value=totalAmount;
});
$(".bonus").change(function(){
	var numberId=$(this).attr("id");
	var id=numberId.substring(numberId.indexOf("_")+1);	
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?update_bonus="+id+"&bonus="+value,
	 success:function(result){
	 }
 });
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
	var amount_before_discount=parseInt($("#number_"+id).val())*parseInt($("#price_"+id).val());
	$lineTotal=amount_before_discount-parseInt($("#discount_"+id).val());
	document.getElementById("amount_"+id).value=$lineTotal;
	tAmount +=$lineTotal;
});
document.getElementById("total").value=tAmount;
});
	</script>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu($_SESSION['tab_no']);
			if(isset($_REQUEST['clear_order'])){
				unset($_SESSION['purchaseOrder']);
				unset($_SESSION['existing_order']);
			}
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
				if(isset($_SESSION['purchaseOrder'])){
				echo '<div class="pull-right" style="margin-bottom:10px;">
					<form class="navbar-form">
				 <button name="clear_order" type="submit" class="glyphicon glyphicon-refresh"></button>
			 </form>
			 </div>';
		 }
			echo '<form method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="purchase_order_cart_form">';
				if (!isset($_SESSION['purchaseOrder'])){
					 $_SESSION['purchaseOrder'] = new PurchaseOrderCart();
				}
				if(isset($_GET['edit_cart'])){
				}
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$product=$dao->getProductByName($SearchString);
				echo $product['name'];
				$AlreadyOnThisCart =0;
				$number=1;
				if (count($_SESSION['purchaseOrder']->LineItems)>0){
					   foreach ($_SESSION['purchaseOrder']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['purchaseOrder']->LineCounter;
						    if ($OrderItem->productID ==$product['id'])
								 {
							     $AlreadyOnThisCart = 1;
									 $dao->showMessage("product already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$_SESSION['purchaseOrder']->add_to_cart($product['id'],$number,$product['name'],$product['buying_price_pack'],'','',0,0,
							$product['units_per_pack'],-1);
						}
				}
				echo '</form>';
				if(!isset($_SESSION['purchaseOrderUpdated'])){
					$_SESSION['purchaseOrderUpdated']=0;
				}
				if (isset($_GET['Delete']))
				{
					$_SESSION['purchaseOrder']->remove_from_cart($_GET['Delete']);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['update_cart']) && isset($_SESSION['purchaseOrder']))
				{
					$number=$dao->sanitize($_GET['number']);
					$_SESSION['purchaseOrder']->update_cart($_GET['update_cart'],$number);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['update_discount']) && isset($_SESSION['purchaseOrder']))
				{
					$discount=$dao->sanitize($_GET['discount']);
					$_SESSION['purchaseOrder']->update_discount($_GET['update_discount'],$discount);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['update_bonus']) && isset($_SESSION['purchaseOrder']))
				{
					$bonus=$dao->sanitize($_GET['bonus']);
					$_SESSION['purchaseOrder']->update_bonus($_GET['update_bonus'],$bonus);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['set_date']) && isset($_SESSION['purchaseOrder']))
				{
					$date=$dao->sanitize($_GET['set_date']);
					$_SESSION['purchaseOrder']->setDeliveryDate($date);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['set_supplier']) && isset($_SESSION['purchaseOrder']))
				{
					$supplier=$dao->sanitize($_GET['set_supplier']);
					$_SESSION['purchaseOrder']->setSupplier($supplier);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['set_batch']) && isset($_SESSION['purchaseOrder']))
				{
					$set_batch=$dao->sanitize($_GET['set_batch']);
					$batchNo=$dao->sanitize($_GET['batchNo']);
					$_SESSION['purchaseOrder']->setLineItemBatchNo($set_batch,$batchNo);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if (isset($_GET['set_expiry_date']) && isset($_SESSION['purchaseOrder']))
				{
					$expiry_date=$dao->sanitize($_GET['expiry_date']);
					$_SESSION['purchaseOrder']->setLineItemExpiryDate($_GET['set_expiry_date'],$expiry_date);
					$_SESSION['purchaseOrderUpdated']=1;
				}
				if(isset($_GET['SelectedOrder'])){
					$client_username=$dao->getClientByOrderId($_GET['SelectedOrder']);
					$_SESSION['client']=$client_username['client'];
					$_SESSION['existing_order']=$_GET['SelectedOrder'];
					if($_SESSION['purchaseOrderUpdated']==0){
					$_SESSION['purchaseOrder'] = new PurchaseOrderCart();
					$purchaseOrder=$dao->getPurchaseOrderById($_GET['SelectedOrder']);
					$orderProducts=$dao->getPurchaseOderDetailsByOrderId($purchaseOrder['purchase_order_id']);
					$_SESSION['purchaseOrder']->orderDate=$purchaseOrder['entry_date'];
					$_SESSION['purchaseOrder']->setSupplier($purchaseOrder['supplier_id']);
					foreach($orderProducts as $product){
						$_SESSION['purchaseOrder']->add_to_cart($product['id'],$product['number_purchased'],$product['name'],$product['price'],$product['expiry_date'],$product['batch_no'],
						$product['discount'],$product['bonus'],$product['units'],-1);
					}
				}
				}

			 ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Purchase Order</h2>
        <label for="entry_date">Delivery Date</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['purchaseOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
					 <label for="supplier">Supplier</label>
        <select  name="supplier" id="supplier" class="form-control" required>
          <option disabled selected>Select Supplier</option>
					<?php
					$suppliers=$dao->getAllSuppliers();
					foreach($suppliers as $supplier){
						if($_SESSION['purchaseOrder']->supplier==$supplier['id']){
							echo '<option selected value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
						}
						else{
							echo '<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
						}
						}
					 ?>
        </select>
				<?php
					if (count($_SESSION['purchaseOrder']->LineItems)>0)
					{
				?>
				<table class="table_condensed" id="purchase_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr><th style="width:25%;">Product</th>
							<th>Expiry Date</th>
							<th>Batch No</th>
							<th>Number</th>
							<th>Units</th>
							<th>Bonus</th>
							<th>Price</th>
							<th>Disc</th>
							<th>Amount</th>
						</tr>
          </head>
        <tbody>
					<?php
					foreach ($_SESSION['purchaseOrder']->LineItems as $order)
					{
					?>
        <tr id="<?php echo $order->LineNumber ?>">
        <input type="hidden"  class="form-control" placeholder="Product" name="product[]"  value="<?php echo $order->productID ?>"/>
				<td><input type="text"  class="form-control drug" placeholder="Product"
 				name="name[]" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->ItemDescription ?>"
 				required readonly/>
 				 </td>
				 <td><input type="text"  class="form-control myDate expiryDate" placeholder="Expiry Date" value="<?php echo $order->expiryDate ?>"
 					 name="expiry_date[]" id="expiry_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"/>
 				 </td>
         <td><input type="text"  class="form-control batchNo" placeholder="Batch No" value="<?php echo $order->batchNo?>"
 					 name="batch_no[]" id="batch_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"/>
 				 </td>
				 <td><input type="number"  class="form-control number" placeholder="number"
 					 name="number[]" id="number_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->number ?>" required="" />
 				 </td>
				 <td><input type="number"  class="form-control units" placeholder="Units"
 					 name="units[]" id="units_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->units ?>" required="" readonly=""/>
 				 </td>
				 <td><input type="number"  class="form-control bonus" placeholder="bonus"
 					 name="bonus[]" id="bonus_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->bonus ?>" required="" />
 				 </td>
				 <td><input type="text"  class="form-control" placeholder="Price"
 					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Price ?>" readonly/>
 				 </td>
				 <td><input type="number"  class="form-control discount" placeholder="Discount"
 					 name="discount[]" id="discount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->discount ?>" required="" />
 				 </td>
				 <td><input type="text"  class="form-control amount" placeholder="Amount"
 					 name="amount[]" id="amount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required=""/>
 				 </td>
				 <?php
				 if(!isset($_SESSION['existing_order'])){
				 echo "<td><a href='".$_SERVER['PHP_SELF']."?"."Delete=".$order->LineNumber ."'><span class='glyphicon glyphicon-trash'>
				 </span></a></td>";
			 }
				echo '</tr>';
			 }?>
        </tbody>
        </table>
				<?php }
				echo '<div class="form-inline" style="float:right;">
				<label for="total">Total Amount</label>
				<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
				</div>
				<div style="clear:both;"></div>';
				echo '<br><input type="text" class="form-control" name="product_search" id="product_search"
				placeholder="Type three characters to display product" />
				<div id="result"></div>';
				?>
				<br><br>
				<?php if(isset($_SESSION['existing_order'])){
					/*
					echo '<button type="submit" name="update" id="order" class="btn btn-lg btn-primary"
						style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Update Order</button>';
						*/
				}
				else{
					echo '<button type="submit" name="receive" id="order" class="btn btn-lg btn-primary"
						style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Receive Order</button>';
				}
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
