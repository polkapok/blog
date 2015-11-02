<div id="billet">

<div id="titre_form">Editer billet</label></div>

Le <?php echo $donnees['date_creation_fr']; ?>, le billet n&deg; <strong><?php echo $donnees['id']; ?></strong> 
a été écrit  par <strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> :<br />

<form action="admin_billets_post.php" method="post" name="admin_edit_billet" id="admin_edit_billet" >
<label for="auteur">Auteur</label>
<input type="text" id="edit_auteur" 
		name="edit_auteur"
		maxlength="255"
		size="44"
		value="<?php echo htmlspecialchars($donnees['auteur']); ?>" 
		placeholder="Le pseudo..." required >
		<br />
		<br />
<label for="titre">Titre du billet</label>
<input type="text" id="edit_titre" 
		name="edit_titre"
		maxlength="255"
		size="44"
		value="<?php echo htmlspecialchars($donnees['titre']); ?>" 
		placeholder="Le titre..." required >
		<br />		
<label for="texte">Billet</label>
<textarea id="edit_texte" name="edit_texte" rows="4" cols="50" 
placeholder="Le billet..." required ><?php echo nl2br($donnees['texte']); ?></textarea>
<br />
<input type="hidden" name="edit_id" id="edit_id" value="<?php echo ($donnees['id']); ?>">
<input type="hidden" name="edit_date_crea" id="edit_date_crea" value="<?php echo ($donnees['date_creation_fr']); ?>">
<input type="submit" name="submit" id="editer" value="Editer" />	
<input type="submit" name="submit" id="supprimer" value="Supprimer le billet" />	
</form>

</div>