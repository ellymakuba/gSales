<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$_SESSION['errors']=array();
	$categories=$dao->getAllProductCategory();
	$pageSecurity=1;
	if(isset($_GET['delete'])){
		unset($_SESSION['errors']);
		$_SESSION['errors']=array();
		$_SESSION['categoryToDelete']=$_GET['delete'];
		$dao->checkIfCategoryHasAttachedProducts($_SESSION['categoryToDelete']);
		if(count($_SESSION['errors'])==0){
			$dao->deleteCategory($_SESSION['categoryToDelete']);
			header("Location:product_category_list.php");
		}
	}
?>
<html>
	<?PHP $dao->includeHead('Settings | Category List',1); ?>
	<body class="container">
		<?PHP
				$dao->includeMenu(4);
		?>
		<div id="menu_main">
			<a href="manage_settings.php">Users List</a>
			<a href="user.php" >User</a>
      <a href="roles.php">Roles</a>
			<a href="client_list.php">Client List</a>
			<a href="client.php">Client</a>
			<a href="product_category_list.php" id="item_selected">Category List</a>
			<a href="product_category.php">Category</a>
		</div>
		<?php if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
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
		<?php if(count($_SESSION['errors'])>0){
			$numberOfErrors=count($_SESSION['errors']);
			for($i=0;$i<$numberOfErrors;$i++){
				echo '<div class="alert alert-danger">
					<strong>'.$_SESSION['errors'][$i].'</strong>
				</div>';
			}
		}?>
			<form action="set_ugroup.php" method="post">
				<table class="table table-striped">
					<tr>
						<th>Action</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<?PHP
					foreach ($categories as $category){
						echo '<td><a href="product_category.php?selectedCategory='.$category['id'].'">Edit</a></td>
									<td>'.$category['name'].'</td>
									<td><a href="product_category_list.php?delete='.$category['id'].'">Delete</a></td>
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
