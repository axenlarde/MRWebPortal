<?php
session_start(); // Création de la session

/******
 * Page used to treat a new parameters value
 */

include "branchFound.php";

$VIPFileName = "";
$VIPFile = "";
$fileFound = false;

if(isset($_GET['vipfilename']))
	{
	$VIPFileName = $_GET['vipfilename'];
	$VIPFile = simplexml_load_file("document/xmlFiles/".$VIPFileName) or die("Error");
	$fileFound = true;
	}
	
if($fileFound)
	{
	/**
	 * We find the last index and we extract all the entries
	 */
	$index = 0;
	$entryName = "";
	$Entries = array();
	
	while(true)
		{
		$entryName = $VIPFile->incomingcall[$index];
	
		if(isset($entryName))
			{
			$Entries[$index]["number"] = $entryName->number;
			$Entries[$index]["name"] = $entryName->name;
			$Entries[$index]["description"] = $entryName->description;
			$Entries[$index]["tag"] = $entryName->tag;
			}
		else
			{
			break;
			}
	
		if($index>1000)break;//Just a security
		$index++;
		}	
	
	if((isset($_POST["newVIPInputNumber"])) && ($_POST["newVIPInputNumber"] != ""))
		{
		/**
		 * We add the new entry
		 */
		$NewEntry = $VIPFile->addChild("incomingcall");
		$NewEntry->addAttribute("number",$_POST["newVIPInputNumber"]);
		$NewEntry->addChild("number",$_POST["newVIPInputNumber"]);
		$NewEntry->addChild("name",$_POST["newVIPInputName"]);
		$NewEntry->addChild("description","");//Description is not used anymore but we still add it as an empty value
		$NewEntry->addChild("tag",$_POST["newVIPInputTag"]);
		
		$NewDoc = new DOMDocument();
		$NewDoc->preserveWhiteSpace = false;
		$NewDoc->formatOutput = true;
		
		$NewDoc->loadXML($VIPFile->asXML());
		$NewDoc->save("document/xmlFiles/".$VIPFileName);
		//$NewDoc->save($VIPFileName);
		}
	else if((isset($_GET["entryToRemove"])) && ($_GET["entryToRemove"] != ""))
		{
		/**
		 * From here we write a brand new XML file without the removed entry
		 */
		$xmlstr = "<incomingcallslist></incomingcallslist>";
		$newXMLFile = new SimpleXMLElement($xmlstr);
		$index = 1;
		
		for($i=0; $i<count($Entries); $i++)
			{
			if(($i+1) != $_GET["entryToRemove"])
				{
				$newChildEntry = $newXMLFile->addChild("incomingcall");
				$newChildEntry->addAttribute("number",$Entries[$i]["number"]);
				$newChildEntry->addChild("number", $Entries[$i]["number"]);
				$newChildEntry->addChild("name", $Entries[$i]["name"]);
				$newChildEntry->addChild("description", $Entries[$i]["description"]);
				$newChildEntry->addChild("tag", $Entries[$i]["tag"]);
				
				$index++;
				}
			}
		
		$NewDoc = new DOMDocument();
		$NewDoc->preserveWhiteSpace = false;
		$NewDoc->formatOutput = true;
		
		$NewDoc->loadXML($newXMLFile->asXML());
		
		if(count($Entries)<=1)
			{
			//We do not delete the last entry
			}
		else
			{
			$NewDoc->save("document/xmlFiles/".$VIPFileName);
			}
		}
	}

//To go back to the global paramaters administration page
header("Location: mainpage.php?page=adminVIPList");
exit;

?>