<div id="billet">

<div id="titre_form">Editer commentaire</label></div>

<?php
if (isset($donnees['id_comm'])){
	
?>

Le <?php echo $donnees['date_comm_fr']; ?>, le commentaire n&deg; <strong><?php echo $donnees['id_comm']; ?></strong> 
a été écrit  par <strong><?php echo htmlspecialchars($donnees['auteur_comm']); ?></strong> :<br />

<form action="admin_commentaires_post.php" method="post" name="admin_edit_commentaire" id="admin_edit_commentaire" >
<label for="auteur_comm">Auteur</label>
<input type="text" id="edit_auteur_comm" 
		name="edit_auteur_comm"
		maxlength="255"
		size="44"
		value="<?php echo htmlspecialchars($donnees['auteur_comm']); ?>" 
		placeholder="Le pseudo..." required >
		<br />
		<br />	
<label for="edit_commentaire">Commentaire</label>
<textarea id="edit_commentaire" name="edit_commentaire" rows="4" cols="50" 
placeholder="Le commentaire..." required ><?php echo nl2br($donnees['commentaire']); ?></textarea>
<br />
<input type="hidden" name="edit_id" id="edit_id" value="<?php echo ($donnees['id_comm']); ?>">
<input type="hidden" name="edit_date_comm" id="edit_date_comm" value="<?php echo ($donnees['date_comm_fr']); ?>">
<input type="submit" name="submit" id="editer" value="Editer" />	
<input type="submit" name="submit" id="supprimer" value="Supprimer le commentaire" />	
</form>

<?php	
}	// fi (isset($donnees['id_comm'])){
else{
	echo "Il n'y a pour l'instant aucun commentaire sur ce billet !<br /><br />";
}
?>

</div>