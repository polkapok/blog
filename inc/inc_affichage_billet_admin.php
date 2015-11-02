<div id="billet">
Le <?php echo $donnees['date_creation_fr']; ?>, 
<strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> 
a écrit le billet n&deg; <strong><?php echo $donnees['id']; ?></strong> :
<blockquote><strong><em><?php echo htmlspecialchars($donnees['titre']); ?></em></strong>
<blockquote><em><?php echo nl2br($donnees['texte']); ?></em></blockquote></blockquote>
</div>