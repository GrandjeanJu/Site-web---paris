<!-- AFFICHAGE ENTREE DES DONNEES POUR CREER UN PARI (lié à historique_post) -->


<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="Style.css" />
        <title>Mini-chat</title>
    </head>
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


<!-- AFFICHAGE TABLEAU -->


		<section>
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
		        $sessid=$_SESSION['id']; // sert à query ci dessous mais aussi pour un query plus loin
		        $reponse = $bdd->query('SELECT resultat, id, commentaire, sport, gain_potentiel, mise, DATE_FORMAT(date_ajout, "%d/%m/%Y") AS date_addi FROM historique WHERE pseudo='.$sessid.' ORDER BY ID DESC');
		    ?>
		        <table>
		        <thead> <!-- En-tête du tableau -->
		            <tr>
		                <th>Date</th>
		                <th>Sport</th>
		                <th>Mise</th>
		                <th>Gain potentiel</th>
		                <th>Commentaire</th>
		                <th>Gagné ?</th>
		                <th>Perte ou Gain </th>
		             </tr>
		        </thead>
		        <tbody>
		        	<tr>
			        	<form action="historique_post.php" method="post">
			       			<td><?php ?></td>
					        <td><label for="sport"></label><input type="text" name="sport"/></td>
					        <td><label for="mise"></label><input type="text" name="mise"/></td>	 
					        <td><label for="gain_potentiel"></label><input type="text" name="gain_potentiel"/></td>
					        <td><label for="commentaire"></label> <input placeholder="Dis pourquoi tu as parié pour t'en souvenir et te perféctionner si tu le souhaite !" name="commentaire"/></td >
					        <td><label for="gagné"></label>
					        <select name="gagné">
					           <option value="??">Je ne sais pas encore</option>
					           <option value="oui">oui</option>
					           <option value="non">non</option>
					        </select>
			 				</td>
			 				<td></td>
			        		<td><input type="submit" value="Envoyer"/></td>
		    			</form>
		             </tr>
			<?php
		        // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
		        while ($donnees = $reponse->fetch())
		        { //avec le if si dessous on calcul les gain ou perte et modif de la bdd pour gain_perte
		        	if(htmlspecialchars($donnees['resultat'])=="oui") 
		        	{
		          		$gain_perte=(htmlspecialchars($donnees['gain_potentiel']));
		        	}
		        	elseif (htmlspecialchars($donnees['resultat'])=="non")
		        	{
		          		$gain_perte=-htmlspecialchars($donnees['mise']);
		        	}
		        	else
		        	{
		            	$gain_perte="En attente";
		        	} 
		        	$insertgain_perte = $bdd->prepare("UPDATE historique SET gain_perte = ? WHERE id = ?");
		        	$insertgain_perte->execute(array($gain_perte,$donnees['id']));
		        //avec le if si dessous on calcul les gain ou perte et modif de la bdd pour gain_perte_chiffre (la diff par rapport a au dessus c'est qu'on remplace en attente par 0 pour ensuite pouvoir calculer la somme total des gain ou des perte)
		        	if (htmlspecialchars($donnees['resultat'])=="oui")
		        	{
		            	$gain_perte_chiffre=(htmlspecialchars($donnees['gain_potentiel']));
		        	}
		        	elseif (htmlspecialchars($donnees['resultat'])=="non")
		        	{
		            	$gain_perte_chiffre=-htmlspecialchars($donnees['mise']);
		        	}
		        	else
		        	{
		            	$gain_perte_chiffre=0;
		        	}
		        	$insertgain_perte_chiffre = $bdd->prepare("UPDATE historique SET gain_perte_chiffre = ? WHERE id = ?");
		        	$insertgain_perte_chiffre->execute(array($gain_perte_chiffre,$donnees['id']));
		        	echo
		        	'
			        <tr>
			         	<td>' .$donnees['date_addi']. '</td>
			         	<td>' . htmlspecialchars($donnees['sport']) . '</td>
			          	<td>' . htmlspecialchars($donnees['mise']) . '</td>
			           	<td>' . htmlspecialchars($donnees['gain_potentiel']) . '</td>
			           	<td>' . htmlspecialchars($donnees['commentaire']) . '</td>
			           	<td>' . htmlspecialchars($donnees['resultat']) . '</td>
			           	<td>' . $gain_perte . '</td>
			           	<td> <a href="modif_pari.php?id='.$donnees['id'].'">Modifier</a> </td>
		           	</tr>
		          	</tbody>'
		          	;
		        }
		        $reponse->closeCursor();
			?>
		    		<tr> 
			    		<td colspan="8"> 
					        <?php
					            $reponse=$bdd->query('SELECT SUM(gain_perte_chiffre) AS gain_perte_total FROM historique WHERE pseudo='.$sessid.'');
					            $userinfo = $reponse->fetch();
					        if(isset($userinfo['gain_perte_total']))
					        {
					            echo 'Recette totale = '. $userinfo['gain_perte_total'];
					        }
					        else
					        {
					            echo 'Recette totale = 0'. $userinfo['gain_perte_total'];
					        }	
					    	?> 
			    		</td> 
		    		</tr>
		    	</tbody>
		      	</table>
		</section>
    </body>
</html>