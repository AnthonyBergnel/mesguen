<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Arrivée</title>
	</head>
	<body>
		<?php
		try
		{
			$bdd = new PDO ( "mysql:host=localhost;dbname=ppe_test" , "root" , ""  , array ( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );
		}
		catch ( Exception $e )
		{
			die ( "Erreur : " . $e -> getMessage () );
		}

		if ( !isset ( $_GET [ 'etpIdInfo' ] ) OR !isset ( $_GET [ 'etpIdCompt' ] ) )
		{
			header ( "location:ajoutEtp.php?trnNumInfo" . $_GET [ 'trnNumInfo' ] );
		}

		if ( isset ( $_GET [ 'palLiv' ] ) AND isset ( $_GET [ 'palLivEur' ] ) AND isset ( $_GET [ 'palChg' ] ) AND isset ( $_GET [ 'palChgEur' ] ) )
		{
			if ( $_GET [ 'palLiv' ] != "" AND $_GET [ 'palLivEur' ] != "" AND $_GET [ 'palChg' ] != "" AND $_GET [ 'palChgEur' ] != "" )
			{
				/*UPLOAD*/

				$requete = $bdd -> prepare (   "UPDATE
													etape
												SET
													etpNbPalLiv = :palLiv ,
													etpNbPalLivEur = :palLivEur ,
													etpNbPalChg = :palChg ,
													etpNbPalChgEur = :palChgEur
												WHERE
													etpId = :etpId" );

				$requete -> execute ( array ( "palLiv" => $_GET [ 'palLiv' ] , "palLivEur" => $_GET [ 'palLivEur' ] , "palChg" => $_GET [ 'palChg' ] , "palChgEur" => $_GET [ 'palChgEur' ] , "etpId" => $_GET [ 'etpIdInfo' ] ) );

				$requete -> closeCursor ();

				header ( "location:ajoutEtp.php?trnNumInfo=" . $_GET [ 'trnNumInfo' ] );
			}
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" enctype="multipart/form-data">
			<h2>Étape : <?php echo $_GET [ 'trnNumInfo' ] . " " . $_GET [ 'etpIdCompt' ]; ?></h2>
			<br />
			<p>Le <?php echo date("d-m-Y"); ?> à <?php echo date("H:i:s"); ?></p>
			<br />
			<h2>Palette(s) :</h2>
			<br />
			<label for="palLiv">Livrées : </label>
			<input type="number" min="0" max="60" name="palLiv" id="palLiv"/>
			<label for="palLivEur"> dont EUR : </label>
			<input type="number" min="0" max="60" name="palLivEur" id="palLivEur"/>
			<br />
			<label for="palChg">Chargées : </label>
			<input type="number" min="0" max="60" name="palChg" id="palChg"/>
			<label for="palChgEur"> dont EUR : </label>
			<input type="number" min="0" max="60" name="palChgEur" id="palChgEur"/>
			<br />
			<label for="photo">Photos : </label>
			<input type="file" name="photo" id="photo"/>
			<br />
			<input type="hidden" name="trnNumInfo" value="<?php echo $_GET [ 'trnNumInfo' ]; ?>"/>
			<input type="hidden" name="etpIdInfo" value="<?php echo $_GET [ 'etpIdInfo' ]; ?>"/>
			<input type="submit" value="Valider" name="valider_arrivee_etape" id="valider"/>
		</form>
	</body>
</html>