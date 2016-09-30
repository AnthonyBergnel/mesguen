<?php
session_start();

if($_SESSION['emplCat']=="Exploitant")
{
	require 'utilitaires/connection/connection_mysql.php';
	mysql_set_charset("UTF8");

	if(isset($_POST['etpIdSup']) AND isset($_POST['trnNumSup']))
	{
		$etpId=$_POST['etpIdSup'];
		$trnNum=$_POST['trnNumSup'];

		$sqlSupprimerEtape="DELETE FROM 
								etape
							WHERE
								etpId=".$etpId."
								AND
								trnNum=".$trnNum;
		mysql_query($sqlSupprimerEtape);

		$_SESSION['trnNumInfo']=$trnNum;

		echo "<meta http-equiv='refresh' content='0;url=AC12.php?message=<font color=green>Etape supprimée</font>'>";
	}

	if(isset($_POST['maj']))
	{
		$trnDepChf=$_POST['trnDepChf'];
		$trnDepChf=date_create_from_format('d/m/Y', $trnDepChf);
		$trnDepChf=date_format($trnDepChf, 'Y-m-d H:i:s');
		$chauffeur=$_POST['chauffeur'];
		$vehicule=$_POST['vehicule'];
		$commentaire=$_POST['commentaire'];
		$trnNum=$_POST['trnNumInfo'];

		$sqlUpdate="UPDATE
						tournee
					SET
						trnDepChf='".$trnDepChf."',
						chfId=".$chauffeur.",
						vehMat='".$vehicule."',
						trnCommentaire='".$commentaire."'
					WHERE
						trnNum=".$trnNum;
		mysql_query($sqlUpdate);
	}

	if(isset($_POST['creer']))
	{
		if($_POST['chauffeur']!="NAN" AND $_POST['vehicule']!="NAN" AND $_POST['trnDepChf']!="")
		{
			$trnDepChf=$_POST['trnDepChf'];
			$trnDepChf=date_create_from_format('d/m/Y', $trnDepChf);
			$trnDepChf=date_format($trnDepChf, 'Y-m-d H:i:s');
			$chauffeur=$_POST['chauffeur'];
			$vehicule=$_POST['vehicule'];
			$sqlControl=   "SELECT
								trnNum
							FROM
								tournee
							WHERE
								trndepchf='".$trnDepChf."'
								AND
								chfId='".$chauffeur."'
								AND
								vehMat='".$vehicule."'";
			if(compteSQL($connexion, $sqlControl)==0)
			{
				if(isset($_POST['commentaire']))
				{
					$commentaire=$_POST['commentaire'];
					$sqlInsert="INSERT INTO tournee
									(trndepchf,
									chfId,
									vehMat,
									trnCommentaire)
								VALUES
									('".$trnDepChf."',
									'".$chauffeur."',
									'".$vehicule."',
									'".$commentaire."')";
				}
				else
				{
					$sqlInsert="INSERT INTO tournee
									(trndepchf,
									chfId,
									vehMat)
								VALUES
									('".$trnDepChf."',
									'".$chauffeur."',
									'".$vehicule."')";
				}
				$result=mysql_query($sqlInsert);
				$result=tableSQL($connexion, $sqlControl);

				$_SESSION['trnNumInfo']=$result[0]['trnNum'];

				header("location:AC12.php");
			}
			else
			{
				echo "<meta http-equiv='refresh' content='0;url=AC12.php?message=<font color=red>Tournée déjà éxistante</font>'>";
			}
		}
		else
		{
			echo "<meta http-equiv='refresh' content='0;url=AC12.php?message=<font color=red>Données manquantes</font>'>";
		}
	}
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
	        <title>Tournée</title>
	        <link rel="stylesheet" type="text/css" href="style/styleorgat.css"/>
	        <script type="text/javascript">
		        <?php
		        require 'javascript/calendrier.js';
		        require 'javascript/controle.js';
		        ?>
	        </script>
		</head>
		<body>
			<?php
			if(isset($_POST['trnNumInfo']) OR isset($_SESSION['trnNumInfo']))
			{
				if(isset($_POST['trnNumInfo']))
				{
					$trnNum=$_POST['trnNumInfo'];
					$_SESSION['trnNumInfo']=$trnNum;
				}
				else
				{
					$trnNum=$_SESSION['trnNumInfo'];
					if(isset($_SESSION['etpIdInfo']))
					{
						unset($_SESSION['etpIdInfo']);
					}
				}
				$sqlTournee=   "SELECT
									chfId,
									emplNom,
									emplPrenom,
									vehicule.vehMat,
									trnDepChf,
									trnCommentaire
								FROM
									employe,
									vehicule,
									tournee
								WHERE
									emplId=chfId
									AND
									vehicule.vehMat=tournee.vehMat
									AND
									trnNum=".$trnNum;
				$sqlTournee=tableSQL($connexion, $sqlTournee);
				?>
				<h3 id="header_Organiser_tournee">AC12 - Organiser les tournées - Liste des étapes de la tournée n° <?php echo $trnNum; ?></h3>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return isValidFormTourneeUpdate();" id="form_tournee">
					<div id="label_float">
						<label for="trnDepChf">Date <sup>(</sup>*<sup>)</sup>:</label>
						<br/>
						<br/>
						<label class='chauffeur'>Chauffeur <sup>(</sup>*<sup>)</sup>:</label>
						<br/>
						<br/>
						<label for="vehicule">Vehicule <sup>(</sup>*<sup>)</sup>:</label>
						<br/>
						<br/>
						<label for="trnDepChfHor">Pris en charge le :</label>
						<br/>
						<br/>
						<label for="commentaire">Commentaire :</label>
					</div>
					<div id="input_float">
						<input required name="trnDepChf" id="trnDepChf" type="text" value="<?php echo date("d/m/Y", strtotime($sqlTournee[0]['trnDepChf'])); ?>" onKeyPress="return isDateKey(event);"/>
						<div id="calendrierTrnDepChf"></div>
						<script type="text/javascript">
							calInit("calendrierTrnDepChf", "", "trnDepChf", "jsCalendar", "day", "selectedDay");
						</script>
						<br/>
						<div class="styled_select">
							<select required id="chauffeur" name="chauffeur">
								<?php
								$sqlChauffeurs="SELECT
													emplId,
													emplNom,
													emplPrenom
												FROM
													employe
												ORDER BY
													emplNom,
													emplPrenom
												ASC";
								$sqlChauffeurs=tableSQL($connexion, $sqlChauffeurs);

								foreach($sqlChauffeurs As $donnees)
								{
									if($sqlTournee[0]['chfId']==$donnees['emplId'])
									{
										?>
										<option value="<?php echo $donnees['emplId']; ?>" selected="true"><?php echo $donnees['emplNom'].' '.$donnees['emplPrenom']; ?></option>
										<?php
									}
									else
									{
										?>
										<option value="<?php echo $donnees['emplId']; ?>"><?php echo $donnees['emplNom'].' '.$donnees['emplPrenom']; ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<br />
						<div class="styled_select">
							<select required id="vehicule" name="vehicule">
								<?php
								$sqlVehicules= "SELECT
													vehMat
												FROM
													vehicule
												ORDER BY
													vehMat
												ASC";
								$sqlVehicules=tableSQL($connexion, $sqlVehicules);

								foreach($sqlVehicules As $donnees)
								{
									if($sqlTournee[0]['vehMat']==$donnees['vehMat'])
									{
										?>
										<option value="<?php echo $donnees['vehMat']; ?>" selected="true"><?php echo $donnees['vehMat']; ?></option>
										<?php
									}
									else
									{
										?>
										<option value="<?php echo $donnees['vehMat']; ?>"><?php echo $donnees['vehMat']; ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<br />
						<input name="trnDepChfHor" type="text" value="<?php echo date("d/m/y H:i", strtotime($sqlTournee[0]['trnDepChf'])); ?>" disabled="disabled"/>
						<br />
						<br />
						<textarea name="commentaire" type="text"><?php echo $sqlTournee[0]['trnCommentaire']; ?></textarea>
					</div>
					<div id="valid_float">
						<input required name="trnNumInfo" id="trnNumInfo" type="hidden" value="<?php echo $trnNum; ?>"/>
						<div id="erreur"></div>
						<input class="bouton_global" id="valider" name="maj" type="submit" value="Valider"/>
						<input class="bouton_global" id="retour" type="button" value="Retour" onClick="location='AC11.php'"/>
						<script type="text/javascript">
							function griser()
							{
								<?php
								$nbEtapes= "SELECT
												etpId
											FROM
												etape
											WHERE
												trnNum=".$trnNum;
								$nbEtapes=compteSQL($connexion, $nbEtapes);
								?>
								if(<?php echo $nbEtapes; ?>==0)
								{
									document.getElementById('retour').setAttribute('disabled', 'disabled');
									document.getElementById('retour').style.opacity=0.5;
								}
							}

							griser();
						</script>
					</div>
				</form>
				<div class="separateur_vertical">
				</div>
				<div id="etapes_tournee">
					<?php
					$sqlEtapes="SELECT
									etpId,
									comNom,
									lieuNom,
									trnNum
								FROM
									commune,
									lieu,
									etape
								WHERE
									commune.comId=lieu.comId
									AND
									lieu.lieuId=etape.lieuId
									AND
									trnNum=".$trnNum."
								ORDER BY
									etpRDV
								ASC";
					?>
					<table id="etapes_tournee">
						<tr>
							<th>
								Ordre
							</th>
							<th>
								Etapes
							</th>
						</tr>
						<?php
						$sqlEtapes=tableSQL($connexion, $sqlEtapes);
						$compteur=1;
						foreach($sqlEtapes As $donnees)
						{
							?>
							<tr>
								<td>
									<?php
									echo $compteur++;
									?>
								</td>
								<td>
									<?php
									echo $donnees['lieuNom']." ".$donnees['comNom'];
									?>
								</td>
								<td>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
										<input name="etpIdSup" type="hidden" value="<?php echo $donnees['etpId']; ?>"/>
										<input name="trnNumSup" type="hidden" value="<?php echo $donnees['trnNum']; ?>"/>
										<input name="suppr_bout_<?php echo $donnees['etpId']; ?>" id="suppr_bout_<?php echo $donnees['etpId']; ?>" class="suppr_form" type="submit"/>
									</form>
								</td>
								<td>
									<form action="AC13.php" method="POST">
										<input name="etpIdInfo" type="hidden" value="<?php echo $donnees['etpId']; ?>"/>
										<input name="trnNumInfo" type="hidden" value="<?php echo $donnees['trnNum']; ?>"/>
										<input name="modif_bout_<?php echo $donnees['etpId']; ?>" id="modif_bout_<?php echo $donnees['etpId']; ?>" class="modif_form" type="submit"/>
									</form>
								</td>
							</tr>
							<?php
						}

						if(isset($_GET['message']))
						{
							echo utf8_decode($_GET['message']);
						}
						?>
						<tr>
							<td>
								<form action="AC13.php" method="POST">
									<input type="hidden" name="trnNumInfo" value="<?php echo $trnNum; ?>"/>
									<input class="bouton_global" id="ajouter" type="submit" value="Ajouter"/>
								</form>
							</td>
						</tr>
					</table>
				</div>
				<?php
				}
				else
				{
					?>
					<div>
					<h3>AC12 - Organiser les tournées - Ajouter une tournée</h3>
					</div>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return isValidFormTourneeCreate();">
						<br/>
						<div id="label_float">
							<label for='chauffeur'>Chauffeur <sup>(</sup>*<sup>)</sup>:</label>
							<br/>
							<br/>
							<label for="vehicule">Vehicule <sup>(</sup>*<sup>)</sup>:</label>
							<br/>
							<br/>
							<label for="trnDepChf">Pris en charge le <sup>(</sup>*<sup>)</sup>:</label>
							<br/>
							<br/>
							<label for="commentaire">Commentaire :</label>
						</div>
						<div id="input_float">
							<select id="chauffeur" name='chauffeur'>
								<option selected value="NAN">Aucun chauffeur</option>
								<?php
								$sqlChauffeur= "SELECT
													emplId,
													emplNom,
													emplPrenom
												FROM
													employe
												WHERE
													emplcat='chauffeur'";
								$sqlChauffeur=tableSQL($connexion, $sqlChauffeur);
								foreach($sqlChauffeur As $row)
								{
									echo "<option value='$row[0]'>".$row[1]." ".$row[2]."</option>";
								}
								?>
							</select>
							<br/>
							<br/>
							<select id="vehicule" name="vehicule">
								<option selected value="NAN">Aucun véhicule</option>
								<?php
								$sqlPlaque="SELECT
												vehMat
											FROM
												vehicule";
								$sqlPlaque=tableSQL($connexion, $sqlPlaque);
								foreach($sqlPlaque As $row)
								{
									echo "<option value='".$row[0]."'>".$row[0]."</option>";
								}
								?>
							</select>
							<br/>
							<br/>
							<input type="text" name="trnDepChf" id="trnDepChf" onKeyPress="return isDateKey(event);"/>
							<div id="calendrierTrnDepChf"></div>
							<script type="text/javascript">
								calInit("calendrierTrnDepChf", "", "trnDepChf", "jsCalendar", "day", "selectedDay");
							</script>
							<br/>
							<textarea name="commentaire"></textarea>
						</div>
						<div id="valid_float">
							<div id="erreur"></div>
							<input class="bouton_global" id="valider" type="submit" name="creer" value="Valider"/>
							<input class="bouton_global" id="retour" type="button" onClick="location='AC11.php'" value="Retour"/>
						</div>
						<?php
						if(isset($_GET['message']))
						{
							echo utf8_decode($_GET['message']);
						}
						?>
					</form>
				</div>
				<?php
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