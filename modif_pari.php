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
	if(isset($_POST['newcôte_W']) AND !empty($_POST['newcôte_W']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newcôte_W = htmlspecialchars($_POST['newcôte_W']);
	    $insertcôte_W = $bdd->prepare("UPDATE historique SET côte_W = ? WHERE id = ?");
	    $insertcôte_W->execute(array($newcôte_W, $getid));
	    header('location: historique.php');
	}
	if(isset($_POST['newcôte_L']) AND !empty($_POST['newcôte_L']) AND isset($_GET['id']) AND $_GET['id'] > 0)
	{
		$getid = $_GET['id']; // ici on reprend l'id du pari transmit pas l'action de form juste en bas, ainsi on peu identifier et le modifier le bon paris.
		$newcôte_L = htmlspecialchars($_POST['newcôte_L']);
	    $insertcôte_L = $bdd->prepare("UPDATE historique SET côte_L = ? WHERE id = ?");
	    $insertcôte_L->execute(array($newcôte_L, $getid));
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
?>
<html>
    <head>
        <meta charset="utf-8" />
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
            <h2>Edition de mon profil</h2>
		    <table>
        <thead> <!-- En-tête du tableau -->
            <tr>
                <th>Sport</th>
                <th>Mise</th>
                <th>Côte W</th>
                <th>Côte L</th>
                <th>Commentaire</th>
                <th>Gagné ?</th>
            </tr>
        </thead>
        <tbody> 
        <tr>
        	<form method="POST" action="modif_pari.php?id=<?php echo $_GET['id']/*ici on reprend l'id du pari (transmit par l'URL grâce à GET) pour l'envoyer juste au dessus pour la modification du bon pari  (la transmission initiale c'est faite par le script historique)*/ ?>"> 
                <td>
                <label></label>
                <input type="text" name="newsport" placeholder="sport">
                </td>
                <td>
                <label></label>
                <input type="text" name="newmise" placeholder="mise">
                </td>
                <td>
                <label></label>
                <input type="text" name="newcôte_W" placeholder="Côte du prétendu gagnant">
                </td>
                <td>
                <label></label>
                <input type="text" name="newcôte_L" placeholder="Côte du prétendu perdant">
                </td>
               	<td>
               	<label></label>
                <input type="text" name="newcommentaire" placeholder="Décris ta stratégie oula raison de ton pari !">
                </td>
                <td>
                <label for="resultat"></label>
		        <select name="newresultat">
		           <option value="??">Je ne sais pas encore !</option>
		           <option value="oui">oui</option>
		           <option value="non">non</option>
		        </select>
		        </td>
		        <td>
                <input type="submit" value="Mettre à jour ce paris !">
                </td>
                <br/>
                <button type="submit" name="supprimer" value="supprimer">Supprimer</button>
            </form>
        </tr> 
        </tbody>
        </table>
    </body>
</html>
<?php
}
?>
