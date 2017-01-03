<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=1;
	if (isset($_GET['selectedUser'])){
		$selectedUser = $_GET['selectedUser'];
	} elseif (isset($_POST['selectedUser'])){
		$selectedUser = $_POST['selectedUser'];
	}
?>
<html>
	<?PHP
	$dao->includeHead('Settings | Manage Users',1);
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
			unset($selectedUser);
		}
	}
	if(isset($selectedUser)){
	if(isset($_GET['remove']) || isset($_GET['add'])){
		$locationAssignment = $_GET['activeLocation'];
		if( isset($_GET['add'])){
			$dao->assignNewUserLocation($selectedUser,$locationAssignment);
		} elseif (isset($_GET['remove'])){
			$dao->removeAssignedUserLocation($selectedUser,$locationAssignment);
		}
		unset($_GET['add']);
		unset($_GET['remove']);
		unset($locationAssignment);
	}
}
	?>
	<body class="container">
		<?PHP
				$_SESSION['tab_no']=4;
				$dao->includeMenu($_SESSION['tab_no']);
				if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
					if(!isset($selectedUser)){
					?>
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
						<th>User Name</th>
						<th>Security Role</th>
						<th>Changed</th>
					</tr>
					<?PHP
					if(isset($_REQUEST['srch-term'])){
						if (isset($_GET['pageno'])){
									 $pageno = $_GET['pageno'];
								} else {
									 $pageno = 1;
								}
								$numofrows=$dao->countAllUsersByUsername($_REQUEST['srch-term']);;
								$targetpage = "manage_users.php";
								$rows_per_page =5;
								$lastpage  = ceil($numofrows['count']/$rows_per_page);
								$pageno = (int)$pageno;
								if ($pageno > $lastpage) {
									 $pageno = $lastpage;
								} // if
						$start =($pageno - 1) * $rows_per_page;
						$users=$dao->getAllUsersByUsername($_REQUEST['srch-term'],$start,$rows_per_page);
						foreach ($users as $user){
							echo '<td><a href="manage_users.php?selectedUser='.$user['user_name'].'">'.$user['user_name'].'</a></td>
										<td>'.$user['role'].'</td>
										<td>'.$user['date_created'].'</td>
									</tr>';
						}
					}
					else{
						if (isset($_GET['pageno'])){
									 $pageno = $_GET['pageno'];
								} else {
									 $pageno = 1;
								}
								$numofrows=$dao->getAllUsersCount();;
								$targetpage = "manage_users.php";
								$rows_per_page =5;
								$lastpage  = ceil($numofrows['count']/$rows_per_page);
								$pageno = (int)$pageno;
								if ($pageno > $lastpage) {
									 $pageno = $lastpage;
								} // if
						$start =($pageno - 1) * $rows_per_page;
						$users=$dao->getAllUsers($start,$rows_per_page);
					foreach ($users as $user){
						echo '<td><a href="manage_users.php?selectedUser='.$user['user_name'].'">'.$user['user_name'].'</a></td>
									<td>'.$user['role'].'</td>
									<td>'.$user['date_created'].'</td>
								</tr>';
					}
				}
			}
			if (isset($selectedUser)){
				$_POST['selectedUser']=$selectedUser;
			}
			if(!isset($selectedUser)){
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
		$roles=$dao->getAllSecurityRoles();
		if(isset($selectedUser)){
			$_SESSION['user']=$dao->getUserByUserName($selectedUser); ?>
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
				if(isset($selectedUser)){
					$locations=$dao->getAllLocations();
					$locationsAssignedToUser=$dao->getLocationAssignedToUser($selectedUser);
					$locationsAttached = array();
					$i=0;
					foreach($locationsAssignedToUser as $locationAssignedToUser){
						$locationsAttached[$i] =$locationAssignedToUser['location_id'];
						$i++;
					}
					echo '<br /><table class="table table-striped table-condensed"><tr>';
					if (count($locations)>0 ) {
						echo "<th colspan=3><div class='centre'>Assigned Locations</div></th>";
						echo "<th colspan=3><div class='centre'>Available Locations</div></th>";
					}
					echo '</tr>';
					$k=0; //row colour counter
					foreach($locations as $location) {
						if (in_array($location['id'],$locationsAttached)){
							printf("<td>%s</td>
								<td><a href=\"%s?selectedUser=%s&remove=1&activeLocation=%s\">Remove</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>",
								$location['name'],
								$_SERVER['PHP_SELF'],
								$selectedUser,
								$location['id']
								);
						} else {
							printf("<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>%s</td>
								<td><a href=\"%s?selectedUser=%s&add=1&activeLocation=%s\">Add</a></td>",
								$location['name'],
								$_SERVER['PHP_SELF'],
								$selectedUser,
								$location['id']
								);
						}
						echo '</tr>';
					}
					echo '</table>';
					}

			}
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
	</body>
</html>
