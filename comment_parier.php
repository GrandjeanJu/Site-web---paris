<?php session_start();?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Mini-chat</title>
    </head>
<?php
if (!isset($_SESSION['id']))
{ 
?>
 	<nav>
		<ul>
		    <li><a href="inscription.php">Inscription</a></li>
		    <li><a href="connexion.php">Connexion</a></li>
		    <li><a href="comment_parier.php">Comment parier ?</a></li>
		</ul>
	</nav>

<?php
} 
else
{
?>
    <body>
	    <header>
		    <h2>Profil de <?php echo $_SESSION['pseudo']?></h2>
		    ID = <?php echo $_SESSION['id']?>
		    eMail = <?php echo $_SESSION['email']?>
		</header>
		
    	<nav>
		    <ul>
		        <li><a href="inscription.php">Inscription</a></li>
		        <li><a href="connexion.php">Connexion</a></li>
				<li><a href="deconnexion.php">Deconnexion</a></li>
		        <li><a href="comment_parier.php">Comment parier ?</a></li>
		        <li><?php echo '<a href="profil.php?id='.$_SESSION['id'].'" > Mon profil </a>' ?></li>
				<li><a href="historique.php">Mon historique</a></li>
		    </ul>
		</nav>
<?php
}
?>
		<section>
			
		</section>
    </body>
</html>
