<?php
/******
 * Page used to display the main admin menu
 * 
 */
?>

<table class="mainmenu">
	<tr><td><a href="mainpage.php?page=adminGlobalParameters">Administration des paramètres globaux</a></td></tr>
	<tr><td><a href="mainpage.php?page=adminOpeningHours">Administration des heures d'ouvertures</a></td></tr>
	<tr><td><a href="mainpage.php?page=adminHolidays">Administration des jours de fermeture</a></td></tr>
	
	<?php
	if($branch != "Cafes") 
		{
		echo '<tr><td><a href="mainpage.php?page=adminVIPList">Administration de la liste des VIP</a></td></tr>';
		}
	?>
</table>


