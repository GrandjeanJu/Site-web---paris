<?php session_start();?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Mini-chat</title>
    </head>
	<header>
		<div>
		<h1>PITAJA</h1>
		<p>Votre site de gestion de paris</p>
		</div>
	</header>
	<?php
	session_start();
	if(isset($_SESSION['id']))
	{
	?>
		<nav>
		    <ul>
		    	<li><a href="deconnexion.php">Deconnexion</a></li>
		        <li><a class="page_utilisee" href="comment_parier.php">Comment parier ?</a></li>
		        <li><?php echo '<a href="profil.php?id='.$_SESSION['id'].'" > Mon profil </a>' ?></li>
				<li><a href="historique.php">Mon historique</a></li>
		    </ul>
		</nav>

	<?php
	}
	else
	{
	?>
	    <nav>
	        <ul>
	        	<li><a href="index.php">Acceuil</a></li>
	            <li><a href="inscription.php">Inscription</a></li>
	            <li><a href="connexion.php">Connexion</a></li>
	            <li><a class="page_utilisee" href="comment_parier.php">Comment parier ?</a></li>
	        </ul>
	    </nav>

	<?php
	}
	?>
		<section>
			
		</section>
    </body>
</html>
