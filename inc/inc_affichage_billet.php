<div id="billet">
Le <?php echo $donnees['date_creation_fr']; ?>, <strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> a écrit ce billet :<br /><br />
<strong><?php echo htmlspecialchars($donnees['titre']); ?></strong><br /><br />
<?php echo nl2br($donnees['texte']); ?><br />
</div>