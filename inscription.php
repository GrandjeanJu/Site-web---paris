
<?php
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=site_paris;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
if(isset($_POST['forminscription'])) {
   $pseudo = htmlspecialchars($_POST['pseudo']);
   $email = htmlspecialchars($_POST['email']);
   $pass = sha1($_POST['pass']);
   $repass = sha1($_POST['repass']);
   if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['pass']) AND !empty($_POST['repass'])) {
        $reqpseudo = $bdd->prepare("SELECT * FROM espace_membre WHERE pseudo = ?");
        $reqpseudo->execute(array($pseudo));
        $pseudoexist = $reqpseudo->rowCount();
        if($pseudoexist == 0) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $reqemail = $bdd->prepare("SELECT * FROM espace_membre WHERE email = ?");
                $reqemail->execute(array($email));
                $emailexist = $reqemail->rowCount();
                if($emailexist == 0) {
                    if($pass == $repass) {
                    // Insertion du pseudo, mot de passe, email à l'aide d'une requête préparée
                    $req = $bdd->prepare('INSERT INTO espace_membre (pseudo, pass, email, date_inscription) VALUES(?, ?, ?, now())');
                    $req->execute(array($pseudo, $repass, $email));
                    $req->closeCursor();
                    $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                    } else {
                        $erreur = "Vos mots de passes ne correspondent pas !";
                    }
                } else {
                    $erreur = "Adresse mail déjà utilisé !";
                }
            } else {
                $erreur = "Votre adresse mail n'est pas valide !";
            }
        } else {
            $erreur = "Pseudo déjà utilisée !";
        }
    } else {
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

    <?php
    session_start();
    ?>
        <nav>
            <ul>
                <li><a href="index.php">Acceuil</a></li>
                <li><a class="page_utilisee" href="inscription.php">Inscription</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="comment_parier.php">Comment parier ?</a></li>
            </ul>
        </nav>

		<section>
		    <form action="inscription.php" method="post">
		        <p>
                <div>
		        <label for="pseudo">Pseudo</label> <br /> <input type="text" name="pseudo" id="pseudo" /><br />
                </div>
                <div>
		        <label for="pass">Mot de passe</label> <br /> <input type="password" name="pass" id="pass" /><br />
                </div>
                <div>
		        <label for="repass">Re Mot de passe</label> <br />  <input type="password" name="repass" id="repass" /><br />
                </div>
                <div>
		        <label for="email">Email</label> <br /> <input type="email" name="email" id="email" /><br />
                </div>

                <div>
		        <input class="image_inscription_connexion" type="submit" value="Creer mon compte" name="forminscription"/>
                </div>
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
