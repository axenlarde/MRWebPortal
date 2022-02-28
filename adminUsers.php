
<script type="text/javascript">


function checkNewUser(count, maxEntry)
	{
	if(count >= maxEntry)
		{
		alert("Vous ne pouvez pas ajouter d'utilisateur : Vous avez atteint le nombre maximum");
		}
	else
		{
		window.location = "mainpage.php?page=newUser";
		}
	}

function checkRemoveUser(index)
	{
	if(confirm("Etes vous sûr de vouloir supprimer cet utilisateur ?"))window.location = "userTreatment.php?action=delete&index="+index;
	}
</script>


<?php
/** 
 * Page used to set the User List
 */
include "checkForAdmin.php";

$usersFile = simplexml_load_file("document/users.xml");
$MaxUser = 20;

?>
<h3><div class="navibar"><a href="mainpage.php?page=selectionFiliale">Retour</a>>Gestion des utilisateurs</div></h3>
<br>
<h3>Liste des utilisateurs : </h3>
<hr>
	<div class="forwardlist">
	<table>
		<tr>
			<td><b>UserID</b></td>
			<td><b>Nom</b></td>
			<td><b>Prénom</b></td>
			<td><b>Filiale</b></td>
			<td><b></b></td>
		</tr>
	<?php
	/**
	 * First we display the current User entries
	 */
	$index = 0;
	while(true)
		{
		if($index >= $MaxUser)break;//Just a security
		
		$User = $usersFile->user[$index];
		
		if(isset($User))
			{
			echo "
	 			<tr>
	 				<td><div class=\"forwarddate\">".$User->userid."</div></td>
					<td><div class=\"forwarddate\">".$User->lastname."</div></td>
					<td><div class=\"forwarddate\">".$User->firstname."</div></td>
					<td><div class=\"forwarddate\">".$User->filiale."</div></td>
					<td><div class=\"forwardaction\"><input type=\"button\" name=\"removeUser\" value=\"X\" title=\"Supprimer\" onclick=\"checkRemoveUser(".$index.")\"></div></td>
	 			</tr>
 				";
			}
		else
			{
			break;
			}
		$index++;
		}
	
	?>
	</table>
	</div>
<?php
if($index > $MaxUser)
	{
	echo "<h4>Désolé, il n'est pas possible d'afficher plus d'utilisateur</h4>";
	}
?>
<br><br><hr>
<?php
echo "
	<table><tr><td>Nombre total : ".$index."</td></tr></table>
	</form>
	<div class=\"addnewbutton\"><input type=\"button\" name=\"addNewUser\" value=\"Ajouter\" title=\"Ajouter\" onclick=\"checkNewUser(".$index.",".$MaxUser.")\"></div>
	";
?>




