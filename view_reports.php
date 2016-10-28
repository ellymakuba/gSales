<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=5;
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Inventory Value',0) ?>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu(3); ?>
	<div id="menu_main">
		<a href="manage_inventory.php" id="item_selected">Inventory Value</a>
		<a href="sales_report.php">Sales</a>
		<a href="profit.php">Profit Report</a>
    </div>
		<?php if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
  <table class="table table-striped">
  <tr>
		  <th>Name</th>
      <th>Unit Price</th>
      <th>Quantity</th>
			<th>Value</th>
  </tr>
  <?php
    $products=$dao->getAllProducts();
		$totalInventory=0;
    foreach($products as $product){
			$productValue=$product['selling_price']*$product['quantity'];
			$totalInventory=$totalInventory+$productValue;
    printf("<tr>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
	  $product['name'],
	  $product['selling_price'],
		$product['quantity'],
		number_format($productValue,2)
    );
    }
		echo '<div style="float:right">';
		echo 'Stock Value Ksh. '.number_format($totalInventory,2);
		echo '</div><div style="clear:both">';
   ?>
  </table>
    </div>
		<?php }
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
  </body>
  </html>
