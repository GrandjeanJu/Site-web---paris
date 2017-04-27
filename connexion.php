<?php
//Pour récuperer les variable identifié dans $_session (id,pseudo et email saisie plus bas)
session_start();
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=site_paris;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
if(isset($_POST['formconnexion'])) {
   $pseudoconnect = htmlspecialchars($_POST['pseudoconnect']);
   $passconnect = sha1($_POST['passconnect']);
   if(!empty($_POST['pseudoconnect']) AND !empty($_POST['passconnect'])) {
        $requser = $bdd->prepare("SELECT * FROM espace_membre WHERE pseudo = ? AND pass = ?");
        $requser->execute(array($pseudoconnect, $passconnect));
        $userexist = $requser->rowCount();
        if($userexist == 1) 
        {
            $userinfo = $requser -> fetch();
            $_SESSION['id'] = $userinfo ['id'];
            $_SESSION['pseudo'] = $userinfo ['pseudo'];
            $_SESSION['email'] = $userinfo ['email'];
            header("Location: profil.php?id=".$_SESSION['id']);
        } 
        else
        {
            $erreur = "Pseudo ou mot de passe incorrect!";
        }
    } 
    else 
    {
        $erreur = "Tous les champs doivent être complétés !";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Mini-chat</title>
    </head>
	<body>
	    <header>
	    	<div>
			<h1>PITAJA</h1>
			<p>Votre site de gestion de paris</p>
			</div>
		</header>
	    <nav>
	        <ul>
	            <li><a href="inscription.php">Inscription</a></li>
		        <li><a href="connexion.php">Connexion</a></li>
	            <li><a href="comment_parier.php">Comment parier ?</a></li>
				<li><a href="deconnexion.php"> Deconnexion </a></li>
	        </ul>
	    </nav>

	    <section>
	        <form action="connexion.php" method="post">
			    <p>
			    <label for="pseudo">Pseudo</label> : <input type="text" name="pseudoconnect" /><br />
			    <label for="pass">Mot de passe</label> :  <input type="password" name="passconnect" /><br />

			    <input type="submit" value="Envoyer" name="formconnexion"/>
			    <br />
			    Cliquez <a href="inscription.php">ici</a>, si vous n'avez pas encore de compte !
			    </p>
	    	</form>
			<?php
				if(isset($erreur)) 
				{
				    echo '<font color="red">'.$erreur."</font>";
			    }
			?>
		</section>
	</body>
</html>