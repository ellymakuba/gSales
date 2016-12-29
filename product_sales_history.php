<?PHP
require 'data_access_object.php';
$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=5;
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Product Sales History',0) ?>
  <script>
	$(document).ready(function(){
		$("#start_date").datepicker();
		$("#end_date").datepicker();
	});
	</script>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu($_SESSION['tab_no']);
	if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
    <div class=" pull-left">
          <form class="navbar-form" role="search">
						<div class="form-inline">
              <select  name="product" class="form-control" required>
      					<option disabled selected>Select Product</option>
      					<?php
      					$products=$dao->getAllProducts();
      					foreach($products as $product){
      							echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
      					}
      					?>
      				</select>
              <input type="text" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
							<input type="text" class="form-control" placeholder="End Date" name="end_date" id="end_date">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
          </div>
          </form>
  </div>
  <?php
	$sales=0;
	$discount=0;
	$totalBalance=0;
	$payment=0;
	$profit=0;
  $totalQuantitySold=0;
	if(isset($_REQUEST['start_date']) && isset($_REQUEST['end_date']) && isset($_REQUEST['product'])){
		$_SESSION['start_date_ph']=$_REQUEST['start_date'];
		$_SESSION['end_date_ph']=$_REQUEST['end_date'];
    $_SESSION['product_ph']=$_REQUEST['product'];
	}
  if(isset($_SESSION['start_date_ph']) && isset($_SESSION['end_date_ph']) && isset($_SESSION['product_ph'])){
    $productSelected=$dao->getProductById($_SESSION['product_ph']);
		?>
		<table class="table table-striped">
			<tr><th colspan="7">
				<h2 class="form-signin-heading"><?php echo $productSelected['name'] ?>:<?php echo $_SESSION['start_date_ph'] ?>-<?php echo $_SESSION['end_date_ph'] ?></h2>
				</th></tr>
		  <tr>
		      <th>Date</th>
          <th>Sales</th>
		      <th>Discount</th>
		      <th>Payment</th>
					<th>Balance</th>
					<th>Profit</th>
          <th>Quantity Sold</th>
		  </tr>
			<?php
    $products=$dao->getProductSalesHistory($_SESSION['product_ph'],$_SESSION['start_date_ph'],$_SESSION['end_date_ph']);
    foreach($products as $product){
			$balance=$product['sales']-$product['discount']-$product['payment'];
			$sales=$sales+$product['sales'];
			$discount=$discount+$product['discount'];
			$totalBalance=$totalBalance+$balance;
			$payment=$payment+$product['payment'];
			$profit=$profit+$product['profit'];
      $totalQuantitySold=$totalQuantitySold+$product['qsold'];
      printf("<tr>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
		<td>%s</td>
    <td>%s</td>
    </tr>",
	  $product['date_required'],
    $product['sales'],
	  $product['discount'],
	  $product['payment'],
	  $balance,
		$product['profit'],
    $product['qsold']
    );
    }
		echo '<tr><td>Totals KSH:</td><td>'.number_format($sales,2).'</td>';
		echo '<td>'.number_format($discount,2).'</td>';
		echo '<td>'.number_format($payment,2).'</td>';
		echo '<td>'.number_format($totalBalance,2).'</td>';
		echo '<td>'.number_format($profit,2).'</td>';
    echo '<td>'.$totalQuantitySold.'</td>';
		echo '</tr>';
	  echo '</table>';
  }
	 ?>
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
