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
	//recuperation et securisation du commentaire
	
	if (isset($_POST['commentaire'])){
		$commentaire = htmlspecialchars($_POST['commentaire']);
		$_SESSION['commentaire'] = $commentaire;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>37 - \$commentaire = ".$commentaire."</p>";}
	}
		
	//recuperation, securisation et cookie de l'auteur du commentaire
	if (isset($_POST['auteur_comm'])){
		$auteur_comm = htmlspecialchars($_POST['auteur_comm']);
		$_SESSION['auteur_comm'] = $auteur_comm;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>44 - \$auteur_comm = ".$auteur_comm."</p>";}
	}
	setcookie('auteur_comm', $_SESSION['auteur_comm'], time() + 3600, null, null, false, true); // On écrit un cookie pour 1 heure
	
	//recuperation et securisation de l'ID du billet original
	if (isset($_POST['id_ext_billet'])){
		$id_ext_billet = (int)htmlspecialchars($_POST['id_ext_billet']);
		$_SESSION['id_ext_billet'] = $id_ext_billet ;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>52 - \$id_ext_billet = ".$id_ext_billet."</p>";}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Blog - ajout de commentaire</title>
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

<h1>Blog - ajout de commentaire</h1>

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

$req = $bdd->prepare('INSERT INTO commentaires 
(id_ext_billet, auteur_comm, commentaire, date_comm ) 
VALUES (:id_ext_billet, :auteur_comm, :commentaire, NOW())');


$req->execute(array(
'id_ext_billet' => $id_ext_billet,
'auteur_comm' => $auteur_comm,
'commentaire' => $commentaire
));

	
if ($_SESSION['debug'] == 1) {echo "<p class='valide'>110 - La base a été mise à jour.</p>";}

$req->closeCursor(); // Termine le traitement de la requête
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>113 - Cursor closed</p>";}


// D'abord effectuer la requête qui insère le message
// Puis rediriger vers blog_commentaires.php comme ceci :
header('Location:/blog/blog_commentaires.php');
?>
</div>
</section>

</div><!-- fin du corps -->
</main>

</body>
</html>