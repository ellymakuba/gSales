<?PHP
class DAO{
    private $conn;
	function __construct() {
		require_once 'DB_Connect.php';
		$db=new DB_Connect();
		$this->conn=$db->connect();
	}
  function showMessage($text) {
  echo '<script language=javascript>
          alert(\''.$text.'\')
        </script>';
}
public function getUserByUsernameAndPassword($username, $password,$location){
 		$password=sha1($password);
    $stmt = $this->conn->prepare("SELECT * FROM user u,securityroles sr,user_locations ul
    WHERE u.secroleid = sr.secroleid
    AND u.user_name=ul.user_name
    AND u.user_name = ? AND u.user_pw LIKE ?
    AND ul.location_id=?");
		$params=array($username,$password,$location);
		$stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetch();
    }
    function getUserByUserName($userName){
      try{
        $stmt=$this->conn->prepare("SELECT * FROM user WHERE user_name LIKE ?");
        $params=array($userName);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		    return $stmt->fetch();
      }
      catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    function getLocationById($id){
      try{
        $stmt=$this->conn->prepare("SELECT * FROM location WHERE id=?");
        $params=array($id);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		    return $stmt->fetch();
      }
      catch(PDOException $e){
        echo $e->getMessage();
      }
    }
public function fingerprint(){
		return $fingerprint = md5($_SERVER['REMOTE_ADDR'].'jikI/20Y,!'.$_SERVER['HTTP_USER_AGENT']);
	}
	 function checkLogin() {
		$fingerprint=$this->fingerprint();
		session_start();
		if (!isset($_SESSION['log_user']) || $_SESSION['log_fingerprint'] != $fingerprint) $this->logout();
		session_regenerate_id();
	}
	function checkLogout(){
		if ($_SESSION['logrec_logout'] == 0){
			$this->showMessage("You forgot to logout last time. Please remember to log out properly.");
			$_SESSION['logrec_logout'] = 1;
		}
	}
	function checkPermissionAdmin() {
		if ($_SESSION['log_admin']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function checkPermissionDelete() {
		if ($_SESSION['log_delete']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function checkPermissionReport() {
		if ($_SESSION['log_report']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function logout(){
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();
		header('Location: login.php');
		die;
	}
	function checkSQL($sqlquery){
		if (!$sqlquery) die ('SQL-Statement failed: '.mysql_error());
	}
	public function getUserRole($id){
    try{
	  $stmt = $this->conn->prepare("SELECT secroleid FROM user WHERE user_id=?");
    $params=array($id);
		$stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result=$stmt->fetch();
    $_SESSION['secroleId'] = $result['secroleid'];
  }
  catch(PDOException $e){
    echo $e->getMessage();
  }
	}
	function sanitize($var) {
		$var=htmlspecialchars($var);
		return $var;
	}

	function convertDays($days){
		return $seconds = $days * 86400;
	}

	function convertMonths($months){
		return $seconds = $months * 2635200; // Seconds for 30.5 days
	}
	function getCustID(){
		if (isset($_GET['cust'])) $_SESSION['cust_id'] = $_GET['cust'];
		else header('Location: start.php');
	}
	function includeHead($title, $endFlag = 1) {
		echo '<!DOCTYPE HTML>
    <head>
			<meta http-equiv="Content-Script-Type" content="text/javascript">
			<meta http-equiv="Content-Style-Type" content="text/css">
			<meta name="robots" content="noindex, nofollow">
      <meta charset="utf-8">
      <link href="responsive-calendar/0.9/css/responsive-calendar.css" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="jquery/jquery-2.2.1.min.js"></script>
			<script src="jquery/jquery-ui-1.11.4/jquery-ui.min.js"></script>
      <script src="printElement.js"></script>
      <link rel="stylesheet" href="dist/css/bootstrap.min.css">
      <script src="dist/js/bootstrap.min.js"></script>
      <script src="bootbox.min.js"></script>
			<title>Poinf Of Sale | '.$title.'</title>
			<link rel="shortcut icon" href="" type="image/x-icon">
			<link rel="stylesheet" href="css/mangoo.css" />
      <link rel="stylesheet" href="jquery/jquery-ui-1.11.4/jquery-ui.min.css">
      <script src="responsive-calendar/0.9/js/responsive-calendar.js"></script>
			<script>
        $("form input").focus(function() {
            var titleText = $(this).attr("placeholder");
            $(this).tooltip({
              title: titleText,
              trigger: "focus",
              container: "form"
            });
      });
      $(document).ready(function(){
        $(document).on("click","#menu_main a",function(){
          $(this).attr("id","item_selected");
        })
      });
			</script>
      <style type="text/css">
      	#size
      	{
      	width:100px;
      	height:150px;
      	}
          .form-signin input,.form-signin select
        	{
            display: block;
            margin-bottom: 1em;
            }
      	.form-signin
      	{
          padding: 15px;
          margin: 0 auto;
          background: #f2f2f2;
        	-moz-border-radius:10px;
          border-radius:10px;
          margin-bottom:20px;
          border:8px groove gray;
          }
      	.form-signin .form-signin-heading,
          .form-signin .checkbox
      	{
           margin-bottom: 10px;
          }
         .form-signin .form-control:focus
         {
          z-index: 2;
         }
         .form-signin .prescription
    	   {
    			position: relative;
    			height: 100px;
    			width : 100%;
    			-webkit-box-sizing: border-box;
    			-moz-box-sizing: border-box;
    			box-sizing: border-box;
    			padding: 10px;
    			font-size: 16px;
    	   }
      	</style>
			';
		if ($endFlag == 1) echo '</head>';
	}
	function includeMenu($tab_no){
    $user=$this->getUserByUserName($_SESSION['log_user']);
    $this->getUserRole($user['user_id']);
		echo '
		<!-- MENU HEADER -->
		<div id="menu_header">
			<div id="menu_logout">
				<ul>
					<li>'.$_SESSION['log_user']." : ".$_SESSION['log_location']['name'].'
						<ul>
							<li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>';

		echo '
		<!-- MENU TABS -->
		<div id="menu_tabs">
			<ul>';

        if($_SESSION['secroleId']==7){
          echo '<li';
				  if ($tab_no == 1) echo ' id="tab_selected"';
				  echo '><a href="products.php">Client Orders</a></li>';
        }
        else{
          echo '<li';
				  if ($tab_no == 1) echo ' id="tab_selected"';
				  echo '><a href="cash_sale.php">Sales</a></li>';
				echo '<li';
				if ($tab_no == 2) echo ' id="tab_selected"';
				echo '><a href="manage_inventory.php">Inventory</a></li>
				<li';
				if ($tab_no == 3) echo ' id="tab_selected"';
				echo '><a href="view_reports.php">Reports</a></li>
        <li';
        if ($tab_no == 4) echo ' id="tab_selected"';
        echo '><a href="manage_users.php">Settings</a></li>';
      }
			echo '</ul>
		</div>';
    if ($tab_no == 1){
      echo '<div id="menu_main">
  			<a href="cash_sale.php" id="item_selected">Cash Sale</a>
  			<a href="credit_sale.php">Credit Sale</a>
  			<a href="client_list.php">Client List</a>
  			<a href="manage_orders.php">Manage Orders</a>
        <a href="receipt.php">Receipt</a>
        </div>';
    }
    else if($tab_no == 2){
      echo '<div id="menu_main">
    		<a href="manage_inventory.php">Product List</a>
    		<a href="manage_purchase_order.php">Manage Purchase Order</a>
    		<a href="purchase_order.php">Purchase Order</a>
        </div>';
    }
    else if($tab_no == 3){
      echo '<div id="menu_main">
      <a href="view_reports.php">Inventory Value</a>
      <a href="reorder_level.php">Reorder Level</a>
      <a href="monthly_sales.php">Monthly Sales</a>
      <a href="monthly_profit.php">Monthly Profits</a>
      <a href="sales_breakdown.php">Sales Breakdown</a>
      <a href="product_sales_history.php">Sales History</a>
      </div>';
    }
    else if($tab_no == 4){
      echo '<div id="menu_main">
      <a href="manage_users.php">Users</a>
			<a href="manage_location.php" >Locations</a>
      <a href="manage_role.php">Roles</a>
      <a href="manage_product.php">Products</a>
			<a href="manage_client.php">Clients</a>
			<a href="manage_category.php">Categories</a>
      </div>';
    }
	}

/**
	* Alternate table rows background color for improved readability
	* @param int row_color : Indicator for row color
	*/
	function tr_colored(&$row_color) {
		if ($row_color == 0){
			echo '<tr>';
			$row_color = 1;
		}
		else {
			echo '<tr class="alt">';
			$row_color = 0;
		}
	}
  function countInventoryByLocation($location){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM product p
      INNER JOIN inventory inv ON p.id=inv.product_id
      WHERE inv.location_id=?");
      $params=array($location);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllInventoryByLocation($start,$numberOfRecords,$location){
    try{
      $stmt=$this->conn->prepare("SELECT p.*,inv.quantity as quantity FROM product p
      INNER JOIN inventory inv ON p.id=inv.product_id
      WHERE inv.location_id=? ORDER BY p.name LIMIT ?,?");
      $params=array($location,$start,$numberOfRecords);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getProductsCount(){
      try{
        $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM product");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
      }
      catch(PDOException $e){
  			echo $e->getMessage();
  		}
  }
  function getAllProducts($start,$numberOfRecords){
    try{
      $stmt=$this->conn->prepare("SELECT p.*
      FROM product p ORDER BY p.name LIMIT ?,?");
      $params=array($start,$numberOfRecords);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllProductsBelowReorderLevel(){
    try{
      $stmt=$this->conn->prepare("SELECT p.*,inv.quantity FROM product p
      INNER JOIN inventory inv ON p.id=inv.product_id
      WHERE quantity<reorder_level");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getProductsByCategoryId($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE category=?");
      $params=array($id);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getProductsByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE name LIKE ?");
      $name="%".$name."%";
      $params=array($name);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getProductByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE name LIKE ?");
      $name='%'.$name.'%';
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getProductById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE id=? LIMIT 1");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
  		return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function updateProduct($productId,$name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel,$unitsPerPack,$packPrice){
		try{
			$stmt=$this->conn->prepare("UPDATE product SET name=?,buying_price=?,selling_price=?,
      description=?,company=?,category=?,reorder_level=?,units_per_pack=?,buying_price_pack=? WHERE id=?");
			$params=array($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel,$unitsPerPack,$packPrice,$productId);
			$stmt->execute($params);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
  function updateProductImage($productId,$image){
		try{
			$stmt=$this->conn->prepare("UPDATE product SET pic=? WHERE id=?");
			$params=array($image,$productId);
			$stmt->execute($params);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
  function getAllSalesOrders(){
    try{
      $stmt=$this->conn->prepare("SELECT so.*,
      (SELECT SUM(sod.amount) as amount FROM sales_order_details sod WHERE so.sales_order_id=sod.sales_order_id) as total
      FROM sales_order so ORDER BY sales_order_id DESC");
      $params=array(0,0);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getClientOrders($user){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM sales_order WHERE client=?");
      $params=array($user);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllPurchaseOrders($start,$rows_per_page,$location){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM purchase_order WHERE location_id=? LIMIT ?,?");
      $params=array($location,$start,$rows_per_page);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllClientOrders($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM sales_order WHERE client LIKE ?");
      $params=array($name);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function updateSecurityRole($roleName,$roleId){
    try{
      $stmt=$this->conn->prepare("UPDATE securityroles SET secrolename =?	WHERE secroleid =?");
      $params=array($roleName,$roleId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function addNewSecurityRole($roleName){
    try{
      $stmt=$this->conn->prepare("INSERT INTO securityroles (secrolename) VALUES (?)");
      $params=array($roleName);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function addNewSecurityGroup($SelectedRole,$PageTokenId){
    try{
      $stmt=$this->conn->prepare("INSERT INTO securitygroups (secroleid, tokenid) VALUES (?,?)");
      $params=array($SelectedRole,$PageTokenId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function removeSecurityGroupByTokenId($SelectedRole,$PageTokenId){
    try{
      $stmt=$this->conn->prepare("DELETE FROM securitygroups WHERE secroleid =? AND tokenid =?");
      $params=array($SelectedRole,$PageTokenId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function assignNewUserLocation($user,$location){
    try{
      $stmt=$this->conn->prepare("INSERT INTO user_locations (user_name, location_id) VALUES (?,?)");
      $params=array($user,$location);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function removeAssignedUserLocation($user,$location){
    try{
      $stmt=$this->conn->prepare("DELETE FROM user_locations WHERE user_name =? AND location_id =?");
      $params=array($user,$location);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function removeSecurityGroup($SelectedRole){
    try{
      $stmt=$this->conn->prepare("DELETE FROM securitygroups WHERE secroleid=?");
      $params=array($SelectedRole);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function removeSecurityRole($SelectedRole){
    try{
      $stmt=$this->conn->prepare("DELETE FROM securityroles WHERE secroleid=?");
      $params=array($SelectedRole);
      $stmt->execute($params);
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function numberOfUsersOnSecurityRole($SelectedRole){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) FROM user WHERE secroleid=?");
      $params=array($SelectedRole);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllSecurityRoles(){
    try{
      $stmt=$this->conn->prepare("SELECT secroleid,secrolename FROM securityroles ORDER BY secroleid");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllLocations(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM location ORDER BY name");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllPrivileges(){
    try{
      $stmt=$this->conn->prepare("SELECT tokenid, tokenname FROM securitytokens");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getPrivilegesByRole($SelectedRole){
    try{
      $stmt=$this->conn->prepare("SELECT tokenid FROM securitygroups WHERE secroleid=?");
      $params=array($SelectedRole);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getLocationAssignedToUser($user){
    try{
      $stmt=$this->conn->prepare("SELECT location_id FROM user_locations WHERE user_name LIKE ?");
      $params=array($user);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getRoleById($SelectedRole){
    try{
      $stmt=$this->conn->prepare("SELECT secroleid,secrolename FROM securityroles WHERE secroleid=?");
      $params=array($SelectedRole);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function addNewProduct($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel,$unitsPerPack,$packPrice){
    try{
      $stmt=$this->conn->prepare("INSERT INTO product (name,buying_price,selling_price,description,company,category,
        reorder_level,units_per_pack,buying_price_pack)
      VALUES(?,?,?,?,?,?,?,?,?)");
      $params=array($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel,$unitsPerPack,$packPrice);
      $stmt->execute($params);
      }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllProductCategory(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product_category");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getProductCategoryById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product_category WHERE id=? LIMIT 1");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getInventoryItemByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT product.*,SUM(inv.quantity) as stock FROM product
      INNER JOIN inventory inv ON product.id=inv.product_id
      WHERE product.name Like ?
      GROUP BY inv.product_id LIMIT 1");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getInventoryItemByNameAndBatch($id,$batch,$location){
    try{
      $stmt=$this->conn->prepare("SELECT product.*,SUM(inv.quantity) as stock,inv.batch_no,inv.discount FROM product
      INNER JOIN inventory inv ON product.id=inv.product_id
      WHERE inv.product_id= ? AND inv.batch_no=? AND inv.location_id=?
      GROUP BY inv.product_id LIMIT 1");
      $params=array($id,$batch,$location);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getInventoryByName($product,$location){
    try{
      $stmt=$this->conn->prepare("SELECT product.*,SUM(inv.quantity) as stock,inv.batch_no,inv.expiry_date FROM product
      INNER JOIN inventory inv ON product.id=inv.product_id
      WHERE product.name Like ? AND inv.location_id=? AND inv.quantity>0
      GROUP BY inv.product_id,inv.batch_no ORDER BY inv.expiry_date");
      $product='%'.$product.'%';
      $params=array($product,$location);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getInventoryItemById($id){
    try{
      $stmt=$this->conn->prepare("SELECT product.*,SUM(inv.quantity) as stock FROM product
      INNER JOIN inventory inv ON product.id=inv.product_id
      WHERE product.id=?
      GROUP BY inv.product_id LIMIT 1");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveSalesOrder($entry_date,$client,$user,$location){
    try{
      $stmt=$this->conn->prepare("INSERT INTO sales_order (date_required,client,user_name,location_id) VALUES (?,?,?,?)");
      $params=array($entry_date,$client,$user,$location);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveSalesOrderDetails($lastId,$productId,$quantity,$price,$amount,$discount,$dispatch){
    try{
      $stmt=$this->conn->prepare("INSERT INTO sales_order_details (sales_order_id,product_id,quantity,price,amount,discount,quantity_delivered)
      VALUES (?,?,?,?,?,?,?)");
      $params=array($lastId,$productId,$quantity,$price,$amount,$discount,$dispatch);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function productDeliveryProcessStarted($orderId){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET delivery_started=? WHERE sales_order_id=?");
      $params=array(1,$orderId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function dispatchSalesOrderProducts($orderId,$quantity,$product_id,$payment){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order_details SET quantity_delivered=?,payment=payment+?
      WHERE sales_order_id=? AND product_id=?");
      $params=array($quantity,$payment,$orderId,$product_id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveClientOrderDetails($lastId,$productId,$quantity,$price,$amount,$discount,$bP,$profit,$batch,$tax){
    try{
      $stmt=$this->conn->prepare("INSERT INTO sales_order_details (sales_order_id,product_id,quantity,price,
        amount,discount,buying_price,profit,batch_no,tax)
       VALUES (?,?,?,?,?,?,?,?,?,?)");
      $params=array($lastId,$productId,$quantity,$price,$amount,$discount,$bP,$profit,$batch,$tax);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateClientOrderDetails($orderId,$productId,$quantity,$discount,$amount){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order_details SET quantity=?,discount=?,amount=?
      WHERE sales_order_id=?
      AND product_id=?");
      $params=array($quantity,$discount,$amount,$orderId,$productId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updatePurchaseOrder($orderId,$productId,$number,$batch,$units,$expiry_date){
    try{
      $stmt=$this->conn->prepare("UPDATE purchase_order_details SET number_purchased=?,batch_no=?
      WHERE purchase_order_id=?
      AND product_id=?");
      $params=array($quantity,$batch,$orderId,$productId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function addStockToInventory($productId,$batch,$quantity,$bonus,$discount){
    try{
      $number=$number+$bonus;
      $quantity=$number*$units;
      $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity+?,bonus=bonus+?,discount=?
      WHERE product_id=? AND batch_no=?");
      $params=array($quantity,$bonus,$discount,$productId,$batch);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function subtractStockFromInventory($productId,$batch,$quantity,$location){
    try{
      $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity-?
      WHERE product_id=? AND batch_no=? AND location_id=?");
      $params=array($quantity,$productId,$batch,$location);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePurchaseOrderDetails($orderId,$productId,$expiryDate,$batch,$number,$units,$price,$discount,$amount,$bonus){
    try{
      $number_received=$number+$bonus;
      $quantity=$number_received*$units;
      $stmt=$this->conn->prepare("INSERT INTO purchase_order_details (purchase_order_id,product_id,expiry_date,batch_no,
      number_purchased,units,quantity,price,discount,amount,bonus) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
      $params=array($orderId,$productId,$expiryDate,$batch,$number,$units,$quantity,$price,$discount,$amount,$bonus);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function productAlreadyOnOrder($orderId,$productId){
    try{
      $stmt=$this->conn->prepare("SELECT product_id FROM sales_order_details
      WHERE sales_order_id=? AND product_id=?");
      $params=array($orderId,$productId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function checkIfProductExistsInInventory($productId){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count,purchase_order_id FROM inventory
      WHERE product_id=?");
      $params=array($productId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $resultSet=$stmt->fetch();
      if($resultSet['count']>0){
        return $resultSet;
      }
      else{
        return 0;
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function checkIfProductBatchExistsInInventory($productId,$batch){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count,product_id,batch_no FROM inventory
      WHERE product_id=? AND batch_no=? LIMIT 1");
      $params=array($productId,$batch);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $resultSet=$stmt->fetch();
      if($resultSet['count']>0){
        return $resultSet;
      }
      else{
        return 0;
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getSingleProductByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE name Like ? LIMIT 1");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getSalesOrderById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM sales_order WHERE sales_order_id=? LIMIT 1");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getSalesOderDetailsByOrderId($id){
    try{
      $stmt=$this->conn->prepare("SELECT sod.*,p.description,p.name,p.id FROM  sales_order_details sod
      INNER JOIN product p ON sod.product_id=p.id
      WHERE sales_order_id=?");
      $params=array($id);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateSalesOrderById($date,$id){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET date_required=? WHERE sales_order_id=?");
      $params=array($date,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateClientOrderById($date,$id,$user){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET date_required=?,updated_by=? WHERE sales_order_id=?");
      $params=array($date,$id,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function removeSalesOderDetailsByOrderId($id){
    try{
      $stmt=$this->conn->prepare("DELETE FROM sales_order_details WHERE sales_order_id=?");
      $params=array($id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllUsersByUsername($name,$start,$rows_per_page){
    try{
      $stmt=$this->conn->prepare("SELECT user.*,sec.secrolename as role FROM user
      INNER JOIN securityroles sec ON user.secroleid=sec.secroleid
      WHERE user_name LIKE ? LIMIT ?,?");
      $params=array($name,$start,$rows_per_page);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function countAllUsersByUsername($name){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM user WHERE user_name LIKE ?");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllUsers($start,$rows_per_page){
    try{
      $stmt=$this->conn->prepare("SELECT user.*,sec.secrolename as role FROM user
      INNER JOIN securityroles sec ON user.secroleid=sec.secroleid LIMIT ?,?");
      $params=array($start,$rows_per_page);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllUsersCount(){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM user");
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getUsersWithClientSecurityRole(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM user WHERE secroleId=?");
      $params=array(7);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveNewUser($user_name,$user_pw,$secroleid){
    try{
      $date=date('Y-m-d');
      $stmt=$this->conn->prepare("INSERT INTO user (user_name,user_pw,secroleid,date_created) VALUES(?,?,?,?)");
       $params=array($user_name,$user_pw,$secroleid,$date);
       $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveLocation($name){
    try{
      $stmt=$this->conn->prepare("INSERT INTO location (name) VALUES(?)");
       $params=array($name);
       $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getClientByOrderId($id){
    try{
      $stmt=$this->conn->prepare("SELECT client FROM sales_order WHERE sales_order_id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function deductStockFromInventory($quantity,$product){
    try{
      $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity-? WHERE product_id=?");
      $params=array($quantity,$product);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllSuppliers(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM supplier");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPurchaseOrderById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM purchase_order WHERE purchase_order_id=? LIMIT 1");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePurchaseOrder($entry_date,$supplier,$location){
    try{
      $stmt=$this->conn->prepare("INSERT INTO purchase_order (entry_date,supplier_id,location_id) VALUES (?,?,?)");
      $params=array($entry_date,$supplier,$location);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveInventory($product,$quantity,$expiry_date,$batch_no,$bonus,$location,$discount){
    try{
      $stmt=$this->conn->prepare("INSERT INTO inventory (product_id,quantity,expiry_date,batch_no,bonus,location_id,discount)
       VALUES (?,?,?,?,?,?,?)");
      $params=array($product,$quantity,$expiry_date,$batch_no,$bonus,$location,$discount);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPurchaseOderDetailsByOrderId($id){
    try{
      $stmt=$this->conn->prepare("SELECT pod.*,p.name,p.id FROM  purchase_order_details pod
      INNER JOIN product p ON pod.product_id=p.id
      WHERE pod.purchase_order_id=?");
      $params=array($id);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function allDeliveriesMadeForSalesOrder($salesOrderId){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM sales_order_details
      WHERE sales_order_id=? AND quantity>quantity_delivered");
      $params=array($salesOrderId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function allPaymentMadeForSalesOrder($salesOrderId){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM sales_order_details
      WHERE sales_order_id=? AND amount>payment");
      $params=array($salesOrderId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function closeSalesOrder($salesOrderId){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET cleared=? WHERE sales_order_id=?");
      $params=array(1,$salesOrderId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function completeDelivery($salesOrderId){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET complete_delivery=? WHERE sales_order_id=?");
      $params=array(1,$salesOrderId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function clearSalesOrder($salesOrderId){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET cleared=? WHERE sales_order_id=?");
      $params=array(1,$salesOrderId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getClientOrderingValidity($user){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM sales_order WHERE client=? AND cleared=?");
      $params=array($user,0);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateUser($user,$password,$role){
    try{
      $date=date('Y-m-d');
      $stmt=$this->conn->prepare("UPDATE user SET user_pw=?,secroleid=?,date_created=? WHERE user_name=?");
      $params=array($password,$role,$date,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateLocation($name,$location,$current_location){
    try{
      $stmt=$this->conn->prepare("UPDATE location SET name=? WHERE name=? AND id !=?");
      $params=array($name,$location,$current_location);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveNewClient($name,$address,$phoneNo,$user){
    try{
      $stmt=$this->conn->prepare("INSERT INTO client (name,address,phone_no,user_name) VALUES(?,?,?,?)");
      $params=array($name,$address,$phoneNo,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateClient($client_id,$name,$address,$phoneNo,$user){
    try{
      $stmt=$this->conn->prepare("UPDATE client SET name=?,address=?,phone_no=?,user_name=? WHERE client_id=?");
      $params=array($name,$address,$phoneNo,$user,$client_id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function userAlreadyAddedAsClient($user){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM client WHERE user_name=?");
      $params=array($user);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function AllowClientUserNameUpdate($user,$client_id){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM client WHERE user_name =? AND client_id !=?");
      $params=array($user,$client_id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function countAllLocationClients($location){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM client WHERE location_id=?");
      $params=array($location);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllClients($start,$rows_per_page,$location){
    try{
      $stmt=$this->conn->prepare("SELECT cl.*,
        (SELECT SUM(sod.amount) as required_payment FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as required,
        (SELECT SUM(sod.payment) as total_paid FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as paid,
        (SELECT required-paid ) as balance
        FROM client cl WHERE cl.location_id=? ORDER BY balance DESC LIMIT ?,?");
        $params=array($location,$start,$rows_per_page);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getClientById($client_id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM client WHERE client_id=?");
      $params=array($client_id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getClientsByName($name){
    try{
        $stmt=$this->conn->prepare("SELECT cl.*,
        (SELECT SUM(sod.amount) as required_payment FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as required,
        (SELECT SUM(sod.payment) as total_paid FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as paid,
        (SELECT required-paid ) as balance
        FROM client cl WHERE name LIKE ? ORDER BY balance DESC");
        $name='%'.$name.'%';
        $params=array($name);
      $stmt->execute($params);
      return $stmt->fetchALL();

    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getClientStatementByUserName($name){
    try{
      $stmt=$this->conn->prepare("SELECT so.*,
      (SELECT SUM(sod.amount) as total_bill FROM sales_order_details sod WHERE sod.sales_order_id=so.sales_order_id) as order_amount,
      (SELECT SUM(sod.payment) as total_paid FROM sales_order_details sod WHERE sod.sales_order_id=so.sales_order_id) as payment,
      (SELECT order_amount-payment) as balance
      FROM sales_order so
      WHERE so.client LIKE ? ORDER BY so.sales_order_id DESC");
      $params=array($name);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getSalesReport($startDate,$endDate){
    try{
      $startDate=date('Y-m-d',strtotime($startDate));
      $endDate=date('Y-m-d',strtotime($endDate));
      $stmt=$this->conn->prepare("SELECT p.name,SUM(sod.discount) as discount,SUM(sod.amount) as sales,SUM(sod.payment) as payment,
      SUM(sod.profit) as profit,SUM(sod.quantity) as qsold
      FROM sales_order_details sod INNER JOIN sales_order so ON sod.sales_order_id=so.sales_order_id
      INNER JOIN product p ON sod.product_id=p.id
      WHERE so.date_required BETWEEN ? AND ?
      GROUP BY sod.product_id
      ORDER BY qsold DESC");
      $params=array($startDate,$endDate);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getProductSalesHistory($product,$startDate,$endDate){
    try{
      $startDate=date('Y-m-d',strtotime($startDate));
      $endDate=date('Y-m-d',strtotime($endDate));
      $stmt=$this->conn->prepare("SELECT p.name,SUM(sod.amount) as sales,SUM(sod.discount) as discount,SUM(sod.payment) as payment,
      SUM(sod.profit) as profit,so.date_required,SUM(sod.quantity) as qsold
      FROM sales_order_details sod
      INNER JOIN sales_order so ON sod.sales_order_id=so.sales_order_id
      INNER JOIN product p ON sod.product_id=p.id
      WHERE p.id=? AND so.date_required BETWEEN ? AND ?
      GROUP BY so.date_required");
      $params=array($product,$startDate,$endDate);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getAllSalesReport(){
    try{
      $stmt=$this->conn->prepare("SELECT so.date_required as date,SUM(sod.amount) as sales,SUM(sod.discount) as discount,SUM(sod.payment)
       as payment,SUM(sod.profit) as profit FROM sales_order_details sod
       INNER JOIN sales_order so ON sod.sales_order_id=so.sales_order_id
      GROUP BY so.date_required");
      $params=array();
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function checkImage($file){
    $target_dir = "upload/";
		$target_file = $target_dir . basename($file["name"]);
		$max_file_size = 500000;
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
    $uploadOk =1;
		if($file['size'] > $max_file_size ){
			$uploadOk=0;
			array_push($_SESSION['errors'],"Please choose an image smaller than 500kB.");
		}
		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		if(!in_array($ext, $valid_exts)){
			$uploadOk=0;
			array_push($_SESSION['errors'],"Unsupported file extension.");
		}
		if (file_exists($target_file)){
			$uploadOk=0;
			array_push($_SESSION['errors'],"Product image already exists.");
		}
		$data = getimagesize($file["tmp_name"]);
		$width = (int)$data[0];
		$height =(int)$data[1];
		if($width >320){
			$uploadOk=0;
			array_push($_SESSION['errors'],"The image width cannot exceed 320px.");
		}
		if($height !=150){
			$uploadOk=0;
			array_push($_SESSION['errors'],"The image height must be equal to 150px.");
		}
		if(!move_uploaded_file($file["tmp_name"],$target_file)) {
			$uploadOk=0;
			array_push($_SESSION['errors'],"Sorry, there was an error uploading your file.");
		}
    return $uploadOk;
	}
  function getCategoryById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM product_category WHERE id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function AllowProductNameUpdate($name,$id){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM product_category WHERE name LIKE ? AND id !=?");
      $params=array($name,$id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $result=$stmt->fetch();
      if($result['count']>0){
        array_push($_SESSION['errors'],"a product category already exists with this name");
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateProductCategory($category,$id){
    try{
      $stmt=$this->conn->prepare("UPDATE product_category SET name=? WHERE id=?");
      $params=array($category,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function productNameExists($name){
    try{
      $stmt=$this->conn->prepare("SELECT COUNT(*) as count FROM product_category WHERE name LIKE ?");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $result=$stmt->fetch();
      if($result['count']>0){
        array_push($_SESSION['errors'],"a product category already exists with this name");
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveProductCategory($category){
    try{
      $stmt=$this->conn->prepare("INSERT INTO product_category (name) VALUES (?)");
      $params=array($category);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
}
?>
