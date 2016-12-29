<?PHP
	require 'data_access_object.php';
	require 'productClass.php';
	$dao=new DAO();
	$dao->checkLogin();
  $pageSecurity=2;
	if (isset($_POST['add_product'])){
		$_SESSION['errors']=array();
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category']) && isset($_POST['reorder_level'])){
			$_POST['name']=$dao->sanitize($_POST['name']);
			$_POST['category']=$dao->sanitize($_POST['category']);
			$_POST['description']=$dao->sanitize($_POST['description']);
			$_POST['bPrice']=$dao->sanitize($_POST['bPrice']);
			$_POST['sPrice']=$dao->sanitize($_POST['sPrice']);
			$_POST['company']=$dao->sanitize($_POST['company']);
			$_POST['reorder_level']=$dao->sanitize($_POST['reorder_level']);
			$_SESSION['product']=new ProductClass();
			$_SESSION['product']->setName($_POST['name']);
			$_SESSION['product']->setCategory($_POST['category']);
			$_SESSION['product']->setDescription($_POST['description']);
			$_SESSION['product']->setBuyingPrice($_POST['bPrice']);
			$_SESSION['product']->setSellingPrice($_POST['sPrice']);
			$_SESSION['product']->setCompany($_POST['company']);
			$_SESSION['product']->setReorderLevel($_POST['reorder_level']);

			$dao->addNewProduct($_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
			$_POST['company'],$_POST['category'],$_POST['reorder_level']);
			unset($_SESSION['product']);
			header("Location:manage_inventory.php");
		}
	}
  if (isset($_POST['edit_product'])){
		$_SESSION['errors']=array();
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category']) && isset($_POST['reorder_level'])){

			$dao->updateProduct($_SESSION['product_id'],$_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
			$_POST['company'],$_POST['category'],$_POST['reorder_level']);
			unset($_SESSION['product_id']);
			unset($_SESSION['product']);
			header("Location:manage_inventory.php");
		}
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Product Details',0) ?>
	<script>
	 $(document).ready(function(){
		 $('#expiry_date').datepicker();
	 });
	</script>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu($_SESSION['tab_no']); 
			if(!isset($_SESSION['errors'])){
				$_SESSION['errors']=array();
			}
			if(!isset($_SESSION['product'])){
				$_SESSION['product']=new ProductClass();
			}
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
				if(count($_SESSION['errors'])>0){
					$numberOfErrors=count($_SESSION['errors']);
					for($i=0;$i<$numberOfErrors;$i++){
						echo '<div class="alert alert-danger">
							<strong>'.$_SESSION['errors'][$i].'</strong>
						</div>';
					}
				}
			if(isset($_REQUEST['SelectedProduct'])){
				unset($_SESSION['errors']);
				$product=$dao->getProductById($_REQUEST['SelectedProduct']);
        $_SESSION['product_id']=$_REQUEST['SelectedProduct'];
					$_SESSION['product']=new ProductClass();
					$_SESSION['product']->setName($product['name']);
					$_SESSION['product']->setCategory($product['category']);
					$_SESSION['product']->setDescription($product['description']);
					$_SESSION['product']->setBuyingPrice($product['buying_price']);
					$_SESSION['product']->setSellingPrice($product['selling_price']);
					$_SESSION['product']->setCompany($product['company']);
					$_SESSION['product']->setReorderLevel($product['reorder_level']);
			}
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Product Details</h2>
				<div class="form-inline">
					<label for="name">Product Name:</label>
        <input type="text"  class="form-control" name="name" style="width:90%;float:right;"
				value="<?php echo $_SESSION['product']->name ?>" required>
			</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="category">Category:</label>
				<select  name="category" class="form-control" style="width:90%;float:right;" required>
					<option disabled selected>category</option>
					<?php
					$categories=$dao->getAllProductCategory();
					foreach($categories as $category){
						if($category['id']==$_SESSION['product']->category){
							echo '<option selected value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						else{
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					}
					?>
				</select>
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="description">Description:</label>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->description ?>" name="description"
				style="width:90%;float:right;" >
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="bPrice">Buying Price:</label>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->buyingPrice ?>"
				name="bPrice"  style="width:90%;float:right;" required>
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="sPrice">Selling Price:</label>
				<input type="text"  class="form-control" value="<?php echo $_SESSION['product']->sellingPrice ?>"  name="sPrice"
				style="width:90%;float:right;" required>
			</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="company">Company:</label>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->company ?>" name="company"
				style="width:90%;float:right;" required>
			</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="reorder_level">Re-order Level:</label>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->reorderLevel ?>" name="reorder_level"
				style="width:90%;float:right;" required>
			</div>
				<div style="clear:both;"></div>
				<?php if(isset($_SESSION['product_id'])){ ?>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Edit Product" name="edit_product"></input>
			<?php }else{ ?>
				<input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Product" name="add_product"></input>
			<?php } ?>
      </form>
      <?php }
			else{
				echo '<div class="alert alert-danger">
					<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
				</div>';
			}
			require 'footer.php';?>
  </body>
  </html>
