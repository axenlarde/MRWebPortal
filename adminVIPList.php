
<script type="text/javascript">

function checkNewVIPInput(VIPCount,maxEntry,formulaire)
	{
	if(document.getElementById("newVIPInputNumber").value == "")
		{
		alert("La saisie n'est pas correcte");
		}
	else if(VIPCount>maxEntry)
		{
		alert("Le nombre d'entrées maximum a été atteint");
		}
	else
		{
		formulaire.submit();
		}
	}

function checkRemoveVIP(index, formulaire)
	{
	//We add the entry to remove index to the url
	var url = document.getElementById("VIPForm").action;
	document.getElementById("VIPForm").action = url+"&entryToRemove="+index;
	
	formulaire.submit();
	}
	
</script>


<?php
/** 
 * Page used to set the VIP List
 */

/****
 * We get the xml parameters to display
 */
//First we read the xml file
$xml = simplexml_load_file("document/xmlFiles/OverallSettings.xml") or die("Error");

//Then we find the good branch to display
$branchIndex = 1;
$branchName = "";
$maxEntry = 2000;

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

//Then we get the VIP and TAG file name
$VIPFileName = $xml->$branchName->incomingcallsdetection->list;
$PTFileName = $xml->$branchName->prioritytag;

//Finally we open this file
$VIPFile = simplexml_load_file("document/xmlFiles/".$VIPFileName) or die("Error");
$PTFile = simplexml_load_file("document/xmlFiles/".$PTFileName) or die("Error");

/**
 * We get the tag list
 */
$tagList = array();
$index = 1;
while(true)
	{
	$TagName = "tag".$index;
	$tag = $PTFile->$TagName;

	if(isset($PTFile->$TagName))
		{
		$tagList[$index-1]["name"] = $tag->name;
		$tagList[$index-1]["priority"] = $tag->priority;
		}
	else
		{
		break;
		}

	if($index>50)break;//Just a security
	$index++;
	}

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Administration de la liste des VIP</div></h3>
<br>
<br>
<?php
		$vipCount = 0;
		while(true)
			{
			$incomingcall = $VIPFile->incomingcall[$vipCount];
			
			if(isset($incomingcall))
				{
				$vipCount++;
				}
			else
				{
				break;
				}
			}
		echo "Nombre total d'entrées : <b>".$vipCount."</b>/".$maxEntry;
	?>
<hr>
<form name="VIPForm" id="VIPForm" method=post action="vipListTreatment.php?vipfilename=<?php echo $VIPFileName?>">
	<h4>Ajouter une nouvelle entrée : </h4>
	<table>
		<tr>
			<td>Numéro</td>
			<td>Nom</td>
			<td>Tag</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td><input type="text" name="newVIPInputNumber" id="newVIPInputNumber">
			<td><input type="text" name="newVIPInputName" id="newVIPInputName">
			<td><select name="newVIPInputTag" id="newVIPInputTag">
				<?php 
				for($i=0; $i<count($tagList); $i++)
					{
					echo "<option value\"".$tagList[$i]["name"]."\">".$tagList[$i]["name"]."</option>";	
					}
				?>
			</select>
			<td></td>
			<?php echo "<td><input type=\"button\" name=\"Ajouter\" value=\"Ajouter\" onclick=\"checkNewVIPInput(".$vipCount.",".$maxEntry.",this.form)\"></td>"; ?>
		</tr>
	</table>
	<br>
	<hr>
	<h4>Liste actuelle des numéros traités : </h4>
	<div class="vipTable">
	<table>
		<tr>
			<td><b>Numéro</b></td>
			<td><b>Nom</b></td>
			<td><b>Tag</b></td>
			<td><b>Priorité</b></td>
			<td><b></b></td>
		</tr>
	<?php
	/**
	 * First we display the current vip entries
	 */
	$index = 0;
	while(true)
		{
		$incomingcall = $VIPFile->incomingcall[$index];
		
		if(isset($incomingcall))
			{
			echo "
	 			<tr>
	 				<td>".$incomingcall->number."</td>
					<td>".$incomingcall->name."</td>
					<td>".$incomingcall->tag."</td>
					<td>
				";
			
			for($i=0; $i<count($tagList); $i++)
				{
				if(((string)$incomingcall->tag) == $tagList[$i]["name"])
					{
					echo $tagList[$i]["priority"];
					break;
					}
				}
			
			echo"
					</td>
					<td><input type=\"button\" name=\"removeDate\" value=\"X\" title=\"Supprimer\" onclick=\"checkRemoveVIP(".($index+1).",this.form)\"></td>
	 			</tr>
 				";
			}
		else
			{
			break;
			}
		
		if($index>$maxEntry)break;//Just a security	
		$index++;
		}
	
	?>
</table>
</div>
</form>



