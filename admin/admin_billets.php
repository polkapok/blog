<?phpsession_start();?>

<?php
	// pour affichage des commentaires de debogage
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
	
	
	
	
	/////////////////////////////////////////////////////////////// NAVIGATION PAR PAGES ////////////
	// nombre de messages a afficher par page
	if (isset($_GET['nombreDeMessagesParPage'])){
		//$nombreDeMessagesParPage = htmlspecialchars($_GET['nombreDeMessagesParPage']);
		$nombreDeMessagesParPage = (int) ($_GET['nombreDeMessagesParPage']);
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>36 - \$nombreDeMessagesParPage = ".$nombreDeMessagesParPage."</p>";}
		if ($nombreDeMessagesParPage == 0) {
			$nombreDeMessagesParPage = 5;
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>39 - \$nombreDeMessagesParPage = ".$nombreDeMessagesParPage."</p>";}
			}
	}
	elseif (! isset($_GET['nombreDeMessagesParPage']) AND isset($_SESSION['nombreDeMessagesParPage'])){
		$nombreDeMessagesParPage = $_SESSION['nombreDeMessagesParPage'];
	}
	else{
		$nombreDeMessagesParPage = 5;	
	}
	$_SESSION['nombreDeMessagesParPage'] = $nombreDeMessagesParPage;
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>49 - \$_SESSION['nombreDeMessagesParPage'] = ".$_SESSION['nombreDeMessagesParPage']."</p>";}
	
	
	// preparation pour la navigation dans le recordset
	if (isset($_GET['page'])){
		$page = (int) htmlspecialchars($_GET['page']);
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>55 - \$page = ".$page."</p>";}
	}
	else{
		$page = 1;
	}
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>60 - \$page = ".$page."</p>";}
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//recuperation du critere de selection, avec un CAST (int) pour être sûr de bien avoir un entier
	if (isset($_GET['id'])){
		$id_billet_demande = (int)htmlspecialchars($_GET['id']);
		//$_SESSION['id_demande'] = $id_demande;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>\$id_billet_demande = ".$id_billet_demande."</p>";}
	}
	
	
	
	//rafraîchir la page toutes les 30 sec
	//header("Refresh:30");
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin - gestion des billets</title>
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

<h1>Admin - billets</h1>


<section id="ajout_billet">
<form action="admin_billets_post.php" method="post">
<div id="titre_form">Nouveau billet</label></div>
<label for="auteur">Auteur</label>
<input type="text" id="auteur" 
		name="auteur"
		maxlength="255"
		size="44"
		value="<?php if(isset($_COOKIE['auteur'])){echo $_COOKIE['auteur'];} ?>" 
		placeholder="Votre pseudo..." required >
		<br />
		<br />
<label for="titre">Titre du billet</label>
<input type="text" id="titre" 
		name="titre"
		maxlength="255"
		size="44"
		value="" 
		placeholder="Titre..." required >
		<br />
<label for="texte">Billet</label>
<textarea id="texte" name="texte" rows="4" cols="50" 
placeholder="Votre billet..." required ></textarea>
<br />
<input type="submit" name="submit" id="ajouter" value="Ajouter" />
</form>
</section>

<br />


<?php
try{
	// On se connecte à la base de données MySQL
	$bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage()). '<br />- Impossible de se connecter à la base de données.';
}?>

<?php
$reponsetest = $bdd->query('SELECT COUNT(*) AS nombre_total FROM billets');
$donneestest = $reponsetest->fetch();
if (!($donneestest['nombre_total'] == 0)){
	
	
	
	if (isset($id_billet_demande)){
	
		// on teste s'il y a bien un enregistrement a cet $id_billet_demande'
		$sqlQuery = 'SELECT COUNT(*) AS nb_billets
					FROM billets 
					WHERE id =' . $id_billet_demande . '';
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>152 - \$strQuery = ".$sqlQuery."</p>";}
	
		$reponse = $bdd -> query($sqlQuery);
		while ($donnees = $reponse->fetch()){
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>156 present_id = ".$donnees['nb_billets']."</p>";}

			if (($donnees['nb_billets']) == 1){		
				// si un ID à ce numéro existe bien, on créé une requête complète pour cet ID :
				$sqlQuery = 'SELECT *, DATE_FORMAT(date_crea, \'%d/%m/%Y à %Hh %imn %ss\') AS date_creation_fr 
							FROM billets 
							WHERE id =' . $id_billet_demande . '';
				if ($_SESSION['debug'] == 1) {echo "<p class='debug'>163 - Le \$id_billet_demande existe bien !</p>";}	
			}
			else{
				if ($_SESSION['debug'] == 1) {echo "<p class='debug'>present_id = ".$donnees['nb_billets']."</p>";}
				// si aucun ID ne porte ce numéro, on affiche ce message derreur':
				echo "<p class='erreur'> Il n'y a pas de billet portant ce numéro précis.<br />
				 Voici donc tous les billets : </p>";
				// et on génère le même SQL que s'il n'y avait pas eu de numéro demandé.
				$sqlQuery = 'SELECT id, titre, auteur, texte, DATE_FORMAT(date_crea, \'%d/%m/%Y à %Hh %imn %ss\') AS date_creation_fr 
							FROM billets 
							ORDER BY date_crea DESC 
							LIMIT '.(($page*$_SESSION['nombreDeMessagesParPage'])-$_SESSION['nombreDeMessagesParPage']).', '.$_SESSION['nombreDeMessagesParPage'].'';
			}
		}
	}
	else{
	$sqlQuery = 'SELECT id, titre, auteur, texte, DATE_FORMAT(date_crea, \'%d/%m/%Y à %Hh %imn %ss\') AS date_creation_fr 
	FROM billets 
	ORDER BY date_crea DESC 
	LIMIT '.(($page*$_SESSION['nombreDeMessagesParPage'])-$_SESSION['nombreDeMessagesParPage']).', '.$_SESSION['nombreDeMessagesParPage'].'';
	}
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>154 - \$strQuery = ".$sqlQuery."</p>";}
	$reponse = $bdd -> query($sqlQuery);
?>


<?php
// On affiche chaque entrée une à une
	while ($donnees = $reponse->fetch()){
?>

<section id="section_billet">

<!--  Affichage du formulaire billet par include -->
<?php include("../inc/inc_form_billet_admin.php"); ?>

<div id="lire_commentaires">
<a href="admin_commentaires.php?id=<?php echo $donnees['id'];?>"> Editer les commentaires... </a>
</div>

</section>

<?php
	}
	$reponse->closeCursor(); // Termine le traitement de la requête
	//echo "Cursor closed<br />";
}	// if (!($donneestest['nombre_total'] == 0)){
	
	
else{
	echo "Il n'y a aucun billet dans la table !";
}	// if (!($donneestest['nombre_total'] == 0)){

?>


		<section id="section_billet"><!-- section vide, juste pour le dernier filet supérieur -->
		</section>	
	

	<?php
/////////////////////////////////////////////// AFFICHAGE DE LA NAVIGATION PAR PAGE ////////////////

// adapter pour chaque page php le champ et la table pris comme references au COUNT

if (! isset($id_billet_demande)){
	// on va compter les enregistrements dans le resultat de la requete
	$retour = $bdd->query('SELECT COUNT(*) AS nb_billets FROM billets');
	$donnees = $retour->fetch();
	$totalDesMessages = $donnees['nb_billets'];	// on a obtenu le nombre d'enregistrements
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>196 - \$totalDesMessages = ".$totalDesMessages."</p>";}
	$retour->closeCursor(); // Termine le traitement de la requête

	$nombreDePages  = ceil($totalDesMessages / $_SESSION['nombreDeMessagesParPage']); // ceil — Arrondit au nombre supérieur
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>200 - \$nombreDePages = ".$nombreDePages."</p>";}	
	
?>	
<section id="pagination">
<div id="pagination">
<?php
// affichage des liens pour la page à afficher
echo 'Page : ';
for ($page_actuelle = 1 ; $page_actuelle <= $nombreDePages ; $page_actuelle++)
{
    echo '<a id="lien_page" href="'.$_SERVER['SCRIPT_NAME'].'?page=' . $page_actuelle . '"><div id="lien_page">' . $page_actuelle . '</div></a> ';
}
?>

<div id="NombresDeMessagesParPage">
Messages par page : 
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=1'?>"><div id="nombreDeMessagesParPage">1</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=2'?>"><div id="nombreDeMessagesParPage">2</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=5'?>"><div id="nombreDeMessagesParPage">5</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=10'?>"><div id="nombreDeMessagesParPage">10</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=20'?>"><div id="nombreDeMessagesParPage">20</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=50'?>"><div id="nombreDeMessagesParPage">50</div></a>
</div>

<div id="finpagination"></div>

</div>
</section> 
<?php	
}	// if !(isset($id_billet_demande)){
?>








</div>
</main>



<!-- Le pied de page -->
<footer>
<?php include("../inc/inc_pied.php"); ?>
</footer>




</body>
</html>