<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$pageSecurity=4;
	$dao->checkLogin();
	?>
  <html>
  <?PHP $dao->includeHead('Sales Order List',0) ?>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu(1); ?>
	<div id="menu_main">
    <a href="manage_orders.php" id="item_selected">Uncleared sales Orders</a>
		<a href="client_orders_list.php">Client Orders List</a>
    </div>
		<?php if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){ ?>
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
    <a href="sales_order.php" class="btn btn-default btn-primary">New Sales Order</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form method="POST">
    <th>#</th>
    <th>Date Required</th>
    <th>Client</th>
		<th>Client</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $salesOrders=$dao->getClientSalesOrders($_REQUEST['srch-term']);
    foreach($salesOrders as $order){
    printf("<tr><td><a href=\"sales_order.php?SelectedOrder=%s\">Dispatch</a></td>
    <td>%s</td>
		<td><a href=\"sales_order.php?updateOrder=%s\">Update</a></td>
		<td>%s</td>
    </tr>",
    $order['sales_order_id'],
    $order['date_required'],
		$order['sales_order_id'],
    $order['client']
    );
    }
  }
  else{
    $salesOrders=$dao->getAllSalesOrders();
		foreach($salesOrders as $order){
      printf("<tr><td><a href=\"sales_order.php?SelectedOrder=%s\">Dispatch</a></td>
			<td>%s</td>
			<td><a href=\"sales_order.php?updateOrder=%s\">Update</a></td>
			<td>%s</td>
	    </tr>",
	    $order['sales_order_id'],
	    $order['date_required'],
			$order['sales_order_id'],
	    $order['client']
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
		require 'footer.php'; ?>		
  </body>
  </html>
