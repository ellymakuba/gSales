<?php
require 'data_access_object.php';
$dao=new DAO();
$dao->checkLogin();
$product=$_GET['product'];
$location=$_GET['location'];
$inventories=$dao->getInventoryByName($product,$location);
foreach($inventories as $inventory)
{
 $obj[]=array('id' => $inventory['id'],'name' => $inventory['name'],'stock' => $inventory['stock'],
 'batch' => $inventory['batch_no'],'expiry' => $inventory['expiry_date']);
}
print json_encode($obj);
?>
