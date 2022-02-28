<?php
/****
 * If somebody arrive on this page without the administrator right, it is redirected to the main page
 */

if((isset($_SESSION['login'])) && ($_SESSION['login'][3] != "All"))
	{
	//It means that there is a problem. We should go back to the login page
	$_SESSION = array();
	header("Location: mainpage.php");
	exit;
	}

?>