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
     
    <form action="historique_post.php" method="post">
    <p>
        <label for="sport">Sport</label> : <input type="text" name="sport"/><br />
 
        <label for="mise">Mise</label> :  <input type="text" name="mise"/><br />
 
        <label for="côte_W">Côte winner (celui que vous esperez voir <strong>gagner</strong>)</label> :  <input type="text" name="côte_W"/><br />
 
        <label for="côte_L">Côte loser (celui que vous esperez voir <strong>perdre</strong>)</label> :  <input type="text" name="côte_L"/><br />
 
        <label for="commentaire">Commentaire :</label> <input placeholder="Dis pourquoi tu as parié pour t'en souvenir et te perféctionner si tu le souhaite !"  size="100" maxlength="255" type="text" name="commentaire"/><br />
         
        <label for="gagné">Gagné ?</label>
        <select name="gagné">
           <option value="??">Je ne sais pas encore !</option>
           <option value="oui">oui</option>
           <option value="non">non</option>
       </select>
 
        <input type="submit" value="Envoyer" />
    </p>
    </form>
 
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
        // Récupération des messages
        $sessid=$_SESSION['id'];
        $reponse = $bdd->query('SELECT resultat, id, commentaire, sport, côte_W, côte_L, mise, DATE_FORMAT(date_ajout, "%d/%m/%Y") AS date_addi FROM historique WHERE pseudo='.$sessid.' ORDER BY ID DESC');
    ?>
        <table>
        <thead> <!-- En-tête du tableau -->
            <tr>
                <th>Date</th>
                <th>Sport</th>
                <th>Mise</th>
                <th>Côte W</th>
                <th>Côte L</th>
                <th>Commentaire</th>
                <th>Gagné ?</th>
             </tr>
        </thead>
<?php
     
        // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
        while ($donnees = $reponse->fetch())
        {
          echo
          '<tbody>
           <tr>
           <td>' .$donnees['date_addi']. '</td>
           <td>' . htmlspecialchars($donnees['sport']) . '</td>
           <td>' . htmlspecialchars($donnees['mise']) . '</td>
           <td>' . htmlspecialchars($donnees['côte_W']) . '</td>
           <td>' . htmlspecialchars($donnees['côte_L']) . '</td>
           <td>' . htmlspecialchars($donnees['commentaire']) . '</td>
           <td>' . htmlspecialchars($donnees['resultat']) . '</td>
           <td> <a href="modif_pari.php?id='.$donnees['id'].'">Modifier</a> </td>
           </tr>
          </tbody>';
        }
 
        $reponse->closeCursor();
    ?>
 
      </table>
    </body>
</html>