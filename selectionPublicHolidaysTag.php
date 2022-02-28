<?php
/**
 * Page used to display the available Tag and then allow the user to select which one he want to 
 * configure
 * 
 * Has to be improved
 */
include "branchFound.php";
?>

<br>
<h3><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Veuillez choisir la catégorie à configurer :</h3>
<br>
<br>
<div class="selectionFiliale">
<table style="width:100%">
<?php

//First we read the xml file
$xml = simplexml_load_file("document/xmlFiles/OverallSettings.xml") or die("Error");

//Then we find the good branch to display
$branchIndex = 1;
$branchName = "";

try
	{
	//The limit is 10 branches
	for($i = 0; $i<10; $i++)
		{
		$branchName = "branch".$branchIndex;
		if(($xml->$branchName->name) == $_SESSION['login'][3])
			{
			break;
			}
		$branchIndex++;
		}
	}
catch(Exception $exc)
	{
	//The branch doesn't exist
	header("Location: mainpage.php");
	exit;
	}

//Then we get the priority tag file name
$PTFileName = $xml->$branchName->prioritytag;

//Finally we open this file
$PTFile = simplexml_load_file("document/xmlFiles/".$PTFileName) or die("Error");
$TagCount = 0;
$TagName = "";
$SavedTagName = "";

try
	{
	//The limit is 20 tags
	for($i = 1; $i<20; $i++)
		{
		$TagName = "tag".$i;
		$PublicHolidaysFileNameTag = $PTFile->$TagName->publicholiday;
		if($PublicHolidaysFileNameTag == null || ($PublicHolidaysFileNameTag == ""))
			{
			//Nothing found
			}
		else
			{
			$TagCount++;
			$SavedTagName = $PTFile->$TagName->name;
			//We display here the tag
			echo"
			<tr>
				<td align=\"left\">- <a href=\"mainpage.php?page=adminHolidaysTag&tag=".$SavedTagName."\">".$SavedTagName."</a></td>
			</tr>
			";
			}
		}
	if($TagCount == 1)
		{
		//If we found only one tag, we do not ask the user to choose
		header("Location: mainpage.php?page=adminHolidaysTag&tag=".$SavedTagName);
		}
	}
catch(Exception $exc)
	{
	//The tag doesn't exist
	header("Location: mainpage.php");
	exit;
	}
?>
</table>
</div>