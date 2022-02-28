
<script type="text/javascript">

function verif(formulaire)
	{
	formulaire.submit();
	}
</script>


<?php
/** 
 * Page used to set the global parameters
 */

/****
 * We get the xml parameters to display
 */
//Common Variables
$MaxWaitingTime;
$MessagePromoEnabled;

include "findBranch.php";

if($branch == "Cafes")
	{
	$settingsFile = simplexml_load_file("document/xmlFiles/OverallSettingsCafes.xml") or die("Error");
	
	$MessagePromoEnabled = $settingsFile->promo->promoenabled;
	$MaxWaitingTime = intval($settingsFile->waitingqueue->maxtimeinqueue);
	}
else if($branch == "Vins")
	{
	$settingsFile = simplexml_load_file("document/xmlFiles/OverallSettings_Vins.xml") or die("Error");
	
	$MessagePromoEnabled = "";
	$MaxWaitingTime = intval($settingsFile->branch->maxwaitingtime)*10;
	}
else
	{
	$settingsFile = simplexml_load_file("document/xmlFiles/OverallSettings.xml") or die("Error");
	
	$MessagePromoEnabled = "";
	$MaxWaitingTime = intval($settingsFile->$branchName->maxwaitingtime)*10;
	}

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Administration des paramètres globaux</div></h3>
<br>
<br>
<form name="globalParametersForm" id="globalParametersForm" method=post action="globalParametersTreatment.php?branch=<?php echo $branchName?>" onkeypress="submitForm(event)">
	<table>
		<tr>
			<td>Temps d'attente maximum : </td>
			<td><b><?php echo $MaxWaitingTime?></b> secondes</td>
			<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
			<td>Nouvelle valeur : </td>
			<td><select name="maxwaitingtime" id="maxwaitingtime">
				<?php
				for($i=10; $i<=300; $i+=10)
					{
					if($i == $MaxWaitingTime)
						{
						echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
						}
					else
						{
						echo "<option value=\"".$i."\">".$i."</option>";
						}
					}
				?>
			</select></td>
		</tr>
		<tr>
		<td>&nbsp</td>
		</tr>
		<?php 
		if($MessagePromoEnabled != "")
			{
			echo '<tr><td>Message de promotion : </td><td>';
			$Enabled = ($MessagePromoEnabled == "true")?true:false;
			
			if($Enabled)
				{
				echo "<b>Activé</b>";
				}
			else
				{
				echo "<b>Désactivé</b>";
				}
			echo '</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td><td>Nouvelle valeur : </td><td>
 					<select name="messagepromoenabled" id="messagepromoenabled">';
			if($Enabled)
				{
				echo'
					<option value="true" selected="selected">Activé</option>
 					<option value="false">Désactivé</option>
					';
				}
			else
				{
				echo'
					<option value="true">Activé</option>
 					<option value="false" selected="selected">Désactivé</option>
					';
				}
 			echo '
 					</select>
 				</td>
 			</tr>';
			}
		?>
	</table>
	<br>
	<div class="addnewbutton"><input type="button" name="modifier" value="modifier" onclick="verif(this.form)"></div>
</form>



