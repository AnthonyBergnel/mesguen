<?php
function ajoutCommune()
{
	require('divers/connectionPDOSelect.php');

	$villes=file('ressources/ville.txt');
	$codes=file('ressources/code.txt');

	$requete=$bdd->prepare("INSERT INTO commune
								(comId,
								comNom)
							VALUES
								(:comId,
								:comNom)");

	for($i=0; $i<sizeof($villes); $i++)
	{
		echo $code=trim($codes[$i]);
		echo $ville=trim($villes[$i]);

		echo "<br />";

		$requete->execute(array("comId"=>$code, "comNom"=>utf8_encode($ville)));
	}

	$requete->closeCursor();
}
?>