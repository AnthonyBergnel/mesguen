<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <title>Ajout Etape</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<?php
		try
		{
			$bdd = new PDO ( "mysql:host=localhost;dbname=mesguen" , "root" , ""  , array ( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );
		}
		catch ( Exception $e )
		{
			die ( "Erreur : " . $e -> getMessage () );
		}

		if ( !isset ( $_GET [ 'trnNumInfo' ] ) )
		{
			header ( "location:ajoutTrn.php" );
		}

		if ( isset ( $_GET [ 'etpEtatModif' ] ) AND isset ( $_GET [ 'etpIdInfo' ] ) )
		{
			if ( $_GET [ 'etpEtatModif' ] == 1 )
			{
				$requete = $bdd -> prepare (   "UPDATE
													etape
												SET
													etpEtat = :etpEtat ,
													etpDteHreDepart = NOW()
												WHERE
													etpId = :etpId" );
				$requete -> execute ( array ( "etpEtat" => $_GET [ 'etpEtatModif' ] , "etpId" => $_GET [ 'etpIdInfo' ] ) );

				$requete -> closeCursor ();

				header ( "location:" . $_SERVER['PHP_SELF'] . "?trnNumInfo=" . $_GET [ 'trnNumInfo' ] );
			}
			elseif ( $_GET [ 'etpEtatModif' ] == 2 )
			{
				$requete = $bdd -> prepare (   "UPDATE
													etape
												SET
													etpEtat = :etpEtat ,
													etpDteHreArrivee = NOW()
												WHERE
													etpId = :etpId" );
				$requete -> execute ( array ( "etpEtat" => $_GET [ 'etpEtatModif' ] , "etpId" => $_GET [ 'etpIdInfo' ] ) );

				$requete -> closeCursor ();

				header ( "location:arrivEtp.php?etpIdInfo=" . $_GET [ 'etpIdInfo' ] . "&trnNumInfo=" . $_GET [ 'trnNumInfo' ] . "&etpIdCompt=" . $_GET [ 'etpIdCompt' ] );
			}
		}

		if ( isset ( $_GET [ 'etpSup' ] ) )
		{
			$requete = $bdd -> prepare (   "DELETE FROM 
													etape
												WHERE
													etpId = :etpId" );
			$requete -> execute ( array ( "etpId" => $_GET [ 'etpSup' ] ) );

			$requete -> closeCursor ();

			header ( "location:" . $_SERVER['PHP_SELF'] . "?trnNumInfo=" . $_GET [ 'trnNumInfo' ] );
		}

		if ( isset ( $_GET [ 'lieuId' ] ) AND isset ( $_GET [ 'etpRDV' ] ) )
		{
			if ( $_GET [ 'lieuId' ] != "NAN" AND $_GET [ 'etpRDV' ] != "" )
			{
				$requete = $bdd -> prepare (   "SELECT 
													lieuId
												FROM
													etape
												WHERE
													lieuId = :lieuId
													AND
													etpRDV = :etpRDV" );
				$requete -> execute ( array ( "lieuId" => $_GET [ 'lieuId' ] , "etpRDV" => $_GET [ 'etpRDV' ] ) );

				if ( $requete -> fetch () == false )
				{
					$requeteSeconde = $bdd -> prepare (    "INSERT INTO etape
																( trnNum ,
																lieuId ,
																etpRDV )
															VALUES
																( :trnNum ,
																(   SELECT
																		lieuId
																	FROM
																		lieu
																	WHERE
																		lieuId = :lieuId ) ,
																:etpRDV )" );

					$requeteSeconde -> execute ( array ( "trnNum" => $_GET [ 'trnNumInfo' ] , "lieuId" => $_GET [ 'lieuId' ] , "etpRDV" => $_GET [ 'etpRDV' ] ) );

					$requeteSeconde -> closeCursor ();
				}

				$requete -> closeCursor ();
			}

			header ( "location:" . $_SERVER['PHP_SELF'] . "?trnNumInfo=" . $_GET [ 'trnNumInfo' ] );
		}

		$requete = $bdd -> prepare ( "SELECT
										etpId ,
										etpRDV ,
										etpEtat ,
										comNom ,
										commune.comId ,
										lieuNom
									FROM
										commune ,
										lieu ,
										tournee ,
										etape
									WHERE
										commune.comId = lieu.comId
										AND
										lieu.lieuId = etape.lieuId
										AND
										tournee.trnNum = etape.trnNum
										AND
										tournee.trnNum = :trnNum
									ORDER BY
										etpRDV
									ASC" );
		$requete -> execute ( array ( "trnNum" => $_GET [ 'trnNumInfo' ] ) );
		?>
		<table>
			<legend><h3>Liste des étapes de la tournée n° <?php echo $_GET [ 'trnNumInfo' ]; ?></h3></legend>
			<tr>
				<th>
					Numéro
				</th>
				<th>
					Lieu
				</th>
			</tr>
			<?php
				$compteur = 1;
				while ( $donnees = $requete -> fetch () )
				{
					?>
					<tr>
						<td>
							<?php
							echo $compteur;
							?>
						</td>
						<td>
							<?php
							echo $donnees [ 'lieuNom' ] . " " . $donnees [ 'comId' ] . " (" . $donnees [ 'comNom' ] . ")";
							?>
						</td>
						<td>
							<?php
							switch ( $donnees [ 'etpEtat' ] )
							{
								case 0 :
									$drap = "drp0";
									$etat = 1;
									break;
								case 1 :
									$drap = "drp1";
									$etat = 2;
									break;
								case 2 :
									$drap = "drp2";
									$etat = 2;
									break;
								default :
									$drap = "erreur";
									break;
							}
							?>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
								<input name="etpEtatModif" type="hidden" value="<?php echo $etat; ?>"/>
								<input name="trnNumInfo" type="hidden" value="<?php echo $_GET [ 'trnNumInfo' ]; ?>"/>
								<input name="etpIdInfo" type="hidden" value="<?php echo $donnees [ 'etpId' ]; ?>"/>
								<input name="etpIdCompt" type="hidden" value="<?php echo $compteur; ?>"/>
								<input name="modif_etat_bout_<?php echo $donnees [ 'etpId' ]; ?>" id="modif_etat_bout_<?php echo $donnees [ 'etpId' ]; ?>" class="modif_etat_<?php echo $drap; ?>_form" type="submit"/>
							</form>
						</td>
						<?php
						if ( $donnees [ 'etpEtat' ] != 2 )
						{
							?>
							<td>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
									<input name="etpSup" type="hidden" value="<?php echo $donnees [ 'etpId' ]; ?>"/>
									<input name="trnNumInfo" type="hidden" value="<?php echo $_GET [ 'trnNumInfo' ]; ?>"/>
									<input name="suppr_bout_<?php echo $donnees [ 'etpId' ]; ?>" id="suppr_bout_<?php echo $donnees [ 'etpId' ]; ?>" class="suppr_form" type="submit"/>
								</form>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					$compteur++;
				}
			?>
		</table>
		<?php
		$requete -> closeCursor ();
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
			<fieldset>
				<legend>Ajouter une étape</legend>
				<label for="lieuId">Lieu* :</label>
				<select name="lieuId" id="lieuId" required>
					<option value="NAN">Aucun lieu sélectionné</option>
					<?php
					$requete = $bdd -> query ( "SELECT
													comNom ,
													lieuId ,
													lieuNom
												FROM
													commune ,
													lieu
												WHERE
													commune.comId = lieu.comId
												ORDER BY
													lieuNom ,
													comNom
												ASC" );

					while ( $donnees = $requete -> fetch () )
					{
						?>
						<option value="<?php echo $donnees [ 'lieuId' ]; ?>"><?php echo $donnees [ 'lieuNom' ] . ' - ' . $donnees [ 'comNom' ]; ?></option>
						<?php
					}

					$requete -> closeCursor ();
					?>
				</select>
				<br />
				<label for="etpRDV">Date* :</label>
				<input name="etpRDV" id="etpRDV" type="text" required/>
				<br />
			</fieldset>
			<input name="trnNumInfo" type="hidden" value="<?php echo $_GET [ 'trnNumInfo' ]; ?>"/>
			<input name="ajout_etape" id="ajout_etape" value="Ajouter" type="submit"/>
			<?php
		?>
		</form>
		<?php
		?>
		<a href="ajoutTrn.php">at</a>
		<a href="ajoutChauff.php">ach</a>
		<a href="ajoutVeh.php">avh</a>
		<a href="ajoutCom.php">aco</a>
		<a href="ajoutlieu.php">ali</a>
	</body>
</html>