<!DOCTYPE HTML>
<?PHP
	session_start();
	require 'data_access_object.php';
	$dao=new DAO();
	if(isset($_POST['login'])){
		// Sanitize user input
		$username = $_POST['log_user'];
		$password = $_POST['log_pw'];
		$location=$_POST['location'];
		$fingerprint=$dao->fingerprint();
		// Select user details from USER
		$user=$dao->getUserByUsernameAndPassword($username,$password,$location);
		if($user){
			// Define Session Variables for this User
			$_SESSION['log_user'] = $username;
			$_SESSION['log_time'] = time();
			$_SESSION['log_id'] = $user['user_id'];
			$_SESSION['log_ugroup'] = $user['ugroup_name'];
			$_SESSION['log_admin'] = $user['ugroup_admin'];
			$_SESSION['log_delete'] = $user['ugroup_delete'];
			$_SESSION['log_report'] = $user['ugroup_report'];
			$_SESSION['log_fingerprint'] = $fingerprint;
			$company=$dao->getLocationById($location);
			$_SESSION['log_location'] = $company;

			$user=$dao->getUserByUserName($_SESSION['log_user']);
	    $dao->getUserRole($user['user_id']);
			$_SESSION['AllowedPageSecurityTokens']=array();
			$tokens=$dao->getPrivilegesByRole($_SESSION['secroleId']);
			$i=0;
			foreach($tokens as $token){
				$_SESSION['AllowedPageSecurityTokens'][$i]=$token['tokenid'];
				$i++;
			}
			header('Location: cash_sale.php');
		}
		else $dao->showMessage('Authentification failed!\nWrong Username and/or Password!');
	}
?>
<html>
<head>
	<meta charset="utf-8">
</head>
	<?PHP $dao->includeHead('Login')
	?>
	<body class="container" style="background:#bd7874;">
		<h2 style="text-align:center;font-weight:bold;color:white;">Welcome to webafriq POS</h2>
		<div id="login">
			<form class="form-signin" action="login.php" method="post">
				<div class="form-inline">
					<label for="user_name">Username:</label>
					<input type="text" name="log_user" style="width:70%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="log_pw">Password:</label>
					<input type="password" name="log_pw" style="width:70%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="location">Location:</label>
				<select  name="location" class="form-control" style="width:70%;float:right;" required>
					<option disabled selected>Select Location</option>
					<?php
					$locations=$dao->getAllLocations();
					foreach($locations as $location){
							echo '<option value="'.$location['id'].'">'.$location['name'].'</option>';
					}
					?>
				</select>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="login" class="btn btn-default btn-primary"
				value="Login" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		</div>
	</body>
</html>
