<?php
function ajoutPhoto()
{
	require('divers/connectionPDOSelect.php');

	$requete=$bdd->query(  "SELECT 
								etpId,
								trnNum
							FROM
								etape");

	$etapes=$requete->fetchAll();

	$requete->closeCursor();


	$requeteSeconde=$bdd->prepare("INSERT INTO photo
								(etpId,
								trnNum,
								etpPhoto)
							VALUES
								(:etape,
								:tournee,
								:photo)");

	for($i=0; $i<100; $i++)
	{
		$ligne=rand(0, sizeof($etapes)-1);
		echo $etape=$etapes[$ligne]['etpId'];

		echo $tournee=$etapes[$ligne]['trnNum'];

		$requete=$bdd->query(  "SELECT 
									MAX(photoId) AS photo
								FROM
									photo");

		$idPhoto=$requete->fetchAll();

		if($idPhoto[0]['photo']==NULL)
		{
			echo $photo="photo_1";
		}
		else
		{
			echo $photo="/photo/photo_".($idPhoto[0]['photo']+1);
		}

		$requete->closeCursor();

		$requeteSeconde->execute(array("etape"=>$etape, "tournee"=>$tournee, "photo"=>$photo));

		echo "<br />";
	}

	$requeteSeconde->closeCursor();
}
?>