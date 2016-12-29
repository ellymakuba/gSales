<?php
$PageSecurity=1;
require 'data_access_object.php';
$dao=new DAO();
$pageSecurity=1;
$dao->checkLogin();
if (isset($_GET['SelectedRole'])){
	$SelectedRole = $_GET['SelectedRole'];
} elseif (isset($_POST['SelectedRole'])){
	$SelectedRole = $_POST['SelectedRole'];
}
?>
<html>
<?PHP $dao->includeHead('User Roles',0) ?>
</head>
<body class="container">
<?PHP $dao->includeMenu($_SESSION['tab_no']);
if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
if (isset($_POST['submit']) || isset($_GET['remove']) || isset($_GET['add']) ) {
	$InputError = 0;
	if (isset($_POST['SecRoleName']) && strlen($_POST['SecRoleName'])<4){
		$InputError = 1;
		//prnMsg(_('The role description entered must be at least 4 characters long'),'error');
	}
	unset($sql);
	if (isset($_POST['SecRoleName']) ){ // Update or Add Security Headings
		if(isset($SelectedRole)) {
			$dao->updateSecurityRole($_POST['SecRoleName'],$SelectedRole);
		} else {
			$dao->addNewSecurityRole($_POST['SecRoleName']);
		}
		unset($_POST['SecRoleName']);
		unset($SelectedRole);
	} elseif (isset($SelectedRole) ) {
		$PageTokenId = $_GET['PageToken'];
		if( isset($_GET['add'])) {
			$dao->addNewSecurityGroup($SelectedRole,$PageTokenId);
		} elseif ( isset($_GET['remove']) ) {
			$dao->removeSecurityGroupByTokenId($SelectedRole,$PageTokenId);
		}
		unset($_GET['add']);
		unset($_GET['remove']);
		unset($_GET['PageToken']);
	}
} elseif (isset($_GET['delete'])) {
	$numberOfUsersOnSecurityRole=$dao->numberOfUsersOnSecurityRole($_GET['SelectedRole']);
	if (count($numberOfUsersOnSecurityRole)>0) {
		//prnMsg( _('Cannot delete this role because user accounts are setup using it'),'warn');
	} else {
		$dao->removeSecurityGroup($_GET['SelectedRole']);
		$dao->removeSecurityRole($_GET['SelectedRole']);
	} //end if account group used in GL accounts
	unset($SelectedRole);
	unset($_GET['SecRoleName']);
}
if (!isset($SelectedRole)) {
	$roles=$dao->getAllSecurityRoles();
	echo '<table class="table table-striped table-condensed">';
	echo "<tr><th>Role</th></tr>";
	$k=0; //row colour counter
	foreach($roles as $role)
	{
		printf("<td>%s</td>
			<td><a href=\"%s?SelectedRole=%s\">Edit</a></td>
			<td><a href=\"%s?SelectedRole=%s&delete=1&SecRoleName=%s\">Delete</a></td>
			</tr>",
			$role['secrolename'],
			$_SERVER['PHP_SELF'],
			$role['secroleid'],
			$_SERVER['PHP_SELF'],
			$role['secroleid'],
			urlencode($role['secrolename']));

	} //END WHILE LIST LOOP
	echo '</table>';
} //end of ifs and buts!
if (isset($SelectedRole)) {
	echo "<br /><div class='btn btn-default btn-primary'><a href='" . $_SERVER['PHP_SELF'] ."'>Review Existing Roles</a></div>";
}
if (isset($SelectedRole)) {
	$role=$dao->getRoleById($SelectedRole);
	if (count($role)== 0 ) {
		//prnMsg( _('The selected role is no longer available.'),'warn');
	} else {
		$_POST['SelectedRole'] = $role['secroleid'];
		$_POST['SecRoleName'] = $role['secrolename'];
	}
}
echo '<br>';
echo "<form method='post' class='form-signin' action=" . $_SERVER['PHP_SELF']. ">";
if( isset($_POST['SelectedRole'])) {
	echo "<input type=hidden name='SelectedRole' VALUE='" . $_POST['SelectedRole'] . "'>";
}
echo '<table class="table table-striped table-condensed">';
if (!isset($_POST['SecRoleName'])) {
	$_POST['SecRoleName']='';
}
echo "<tr><td>Role</td>
	<td><input type='text' name='SecRoleName' class='form-control' VALUE='" . $_POST['SecRoleName'] . "'></tr>";
echo "</table><br />
	<div class='centre'><input type='Submit' name='submit' value='Enter Role'></div></form>";
if (isset($SelectedRole)) {
	$privileges=$dao->getAllPrivileges();
	$tokens=$dao->getPrivilegesByRole($SelectedRole);
	$TokensUsed = array();
	$i=0;
	foreach($tokens as $token){
		$TokensUsed[$i] =$token['tokenid'];
		$i++;
	}
	echo '<br /><table class="table table-striped"><tr>';
	if (count($privileges)>0 ) {
		echo "<th colspan=3><div class='centre'>Assigned Privileges</div></th>";
		echo "<th colspan=3><div class='centre'>Available Privileges</div></th>";
	}
	echo '</tr>';
	$k=0; //row colour counter
	foreach($privileges as $privilege) {
		if (in_array($privilege['tokenid'],$TokensUsed)){
			printf("<td>%s</td><td>%s</td>
				<td><a href=\"%s?SelectedRole=%s&remove=1&PageToken=%s\">Remove</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>",
				$privilege['tokenid'],
				$privilege['tokenname'],
				$_SERVER['PHP_SELF'],
				$SelectedRole,
				$privilege['tokenid']
				);
		} else {
			printf("<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>%s</td>
				<td>%s</td>
				<td><a href=\"%s?SelectedRole=%s&add=1&PageToken=%s\">Add</a></td>",
				$privilege['tokenid'],
				$privilege['tokenname'],
				$_SERVER['PHP_SELF'],
				$SelectedRole,
				$privilege['tokenid']
				);
		}
		echo '</tr>';
	}
	echo '</table>';
	}
 	}
	else{
		echo '<div class="alert alert-danger">
			<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
		</div>';
	}
	require 'footer.php';
echo '</body>';
echo	'</html>';
?>
