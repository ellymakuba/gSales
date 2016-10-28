<?PHP
//Select Overdue Loan Instalments from LTRANS
$timestamp = time();
$defaulters=$functionObject->getLoanDefaulters($timestamp);
?>

<table id="tb_table">
	<colgroup>
		<col width="15%">
		<col width="45%">
		<col width="20%">
		<col width="20%">
	</colgroup>
	<tr>
		<th colspan="4" class="title">Defaulted Loan Instalments</th>
	</tr>
	<tr>
		<th>Loan No.</th>
		<th>Client Name</th>
		<th>Due Date</th>
		<th>Amount Due</th>
	</tr>
	<?PHP
	$color = 0;
	foreach($defaulters as $defaulter){
		tr_colored($color);
		echo '	<td><a href="loan.php?lid='.$defaulter['loan_id'].'">'.$defaulter['loan_no'].'</a></td>
						<td>'.$defaulter['cust_name'].'</td>
						<td>'.date("d.m.Y",$defaulter['ltrans_due']).'</td>
						<td>'.number_format($defaulter['ltrans_principaldue']+$defaulter['ltrans_interestdue']).' '.$_SESSION['set_cur'].'</td>
					</tr>';
		
		// Module for automatic fine charging
		if ($_SESSION['set_auf'] != NULL) include './modules/mod_autofine.php';
	}
	?>
</table>