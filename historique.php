<!-- AFFICHAGE ENTREE DES DONNEES POUR CREER UN PARI -->
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
		        <li><a href="comment_parier.php">Comment parier ?</a></li>
		        <li><?php echo '<a href="profil.php?id='.$_SESSION['id'].'" > Mon profil </a>' ?></li>
				<li><a class="page_utilisee" href="historique.php">Mon historique</a></li>
		    	<li><a href="deconnexion.php">Déconnexion</a></li>
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
		    			<form class="form_2_form" action="historique.php" method="post">
		    			<div class="form_2_div_flex">
              				<div class="form_2_div">
				        		Date <br/>
				        	<div class="date_couleur">
				        		<?php
									$date = date("d/m/Y");
									Print("$date");
								?>
							</div>
							</div>
              				<div class="form_2_div">
					        	Type <br/> <label class="form_2_label" for="sport"></label><input class="form_2_input" placeholder="Foot, Jeux à gratter, etc" type="text" name="sport" maxlength="15"/>
					        </div>
              				<div class="form_2_div">
					        	Dépense <br/> <label class="form_2_label" for="mise"></label><input class="form_2_input" placeholder="Ex: 30€" type="text" name="mise" maxlength="10"/>
					        </div>
              				<div class="form_2_div">
					        	Gain maximum <br/> <label class="form_2_label" for="gain_potentiel"></label><input class="form_2_input" placeholder="Ex: 55€" type="text" name="gain_potentiel" maxlength="10"/>
					        </div>
					    </div>    
              				<div class="form_2_div">
					        	Vos remarques <br/> <label class="form_2_label" for="commentaire"></label> 
					        	<textarea  class="form_2_input" name="commentaire" id="ameliorer" rows="5" cols="100" placeholder=" Notez ici, par exemples : les équipes qui s'affrontent ; celle sur laquelle vous pariez ; pourquoi ? ; etc... " maxlength="400"></textarea> <br/> 
					        </div>
              				<div class="form_2_div">
					        	Gagné ? <br/> <label class="form_2_label" for="gagné"></label>
					        <select class="form_2_input" name="gagné">
					           <option value="oui">oui</option>
					           <option value="??">Je ne sais pas encore</option>
					           <option value="non">non</option>
					        </select><br/>
			        		<input type="submit" value="Enregistrer mon pari !" class="image_enregistrer_pari"/>
			        		</div>
		             	</form>
		        <br/>
		        <table>
		        <thead> <!-- En-tête du tableau -->
		            <tr>
		                <th class="petit_case">Date</th>
		                <th class="petit_case">Type</th>
		                <th class="petit_case">Dépense</th>
		                <th class="petit_case">Gain potentiel</th>
		                <th>Vos remarques</th>
		                <th class="petit_case">Gagné ?</th>
		                <th class="petit_case">Resultats</th>
		                <th></th>
		             </tr>
		        </thead>
		        <tbody>
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
			        	
			        	if (strlen($donnees['commentaire'])>35) // Pour afficher les petit points après les commentaire si il y a plus de 35 caractères et donc que le texte est plus long que ce qui s'affiche dans la case du tableau
			        	{
				        	echo
				        	'
					        <tr>
					         	<td class="petit_case">' . $donnees['date_addi']. '</td>
					         	<td class="petit_case">' . htmlspecialchars($donnees['sport']) . '</td>
					          	<td class="petit_case">' . htmlspecialchars($donnees['mise']) . '</td>
					           	<td class="petit_case">' . htmlspecialchars($donnees['gain_potentiel']) . '</td>
					           	<td>' .substr(htmlspecialchars($donnees['commentaire']), 0, 35). '...</td>
					           	<td class="petit_case">' . htmlspecialchars($donnees['resultat']) . '</td>
					           	<td class="petit_case">' . $gain_perte . '</td>
					           	<td class="petit_case"> <a class="fond_image" href="modif_pari.php?id='.$donnees['id'].'"><img class="fond_image" src="image/loupe.jpeg" alt="loupe" width="15" height="15"/></a> </td>
				           	</tr>
				          	</tbody>
	                        '
				          	;
			        	}
			        	else
			        	{
			        		echo
				        	'
					        <tr>
					         	<td class="petit_case">' . $donnees['date_addi']. '</td>
					         	<td class="petit_case">' . htmlspecialchars($donnees['sport']) . '</td>
					          	<td class="petit_case">' . htmlspecialchars($donnees['mise']) . '</td>
					           	<td class="petit_case">' . htmlspecialchars($donnees['gain_potentiel']) . '</td>
					           	<td>' . substr(htmlspecialchars($donnees['commentaire']), 0, 35). '</td>
					           	<td class="petit_case">' . htmlspecialchars($donnees['resultat']) . '</td>
					           	<td class="petit_case">' . $gain_perte . '</td>
					           	<td class="petit_case"> <a class="fond_image" href="modif_pari.php?id='.$donnees['id'].'"><img class="fond_image" src="image/loupe.jpeg" alt="loupe" width="15" height="15"/></a> </td>
				           	</tr>
				          	</tbody>
	                        '
				          	;
			        	}
			        	
			          
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