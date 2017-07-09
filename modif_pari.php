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
if(isset($_SESSION['id'])) // c'est juste pour voir si la personne est connecté, la session prend ça valeur dans la page connexion via ce qu'a rentré l'utilisateur en ce connectant
{
	if(isset($_POST['newsport']) AND !empty($_POST['newsport']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newsport = htmlspecialchars($_POST['newsport']);
	    $insertsport = $bdd->prepare("UPDATE historique SET sport = ? WHERE id = ?");
	    $insertsport->execute(array($newsport, $getid));
	    header('location: historique.php');
	}
	if(isset($_POST['newmise']) AND !empty($_POST['newmise']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newmise = htmlspecialchars($_POST['newmise']);
	    $insertmise = $bdd->prepare("UPDATE historique SET mise = ? WHERE id = ?");
	    $insertmise->execute(array($newmise, $getid));
	    header('location: historique.php');
	}
	if(isset($_POST['gain_potentiel']) AND !empty($_POST['gain_potentiel']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newgain_potentiel = htmlspecialchars($_POST['gain_potentiel']);
	    $insertgain_potentiel = $bdd->prepare("UPDATE historique SET gain_potentiel = ? WHERE id = ?");
	    $insertgain_potentiel->execute(array($newgain_potentiel, $getid));
	    header('location: historique.php');
	}
	if(isset($_POST['newcommentaire']) AND !empty($_POST['newcommentaire']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newcommentaire = htmlspecialchars($_POST['newcommentaire']);
	    $insertcommentaire = $bdd->prepare("UPDATE historique SET commentaire = ? WHERE id = ?");
	    $insertcommentaire->execute(array($newcommentaire, $getid));
	    header('location: historique.php');
	}
	if(isset($_POST['newresultat']) AND !empty($_POST['newresultat']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newresultat = htmlspecialchars($_POST['newresultat']);
	    $insertresultat = $bdd->prepare("UPDATE historique SET resultat = ? WHERE id = ?");
	    $insertresultat->execute(array($newresultat, $getid));
	    header('location: historique.php');
	}
    if(isset($_POST['supprimer']) AND !empty($_POST['supprimer']) AND isset($_GET['id']) AND $_GET['id'] > 0)
    {
        $getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
        $insertsupprimer = $bdd->prepare("DELETE FROM historique WHERE id = ?");
        $insertsupprimer->execute(array($getid));
        header('location: historique.php');
    }

    //recupération des message pour les afficher en "value" dans les input
    $sessid=$_SESSION['id']; // sert à query ci dessous mais aussi pour un query plus loin
                $reponse = $bdd->query('SELECT resultat, commentaire, sport, gain_potentiel, mise FROM historique WHERE id='.$_GET['id'].'');
                $donnees = $reponse->fetch()
?>
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
		        <a href="historique.php">Retour à l'historique</a>
		    </ul>
		</nav>
		<table class="table_form_modif">
        <thead> <!-- En-tête du tableau -->
            <tr>
                <th>Type</th>
                <th>Dépense</th>
                <th>Gain potentiel</th>
                <th>Gagné ?</th>
            </tr>
        </thead>
        <tbody> 
        <tr>
        	<form method="POST" action="modif_pari.php?id=<?php echo $_GET['id']/*ici on reprend l'id du pari (transmit par l'URL grâce à GET) pour l'envoyer juste au dessus pour la modification du bon pari  (la transmission initiale c'est faite par le script historique)*/ ?>">
                <td>
                <input class="form_2_input" type="text" name="newsport" placeholder="Type" value="<?php echo $donnees['sport'];?>">
                </td>
                <td>
                <input class="form_2_input" type="text" name="newmise" placeholder="Dépense" value="<?php echo $donnees['mise'];?>">
                </td>
                <td>
                <input class="form_2_input" type="text" name="gain_potentiel" placeholder="Gain potentiel" value="<?php echo $donnees['gain_potentiel'];?>">
                </td>
                <td>
                <label for="resultat" ></label>
		        <select class="form_2_input" name="newresultat" >
		           <option value="oui">oui</option>
		           <option value="non">non</option>
                   <option value="??">Je ne sais pas encore !</option>
		        </select>
		        </td>
        </tr> 
        </tbody>
        </table>

        <table class="table_form_modif">
        <thead>
        <tr>
                <th>Vos remarques</th>
        </tr>
        </thead>
        <tbody>
        <tr>
                <td>
                <textarea  class="form_2_input" name="newcommentaire" rows="5" cols="100" placeholder=" Notez ici, par exemples : les équipes qui s'affrontent ; celle sur laquelle vous pariez ; pourquoi ? ; etc... " maxlength="400"><?php echo $donnees['commentaire'];?></textarea>
                </td>
        </tr>
        </tbody>
        </table>
                <input type="submit" class="image_enregistrer_pari" value="Enregistrer mes modifications">
                <input type="image" src="image/poubelle.jpeg" width="30" height="30" name="supprimer" value="supprimer">

            </form>
    </body>
</html>
<?php
}
?>
