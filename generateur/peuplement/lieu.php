<?php
function ajoutLieu()
{
	require('divers/connectionPDOSelect.php');

	$adresses=file('ressources/adresse.txt');

	$requete=$bdd->query(  "SELECT 
								comId
							FROM
								commune");

	$villes=$requete->fetchAll();

	$requete->closeCursor();

	$requete=$bdd->prepare("SELECT 
								lieuId
							FROM
								lieu
							WHERE
								comId=:ville
								AND
								lieuNom=:nom
								AND
								lieuAdresse=:adresse");

	$requeteSeconde=$bdd->prepare( "INSERT INTO lieu
										(comId,
										lieuNom,
										lieuAdresse,
										lieuTel,
										lieuMail,
										lieuGps)
									VALUES
										(:ville,
										:nom,
										:adresse,
										:telephone,
										:mail,
										:gps)");

	for($i=0; $i<100; $i++)
	{
		echo $ville=$villes[rand(0, sizeof($villes)-1)]['comId'];

		echo $nom=nomAleatoire();

		echo $adresse=$adresses[rand(0, sizeof($adresses)-1)];

		$numero=array();

		for($j=0; $j<8; $j++)
		{
			$numero[$j]=rand(0, 9);
		}

		$telephone=implode("", $numero);
		echo $telephone="02".$telephone;

		echo $mail=$nom.".contact@".$nom.".com";

		echo $gps=rand(0, 5000);

		echo "<br />";

		$requete->execute(array("ville"=>$ville, "nom"=>$nom, "adresse"=>$adresse));

		if($requete->fetch()==false)
		{
			$requeteSeconde->execute(array("ville"=>utf8_encode($ville), "nom"=>utf8_encode($nom), "adresse"=>utf8_encode($adresse), "telephone"=>$telephone, "mail"=>utf8_encode($mail), "gps"=>$gps));
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