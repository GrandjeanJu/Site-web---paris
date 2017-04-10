<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="Style.css" />
        <title>Mini-chat</title>
    </head>
    <style>
    form
    {
        text-align:center;
    }
    </style>
    <body>
	    <header>
		    <h2>Profil de <?php echo $_SESSION['pseudo']?></h2>
		    ID = <?php echo $_SESSION['id']?>
		    eMail = <?php echo $_SESSION['email']?>
		</header>
		
    	<nav>
		    <ul>
		        <a href="inscription.php">Inscription</a>
		        <a href="connexion.php">Connexion</a>
				<a href="deconnexion.php">Deconnexion</a>
		        <a href="comment_parier.php">Comment parier ?</a>
		        <?php echo '<a href="profil.php?id='.$_SESSION['id'].'" > Mon profil </a>' ?>

				<a href="historique.php">Mon historique</a>
		    </ul>
		</nav>

		<section>
			
		</section>
    </body>
</html>