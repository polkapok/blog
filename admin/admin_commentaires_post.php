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

	
	///////////////////////////// ON RECUPERE LES DONNEES $_POST ///////////////////////////////////
	
	// EN CAS D'INSERT -----------------------------------------------------------------------------
	//recuperation et securisation du texte du commentaire	
	if (isset($_POST['commentaire'])){
		$commentaire = htmlspecialchars($_POST['commentaire']);
		$_SESSION['commentaire'] = $commentaire;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>37 - \$commentaire = ".$commentaire."</p>";}
	}
		
	//recuperation et securisation de l'auteur du commentaire
	if (isset($_POST['auteur_comm'])){
		$auteur_comm = htmlspecialchars($_POST['auteur_comm']);
		$_SESSION['auteur_comm'] = $auteur_comm;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>44 - \$auteur_comm = ".$auteur_comm."</p>";}
	}
		
	//recuperation et securisation du submit
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Ajouter'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>51 - \$submit == 'Ajouter' </p>";}
		}
	}
	// --------------------------------------------------------------------------------------------
	
	
	
	// EN CAS D'UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	//recuperation et securisation de l'id
	if (isset($_POST['edit_id'])){
		$edit_id = htmlspecialchars($_POST['edit_id']);
		$_SESSION['edit_id'] = $edit_id;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>63 - \$edit_id = ".$edit_id."</p>";}
	}
	
	//recuperation et securisation du texte du commentaire	
	if (isset($_POST['edit_commentaire'])){
		$edit_commentaire = htmlspecialchars($_POST['edit_commentaire']);
		$_SESSION['edit_commentaire'] = $edit_commentaire;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>70 - \$edit_commentaire = ".$edit_commentaire."</p>";}
	}
		
	//recuperation et securisation de l'auteur du billet
	if (isset($_POST['edit_auteur_comm'])){
		$edit_auteur_comm = htmlspecialchars($_POST['edit_auteur_comm']);
		$_SESSION['edit_auteur_comm'] = $edit_auteur_comm;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>77 - \$edit_auteur_comm = ".$edit_auteur_comm."</p>";}
	}
	
	//recuperation et securisation du submit
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Editer'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>84 - \$submit == 'Editer' </p>";}
		}
	}
	// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	
	
	
	// EN CAS DE SUPPRESSION ************************************************************************
	//recuperation et securisation de 'supprimer'
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Supprimer le commentaire'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>96 - \$submit == 'Supprimer le commentaire' </p>";}
		}
	}
	//***********************************************************************************************
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin - commentaires</title>
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

<h1>Admin - commentaires</h1>

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
//----------------------------------------------------------------------
if ($submit == 'Ajouter'){
	
$req = $bdd->prepare('INSERT INTO commentaires 
(auteur_comm, commentaire, date_comm ) 
VALUES (:auteur_comm, :commentaire, now())');

$req->execute(array(
'auteur_comm' => $auteur_comm,
'commentaire' => $commentaire,
));
}
//---------------------------------------------------------------------


// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($submit == 'Editer'){

$req = $bdd->prepare('
UPDATE billets SET
	auteur = :edit_auteur,
	titre = :edit_titre,
	texte = :edit_texte
	WHERE id = :edit_id
	'); //	date_crea = :date_crea,
	try{
	//$req->bindParam(':date_crea', $_POST['date_crea'], PDO:: PARAM_STR); // date
	$req->bindParam(':edit_auteur', $_POST['edit_auteur'], PDO:: PARAM_STR); // string
	$req->bindParam(':edit_titre', $_POST['edit_titre'], PDO:: PARAM_STR); // string
	$req->bindParam(':edit_texte', $_POST['edit_texte'], PDO:: PARAM_STR); // string
	$req->bindParam(':edit_id', $_POST['edit_id'], PDO:: PARAM_INT); // integer
	$req->execute();
	}
	catch ( Exception $e )
	{
		echo 'Erreur de requête : ', $e->getMessage();
	}
if ($_SESSION['debug'] == 1) {echo "<p class='valide'>201 - La base a été mise à jour.</p>";}
}	//fi ($submit == 'Editer')
// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


// *************************************************************************************************
if ($submit == 'Supprimer le commentaire'){

// suppression, en premier lieu, des commentaires attachés au billet
$req = $bdd->prepare('
	DELETE FROM commentaires
	WHERE id_ext_billet = :edit_id
	');
	try{
	$req->bindParam(':edit_id', $_POST['edit_id'], PDO:: PARAM_INT); // integer
	$req->execute();
	}
	catch ( Exception $e )
	{
		echo 'Erreur de requête : ', $e->getMessage();
	}
if ($_SESSION['debug'] == 1) {echo "<p class='valide'>222 - Le commentaire précédent a été suppimé.</p>";}

$req->closeCursor(); // Termine le traitement de la requête
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>194 - Cursor closed</p>";}
}
// *************************************************************************************************





// D'abord effectuer la requête qui insère le message
// Puis rediriger vers admin_billets.php comme ceci :
//header('Location:/blog/admin/admin_billets.php');
?>
</div>
</section>

</div><!-- fin du corps -->
</main>

</body>
</html>