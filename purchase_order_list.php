<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$pageSecurity=3;
	$dao->checkLogin();
	?>
  <html>
  <?PHP $dao->includeHead('Sales Order List',0) ?>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu($_SESSION['tab_no']);
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
    <a href="sales_order.php" class="btn btn-default btn-primary">New Purchase Order</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form method="POST">
    <th>#</th>
    <th>Date Required</th>
    <th>Supplier</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $salesOrders=$dao->getClientSalesOrders($_REQUEST['srch-term']);
    foreach($salesOrders as $order){
      printf("<tr><td><a href=\"purchase_order.php?SelectedOrder=%s\">" .$order['purchase_order_id'] . "</a></td>
      <td>%s</td>
  		<td>%s</td>
      </tr>",
      $order['purchase_order_id'],
      $order['entry_date'],
      $order['supplier_id']
    );
    }
  }
  else{
    $salesOrders=$dao->getAllPurchaseOrders();
		foreach($salesOrders as $order){
    printf("<tr><td><a href=\"purchase_order.php?SelectedOrder=%s\">" .$order['purchase_order_id'] . "</a></td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
    $order['purchase_order_id'],
    $order['entry_date'],
    $order['supplier_id']
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
