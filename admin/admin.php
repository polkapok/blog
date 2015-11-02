<?phpsession_start();?>

<?php
	/////////////////////AFFICHAGE DEBUG/////////////////
	

?>


<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Admin</title>
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

<h1>Blog - admin</h1>



<div id="liens_admin"><a id="liens_admin" href="/blog/admin/admin_billets.php">Admin - billets</a></div>
<br /><br />
<div id="liens_admin"><a id="liens_admin" href="/blog/admin/admin_commentaires.php">Admin - commentaires</a></div>
<br /><br />
<div id="liens_admin"><a id="liens_admin" href="/blog/admin/admin_post.php?base=vider">Admin - vider la base</a></div>
<br /><br />
<div id="liens_admin"><a id="liens_admin" href="/blog/admin/admin_post.php?base=reinit">Admin - réinitialiser la base</a></div>
<br /><br />


</div>
</main>




<!-- Le pied de page -->
<footer>
<?php include("../inc/inc_pied.php"); ?>
</footer>

</body>
</html>