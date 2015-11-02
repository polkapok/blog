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
	
	
	/////////////////////////RECUPERATION DES DONNEES $_GET ////////////////////////////////////////
	//recuperation du critere de selection, avec un CAST (int) pour être sûr de bien avoir un entier
	if (isset($_GET['id'])){
		$id_demande = (int)htmlspecialchars($_GET['id']);
		$_SESSION['id_demande'] = $id_demande;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>34 - \$id_billet_demande = ".$id_demande."</p>";}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	/////////////////////////////////////////////////////////////// NAVIGATION PAR PAGES ////////////
	// nombre de messages a afficher par page
	if (isset($_GET['nombreDeMessagesParPage'])){
		//$nombreDeMessagesParPage = htmlspecialchars($_GET['nombreDeMessagesParPage']);
		$nombreDeMessagesParPage = (int) ($_GET['nombreDeMessagesParPage']);
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>46 - \$nombreDeMessagesParPage = ".$nombreDeMessagesParPage."</p>";}
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
	
	

	
	
	
	//rafraîchir la page toutes les 30 sec
	//header("Refresh:30");
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin - gestion des commentaires</title>
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

<h1>Admin - commentaires</h1>



<section id="ajout_commentaire">
<form action="admin_commentaires_post.php" method="post">
<div id="titre_form">Nouveau commentaire</label></div>
<label for="auteur_comm">Auteur</label>
<input type="text" id="auteur_comm" 
		name="auteur_comm" 
		maxlength="255"
		size="44"
		value="<?php if(isset($_COOKIE['auteur_comm'])){echo $_COOKIE['auteur_comm'];} ?>" 
		placeholder="Le pseudo..." required >
		<br />
<label for="commentaire">Commentaire</label>
<textarea id="commentaire" name="commentaire" rows="4" cols="50" 
placeholder="Le commentaire..." required ></textarea>
<br />
<input type="submit" name="submit" id="ajouter" value="Ajouter" />
<input type="hidden" name="id_ext_billet"
		value="<?php if(isset($_SESSION['id_demande'])){echo $_SESSION['id_demande'];} ?>" />
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



	
if (isset($id_demande)){
	
	// on teste s'il y a bien un enregistrement a cet $id_billet_demande'
	$sqlQuery = 'SELECT COUNT(*) AS nb_commentaires
	FROM commentaires 
	WHERE id_ext_billet =' . $id_demande . '';
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>146 - \$strQuery = ".$sqlQuery."</p>";}
	
	$reponse = $bdd -> query($sqlQuery);
	while ($donnees = $reponse->fetch()){
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>155 - nombre d'enregistrements = ".$donnees['nb_commentaires']."</p>";}

		if (($donnees['nb_commentaires']) >= 1){		
				// si des ID à ce numéro existent bien, on créé une requête complète pour cet ID de billet :
				$sqlQuery = 'SELECT *, 
							DATE_FORMAT(date_comm, \'%d/%m/%Y à %Hh %imn %ss\') AS date_comm_fr 
							FROM commentaires 
							WHERE id_ext_billet =' . $id_demande . '';
				if ($_SESSION['debug'] == 1) {echo "<p class='debug'>163 - Des commentaires existent bels et biens !</p>";}	
		}
		else{
				if ($_SESSION['debug'] == 1) {echo "<p class='debug'>166 nombre d'enregistrements = ".$donnees['nb_commentaires']."</p>";}
				// si aucun ID ne porte ce numéro, on affiche ce message derreur':
				echo "<p class='erreur'> Il n'y a pas de commentaire pour ce numéro de billet précis.<br />";
		}
	}
}
else{
	// si on ne demande pas d'id de billet bien précis, on affiche tous les commentaires par date DESC
	$sqlQuery = 'SELECT *, 
	DATE_FORMAT(date_comm, \'%d/%m/%Y à %Hh %imn %ss\') AS date_comm_fr 
	FROM commentaires 
	ORDER BY date_comm DESC 
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

<!--  Affichage du billet par include -->
<?php include("../inc/inc_form_commentaire_admin.php"); ?>

</section>

<?php
	$reponse->closeCursor(); // Termine le traitement de la requête
//echo "Cursor closed<br />";
	}	// while ($donnees = $reponse->fetch()){
		
		

?>

		<section id="section_billet"><!-- section vide, juste pour le dernier filet supérieur -->
		</section>	
	

	<?php
/////////////////////////////////////////////// AFFICHAGE DE LA NAVIGATION PAR PAGE ////////////////

// adapter pour chaque page php le champ et la table pris comme references au COUNT

if (! isset($id_demande)){
	// on va compter les enregistrements dans le resultat de la requete
	$retour = $bdd->query('SELECT COUNT(*) AS nb_commentaires FROM commentaires');
	$donnees = $retour->fetch();
	$totalDesMessages = $donnees['nb_commentaires'];	// on a obtenu le nombre d'enregistrements
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>223 - \$totalDesMessages = ".$totalDesMessages."</p>";}
	$retour->closeCursor(); // Termine le traitement de la requête

	$nombreDePages  = ceil($totalDesMessages / $_SESSION['nombreDeMessagesParPage']); // ceil — Arrondit au nombre supérieur
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>227 - \$nombreDePages = ".$nombreDePages."</p>";}	
	
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
}	// if !(isset($id_demande)){
?>








</div>
</main>



<!-- Le pied de page -->
<footer>
<?php include("../inc/inc_pied.php"); ?>
</footer>




</body>
</html>