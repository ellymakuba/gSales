<?php
require 'data_access_object.php';
$dao=new DAO();
$dao->checkLogin();
$sales=$dao->getAllSalesReport();
foreach($sales as $sale)
{
 $obj[]=array('date' => $sale['date'],'profit' => number_format($sale['profit']),'sales' => number_format($sale['sales']));
}
print json_encode($obj);
?>
