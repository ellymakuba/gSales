<?PHP
//Select Subscription Defaulters from CUSTOMER

$last_subscr = time() - $functionObject->convertDays(365); //Seconds for 365 days
$sql_subscrdef = $functionObject->getCustomerLastSubscription($last_subscr);
?>
<table id="tb_table">
	<colgroup>
		<col width="20%">
		<col width="60%">
		<col width="20%">
	</colgroup>
	<tr>
		<th colspan="3" class="title">Overdue Subscription Fees</th>
	</tr>
	<tr>
		<th>Cust. No.</th>
		<th>Client Name</th>
		<th>Last Paid</th>
	</tr>
	<?PHP
	$color = 0;
	foreach($sql_subscrdef as $custSubscription){
		tr_colored($color);
		echo '	<td><a href="customer.php?cust='.$custSubscription['cust_id'].'">'.$custSubscription['cust_no'].'</a></td>
						<td>'.$custSubscription['cust_name'].'</td>
						<td>'.date("d.m.Y", $custSubscription['cust_lastsub']).'</td>
					</tr>';
					
		// Module for automatic account deactivation if customer failed to renew subscription 
		if ($_SESSION['set_deact'] != NULL) include './modules/mod_deactivate.php';
	}
	?>
</table>