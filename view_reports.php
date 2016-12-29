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
  <?PHP
	$_SESSION['tab_no']=3;
	$dao->includeMenu($_SESSION['tab_no']);
	if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
  <table class="table table-striped table-condensed">
  <tr>
		  <th>Name</th>
      <th>Unit Price</th>
      <th>Quantity</th>
			<th>Value</th>
  </tr>
  <?php
	if (isset($_GET['pageno'])) {
				 $pageno = $_GET['pageno'];
			} else {
				 $pageno = 1;
			}
			$numofrows=$dao->getProductsCount();;
			$targetpage = "view_reports.php";
			$rows_per_page = 12;
			$lastpage  = ceil($numofrows['count']/$rows_per_page);
			$pageno = (int)$pageno;
			if ($pageno > $lastpage) {
				 $pageno = $lastpage;
			} // if
	$start =($pageno - 1) * $rows_per_page;
	$products=$dao->getAllProducts($start,$rows_per_page);
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
		echo "<tr><td>";
		if ($pageno == 1) {
				   echo "<span class='glyphicon glyphicon-fast-backward'>";
				} else {
				   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=1'><span class='glyphicon glyphicon-fast-backward'></span></a> ";
				   $prevpage = $pageno-1;
				   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'><span class='glyphicon glyphicon-backward'></span></a> ";
				}
				echo " ($pageno of $lastpage) ";
				if ($pageno == $lastpage) {
				   echo "<span class='glyphicon glyphicon-fast-forward'> ";
				} else {
				   $nextpage = $pageno+1;
				   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'><span class='glyphicon glyphicon-forward'></span></a> ";
				   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'><span class='glyphicon glyphicon-fast-forward'></span></a> ";
				}
				echo "</td></tr>";
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
