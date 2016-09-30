<?php
session_start();

//Autorisation de regard par rapport au statut
if($_SESSION['emplCat']=="Exploitant")
{
	//Fichier de connexion requis afin de pouvoir se connecter à la BDD
	//MySql
	require 'utilitaires/connection/connection_mysql.php';
	//Encodage des sorties de la BDD en utf8
	mysql_set_charset("UTF8");

	if(isset($_SESSION['trnNumInfo']))
	{
		unset($_SESSION['trnNumInfo']);
	}

	if(isset($_POST['trnNumSup']))
	{
		$trnNum=$_POST['trnNumSup'];

		$sqlSupprimerTournee=  "DELETE FROM 
									tournee
								WHERE
									trnNum=".$trnNum;
		mysql_query($sqlSupprimerTournee);

		echo "<meta http-equiv='refresh' content='0;url=AC11.php?message=<font color=green>Tournée supprimée</font>'>";
	}

	$sqlTournees=  "SELECT
						tournee.trnNum,
						emplNom,
						emplPrenom,
						vehMat,
						trnDepChf
					FROM
						employe,
						tournee
					WHERE
						emplId=chfId
					ORDER BY
						trnNum
					ASC";
	$sqlTournees=tableSQL($connexion, $sqlTournees);
	?>
	<!DOCTYPE html>
	<html>
		<head>
	        <title>Tournées qui commit</title>
	        <link rel="stylesheet" type="text/css" href="style/styleac11.css" />
	        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<body>
			<h3>AC11 - Organiser les tournées - Liste des tournées</h3>
			<table id="tableau_tournees">
				<?php
				$couleur="Claire";
				?>
				<tr class="tournee<?php echo $couleur; ?>">
					<th>
						Tournée
					</th>
					<th>
						Date
					</th>
					<th>
						Chauffeur
					</th>
					<th>
						Véhicule
					</th>
					<th>
						Départ
					</th>
					<th>
						Arrivée
					</th>
					<th>
						Supprimer
					</th>
					<th>
						Modifier
					</th>
				</tr>
				<?php
				foreach($sqlTournees As $donnees)
				{
					if($couleur=="Foncee")
					{
						$couleur="Claire";
					}
					else
					{
						$couleur="Foncee";
					}
					?>
					<tr class="tournee<?php echo $couleur; ?>">
						<td>
							<?php
							echo $donnees['trnNum'];
							?>
						</td>
						<td>
							<?php
							echo date("d/m/Y", strtotime($donnees['trnDepChf']));
							?>
						</td>
						<td>
							<?php
							echo $donnees['emplNom']." ".$donnees['emplPrenom'];
							?>
						</td>
						<td>
							<?php
							echo $donnees['vehMat'];
							?>
						</td>
						<td>
							<?php
							$sqlMinEtape=  "SELECT
												lieuNom,
												comNom
											FROM
												commune,
												lieu,
												etape
											WHERE
												commune.comId=lieu.comId
												AND
												lieu.lieuId=etape.lieuId
												AND
												trnNum=".$donnees['trnNum']."
                                            ORDER BY
                                            	etpRDV
                                            ASC";

							if(compteSQL($connexion, $sqlMinEtape)!=0)
							{
								$sqlMinEtape=tableSQL($connexion, $sqlMinEtape);
								echo $sqlMinEtape[0]['lieuNom']." ".$sqlMinEtape[0]['comNom'];
							}
							else
							{
								echo "Aucune étape disponible";
							}
							?>
						</td>
						<td>
							<?php
							$sqlMaxEtape=  "SELECT
												lieuNom,
												comNom
											FROM
												commune,
												lieu,
												etape
											WHERE
												commune.comId=lieu.comId
												AND
												lieu.lieuId=etape.lieuId
												AND
												trnNum=".$donnees['trnNum']."
                                            ORDER BY
                                            	etpRDV
                                            DESC";

							if(compteSQL($connexion, $sqlMaxEtape)!=0)
							{
								$sqlMaxEtape=tableSQL($connexion, $sqlMaxEtape);
								echo $sqlMaxEtape[0]['lieuNom']." ".$sqlMaxEtape[0]['comNom'];
							}
							else
							{
								echo "Aucune étape disponible";
							}
							?>
						</td>
						<td>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
								<input name="trnNumSup" type="hidden" value="<?php echo $donnees['trnNum']; ?>"/>
								<input name="suppr_bout_<?php echo $donnees['trnNum']; ?>" id="suppr_bout_<?php echo $donnees['trnNum']; ?>" class="suppr_form" type="submit"/>
							</form>
						</td>
						<td>
							<form action="AC12.php" method="POST">
								<input name="trnNumInfo" type="hidden" value="<?php echo $donnees['trnNum']; ?>"/>
								<input name="modif_bout_<?php echo $donnees['trnNum']; ?>" id="modif_bout_<?php echo $donnees['trnNum']; ?>" class="modif_form" type="submit"/>
							</form>
						</td>
						<script type="text/javascript">
							function griser()
							{
								var now = new Date();
								var annee=now.getFullYear();
								var mois=now.getMonth()+1;
								if(mois<10)
								{
									mois='0'+mois;
								}
								var jour=now.getDate();
								if(jour<10)
								{
									jour='0'+jour;
								}
								var heure=now.getHours();
								if(heure<10)
								{
									heure='0'+heure;
								}
								var minute=now.getMinutes();
								if(minute<10)
								{
									minute='0'+minute;
								}
								var seconde=now.getSeconds();
								if(seconde<10)
								{
									seconde='0'+seconde;
								}
								date=annee+'-'+mois+'-'+jour+' '+heure+':'+minute+':'+seconde;
								if("<?php echo $donnees['trnDepChf']; ?>"<date)
								{
									document.getElementById('suppr_bout_<?php echo $donnees["trnNum"]; ?>').setAttribute('disabled', 'disabled');
									document.getElementById('suppr_bout_<?php echo $donnees["trnNum"]; ?>').style.opacity=0.5;
									document.getElementById('modif_bout_<?php echo $donnees["trnNum"]; ?>').setAttribute('disabled', 'disabled');
									document.getElementById('modif_bout_<?php echo $donnees["trnNum"]; ?>').style.opacity=0.5;
								}
							}

							griser();
						</script>
					</tr>
					<?php
				}
				?>
			</table>
			<input class="bouton_global" id="ajouter" type="button" onClick="location='AC12.php'" value="Ajouter"/>
			<input class="bouton_global" id="retour" type="button" onClick="location='accueil.php'" value="Retour"/>
			<?php
			if(isset($_GET['message']))
			{
				echo $_GET['message'];
			}
			?>
		</body>
	</html>
	<?php
}
else
{
	header("location:accueil.php");
}
?>