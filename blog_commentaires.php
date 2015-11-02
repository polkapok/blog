<?phpsession_start();?>

<?php

	if (! isset($_SESSION['debug'])){
		$_SESSION['debug'] = -1;
	}
	if ($_SESSION['debug'] == 1) {echo"<p class='debug'>8 - \$_SESSION['debug'] = " . $_SESSION['debug'] . "</p>";}
	
	
	
	// pour affichage des infos de GET ou POST
	if (isset($_GET)){
		if ($_SESSION['debug'] == 1) {
			echo"<pre class='debug'>";
			print_r($_GET);
			echo"</pre>";}
	}
	
	if (isset($_POST)){
		if ($_SESSION['debug'] == 1) {
			echo"<pre class='debug'>";
			print_r($_POST);
			echo"</pre>";}
	}

	
	
	/////////////////////////////////////////////////////////////// NAVIGATION PAR PAGES ////////////
	// nombre de messages a afficher par page
	if (isset($_GET['nombreDeMessagesParPage'])){
		// sécurisation du $_GET par le cast (int) et htmlspecialchars()
		$nombreDeMessagesParPage = (int) (htmlspecialchars($_GET['nombreDeMessagesParPage']));
		if ($_SESSION['debug'] == 1) {echo "<p class='debug'>34 - \$nombreDeMessagesParPage = ".$nombreDeMessagesParPage."</p>";}
		// si le cast (int) donne un zero, on donne la valeur 5 à $nombreDeMessagesParPage
		if ($nombreDeMessagesParPage == 0) {
			$nombreDeMessagesParPage = 5;
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>38 - \$nombreDeMessagesParPage = ".$nombreDeMessagesParPage."</p>";}
			}
	}
	// s'il n'y a pas de $_GET, $nombreDeMessagesParPage prend la valeur de la session $_SESSION['nombreDeMessagesParPage']
	elseif (! isset($_GET['nombreDeMessagesParPage']) AND isset($_SESSION['nombreDeMessagesParPage'])){
		$nombreDeMessagesParPage = $_SESSION['nombreDeMessagesParPage'];
	}
	// si ni $_GET ni session, c'est la valeur 5
	else{
		$nombreDeMessagesParPage = 5;	
	}
	// on donne à la session la valeur finale de la variable
	$_SESSION['nombreDeMessagesParPage'] = $nombreDeMessagesParPage;
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>51 - \$_SESSION['nombreDeMessagesParPage'] = ".$_SESSION['nombreDeMessagesParPage']."</p>";}
	
	
	// preparation pour la navigation dans le recordset
	if (isset($_GET['page'])){
		$page = (int)htmlspecialchars($_GET['page']);
		// si la securisation précédente donne un zero, on le transforme en UN.
		if ($page == 0){$page=1;}
	}
	else{
		$page = 1;
	}
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>63 - \$page = ".$page."</p>";}
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	//recuperation et securisation du critere de selection du BILLET ORIGINAL et mise en session
	if (isset($_GET['id'])){
		$id_demande = (int)htmlspecialchars($_GET['id']);
		$_SESSION['id_demande'] = $id_demande;
		if ($_SESSION['debug'] == 1) {echo"<p class='debug'>72 - \$id_demande = ".$id_demande."</p>";}
	}
	
?>

<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Blog - affichage des commentaires</title>
        <!-- Load css styles -->
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



<h1>Blog - commentaires</h1>




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

/* Test s'il existe bien un enregistrement correspondant au $_GET[(id)] et donc à $_SESSION['id_demande']' */

if (isset($_SESSION['id_demande'])){
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>132 - \isset(\$_SESSION['id_demande']) ?</p>";}
	// on teste s'il y a bien un enregistrement correspondant à $_SESSION['id_demande']'
	$sqlTest = 'SELECT COUNT(*) AS nb_billets
	FROM billets 
	WHERE id =' . $_SESSION['id_demande'] . '';

	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>137 - \$strTest = ".$sqlTest."</p>";}
	
	$reponse = $bdd -> query($sqlTest);
	while ($donnees = $reponse->fetch()){

		if (($donnees['nb_billets']) == 1){	
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>143 - Le \$id_billet_demande existe bien.</p>";}
			$blnBonneSessionDemandee = 1;	
		}
		else{
			if ($_SESSION['debug'] == 1) {echo "<p class='debug'>147 - Le \$id_billet_demande n'existe pas !</p>";}
			// si aucun ID ne porte ce numéro, on affiche ce message :
			echo "<p class='erreur'> Il n'y a pas de billet portant ce numéro précis !<br />
			Voici donc les commentaires du dernier billet en date...</p>";
		}
	} 	// fin de while ($donnees = $reponse->fetch())
	if ($_SESSION['debug'] == 1) {
		if (isset($blnBonneSessionDemandee)){echo "<p class='debug'>154 - \$blnBonneSessionDemandee = ".$blnBonneSessionDemandee."</p>";}
	}
}?>





<section id="billet_original">
<div id="vers_billet_origine">
<?php
/***************************************************************************************************
 S'il y a bien une session demandée cohérente, on affiche un lien vers le billet d'origine  */
 
if (isset($blnBonneSessionDemandee) AND ($blnBonneSessionDemandee == 1)){
	$sql = 'SELECT id, titre, auteur, texte, DATE_FORMAT(date_crea, \'%d/%m/%Y à %Hh %imn %ss\') AS date_creation_fr 
				FROM billets 
				WHERE id = '.$_SESSION['id_demande'];
	}
	else {
		$sql = 'SELECT id, titre, auteur, texte, DATE_FORMAT(date_crea, \'%d/%m/%Y à %Hh %imn %ss\') AS date_creation_fr 
				FROM billets 
				ORDER BY id DESC
				LIMIT 0,1';	
}	//fi ($blnBonneSessionDemandee == 1)
/***************************************************************************************************
 Fin de S'il y a bien une session demandée cohérente, on affiche un lien vers le billet d'origine */
 
	
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>183 - \$sql = ".$sql."</p>";}

$reponse = $bdd -> query($sql);

$donnees = $reponse->fetch();
// On affiche l'unique entrée demandée.

echo '<a href="blog_billets.php?id=' . $donnees['id'] . '">Relire le billet original</a><br />';

?>
</div>



<div id="billet_original">
<!--  Affichage du billet original par include -->
<?php include("inc/inc_affichage_billet.php"); ?>

<?php
// on passe l'ID du dernier billet affiché en session :
$_SESSION['id_demande'] = $donnees['id'];
$reponse->closeCursor(); // Termine le traitement de la requête
//echo "Cursor closed<br />";

?>



</div>
</section>


<section id="ajout_commentaire">
<form action="blog_commentaires_post.php" method="post">
<label for="auteur_comm">Auteur</label>
<input type="text" id="auteur_comm" 
		name="auteur_comm" 
		maxlength="255"
		size="44"
		value="<?php if(isset($_COOKIE['auteur_comm'])){echo $_COOKIE['auteur_comm'];} ?>" 
		placeholder="Votre pseudo..." required >
		<br />
<label for="commentaire">Commentaire</label>
<textarea id="commentaire" name="commentaire" rows="4" cols="50" 
placeholder="Votre commentaire..." required ></textarea>
<br />
<input type="submit" value="Valider" />
<input type="hidden" name="id_ext_billet"
		value="<?php if(isset($_SESSION['id_demande'])){echo $_SESSION['id_demande'];} ?>" />
</form>
</section>



<?php
/////////////////////////////////////////////// AFFICHAGE DE LA NAVIGATION PAR PAGE ////////////////

//////////////////  adapter pour chaque page php le champ et la table pris comme references au COUNT

// on va compter les enregistrements dans le resultat de la requete
if (isset($_SESSION['id_demande'])){
	$retour = $bdd->query('SELECT COUNT(*) AS nb_commentaires FROM commentaires WHERE id_ext_billet = '.$_SESSION['id_demande'].'');
}
else{
	$retour = $bdd->query('SELECT COUNT(*) AS nb_commentaires FROM commentaires');
}
$donnees = $retour->fetch();
$totalDesMessages = $donnees['nb_commentaires'];	// on a obtenu le nombre d'enregistrements
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>238 - \$totalDesMessages = ".$totalDesMessages."</p>";}
$retour->closeCursor(); // Termine le traitement de la requête

// on calcule le nombre de pages nécessaires pour afficher tous les enregistrements demandés :
$nombreDePages  = ceil($totalDesMessages / $_SESSION['nombreDeMessagesParPage']); // ceil — Arrondit au nombre supérieur
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>243 - \$nombreDePages = ".$nombreDePages."</p>";}

?>

<section id="pagination">
<div id="pagination">

<div id="liens_pages">
<?php
// affichage des liens pour la page à afficher
echo 'Page : ';
for ($page_actuelle = 1 ; $page_actuelle <= $nombreDePages ; $page_actuelle++)
{
    echo '<a id="lien_page" href="'.$_SERVER['SCRIPT_NAME'].'?page=' . $page_actuelle . '"><div id="lien_page">' . $page_actuelle . '</div></a> ';
}
?>
</div>

<div id="NombresDeMessagesParPage">
Messages par page : 
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=1'?>"><div id="nombreDeMessagesParPage">1</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=2'?>"><div id="nombreDeMessagesParPage">2</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=5'?>"><div id="nombreDeMessagesParPage">5</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=10'?>"><div id="nombreDeMessagesParPage">10</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=20'?>"><div id="nombreDeMessagesParPage">20</div></a>
<a id="nombreDeMessagesParPage" href="<?php echo $_SERVER['SCRIPT_NAME'].'?nombreDeMessagesParPage=50'?>"><div id="nombreDeMessagesParPage">50</div></a>
</div>

<div id="finpagination"><!-- juste pour fermer les CSS-FLOAT --></div>

</div>
</section>
<!-- //////////////////////////////////////FIN DE L'AFFICHAGE DE LA NAVIGATION PAR PAGE //////////-->






<?php
if (isset($_SESSION['id_demande'])){

	if (isset($_SESSION['nombreDeMessagesParPage'])){
		$sqlCommentaire = 'SELECT *, 
		DATE_FORMAT(date_comm, \'%d/%m/%Y à %Hh %imn %ss\') AS date_comm_fr 
		FROM commentaires 
		WHERE id_ext_billet = '.$_SESSION['id_demande'].' 
		ORDER BY id_comm DESC 
		LIMIT '.(($page*$_SESSION['nombreDeMessagesParPage'])-$_SESSION['nombreDeMessagesParPage']).', '.$_SESSION['nombreDeMessagesParPage'].'';
	}
	else{
		$sqlCommentaire = 'SELECT *, 
		DATE_FORMAT(date_comm, \'%d/%m/%Y à %Hh %imn %ss\') AS date_comm_fr 
		FROM commentaires 
		WHERE id_ext_billet = '.$_SESSION['id_demande'].' 
		ORDER BY id_comm DESC';	
	}

	
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>302 - \$sqlCommentaire = ".$sqlCommentaire."</p>";}

	$reponseCommentaire = $bdd -> query($sqlCommentaire);

	// On affiche chaque entrée une à une
	while ($donneesCommentaire = $reponseCommentaire->fetch())
	{
?>
		<section id="section_commentaire">

			<div id="infos_commentaire">
			<em>Commentaire publié par <strong><?php echo htmlspecialchars($donneesCommentaire['auteur_comm']); ?></strong>,
			le <?php echo $donneesCommentaire['date_comm_fr']; ?></em>.
			</div>

			<div id="commentaire">
			<?php echo nl2br(htmlspecialchars($donneesCommentaire['commentaire'])); ?>
			</div>

		</section>
<?php
	}
	?>
		<section id="section_commentaire"><!-- section vide, juste pour le dernier filet supérieur -->
		</section>	
<?php
$reponseCommentaire->closeCursor(); // Termine le traitement de la requête
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>329 - \$reponseCommentaire->closeCursor</p>";}

}
else	// fi (isset($_SESSION['id_demande'])) // pas de session demandée
{
if ($_SESSION['debug'] == 1) {echo "<p class='debug'>334 - pas de session ID billet demandée</p>";}	
}

?>


</div><!-- fin du corps -->
</main>


<!-- Le pied de page -->
<footer>
<?php include("inc/inc_pied.php"); ?>
</footer>


</body>
</html>