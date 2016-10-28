<?PHP
	session_start();
	require 'data_access_object.php';
	$functionObject=new DAO();
	
	//Record logout time for this session
	/*$logrec_end = time();
	$sql_update_logrec = "UPDATE logrec SET logrec_end = '$logrec_end', logrec_logout = '1' WHERE logrec_id = '$_SESSION[logrec_id]'";
	$query_update_logrec = mysql_query($sql_update_logrec);
	checkSQL($query_update_logrec);*/
	
	$functionObject->logout();
?>