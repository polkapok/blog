<?phpsession_start();?>

<?php

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

	
	////////////////////////////////////////// ON RECUPERE LES DONNEES $_POST ///////////////////////
	//recuperation et securisation du texte du billet	
	if (isset($_POST['texte'])){
		$texte = htmlspecialchars($_POST['texte']);
		$_SESSION['texte'] = $texte;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>37 - \$texte = ".$texte."</p>";}
	}
		
	//recuperation, securisation et cookie de l'auteur du billet
	if (isset($_POST['auteur'])){
		$auteur = htmlspecialchars($_POST['auteur']);
		$_SESSION['auteur'] = $auteur;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>44 - \$auteur = ".$auteur."</p>";}
	}
	setcookie('auteur_comm', $_SESSION['auteur_comm'], time() + 3600, null, null, false, true); // On écrit un cookie pour 1 heure
	
	//recuperation et securisation du titre
	if (isset($_POST['titre'])){
		$titre = htmlspecialchars($_POST['titre']);
		$_SESSION['titre'] = $titre ;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>52 - \$titre = ".$titre."</p>";}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Blog - ajout de billet</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>


<!-- L'en-tête -->
<header>
<?php include("../inc/inc_tete.php"); ?>
</header>


<!-- Le corps -->
<main>
<div id="corps">

<h1>Blog - ajout de billet</h1>

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
?>


<section id="billet_original">
<div id="vers_billet_origine">
<?php

$req = $bdd->prepare('INSERT INTO billets 
(titre, auteur, texte, date_crea ) 
VALUES (:titre, :auteur, :texte, NOW())');

$req->execute(array(
'titre' => $titre,
'auteur' => $auteur,
'texte' => $texte
));

	
if ($_SESSION['debug'] == 1) {echo "<p class='valide'>110 - La base a été mise à jour.</p>";}

$req->closeCursor(); // Termine le traitement de la requête
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>113 - Cursor closed</p>";}


// D'abord effectuer la requête qui insère le message
// Puis rediriger vers blog_commentaires.php comme ceci :
header('Location:/blog/blog_billets.php');
?>
</div>
</section>

</div><!-- fin du corps -->
</main>

</body>
</html>