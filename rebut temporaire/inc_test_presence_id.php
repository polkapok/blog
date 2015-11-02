<?php
/* Test s'il existe bien un enregistrement correspondant au $_GET[(id)] et donc à $_SESSION['id_demande']' */

if (isset($_SESSION['id_demande'])){
	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>132 - \isset(\$_SESSION['id_demande']) ?</p>";}
	// on teste s'il y a bien un enregistrement correspondant à $_SESSION['id_demande']'
	$sqlTest = 'SELECT *
	FROM billets 
	WHERE id =' . $_SESSION['id_demande'] . '';

	if ($_SESSION['debug'] == 1) {echo "<p class='debug'>137 - \$strTest = ".$sqlTest."</p>";}
	
	$reponse = $bdd -> query($sqlTest);
	while ($donnees = $reponse->fetch()){

		if (!empty($donnees['id'])){
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
}
?>