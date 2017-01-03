<?PHP
	require 'data_access_object.php';
	$pageSecurity=1;
	function CryptPass($Password ) {
		return sha1($Password);
    }
		$dao=new DAO();
	$dao->checkLogin();
	if(isset($_POST["save_changes"])){
		if(isset($_POST['name'])){
			$dao->saveLocation($_POST['name']);
		}
	}
	if(isset($_POST['update'])){
		if(isset($_POST['name'])){
			$dao->updaLocation($_POST['name'],$_SESSION['location']['name'],$_SESSION['log_location']['id']);
			unset($_SESSION['location']);
		}
	}
	$locations=$dao->getAllLocations();
?>
<html>
	<?PHP $dao->includeHead('Settings | Manage Locations', 0) ?>
	</head>
	<body class="container">
		<?PHP $dao->includeMenu($_SESSION['tab_no']);
		if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
			<div>
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
							<th>Name</th>
							<th>Action</th>
							<th>Action</th>
						</tr>
						<?PHP
						foreach ($locations as $location){
							echo '<tr><td>'.$location['name'].'</td>
							<td><a href="manage_location.php?selectedLocation='.$location['id'].'">Edit</a></td>
							<td><a href="manage_location.php?delete='.$location['id'].'">Delete</a></td>
							</tr>';
						}
						?>
					</table>
				</form>
			</div>
			<?php
		if(isset($_GET['selectedLocation'])){
			$_SESSION['location']=$dao->getLocationById($_GET['selectedLocation']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
				<h2 class="form-signin-heading">Edit Location</h2>
				<div class="form-inline">
					<label for="user_name">Name:</label>
					<input type="text" name="name" placeholder="Name" style="width:90%;float:right;" class="form-control"
					value="<?PHP  echo $_SESSION['location']['name'];?>" required=""/>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="update" class="btn btn-lg btn-primary"
				value="Update Location" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		<?php }else{ ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
					<h2 class="form-signin-heading">New Location</h2>
					<div class="form-inline">
						<label for="name">Name:</label>
						<input type="text" name="name" placeholder="Name" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<input type="submit" name="save_changes" class="btn btn-lg btn-primary"
        	value="Add Location" style="display: block; margin: 0 auto;width:200px;"></input>
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
