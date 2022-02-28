<?php
session_start(); // Création de la session

if (!is_writable(session_save_path())) {
	echo 'Session path "'.session_save_path().'" is not writable for PHP!';
}
?>
<script src="include/jquery-3.2.1.js"></script>
<script src="include/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript">

function changeStyle(cssname)
	{
	document.getElementById('pagestyle').setAttribute('href', cssname);
	}

function getParameterByName(name)
	{
	url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	    results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

function displayMessage()
	{
	var message = getParameterByName("message");
	if(message != null)
		{
		if(message == "duplicateid")alert("L'utilisateur existe déja, il ne peut pas être ajouté à nouveau");
		else if(message == "unknownid")alert("L'utilisateur n'existe pas, il ne peut pas être modifié");
		else if(message == "duplicateforward")alert("L'entrée existe déja. L'action a donc été annulée");
		else if(message == "generalerror")alert("Quelque chose s'est mal passé, contactez l'administrateur !");
		}
	}

</script>

<!DOCTYPE html>

<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accueil</title>
<LINK id="pagestyle" REL="stylesheet" TYPE="text/css" HREF="mainstyle.css">

<?php
if(isset($_SESSION['login']))
	{
	if($_SESSION['login'][3] == "Vins")
		{
		echo '<LINK id="pagestyle" REL="stylesheet" TYPE="text/css" HREF="vinsstyle.css">';
		}
	else if($_SESSION['login'][3] == "Expresso")
		{
		echo '<LINK id="pagestyle" REL="stylesheet" TYPE="text/css" HREF="expressostyle.css">';
		}
	else if($_SESSION['login'][3] == "Cafes")
		{
		echo '<LINK id="pagestyle" REL="stylesheet" TYPE="text/css" HREF="cafesstyle.css">';
		}
	}

?>
<link rel="stylesheet" href="include/jquery-ui/jquery-ui.css">
</HEAD>
<body>
<div class="contenu">
	<div class="entete">
		<table style="width: 100%">
			<tr>
				<td><h1>Portail de gestion du centre d'appel</h1></td>
			</tr>
<?php
$connected = false;
$branchFound = false;
$branch;
    
if(isset($_SESSION['login']))
	{
	if(!empty($_SESSION['login']))
		{
		$connected = true;
		
		$branch = $_SESSION['login'][3];
		if(($branch == "Vins") || ($branch == "Expresso") || ($branch == "Cafes"))
			{
			$branchFound = true;
			}
		
		echo"
			<tr>
				<td><div style=\"color: white; font-weight: bold; text-align: right;\">Connecté avec le compte : ".$_SESSION['login'][0]."</div></td>
			</tr>
			<tr>
				<td><div style=\"text-align: right;\"><a href=\"mainpage.php\" style=\"color: white;\">Se déconnecter</a></div></td>
			</tr>
		";
		}
	}

echo '</table></div>';

if($branchFound) echo '<br><div class="branchname">Administration de la branche : '.$branch.'</div>';
	
echo '<div class="centre">';

$page = "connexion";
		
if($connected)
	{
	if(!empty($_GET))
		{
		$page = $_GET['page'];
		}
	else
		{
		$_SESSION = array();
		header("Location: mainpage.php");
		exit;
		}
	}
	
include $page.".php";
?>
	</div>
	
	<div class="pied">
		Maison Richard : 2.0 : 2018
	</div>
	
</div>
</body>
</html>





