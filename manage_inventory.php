<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$pageSecurity=2;
	$dao->checkLogin();
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Product List',0) ?>
  </head>
  <body class="container">
  <?PHP
	$_SESSION['tab_no']=2;
	$dao->includeMenu($_SESSION['tab_no']);
		if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
    <div class="col-sm-3 col-md-3 pull-left">
          <form class="navbar-form" role="search">
          <div class="input-group">
              <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
              <div class="input-group-btn">
                  <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
          </div>
          </form>
  </div>
  <div class="col-sm-3 col-md-3 pull-right">
    <a href="product_details.php" class="btn btn-default btn-primary">New Product</a>
  </div>
  <table class="table table-condensed table-striped">
  <tr>
  <form method="POST">
            <th>Action</th>
            <th>Name</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
						<th>Stock</th>
						<th>Reorder Level</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $products=$dao->getProductByName($_REQUEST['srch-term']);
    foreach($products as $product){
      printf("<tr><td><a href=\"product_details.php?SelectedProduct=%s\">Edit</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
  $product['id'],
  $product['name'],
  $product['buying_price'],
  $product['selling_price'],
	$product['quantity'],
	$product['reorder_level']
    );
    }
  }
  else{
		if (isset($_GET['pageno'])) {
					 $pageno = $_GET['pageno'];
				} else {
					 $pageno = 1;
				}
				$numofrows=$dao->getProductsCount();;
				$targetpage = "manage_inventory.php";
				$rows_per_page = 12;
				$lastpage  = ceil($numofrows['count']/$rows_per_page);
				$pageno = (int)$pageno;
				if ($pageno > $lastpage) {
					 $pageno = $lastpage;
				} // if
		$start =($pageno - 1) * $rows_per_page;
    $products=$dao->getAllProducts($start,$rows_per_page);
    foreach($products as $product){
      printf("<tr><td><a href=\"product_details.php?SelectedProduct=%s\">Edit</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
		$product['id'],
	  $product['name'],
	  $product['buying_price'],
	  $product['selling_price'],
		$product['quantity'],
		$product['reorder_level']
    );
    }
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
  }
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
