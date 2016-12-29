<?PHP
	require 'data_access_object.php';
	$pageSecurity=1;
	function CryptPass($Password ) {
		return sha1($Password);
    }
		$dao=new DAO();
	$dao->checkLogin();
	if(isset($_POST["save_changes"])){
		if(isset($_POST['user_name']) && isset($_POST['user_pw']) && isset($_POST['secroleid'])){
			$user_pw = CryptPass($_POST['user_pw']);
			$dao->saveNewUser($_POST['user_name'],$user_pw,$_POST['secroleid']);
		}
	}
	if(isset($_POST['update'])){
		if(isset($_POST['user_pw']) && isset($_POST['user_pw_conf']) && isset($_POST['secroleid'])){
			$password=CryptPass($_POST['user_pw']);
			$dao->updateUser($_SESSION['user']['user_name'],$password,$_POST['secroleid']);
			unset($_SESSION['user']);
		}
	}
	$roles=$dao->getAllSecurityRoles();
?>
<html>
	<?PHP $dao->includeHead('Settings | User', 0) ?>
	</head>
	<body class="container">
		<?PHP $dao->includeMenu($_SESSION['tab_no']); 
		if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
		if(isset($_GET['selectedUser'])){
			$_SESSION['user']=$dao->getUserByUserName($_GET['selectedUser']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
				<h2 class="form-signin-heading">Edit User</h2>
				<div class="form-inline">
					<label for="user_name">User Name:</label>
					<input type="text" name="user_name" placeholder="Username" style="width:90%;float:right;" class="form-control"
					value="<?PHP  echo $_SESSION['user']['user_name'];?>" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="password">Password:</label>
					<input type="password" name="user_pw" placeholder="Password" style="width:90%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="user_pw_conf">Repeat Password:</label>
					<input type="password" name="user_pw_conf" placeholder="Repeat Password" style="width:90%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="secroleid">Security Role:</label>
					<select name="secroleid" style="width:90%;float:right;" class="form-control" required>
						<?PHP
						foreach($roles as $role){
							if($role['secroleid'] == $_SESSION['user']['secroleid']){
								echo '<option selected value='.$role['secroleid'].'>'.$role['secrolename'].'</option>';
							}
							else{
								echo '<option value='.$role['secroleid'].'>'.$role['secrolename'].'</option>';
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
					<h2 class="form-signin-heading">New User</h2>
					<div class="form-inline">
						<label for="user_name">User Name:</label>
						<input type="text" name="user_name" placeholder="Username" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="password">Password:</label>
						<input type="password" name="user_pw" placeholder="Password" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="user_pw_conf">Repeat Password:</label>
						<input type="password" name="user_pw_conf" placeholder="Repeat Password"  class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="secroleid">Security Role:</label>
						<select name="secroleid" style="width:90%;float:right;" class="form-control" required="">
							<?PHP
							foreach($roles as $role){
									echo '<option value='.$role['secroleid'].'>'.$role['secrolename'].'</option>';
								}
							?>
						</select>
					</div>
					<div style="clear:both;"></div>
					<input type="submit" name="save_changes" class="btn btn-lg btn-primary"
        	value="Add User" style="display: block; margin: 0 auto;width:200px;"></input>
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
