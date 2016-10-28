<?php
require 'data_access_object.php';
$dao=new DAO();
$dao->checkLogin();
$q=$_GET['term'];
$inventories=$dao->getInventoryByName($q);
foreach($inventories as $inventory)
{
 $obj[]=array('id' => $inventory['id'],'name' => $inventory['name'],'stock' => $inventory['stock']);
}
print json_encode($obj);
?>
