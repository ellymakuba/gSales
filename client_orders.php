<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	?>
  <html>
  <?PHP $dao->includeHead('Sales Order List',0) ?>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu($_SESSION['tab_no']); ?>
	<div id="menu_main">
    <a href="client_orders.php" id="item_selected">Client Orders List</a>
    <a href="client_order.php">Order</a>
    </div>
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
    <a href="sales_order.php" class="btn btn-default btn-primary">New Order</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form method="POST">
    <th>#</th>
    <th>Date Required</th>
    <th>Client</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $salesOrders=$dao->getAllClientOrders($_SESSION['log_user']);
    foreach($salesOrders as $order){
    printf("<tr><td><a href=\"client_order.php?SelectedOrder=%s\">View</a></td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
    $order['sales_order_id'],
    $order['entry_date'],
    $order['client']
    );
    }
  }
  else{
    $salesOrders=$dao->getAllClientOrders($_SESSION['log_user']);
		foreach($salesOrders as $order){
    printf("<tr><td><a href=\"client_order.php?SelectedOrder=%s\">" .$order['sales_order_id'] . "</a></td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
    $order['sales_order_id'],
    $order['date_required'],
    $order['client']
    );
    }
  }
  ?>
  </table>
    </div>
  </body>
  </html>
