<?PHP
	require 'data_access_object.php';
	require 'productCart.php';
	require 'salesOrderCart.php';
	$pageSecurity=6;
	$dao=new DAO();
	$dao->checkLogin();
	if (isset($_POST['update'])){
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Product Cart',0) ?>
	<script>
	$(document).ready(function(){
$(document).on("click", "#save", function(e) {
		bootbox.confirmation("Are you sure?", function() {
		});
});
});
	</script>
  </head>
  <body class="container">
    <?PHP $dao->includeMenu(1);
		$categories=$dao->getAllProductCategory();
		if(!isset($_SESSION['productList'])){
			$_SESSION['productList'] = new ProductCart();
			$products=$dao->getAllProducts();
			foreach($products as $product){
				$_SESSION['productList']->add_to_cart($product['id'],$product['description'],$product['selling_price'],$product['pic'],$product['name'],-1);
			}
		}
		if(isset($_GET['category'])){
			$_SESSION['productList'] = new ProductCart();
			$selectedCategory=$dao->getProductCategoryById($_GET['category']);
			$_SESSION['productList']->setCategory($selectedCategory['name']);
			$categoryProducts=$dao->getProductsByCategoryId($_GET['category']);
			foreach($categoryProducts as $product){
				$_SESSION['productList']->add_to_cart($product['id'],$product['description'],$product['selling_price'],$product['pic'],$product['name'],-1);
			}
		}
		if (!isset($_SESSION['salesOrder'])){
			 $_SESSION['salesOrder'] = new Cart();
		}
		if(isset($_GET['add_cart'])){
		$id =$_GET['add_cart'];
		$product=$dao->getInventoryItemById($id);
		$AlreadyOnThisCart =0;
		$quantity=1;
		if (count($_SESSION['salesOrder']->LineItems)>0){
				 foreach ($_SESSION['salesOrder']->LineItems AS $OrderItem)
						{
						$LineNumber = $_SESSION['salesOrder']->LineCounter;
						if ($OrderItem->productID ==$product['id'])
						 {
							 $AlreadyOnThisCart = 1;
							}
					 }
				 }
				if ($AlreadyOnThisCart!=1)
				{
					$profit=$product['selling_price']-$product['buying_price'];
					$discount=0.5*$profit;
					$_SESSION['salesOrder']->add_to_cart($product['id'],$quantity,$product['description'],$product['selling_price'],$discount,0,0,0,0,$product['buying_price'],$product['name'],-1);
				}
		}//end of if(isset($_POST['add_cart]))
    ?>
  	<div id="menu_main">
			<a href="products.php" id="item_selected">Products</a>
      <a href="client_statement.php">Statement</a>
      </div>
			<?php
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
			 if (count($_SESSION['salesOrder']->LineItems)>0)
			{ ?>
			<div class="col-sm-3 col-md-2 pull-right" style="margin-bottom:10px;">
				<a href="client_order.php" class="btn btn-default btn-primary">View Order Cart</a>
			</div>
			<?php } ?>
			<div class="row">
            <div class="col-md-3">
                <p class="lead"><?php echo $_SESSION['productList']->category ?></p>
                <div class="list-group">
									<?php foreach($categories as $category){
                    echo '<a href="products.php?category='.$category['id'].'" class="list-group-item" style="background-color:#F0F8FF;">'.$category['name'].'</a>';
									}
								?>
                </div>
            </div>
			<div class="col-md-9">
				<div class="row">
			<?php
			if (count($_SESSION['productList']->LineItems)>0)
			{
				foreach ($_SESSION['productList']->LineItems as $order)
				{
				?>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail">
						<?php  echo '<img src="upload/'.$order->photo.'">'; ?>
            <div class="caption">
							<h4 class="pull-right">KSH.<?php echo $order->Price ?></h4>
							<div style="height:100px;">
            <h4><a href="#"><?php echo $order->name ?></a></h4>
            <p><?php echo $order->ItemDescription ?>.</p>
					</div>
						<a href="products.php?add_cart=<?php echo $order->productID ?>"><h4 class="pull-right">Order Product</h4></a>
            </div>
            <div class="ratings">
            <p>
              <span class="glyphicon glyphicon-star"></span>
              <span class="glyphicon glyphicon-star"></span>
              <span class="glyphicon glyphicon-star"></span>
              <span class="glyphicon glyphicon-star"></span>
              <span class="glyphicon glyphicon-star"></span>
            </p>
            </div>
          </div>
        </div>
			<?php } } ?>
			</div>
      </div>
		</div>
		<?php }else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
  </body>
  </html>
