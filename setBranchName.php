<?php
session_start(); // Cr�ation de la session

include "checkForAdmin.php";

//If the get variables "branch is define, we use it to change the branch of the user
if((!empty($_GET)) && (isset($_GET['branch'])))
	{
	$branch = $_GET['branch'];

	if(($branch == "Vins") || ($branch == "Expresso") || ($branch == "Cafes"))
		{
		$_SESSION['login'][3] = $branch;
		header("Location: mainpage.php?page=branchMainAdmin");
		exit;
		}
	}

?>