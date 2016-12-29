<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=4;
	$clients=$dao->getAllClients();
?>
<html>
	<?PHP $dao->includeHead('Orders | Clients',1); ?>
	<body class="container">
		<?PHP
				$dao->includeMenu($_SESSION['tab_no']);
				 if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){ ?>
		<div class="container">
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
			<form action="set_ugroup.php" method="post">
				<table class="table table-striped">
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Address</th>
						<th>Phone No</th>
						<th>User Name</th>
						<th>Balance</th>
					</tr>
					<?PHP
					foreach ($clients as $client){
						echo '<td><a href="view_statement.php?selectedClient='.$client['user_name'].'">View</a></td>
									<td>'.$client['name'].'</td>
									<td>'.$client['address'].'</td>
									<td>'.$client['phone_no'].'</td>
									<td>'.$client['user_name'].'</td>
									<td>'.$client['balance'].'</td>
								</tr>';
					}
					?>
				</table>
			</form>
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
