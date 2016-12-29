<!DOCTYPE HTML>
<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$users=$dao->getUsersWithClientSecurityRole();
	$pageSecurity=1;
?>
<html>
	<?PHP $dao->includeHead('Settings | Clients', 0) ?>
	</head>
	<body class="container">
		<?PHP $dao->includeMenu($_SESSION['tab_no']); 
		if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
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
