<?php
session_start ();

//Fichier de connexion requis afin de pouvoir se connecter à la BDD
//MySql
require 'utilitaires/connection/connection_mysql.php';

//Vérifie l'existance des entrées au chargement de la page (check du login et du password)
//Si les champs d'entrée sont remplis, la condition est vérifiée
if(isset($_POST['loggin']) AND isset($_POST['mdpL']))
{
	//Destruction des espaces inutiles au début et à la fin des entrées
	$loggin=trim($_POST['loggin']);
	$mdpL=trim($_POST['mdpL']);

	//Vérifie si les chaines obtenues sont vides
	//Si les chaines ne sont pas vides, la condition est vérifiée
	if($loggin!="" AND $mdpL!="")
	{
		//Prépartation de la requête permettant de vérifier si un enregistrement avec le loggin et password précédents existe
		$verifCompte=  "SELECT 
							emplLoggin,
							emplMdp
						FROM
							employe
						WHERE
							emplLoggin='".$loggin."'
							AND
							emplMdp='".$mdpL."'";
		//Vérifie l'existance de l'enregistrement
		//Si l'enregistrement existe, la condition est validée
		if(compteSQL($connexion, $verifCompte)!=0)
		{
			//Prépartation de la requête permettant de récupérer les informations nécessaires par rapport aux loggin
			$compteUtil=   "SELECT 
								emplId,
								emplNom,
								emplPrenom,
								emplCat
							FROM
								employe
							WHERE
								emplLoggin='".$loggin."'
								AND
								emplMdp='".$mdpL."'";
			//Stockage des données
			$compteUtil=tableSQL($connexion, $compteUtil);

			$_SESSION['emplId']=$compteUtil[0]['emplId'];
			$_SESSION['emplNom']=$compteUtil[0]['emplNom'];
			$_SESSION['emplPrenom']=$compteUtil[0]['emplPrenom'];
			$_SESSION['emplCat']=$compteUtil[0]['emplCat'];

			//redirection vers l'accueil
			header("location:accueil.php");
		}
		//Message en cas d'échec de connexion
		echo "<meta http-equiv='refresh' content='0;url=index.php?message=<font color=red>Identifiant ou mot de passe incorrects</font>'>";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mesguen</title>
		<link rel='stylesheet' type='text/css' href='style/style.css'/>
	</head>
	<body>
		<!-- Le formulaire envois les données sur cette page en methode POST -->
		<form class='connexion' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<?php
			//Récupération du message d'erreur
			if(isset($_GET['message']))
			{
				echo $_GET['message'];
			}
			?>
			<fieldset class='connexion'>
				<legend>Connexion</legend>
				<label class='identifiant'>Identifiant</label>
				<input name="loggin" id="loggin" type="text" class='identifiant' required/>
				<br />
				<label class='mdp'>Mot de passe</label>
				<input name="mdpL" id="mdpL" type="password" class='mdp' required/>
				<br />
				<input name="valid" class='bouton5' id="valid" value="Se connecter" type="submit"/>
				<br />
			</fieldset>
		</form>
		<img class='mesguen' src='images/Mesguen.jpg'/>
	</body>
</html>