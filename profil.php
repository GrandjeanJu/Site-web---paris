<?php
session_start();
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
		    	<li><a href="deconnexion.php">Deconnexion</a></li>
		        <li><a href="comment_parier.php">Comment parier ?</a></li>
		        <li><?php echo '<a class="page_utilisee" href="profil.php?id='.$_SESSION['id'].'" > Mon profil </a>' ?></li>
				<li><a href="historique.php">Mon historique</a></li>
		    </ul>
		</nav>
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
if(isset($_SESSION['id'])) // c'est juste pour voir si la personne est connecté, la session prend ça valeur dans la page connexion via ce qu'a rentré l'utilisateur en ce connectant
{
    $requser = $bdd->prepare('SELECT * FROM espace_membre WHERE id = ?');
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
    {
        $reqpseudo = $bdd->prepare("SELECT * FROM espace_membre WHERE pseudo = ?");
        $reqpseudo->execute(array($_POST['newpseudo']));
        $pseudoexist = $reqpseudo->rowCount();
        if($pseudoexist == 0)
        {
            $newpseudo = htmlspecialchars($_POST['newpseudo']);
            $insertpseudo = $bdd->prepare("UPDATE espace_membre SET pseudo = ? WHERE id = ?");
            $insertpseudo->execute(array($newpseudo,$_SESSION['id']));
            header('location: profil.php?id='.$_SESSION['id']);
        }
        else
        {
            $msg = "Ce pseudo est déja utilisé par un membre, veuillez en utilisez un autre s'il vous plait !";
        }
    }

    if(isset($_POST['newemail']) AND !empty($_POST['newemail']) AND $_POST['newemail'] != $user['email'])
    {
        if(filter_var($_POST['newemail'], FILTER_VALIDATE_EMAIL)) 
        {
            $reqemail = $bdd->prepare("SELECT * FROM espace_membre WHERE email = ?");
            $reqemail->execute(array($_POST['newemail']));
            $emailexist = $reqemail->rowCount();
            if($emailexist == 0) 
            {
                $newemail = htmlspecialchars($_POST['newemail']);
                $insertemail = $bdd->prepare("UPDATE espace_membre SET email = ? WHERE id = ?");
                $insertemail->execute(array($newemail,$_SESSION['id']));
                header('location: profil.php?id='.$_SESSION['id']);
            }
            else
            {
                $msg = "Votre mail est déja utilisé par un membre, veuillez en utilisez un autre s'il vous plait !";
            }
        }
    }
//continuer le changement de mdp avec le if ci dessous = 14min
    if(isset($_POST['newpass']) AND !empty($_POST['newpass']) AND isset($_POST['newrepass']) AND !empty($_POST['newrepass']))
    {
        $newpass = sha1($_POST['newpass']);
        $newrepass = sha1($_POST['newrepass']);

        if($newpass == $newrepass)
        {
            $insertpass = $bdd->prepare("UPDATE espace_membre SET pass = ? WHERE id = ?");
            $insertpass->execute(array($newpass, $_SESSION['id']));
            header('location: profil.php?id='.$_SESSION['id']);
        }
        else
        {
            $msg = "Vos deux mots de passe ne correspondent pas !";
        }
    }
?>
		<section>
            <form method="POST" action="">
                <div>
                <label>Pseudo</label> <br/>
                <input type="text" name="newpseudo" placeholder="pseudo" value="<?php echo $user['pseudo']; ?>"><br/>
                </div>
                <div>
                <label>Votre mail</label> <br/>
                <input type="text" name="newemail" placeholder="email" value="<?php echo $user['email']; ?>"> <br/>
                </div>
                <div>
                <label>Nouveau mot de passe</label> <br/>
                <input type="password" name="newpass"> <br/>
                </div>
                <div>
                <label>Confirmation du mot de passe</label> <br/>
                <input type="password" name="newrepass"> <br/>
                </div>
                <div>
                <input class="image_inscription_connexion" type="submit" value="Mettre à jour mon profil !">
                </div>
            </form>
            <?php if(isset($msg)) {echo $msg;} ?>
<?php
}
else
{
    header("Location: profil.php");
}
?>
		</section>
	</body>
</html>