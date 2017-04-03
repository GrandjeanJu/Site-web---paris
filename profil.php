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
if(isset($_GET['id']) AND $_GET['id'] > 0) 
{
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM espace_membre WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Mini-chat</title>
    </head>
    <style>
    *
    {
        text-align:center;
    }
    </style>
    <body>
    <h2>Profil de <?php echo $userinfo['pseudo']?></h2>
    <br/><br/>
    ID = <?php echo $userinfo['id']?>
    <br/>
    eMail = <?php echo $userinfo['email']?>
    <br/>
<?php
    if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) // session id 
    {
?>
    <a href="edition_profil.php">Editer mon profil</a>
    <a href="deconnexion.php">Deconnexion</a>
    <a href="historique.php">Mon historique</a>
<?php
    }
?>
</body>
</html>
<?php
}
else
{

}
?>