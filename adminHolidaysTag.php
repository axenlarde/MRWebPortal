
<script type="text/javascript">

function checkNewDate(formulaire)
	{
	if(document.getElementById("newDateInput").value == "")
		{
		alert("Veuillez entrer une date");
		}
	else
		{
		formulaire.submit();
		}
	}

function checkRemoveDate(index, formulaire)
	{
	//We add the date to remove index to the url
	var url = document.getElementById("holidaysForm").action;
	document.getElementById("holidaysForm").action = url+"&dateToRemove="+index;
	
	formulaire.submit();
	}
	
</script>

<script>
$(function()
	{
    $("#newDateInput").datepicker({dateFormat: 'd/m'});
  	});
</script>

<?php
/** 
 * Page used to set the closure days
 */

/****
 * We get the xml parameters to display
 */
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

//We detect here if there are other opening hours files to use in the tag file
$PublicHolidaysFileNameTag = "";
$TagName = "";
$holidaysFile = null;

//We get here the choosen tag name
if(!empty($_GET))
	{
	$TagName = $_GET['tag'];
	}
else
	{
	header("Location: mainpage.php");
	exit;
	}

try
	{
	//The limit is 20 tags
	for($i = 1; $i<20; $i++)
		{
		$TagNumber = "tag".$i;
		if($PTFile->$TagNumber->name = $TagName)
			{
			$PublicHolidaysFileNameTag = $PTFile->$TagNumber->publicholiday;
			if($PublicHolidaysFileNameTag == null || ($PublicHolidaysFileNameTag == ""))
				{
				throw new Exception("Corrupted value");
				}
			else
				{
				$holidaysFile = simplexml_load_file("document/xmlFiles/".$PublicHolidaysFileNameTag) or die("Error");
				}
			break;
			}
		}
	}
catch(Exception $exc)
	{
	header("Location: mainpage.php");
	exit;
	}
	
echo"
<h3><div class=\"navibar\"><a href=\"mainpage.php?page=branchMainAdmin\">Retour</a>>Administration des jours de fermeture</div></h3>
<h4>Catégorie ".$TagName."</h4>
<h4>Liste actuelle des jours de fermeture : </h4>
<form name=\"holidaysForm\" id=\"holidaysForm\" method=post action=\"holidaysTreatment.php?holidaysfilename=".$PublicHolidaysFileNameTag."&adminpage=adminHolidaysTag&tag=".$TagName."\">
	<table>";
	/**
	 * First we display the current closure days
	 */
	$index = 1;
	while(true)
		{
		$closureDayName = "PUBLICHOLIDAY".$index;
		$closureday = $holidaysFile->$closureDayName;
		
		if(isset($holidaysFile->$closureDayName))
			{
			echo "
	 			<tr>
	 				<td>Jour ".$index." : <b>".$closureday."</b></td>
					<td><input type=\"button\" name=\"removeDate\" value=\"-\" title=\"Supprimer\" onclick=\"checkRemoveDate(".$index.",this.form)\"></td>
	 			</tr>
 				";
			}
		else
			{
			break;
			}
		
		if($index>500)break;//Just a security	
		$index++;
		}
	
	?>
	<tr><td>&nbsp</td></tr>
	<tr>
		<td>Ajouter une nouvelle date : </td>
		<td><input type="text" name="newDateInput" id="newDateInput"><input type="button" name="Ajouter" value="Ajouter" onclick="checkNewDate(this.form)"></td>
	</tr>
</table>
</form>



