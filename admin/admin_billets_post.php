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
	//recuperation et securisation du texte du billet	
	if (isset($_POST['texte'])){
		$texte = htmlspecialchars($_POST['texte']);
		$_SESSION['texte'] = $texte;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>37 - \$texte = ".$texte."</p>";}
	}
		
	//recuperation et securisation de l'auteur du billet
	if (isset($_POST['auteur'])){
		$auteur = htmlspecialchars($_POST['auteur']);
		$_SESSION['auteur'] = $auteur;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>44 - \$auteur = ".$auteur."</p>";}
	}
	
	//recuperation et securisation du titre
	if (isset($_POST['titre'])){
		$titre = htmlspecialchars($_POST['titre']);
		$_SESSION['titre'] = $titre ;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>52 - \$titre = ".$titre."</p>";}
	}	
	
	//recuperation et securisation du submit
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Ajouter'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>58 - \$submit == 'Ajouter' </p>";}
		}
	}
	// --------------------------------------------------------------------------------------------
	
	
	
	// EN CAS D'UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	//recuperation et securisation de l'id
	if (isset($_POST['edit_id'])){
		$edit_id = htmlspecialchars($_POST['edit_id']);
		$_SESSION['edit_id'] = $edit_id;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>68 - \$edit_id = ".$edit_id."</p>";}
	}
	
	//recuperation et securisation du texte du billet	
	if (isset($_POST['edit_texte'])){
		$edit_texte = htmlspecialchars($_POST['edit_texte']);
		$_SESSION['edit_texte'] = $edit_texte;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>75 - \$edit_texte = ".$edit_texte."</p>";}
	}
		
	//recuperation et securisation de l'auteur du billet
	if (isset($_POST['edit_auteur'])){
		$edit_auteur = htmlspecialchars($_POST['edit_auteur']);
		$_SESSION['edit_auteur'] = $edit_auteur;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>82 - \$edit_auteur = ".$edit_auteur."</p>";}
	}
	
	//recuperation et securisation de la date_crea
	if (isset($_POST['edit_date_crea'])){
		$edit_date_crea = htmlspecialchars($_POST['edit_date_crea']);
		$_SESSION['edit_date_crea'] = $edit_date_crea ;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>89 - \$edit_date_crea = ".$edit_date_crea."</p>";}
	}
	
	//recuperation et securisation du titre
	if (isset($_POST['edit_titre'])){
		$edit_titre = htmlspecialchars($_POST['edit_titre']);
		$_SESSION['edit_titre'] = $edit_titre ;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>96 - \$edit_titre = ".$edit_titre."</p>";}
	}
	
	//recuperation et securisation du submit
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Editer'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>103 - \$submit == 'Editer' </p>";}
		}
	}
	// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	
	
	
	// EN CAS DE SUPPRESSION ************************************************************************
	//recuperation et securisation de 'supprimer'
	if (isset($_POST['submit'])){
		$submit = htmlspecialchars($_POST['submit']);
		if ($submit == 'Supprimer le billet'){
			if ($_SESSION['debug'] == 1) {echo"<p class='debug'>13 - \$submit == 'Supprimer le billet' </p>";}
		}
	}
	//***********************************************************************************************
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin - billets</title>
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

<h1>Admin - billets</h1>

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
	
$req = $bdd->prepare('INSERT INTO billets 
(auteur, titre, texte, date_crea ) 
VALUES (:auteur, :titre, :texte, now())');

$req->execute(array(
'auteur' => $auteur,
'titre' => $titre,
'texte' => $texte
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


// ******************************************************************************************
if ($submit == 'Supprimer le billet'){
	// s'il faut supprimer un billet, assurons-nous d'abord qu'il existe
	$sqlQuery = 'SELECT COUNT(*) AS nb_billets
				FROM billets 
				WHERE id =' . $edit_id . '';
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>212 - \$strQuery = ".$sqlQuery."</p>";}
	$reponse = $bdd -> query($sqlQuery);
	while ($donnees = $reponse->fetch()){
		if (($donnees['nb_billets']) >= 1){
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>216 - Le billet a bien été trouve.</p>";}
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
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>230 - Les commentaires de ce  billet ont étés suppimés.</p>";}
		// dans un second temps, suppression du billet lui-même
		$req = $bdd->prepare('
		DELETE FROM billets
		WHERE id = :edit_id
		');
		try{
		$req->bindParam(':edit_id', $_POST['edit_id'], PDO:: PARAM_INT); // integer
		$req->execute();
		}
		catch ( Exception $e )
		{
		echo 'Erreur de requête : ', $e->getMessage();
		}
	
		}	// fi (($donnees['presence_billet']) >= 1)

	}
		if ($_SESSION['debug'] == 1) {echo "<p class='valide'>248 - Le billet a bien été suppimé.</p>";}
}	//fi ($submit == 'Editer')
// ******************************************************************************************

$req->closeCursor(); // Termine le traitement de la requête
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>253 - Cursor closed</p>";}


// D'abord effectuer la requête qui insère le message, l'édite ou le supprime.
// Puis rediriger vers admin_billets.php comme ceci :
header('Location:/blog/admin/admin_billets.php');
?>
</div>
</section>

</div><!-- fin du corps -->
</main>

</body>
</html>