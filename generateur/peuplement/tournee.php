<?php
function ajoutTournee()
{
	require('divers/connectionPDOSelect.php');

	$requete=$bdd->query(  "SELECT 
								emplId
							FROM
								employe
							WHERE
								emplCat='Chauffeur'");

	$chauffeurs=$requete->fetchAll();

	$requete->closeCursor();
	$requete=$bdd->query(  "SELECT 
								vehMat
							FROM
								vehicule");

	$vehicules=$requete->fetchAll();

	$requete->closeCursor();

	$requete=$bdd->prepare("SELECT 
								trnNum
							FROM
								tournee
							WHERE
								chfId=:chfId
								AND
								vehMat=:vehMat
								AND
								trnDepChf=:depart");
	
	$requeteSeconde=$bdd->prepare( "INSERT INTO tournee
										(chfId,
										vehMat,
										trnCommentaire,
										trnDepChf)
									VALUES
										(:chfId,
										:vehMat,
										:commentaire,
										:depart)");

	for($i=0; $i<100; $i++)
	{
		echo $chauffeur=$chauffeurs[rand(0, sizeof($chauffeurs)-1)]['emplId'];

		echo $vehicule=$vehicules[rand(0, sizeof($vehicules)-1)]['vehMat'];

		echo $commentaire=trim(lipsum(1, 5, 20));

		$dates=triFichier("date");

		$heure=rand(0, 23);
		$minute=rand(0, 59);
		$seconde=rand(0, 59);

		if($heure<10)
		{
			$heure="0".$heure;
		}
		if($minute<10)
		{
			$minute="0".$minute;
		}
		if($seconde<10)
		{
			$seconde="0".$seconde;
		}

		$date=$dates[rand(0, sizeof($dates)-2)];
		echo $date=$date." ".$heure.":".$minute.":".$seconde;

		echo "<br />";

		$requete->execute(array("chfId"=>$chauffeur, "vehMat"=>$vehicule, "depart"=>$date));

		if($requete->fetch()==false)
		{
			$requeteSeconde->execute(array("chfId"=>$chauffeur, "vehMat"=>$vehicule, "commentaire"=>$commentaire, "depart"=>$date));
		}
		else
		{
			$i--;
		}
	}

	$requete->closeCursor();
	$requeteSeconde->closeCursor();
}
?>