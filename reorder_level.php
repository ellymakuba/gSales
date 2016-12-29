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
  <?PHP $dao->includeMenu($_SESSION['tab_no']);
	 if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
  <table class="table table-striped">
  <tr>
		  <th>Name</th>
      <th>Quantity</th>
			<th>Reorder Level</th>
  </tr>
  <?php
    $products=$dao->getAllProductsBelowReorderLevel();
		$totalInventory=0;
    foreach($products as $product){
    printf("<tr>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
	  $product['name'],
		$product['quantity'],
		$product['reorder_level']
    );
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
