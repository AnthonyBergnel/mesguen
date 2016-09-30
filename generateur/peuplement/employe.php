<?php
function ajoutEmploye()
{
	require('divers/connectionPDOSelect.php');

	$garcons=file('ressources/garcon.txt');
	$filles=file('ressources/fille.txt');
	$noms=file('ressources/nom.txt');

	$requete=$bdd->prepare("SELECT 
								emplId
							FROM
								employe
							WHERE
								emplNom=:nom
								AND
								emplPrenom=:prenom");

	$requeteSeconde=$bdd->prepare( "INSERT INTO employe
										(emplNom,
										emplPrenom,
										emplTel,
										emplMail,
										emplLoggin,
										emplMdp,
										emplCat,
										emplGps)
									VALUES
										(:nom,
										:prenom,
										:tel,
										:mail,
										:loggin,
										:mdp,
										:cat,
										:gps)");

	for($i=0; $i<100; $i++)
	{
		$filleOuGarcon=rand(0, 1);

		if($filleOuGarcon==0)
		{
			echo $prenom=trim($filles[rand(0, sizeof($filles)-1)]);
		}
		else
		{
			echo $prenom=trim($garcons[rand(0, sizeof($garcons)-1)]);
		}

		echo $nom=trim($noms[rand(0, sizeof($noms)-1)]);

		$numero=array();

		for($j=0; $j<8; $j++)
		{
			$numero[$j]=rand(0, 9);
		}

		$telephone=implode("", $numero);
		echo $telephone="06".$telephone;

		echo $mail=strtolower($nom).".".strtolower($prenom)."@domaine.com";

		echo $loggin=strtolower($nom).".".strtolower($prenom);

	    echo $mdp=nomAleatoire();

		$choixCategorie=rand(0, 1);

		if($choixCategorie==0)
		{
			echo $categorie="Chauffeur";
			echo $GPS=rand(0, 5000);
		}
		else
		{
			echo $categorie="Exploitant";
			echo $GPS=0;
		}

		echo "<br />";

		$requete->execute(array("nom"=>$nom, "prenom"=>$prenom));

		if($requete->fetch()==false)
		{
			$requeteSeconde->execute(array("nom"=>utf8_encode($nom), "prenom"=>utf8_encode($prenom), "tel"=>$telephone, "mail"=>utf8_encode($mail), "loggin"=>utf8_encode($loggin), "mdp"=>$mdp, "cat"=>$categorie, "gps"=>$GPS));
		}
		else
		{
			$i--;
		}
	}

	$requete->closeCursor ();
	$requeteSeconde->closeCursor();
}
?>