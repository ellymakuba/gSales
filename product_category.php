<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=1;
	$_SESSION['errors']=array();
?>
<html>
	<?PHP $dao->includeHead('Settings | Category Details', 0) ?>
	</head>
	<body class="container">
		<?PHP $dao->includeMenu($_SESSION['tab_no']); 
		if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
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
