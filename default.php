<?phpsession_start();?>

<?php
	/////////////////////AFFICHAGE DEBUG/////////////////
	
	if (! isset($_SESSION['debug'])){
		$_SESSION['debug'] = -1;
	}
	if ($_SESSION['debug'] == 1) {echo"<p class='debug'>\$_SESSION['debug'] = " . $_SESSION['debug'] . "</p>";}
	
	
	
	if (isset($_GET['dbg'])){
		$_GET['dbg'] = htmlspecialchars($_GET['dbg']);
		//echo "<p class='debug'>htmlspecialchars \$_GET['dbg'] = ".$_GET['dbg']."</p>";
		//$_GET['dbg'] = str_replace("<","_",$_GET['dbg']);
		//$_GET['dbg'] = str_replace(">","_",$_GET['dbg']);
		
		
		if (!($_GET['dbg'] == "v") and !($_GET['dbg'] == "f")){
			$_GET['dbg'] = "f";
		}
		//echo "<p class='debug'>\$_GET['dbg'] vérifié = ".$_GET['dbg']."</p>";
		
		if (($_GET['dbg']) == "v") {$_SESSION['debug'] = 1;}	
		if (($_GET['dbg']) == "f") {$_SESSION['debug'] = -1;}
		//echo "<p class='debug'>\$_SESSION['debug'] = ".$_SESSION['debug']."</p>";
	}
	
	/////////////////////AFFICHAGE DEBUG/////////////////
	

	if (isset($_GET))
	{
		if ($_SESSION['debug'] == 1) {
			echo"<p ><pre class='debug'>";
			print_r($_GET);
			echo"</pre></p>";}
	}
	
?>


<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Modele</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>



<!-- L'en-tête -->
<header>
<?php include("inc/inc_tete.php"); ?>
</header>



<!-- Le corps -->
<main>
<div id="corps">
<p>
<a href="<?php echo $_SERVER['SCRIPT_NAME'].'?dbg=v'; ?>" class="XXL valide">debug</a>&nbsp;
<a href="<?php echo $_SERVER['SCRIPT_NAME'].'?dbg=f'; ?>" class="XXL erreur">no debug</a>
</p>
</div>
</main>


<?php
	/////////////////////AFFICHAGE DEBUG/////////////////
	echo"<p class='debug'>Réponse : \$_SESSION['debug'] = " . $_SESSION['debug'] . "</p>";
	/////////////////////AFFICHAGE DEBUG/////////////////
?>

<!-- Le pied de page -->
<footer>
<?php include("inc/inc_pied.php"); ?>
</footer>




</body>
</html>