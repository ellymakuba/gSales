<!DOCTYPE HTML>
<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=1;
	if (isset($_GET['selectedClient'])){
		$selectedClient = $_GET['selectedClient'];
	} elseif (isset($_POST['selectedClient'])){
		$selectedClient = $_POST['selectedClient'];
	}
	if(isset($_POST["save"])){
		if(isset($_POST['client_name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['user'])){
			$userAlreadyAddedAsClient=$dao->userAlreadyAddedAsClient($_POST["user"]);
			if($userAlreadyAddedAsClient['count']==0){
				$dao->saveNewClient($_POST['client_name'],$_POST['address'],$_POST['phone'],$_POST['user']);
				unset($_SESSION['client']);
				header("Location:client_list.php");
			}
			else{
				echo '<div class="alert alert-danger">
					<strong>User already exists as client</strong>
				</div>';
			}
		}
	}
	if(isset($_POST['update'])){
		if(isset($_POST['client_name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['user'])){
			$userAlreadyAddedAsClient=$dao->AllowClientUserNameUpdate($_POST["user"],$_SESSION['client']['client_id']);
			if($userAlreadyAddedAsClient['count']==0){
			$dao->updateClient($_SESSION['client']['client_id'],$_POST['client_name'],$_POST['address'],$_POST['phone'],$_POST['user']);
			unset($_SESSION['client']);
			header("Location:client_list.php");
		}
		else{
			echo '<div class="alert alert-danger">
				<strong>User already exists as client</strong>
			</div>';
		}
		}
	}

?>
<html>
	<?PHP $dao->includeHead('Orders | Clients',1); ?>
	<body class="container">
		<?PHP
				$dao->includeMenu($_SESSION['tab_no']);
	 if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
		<div >
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
						<th>Action</th>
					</tr>
					<?PHP
					if(isset($_REQUEST['srch-term'])){
						$clients=$dao->getClientsByName($_REQUEST['srch-term']);
						foreach ($clients as $client){
							echo '<td><a href="manage_client.php?selectedClient='.$client['client_id'].'">View</a></td>
										<td>'.$client['name'].'</td>
										<td>'.$client['address'].'</td>
										<td>'.$client['phone_no'].'</td>
										<td>'.$client['user_name'].'</td>
										<td>'.$client['balance'].'</td>
										<td><a href="credit_sale.php?selectedClient='.$client['client_id'].'">Sale</a></td>
									</tr>';
						}
					}
					else{
						if (isset($_GET['pageno'])){
									 $pageno = $_GET['pageno'];
								} else {
									 $pageno = 1;
								}
								$numofrows=$dao->countAllLocationClients($_SESSION['log_location']['id']);;
								$targetpage = "manage_client.php";
								$rows_per_page =5;
								$lastpage  = ceil($numofrows['count']/$rows_per_page);
								$pageno = (int)$pageno;
								if ($pageno > $lastpage) {
									 $pageno = $lastpage;
								} // if
						$start =($pageno - 1) * $rows_per_page;
						$clients=$dao->getAllClients($start,$rows_per_page,$_SESSION['log_location']['id']);
					foreach ($clients as $client){
						echo '<td><a href="manage_client.php?selectedClient='.$client['client_id'].'">View</a></td>
									<td>'.$client['name'].'</td>
									<td>'.$client['address'].'</td>
									<td>'.$client['phone_no'].'</td>
									<td>'.$client['user_name'].'</td>
									<td>'.$client['balance'].'</td>
									<td><a href="credit_sale.php?selectedClient='.$client['client_id'].'">Sale</a></td>
								</tr>';
					}
				}
				if(!isset($selectedClient)){
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
			</form>
		</div>
		<?php
		if(isset($_GET['selectedClient'])){
			$_SESSION['client']=$dao->getClientById($_GET['selectedClient']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
				<h2 class="form-signin-heading">Edit Client</h2>
				<div class="form-inline">
					<label for="user_name">Client Name:</label>
					<input type="text" name="client_name" placeholder="Name" style="width:90%;float:right;" class="form-control"
					value="<?PHP  echo $_SESSION['client']['name'];?>" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="address">Address:</label>
					<input type="text" name="address" placeholder="Address" style="width:90%;float:right;" value="<?PHP  echo $_SESSION['client']['address'];?>"
					class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="phone">Phone Number:</label>
					<input type="number" name="phone" placeholder="Phone Number" style="width:90%;float:right;" value="<?PHP  echo $_SESSION['client']['phone_no'];?>"
					class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="user">User:</label>
					<select name="user" style="width:90%;float:right;" class="form-control" required>
						<?PHP
						foreach($users as $user){
							if($user['user_name'] == $_SESSION['user']['user_name']){
								echo '<option selected value='.$user['user_name'].'>'.$user['user_name'].'</option>';
							}
							else{
								echo '<option value='.$user['user_name'].'>'.$user['user_name'].'</option>';
							}
							}
						?>
					</select>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="update" class="btn btn-lg btn-primary"
				value="Update User" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		<?php }else{ ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
					<h2 class="form-signin-heading">New Client</h2>
					<div class="form-inline">
						<label for="name">Client Name:</label>
						<input type="text" name="client_name" placeholder="Name" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="address">Address:</label>
						<input type="text" name="address" placeholder="Address" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="phone">Phone No:</label>
						<input type="number" name="phone" placeholder="Phone Number"  class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="user">User:</label>
						<select name="user" style="width:90%;float:right;" class="form-control" required="">
							<?PHP
							foreach($users as $user){
									echo '<option value='.$user['user_name'].'>'.$user['user_name'].'</option>';
								}
							?>
						</select>
					</div>
					<div style="clear:both;"></div>
					<input type="submit" name="save" class="btn btn-lg btn-primary"
					value="Add User" style="display:block; margin: 0 auto;width:200px;"></input>
				</form>
				<?php }
	}
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
	</body>
</html>
