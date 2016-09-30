<?php
function ajoutEtape()
{
	require('divers/connectionPDOSelect.php');

	$requete=$bdd->query(  "SELECT 
								trnNum,
								trnDepChf
							FROM
								tournee");

	$tournees=$requete->fetchAll();

	$requete->closeCursor();
	$requete=$bdd->query(  "SELECT 
								lieuId
							FROM
								lieu");

	$lieux=$requete->fetchAll();

	$requete->closeCursor();

	$requete=$bdd->prepare("SELECT 
								etpId
							FROM
								etape
							WHERE
								trnNum=:tournee
								AND
								lieuId=:lieu
								AND
								etpRDV=:rdv");

	$requeteSeconde=$bdd->prepare( "INSERT INTO etape
										(trnNum,
										lieuId,
										etpRDV)
									VALUES
										(:tournee,
										:lieu,
										:rdv)");

	for($i=0; $i<750; $i++)
	{
		$ligneTournee=rand(0, sizeof($tournees)-1);
		echo $tournee=$tournees[$ligneTournee]['trnNum'];

		echo $lieu=$lieux[rand(0, sizeof($lieux)-1)]['lieuId'];

		$rdv=$tournees[$ligneTournee]['trnDepChf'];
		$rdv=new DateTime($rdv);
		$rdv->modify("+".rand(0,400000)." seconde");
		echo $rdv=date_format($rdv, 'Y-m-d H:i:s');
		
		$requete->execute(array("tournee"=>$tournee, "lieu"=>$lieu, "rdv"=>$rdv));

		if($requete->fetch()==false)
		{
			$requeteSeconde->execute(array("tournee"=>$tournee, "lieu"=>$lieu, "rdv"=>$rdv));
		}
		else
		{
			$i--;
		}

		echo "<br />";
	}

	$requete->closeCursor();
	$requeteSeconde->closeCursor();
}
?>