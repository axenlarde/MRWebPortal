<?php
/**
 * Page used to display the available branches and then allow the user to select which one he want to 
 * configure
 * 
 * Has to be improved
 */

include "checkForAdmin.php";
?>

<br>
<h4>Veuillez choisir la branche à administrer :</h4>
<br>
<br>
<div class="selectionFiliale">
<table class="mainmenu">
	<tr>
		<td><a href="setBranchName.php?branch=Vins">Vins</a></td>
		<td><a href="setBranchName.php?branch=Expresso">Expresso</a></td>
		<td><a href="setBranchName.php?branch=Cafes">Cafes</a></td>
		<td><a href="mainpage.php?page=adminUsers">Gestion des utilisateurs</a></td>
	</tr>
</table>
</div>