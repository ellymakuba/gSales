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
  <?PHP $dao->includeMenu(2); ?>
	<div id="menu_main">
		<a href="manage_inventory.php" id="item_selected">Product List</a>
		<a href="product_details.php">Product Details</a>
		<a href="purchase_order_list.php">Purchase Order List</a>
		<a href="purchase_order.php">Purchase Order</a>
    </div>
	<?php	if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
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
  <table class="table table-striped">
  <tr>
  <form method="POST">
            <th>Action</th>
            <th>Name</th>
            <th>Description</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
						<th>Stock</th>
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
  $product['description'],
  $product['buying_price'],
  $product['selling_price'],
	$product['quantity']
    );
    }
  }
  else{
    $products=$dao->getAllProducts();
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
	  $product['description'],
	  $product['buying_price'],
	  $product['selling_price'],
		$product['quantity']
    );
    }
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
