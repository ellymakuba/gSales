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
public function getUserByUsernameAndPassword($username, $password){
 		$password=sha1($password);
    $stmt = $this->conn->prepare("SELECT * FROM user,securityroles WHERE user.secroleid = securityroles.secroleid AND user_name = ? AND user_pw LIKE ?");
		$data=array($username, $password);
		$stmt->execute($data);
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
					<li>'.$_SESSION['log_user'].'
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
        echo '><a href="manage_settings.php">Settings</a></li>';
      }
			echo '</ul>
		</div>';
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

  function getAllProducts(){
    try{
      $stmt=$this->conn->prepare("SELECT p.*,
      (SELECT inv.quantity FROM inventory inv WHERE inv.product_id=p.id) as quantity
      FROM product p ");
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
      $stmt=$this->conn->prepare("SELECT * FROM product WHERE name LIKE ? LIMIT 1");
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
  function updateProduct($productId,$name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel){
		try{
			$stmt=$this->conn->prepare("UPDATE product SET name=?,buying_price=?,selling_price=?,
      description=?,company=?,category=?,reorder_level=? WHERE id=?");
			$params=array($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel,$productId);
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
      $stmt=$this->conn->prepare("SELECT * FROM sales_order where  (cleared=? OR complete_delivery=?)");
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
  function getAllPurchaseOrders(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM purchase_order");
      $stmt->execute();
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
  function addNewProduct($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel){
    try{
      $stmt=$this->conn->prepare("INSERT INTO product (name,buying_price,selling_price,description,company,category,reorder_level)
      VALUES(?,?,?,?,?,?,?)");
      $params=array($name,$bPrice,$sPrice,$description,$company,$category,$reorderLevel);
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
  function getInventoryByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT product.*,SUM(inv.quantity) as stock FROM product
      INNER JOIN inventory inv ON product.id=inv.product_id
      WHERE name Like ?
      GROUP BY inv.product_id");
      $name='%'.$name.'%';
      $params=array($name);
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
  function saveSalesOrder($entry_date,$client){
    try{
      $stmt=$this->conn->prepare("INSERT INTO sales_order (date_required,client) VALUES (?,?)");
      $params=array($entry_date,$client);
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
  function saveClientOrderDetails($lastId,$productId,$quantity,$price,$amount,$discount,$bP,$profit){
    try{
      $stmt=$this->conn->prepare("INSERT INTO sales_order_details (sales_order_id,product_id,quantity,price,amount,discount,buying_price,profit)
       VALUES (?,?,?,?,?,?,?,?)");
      $params=array($lastId,$productId,$quantity,$price,$amount,$discount,$bP,$profit);
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
  function updatePurchaseOrder($orderId,$productId,$quantity,$batch){
    try{
      $stmt=$this->conn->prepare("UPDATE inventory SET quantity=?,batch_no=?
      WHERE purchase_order_id=?
      AND product_id=?");
      $params=array($quantity,$batch,$orderId,$productId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function addStockToInventory($orderId,$lastId,$batch,$productId,$quantity,$expiryDate){
    try{
      $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity+?,purchase_order_id=?,batch_no=?,expiry_date=?
      WHERE purchase_order_id=?
      AND product_id=?");
      $params=array($quantity,$lastId,$batch,$expiryDate,$orderId,$productId);
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
      $stmt=$this->conn->prepare("SELECT sod.discount,sod.buying_price,sod.quantity,sod.quantity_delivered,sod.price,p.description,p.name,sod.payment,p.id FROM  sales_order_details sod
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
  function updateClientOrderById($date,$id){
    try{
      $stmt=$this->conn->prepare("UPDATE sales_order SET date_required=? WHERE sales_order_id=?");
      $params=array($date,$id);
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
  function getAllUsers(){
    try{
      $stmt=$this->conn->prepare("SELECT user.*,sec.secrolename as role FROM user
      INNER JOIN securityroles sec ON user.secroleid=sec.secroleid");
      $stmt->execute();
      return $stmt->fetchALL();
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
  function savePurchaseOrder($entry_date,$supplier){
    try{
      $stmt=$this->conn->prepare("INSERT INTO purchase_order (entry_date,supplier_id) VALUES (?,?)");
      $params=array($entry_date,$supplier);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveInventory($lastId,$product,$quantity,$expiry_date,$batch_no){
    try{
      $stmt=$this->conn->prepare("INSERT INTO inventory (purchase_order_id,product_id,quantity,expiry_date,batch_no) VALUES (?,?,?,?,?)");
      $params=array($lastId,$product,$quantity,$expiry_date,$batch_no);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPurchaseOderDetailsByOrderId($id){
    try{
      $stmt=$this->conn->prepare("SELECT inv.quantity,inv.expiry_date,inv.batch_no,p.name,p.id,p.buying_price FROM  inventory inv
      INNER JOIN product p ON inv.product_id=p.id
      WHERE inv.purchase_order_id=?");
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
  function getAllClients(){
    try{
      $stmt=$this->conn->prepare("SELECT cl.*,
        (SELECT SUM(sod.amount) as required_payment FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as required,
        (SELECT SUM(sod.payment) as total_paid FROM sales_order so
        INNER JOIN sales_order_details sod ON so.sales_order_id=sod.sales_order_id
        WHERE so.client=cl.user_name) as paid,
        (SELECT required-paid ) as balance
        FROM client cl ORDER BY balance DESC");
      $stmt->execute();
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
      $stmt=$this->conn->prepare("SELECT p.name,SUM(sod.amount) as sales,SUM(sod.discount) as discount,SUM(sod.payment) as payment,
      SUM(sod.profit) as profit
      FROM sales_order_details sod INNER JOIN sales_order so ON sod.sales_order_id=so.sales_order_id
      INNER JOIN product p ON sod.product_id=p.id
      WHERE so.date_required BETWEEN ? AND ?
      GROUP BY sod.product_id");
      $params=array($startDate,$endDate);
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
