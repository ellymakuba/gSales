<?PHP
	require 'data_access_object.php';
	require 'salesOrderCart.php';
	$dao=new DAO();
	$pageSecurity=6;
	$dao->checkLogin();
  ?>
  <html>
  <?PHP $dao->includeHead('Sales Receipt',0) ?>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu(1);
    ?>
  	<div id="menu_main">
			<a href="cash_sale.php">Cash Sale</a>
			<a href="credit_sale.php">Credit Sale</a>
			<a href="client_list.php">Client List</a>
			<a href="receipt.php" id="item_selected">Receipt</a>
      </div>
			<?php
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
			if (!isset($_SESSION['salesOrder'])){
				 $_SESSION['salesOrder'] = new Cart();
				 $_SESSION['salesOrder']->orderDate=date('m/d/Y');
				 $_SESSION['salesOrderUpdated']=0;
			}
			if(isset($_GET['SelectedOrder'])){
				$_SESSION['existing_order']=$_GET['SelectedOrder'];
				if($_SESSION['salesOrderUpdated']==0){
					$_SESSION['salesOrder'] = new Cart();
					$salesOrder=$dao->getSalesOrderById($_GET['SelectedOrder']);
					$orderProducts=$dao->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
					$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
				foreach($orderProducts as $product){
					$_SESSION['salesOrder']->add_to_cart($product['id'],$product['quantity'],$product['description'],$product['price'],$product['discount'],
					0,0,0,0,$product['buying_price'],$product['name'],-1);
				}
			}
			}
			 ?>
			 <form class="form-signin" method="POST">
         <h2 class="form-signin-heading">Paid: <?php echo number_format($_SESSION['paid'],2) ?></h2>
				 <h2 class="form-signin-heading">Total: <?php echo number_format($_SESSION['total'],2) ?></h2>
				 <h2 class="form-signin-heading">Balance: <?php echo number_format($_SESSION['balance'],2) ?></h2>
			 </form>
			 <div id="print_area">
         <label for="entry_date">Date</label><?php echo $_SESSION['salesOrder']->orderDate ?>
 				<table style="border-spacing:2px;border-collapse:separate;width:30%;">
 					<thead>
             <tr>
 							<th>Product</th>
 							<th>Quantity</th>
 							<th>Price</th>
 							<th>Discount</th>
 							<th>Amount</th>
 						</tr>
           </head>
         <tbody>
 					<?php
 					foreach ($_SESSION['salesOrder']->LineItems as $order)
 					{
						$amount=$order->Quantity*$order->Price-$order->Discount;
		 					?>
		         <tr>
		 				 <td><?php echo $order->name ?></td>
		 					<td><?php echo $order->Quantity ?></td>
		 				 <td><?php echo $order->Price ?></td>
		 				 <td><?php echo $order->Discount ?></td>
		 				 <td><?php echo $amount ?></td>
		 				</tr>;
		 			 	<?php }?>
				 		<tr></tr><tr></tr><tr></tr>
					 	<tr><td>Total: <?php echo number_format($_SESSION['total'],2) ?></td></tr>
						<tr><td>Paid: <?php echo number_format($_SESSION['paid'],2) ?></td></tr>
						<tr><td>Balance: <?php echo number_format($_SESSION['balance'],2) ?></td></tr>
			 		</tr>
         </tbody>
         </table>
			 </div>
		<?php }
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
			require 'footer.php';
		}
		echo '</br></br>';
			?>
  </body>
	<script type="text/javascript">
   var prtContent = document.getElementById('print_area');
    var WinPrint = window.open('', '','width=600,height=650');
    if(!WinPrint || WinPrint.closed || typeof WinPrint.closed=='undefined')
    {
        alert('please unblock pop ups in your browser to be able to print invoice');
    }
    else
    {
    var str =  prtContent.innerHTML;
    WinPrint.document.write(str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
    }

</script>
  </html>
