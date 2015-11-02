
<?phpsession_start();?>
<?php
if (isset($_POST['login']) AND isset($_POST['pass']))
{
    $login = $_POST['login'];
    $pass_crypte = crypt($_POST['pass']); // On crypte le mot de passe

    echo '<p>Ligne à copier dans le .htpasswd :<br />' . $login . ':' . $pass_crypte . '</p>';
}

else // On n'a pas encore rempli le formulaire
{
?>

<!DOCTYPE html>
<html lang="fr">
<head>
		<meta charset="utf-8" />
		<title>Crypter password</title>
        <link rel="stylesheet" type="text/css" href="/blog/css/style.css" />
</head>
<body>
<p>Entrez votre login et votre mot de passe pour le crypter.</p>
<form method="post">
    <p>
        Login : <input type="text" name="login"><br /><br />
        Mot de passe : <input type="text" name="pass"><br /><br />
    
        <input type="submit" value="Crypter !">
    </p>
</form>
</body>
</html>
<?php
}
?>