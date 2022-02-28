<?php
/**
 * Used to check that we are well connected as a branch administrator
 */
$branch;
$branchFound = false;

if(isset($_SESSION['login']))
	{
	if(!empty($_SESSION['login']))
		{
		$branch = $_SESSION['login'][3];
		if(($branch == "Vins") || ($branch == "Expresso") || ($branch == "Cafes"))
			{
			$branchFound = true;
			}
		}
	}

if(!$branchFound)
	{
	//It means that there is a problem. We should go back to the login page
	$_SESSION = array();
	header("Location: mainpage.php");
	exit;
	}

?>