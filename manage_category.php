<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$_SESSION['errors']=array();
	$pageSecurity=1;
	if(isset($_POST["save"])){
		unset($_SESSION['errors']);
		$_SESSION['errors']=array();
		if(isset($_POST['name'])){
			$dao->productNameExists($_POST['name']);
			if(count($_SESSION['errors'])==0){
				$dao->saveProductCategory($_POST['name']);
				header("Location:product_category_list.php");
			}
		}
	}
	if(isset($_POST['update'])){
		unset($_SESSION['errors']);
		$_SESSION['errors']=array();
		if(isset($_POST['name'])){
			$dao->AllowProductNameUpdate($_POST['name'],$_SESSION['category']['id']);
			if(count($_SESSION['errors'])==0){
				$dao->updateProductCategory($_POST['name'],$_SESSION['category']['id']);
				unset($_SESSION['category']);
				header("Location:product_category_list.php");
			}
		}
	}
	if(count($_SESSION['errors'])>0){
		$numberOfErrors=count($_SESSION['errors']);
		for($i=0;$i<$numberOfErrors;$i++){
			echo '<div class="alert alert-danger">
				<strong>'.$_SESSION['errors'][$i].'</strong>
			</div>';
		}
	}
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
				$dao->includeMenu($_SESSION['tab_no']);
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
		<?php if(count($_SESSION['errors'])>0){
			$numberOfErrors=count($_SESSION['errors']);
			for($i=0;$i<$numberOfErrors;$i++){
				echo '<div class="alert alert-danger">
					<strong>'.$_SESSION['errors'][$i].'</strong>
				</div>';
			}
		}?>
			<form action="set_ugroup.php" method="post">
				<table class="table table-striped table-condensed">
					<tr>
						<th>Action</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<?PHP
					if(isset($_REQUEST['srch-term'])){
						$categories=$dao->getAllProductCategory();
						foreach ($categories as $category){
						echo '<tr><td><a href="manage_category.php?selectedCategory='.$category['id'].'">Edit</a></td>
									<td>'.$category['name'].'</td>
									<td><a href="product_category_list.php?delete='.$category['id'].'">Delete</a></td>
								</tr>';
						}
					}
					else{
						$categories=$dao->getAllProductCategory();
					  foreach ($categories as $category){
						echo '<tr><td><a href="manage_category.php?selectedCategory='.$category['id'].'">Edit</a></td>
									<td>'.$category['name'].'</td>
									<td><a href="product_category_list.php?delete='.$category['id'].'">Delete</a></td>
								</tr>';
					}
				}
					?>
				</table>
			</form>
		</div>
		<?php
		if(isset($_GET['selectedCategory'])){
			$_SESSION['category']=$dao->getCategoryById($_GET['selectedCategory']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
				<h2 class="form-signin-heading">Edit Category</h2>
				<div class="form-inline">
					<label for="name">Category Name:</label>
					<input type="text" name="name" placeholder="Name" style="width:90%;float:right;" class="form-control"
					value="<?PHP  echo $_SESSION['category']['name'];?>" required=""/>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="update" class="btn btn-lg btn-primary"
				value="Update Category" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		<?php }else{ ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
					<h2 class="form-signin-heading">New Category</h2>
					<div class="form-inline">
						<label for="name">Category Name:</label>
						<input type="text" name="name" placeholder="Name" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<input type="submit" name="save" class="btn btn-lg btn-primary"
					value="Add Category" style="display:block; margin: 0 auto;width:200px;"></input>
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
