<?php
session_start(); // Création de la session

/******
 * Page used to treat a new parameters value
 *
 */
include "checkForBranch.php";

$newValue = false;
$filename;

if($branch == "Cafes")
	{
	$filename = "document/xmlFiles/OverallSettingsCafes.xml";
	$xml=simplexml_load_file($filename) or die("Error");
	
	if(isset($_POST['maxwaitingtime']))
		{
		$maxwaitingtime = $_POST['maxwaitingtime'];
		$xml->waitingqueue->maxtimeinqueue = $maxwaitingtime;
		$newValue = true;
		}
		
	if(isset($_POST['messagepromoenabled']))
		{
		$messagePromoEnabled = $_POST['messagepromoenabled'];
		$xml->promo->promoenabled = $messagePromoEnabled;
		$newValue = true;
		}
	}
else if($branch == "Vins")
	{
	$filename = "document/xmlFiles/OverallSettings_Vins.xml";
	$xml=simplexml_load_file($filename) or die("Error");
	
	if(isset($_POST['maxwaitingtime']))
		{
		$maxwaitingtime = $_POST['maxwaitingtime'];
		$xml->branch->maxwaitingtime = $maxwaitingtime/10;
		$newValue = true;
		}
	}
else
	{
	$filename = "document/xmlFiles/OverallSettings.xml";
	$xml=simplexml_load_file($filename) or die("Error");
	
	if(isset($_POST['maxwaitingtime']))
		{
		$maxwaitingtime = $_POST['maxwaitingtime'];
	
		if(isset($_GET['branch']))
			{
			$xml->$_GET['branch']->maxwaitingtime = $maxwaitingtime/10;
			$newValue = true;
			}
		}
	}
	
	
if($newValue)
	{
	$xml->asXML($filename);
	}

//To go back to the global paramaters administration page
header("Location: mainpage.php?page=adminGlobalParameters");
exit;

?>