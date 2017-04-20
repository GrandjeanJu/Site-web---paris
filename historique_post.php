<?php
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

// Insertion du message à l'aide d'une requête préparée
$req = $bdd->prepare('INSERT INTO historique (date_ajout, sport, mise, resultat, pseudo, commentaire, gain_potentiel) VALUES(now(),?,?,?,?,?,?)');
$req->execute(array($_POST['sport'], $_POST['mise'], $_POST['gagné'], $_SESSION['id'], $_POST['commentaire'], $_POST['gain_potentiel']));

// Redirection du visiteur vers la page du minichat
header('Location: historique.php');
?>