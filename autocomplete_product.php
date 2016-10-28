<?php
require 'data_access_object.php';
$dao=new DAO();
$dao->checkLogin();
$q=$_GET['term'];
$products=$dao->getProductsByName($q);
foreach($products as $product)
{
 $obj[]=array('id' => $product['id'],'name' => $product['name'],'cost' => $product['buying_price']);
}
print json_encode($obj);
?>
