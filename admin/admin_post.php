<?phpsession_start();?>

<?php
	/////////////////////AFFICHAGE DEBUG/////////////////
		if (! isset($_SESSION['debug'])){
		$_SESSION['debug'] = -1;
	}
	if ($_SESSION['debug'] == 1) {echo"<p class='debug'>8 - \$_SESSION['debug'] = " . $_SESSION['debug'] . "</p>";}
	
	
	
	// pour affichage des infos de GET ou POST
	if (isset($_GET))
	{
		if ($_SESSION['debug'] == 1) {
			echo"<pre class='debug'>";
			print_r($_GET);
			echo"</pre>";}
	}
	
	if (isset($_POST))
	{
		if ($_SESSION['debug'] == 1) {
			echo"<pre class='debug'>";
			print_r($_POST);
			echo"</pre>";}
	}
	
	
	
//base=vider
//base=reinit



	// EN CAS DE VIDAGE DE LA BASE ******************************************************************
	//recuperation et securisation de 'base'
	if (isset($_GET['base'])){
		$base = htmlspecialchars($_GET['base']);
		if ($base == 'vider'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>41 - \$base == 'vider' </p>";}
		}
	}
	//***********************************************************************************************
	
	
	// EN CAS DE REINIT DE LA BASE ******************************************************************
	//recuperation et securisation de 'base'
	if (isset($_GET['base'])){
		$base = htmlspecialchars($_GET['base']);
		if ($base == 'reinit'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>41 - \$base == 'reinit' </p>";}
		}
	}
	//***********************************************************************************************

?>


<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body style="background: #fdd;">

<!-- L'en-tête -->
<header>
<?php include("../inc/inc_tete.php"); ?>
</header>



<!-- Le corps -->
<main>
<div id="corps">

<h1>Blog - admin</h1>


<?php
try{
	// On se connecte à la base de données MySQL
	$bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage()). '<br />- Impossible de se connecter à la base de données.';
}




// ******************************             VIDER LA BASE          *******************************
if ($base == 'vider'){

	$sqlQuery = 'DELETE FROM commentaires';
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>104 - \$sqlQuery = ".$sqlQuery."</p>";}
		try{
		$reponse = $bdd -> query($sqlQuery);
		//$bdd->exec('DELETE FROM commentaires');
		}
		catch ( Exception $e )
		{
		echo 'Erreur de requête : ', $e->getMessage();
		}
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>114 - Tous les commentaires ont étés suppimés.</p>";}
	$reponse->closeCursor(); // Termine le traitement de la requête
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>116 - Cursor closed</p>";}	
		
	$sqlQuery = 'DELETE FROM billets';
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>121 - \$sqlQuery = ".$sqlQuery."</p>";}
		try{
		$reponse = $bdd -> query($sqlQuery);
		//$bdd->exec('DELETE FROM billets');
		}
		catch ( Exception $e )
		{
		echo 'Erreur de requête : ', $e->getMessage();
		}
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>131 - Tous les billets ont étés suppimés.</p>";}
	$reponse->closeCursor(); // Termine le traitement de la requête
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>133 - Cursor closed</p>";}		
		
}	//if ($base == 'vider')
// *************************************************************************************************




// ****************************             REINITIALISER LA BASE          *************************
if ($base == 'reinit'){


	$sqlQuery = 'TRUNCATE TABLE commentaires';
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>140 - \$sqlQuery = ".$sqlQuery."</p>";}
		try{
		$reponse = $bdd -> query($sqlQuery);
		}
		catch ( Exception $e )
		{
		echo 'Erreur de requête : ', $e->getMessage();
		}
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>148 - Table commentaires réinitialisée.</p>";}
	$reponse->closeCursor(); // Termine le traitement de la requête
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>150 - Cursor closed</p>";}
		
		
	$sqlQuery = 'TRUNCATE TABLE billets';
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>154 - \$sqlQuery = ".$sqlQuery."</p>";}
		try{
		$reponse = $bdd -> query($sqlQuery);
		}
		catch ( Exception $e )
		{
		echo 'Erreur de requête : ', $e->getMessage();
		}
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>162 - Table billets réinitialisée.</p>";}
	$reponse->closeCursor(); // Termine le traitement de la requête
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>164 - Cursor closed</p>";}		
		
		
}	//if ($base == 'vider')
// *************************************************************************************************

	
	
// D'abord effectuer la requête qui insère le message, l'édite ou le supprime.
// Puis rediriger vers admin.php comme ceci :
header('Location:/blog/admin/admin.php');

?>


</div>
</main>




<!-- Le pied de page -->
<footer>
<?php include("../inc/inc_pied.php"); ?>
</footer>

</body>
</html>