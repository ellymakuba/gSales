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
    <?PHP $dao->includeMenu($_SESSION['tab_no']);
    if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
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
					0,0,0,0,$product['buying_price'],$product['name'],$product['batch_no'],$product['tax'],0,-1);
				}
			}
			}
			$balance=$_REQUEST['paid']-$_REQUEST['total'];
			 ?>
			 <form class="form-signin" method="POST">
         <h2 class="form-signin-heading">Paid: <?php echo number_format($_REQUEST['paid'],2) ?></h2>
				 <h2 class="form-signin-heading">Total: <?php echo number_format($_REQUEST['total'],2) ?></h2>
				 <h2 class="form-signin-heading">Balance: <?php echo number_format($balance,2) ?></h2>
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
					 	<tr><td>Paid: <?php echo number_format($_REQUEST['paid'],2) ?></td></tr>
						<tr><td>Total: <?php echo number_format($_REQUEST['total'],2) ?></td></tr>
						<tr><td>Balance: <?php echo number_format($balance,2) ?></td></tr>
         </tbody>
         </table>
			 </div>
		<?php
	unset($_SESSION['salesOrder']);}
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
