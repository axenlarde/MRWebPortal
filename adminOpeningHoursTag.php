
<script type="text/javascript">

function verif(formulaire)
	{
	formulaire.submit();
	}

function closeThisDay(formulaire, dayName)
	{
	document.getElementById(dayName+"StartHours1").value = "00";
	document.getElementById(dayName+"StartMinutes1").value = "00";
	document.getElementById(dayName+"EndHours1").value = "00";
	document.getElementById(dayName+"EndMinutes1").value = "00";
	document.getElementById(dayName+"StartHours2").value = "00";
	document.getElementById(dayName+"StartMinutes2").value = "00";
	document.getElementById(dayName+"EndHours2").value = "00";
	document.getElementById(dayName+"EndMinutes2").value = "00";

	formulaire.submit();
	}
</script>


<?php
/** 
 * Page used to set the opening hours
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
$OpeningHoursFileNameTag = "";
$TagName = "";
$OHFile = null;

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
			$OpeningHoursFileNameTag = $PTFile->$TagNumber->openinghours;
			if($OpeningHoursFileNameTag == null || ($OpeningHoursFileNameTag == ""))
				{
				throw new Exception("Corrupted value");
				}
			else
				{
				$OHFile = simplexml_load_file("document/xmlFiles/".$OpeningHoursFileNameTag) or die("Error");
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
<h3><div class=\"navibar\"><a href=\"mainpage.php?page=branchMainAdmin\">Retour</a>>Administration des horaires d'ouverture</div></h3>
<h4>Catégorie ".$TagName."</h4>
<table>
";
	
	$dayIndex = 2; //Monday in the xml file
	$dayNames = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
	
	for($j=0; $j<7; $j++)
		{
		//We display each day	
		
		if($dayIndex == 8)$dayIndex = 1;
		$dayName = "DAY".$dayIndex;
		
		$start1 = $OHFile->$dayName->START1;
		$end1 = $OHFile->$dayName->END1;
		$start2 = $OHFile->$dayName->START2;
		$end2 = $OHFile->$dayName->END2;
		
		$tabStart1 = explode(":", $start1);
		$start1 = $tabStart1[0].":".$tabStart1[1];
		$tabStart2 = explode(":", $start2);
		$start2 = $tabStart2[0].":".$tabStart2[1];
		$tabEnd1 = explode(":", $end1);
		$end1 = $tabEnd1[0].":".$tabEnd1[1];
		$tabEnd2 = explode(":", $end2);
		$end2 = $tabEnd2[0].":".$tabEnd2[1];
		
		if($start1 == "00:00")
			{
			echo "<tr><td><b>".$dayNames[$j]."</b> : </td><td>Fermé</td>";
			}
		else
			{
			echo "
				<tr>
					<td><b>".$dayNames[$j]."</b> :</td>
					<td>".$start1."</td>
					<td> à </td>
					<td>".$end1."</td>
				";
				
			if($start2 == "00:00")
				{
				//echo "</tr>";
				}
			else
				{
				echo "
					<td> puis de </td>
					<td>".$start2."</td>
					<td> à </td>
					<td>".$end2."</td>
					";
				}
			}
		
		echo "</tr>";
		
		$dayIndex++;
		}
	?>
</table>
<form name="openingHoursForm" id="openingHoursForm" method=post action="openingHoursTreatment.php?ohfilename=<?php echo $OpeningHoursFileNameTag."&adminpage=adminOpeningHoursTag&tag=".$TagName?>" onkeypress="submitForm(event)">
	<br>
	<br>
	<b>Nouvelles valeurs :</b>
	<table>
	
	<?php
	$dayIndex = 2; //We start with monday
	
	/**
	 * We now propose to update the value
	 */
	
	for($j=0; $j<7; $j++)
		{
		//We display each day
	
		if($dayIndex == 8)$dayIndex = 1;
		$dayName = "DAY".$dayIndex;
	
		$start1 = $OHFile->$dayName->START1;
		$end1 = $OHFile->$dayName->END1;
		$start2 = $OHFile->$dayName->START2;
		$end2 = $OHFile->$dayName->END2;
	
		$tabStart1 = explode(":", $start1);
		$tabStart2 = explode(":", $start2);
		$tabEnd1 = explode(":", $end1);
		$tabEnd2 = explode(":", $end2);
		
		echo "
			<td><b>".$dayNames[$j]."</b> :</td>
			<td><select name=\"".$dayNames[$j]."StartHours1\" id=\"".$dayNames[$j]."StartHours1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabStart1[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."StartMinutes1\" id=\"".$dayNames[$j]."StartMinutes1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabStart1[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> à </td>
			<td><select name=\"".$dayNames[$j]."EndHours1\" id=\"".$dayNames[$j]."EndHours1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabEnd1[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."EndMinutes1\" id=\"".$dayNames[$j]."EndMinutes1\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabEnd1[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> puis de </td>
			<td><select name=\"".$dayNames[$j]."StartHours2\" id=\"".$dayNames[$j]."StartHours2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabStart2[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."StartMinutes2\" id=\"".$dayNames[$j]."StartMinutes2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabStart2[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td> à </td>
			<td><select name=\"".$dayNames[$j]."EndHours2\" id=\"".$dayNames[$j]."EndHours2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=23; $i++)
			{
			if($i==$tabEnd2[0])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
			<td>:</td>
			<td><select name=\"".$dayNames[$j]."EndMinutes2\" id=\"".$dayNames[$j]."EndMinutes2\">
			<option value=\"00\">00</option>
			";
		for($i=1; $i<=59; $i++)
			{
			if($i==$tabEnd2[1])echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
			else echo "<option value=\"".$i."\">".$i."</option>";
			}
		echo "
			</select></td>
				<td><input type=\"button\" name=\"reset\" value=\"Fermer ce jour\" onclick=\"closeThisDay(this.form,'".$dayNames[$j]."')\"></td>
			";
			
		echo "</tr>";
	
		$dayIndex++;
		}
	?>
	<tr><td><input type="button" name="modifier" value="Valider" onclick="verif(this.form)"></td></tr>
	</table>
</form>



