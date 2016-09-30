<?php
function creationBase()
{
	try
	{
		$bdd=new PDO("mysql:host=localhost", "root", "", array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	}
	catch(Exception $e)
	{
		die("Erreur : ".$e->POSTMessage());
	}

	$bdd->exec("CREATE DATABASE IF NOT EXISTS mesguen DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci");
}

function creationTableEmploye()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS employe
				(
					emplId INT(11) NOT NULL AUTO_INCREMENT,
					emplNom VARCHAR(60) NOT NULL,
					emplPrenom VARCHAR(60) NOT NULL,
					emplTel VARCHAR(10) NOT NULL,
					emplMail VARCHAR(255) NOT NULL,
					emplLoggin VARCHAR(255) NOT NULL,
					emplMdp VARCHAR(255) NOT NULL,
					emplCat VARCHAR(255 ) NOT NULL,
					emplGps VARCHAR(255) NULL,
					CONSTRAINT pk_employe PRIMARY KEY(emplId)
				)engine=InnoDB default charset=utf8");
}

function creationTableVehicule()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS vehicule
				(
					vehMat VARCHAR(10) NOT NULL,
					vehMarque VARCHAR(60) NOT NULL,
					vehKmCompteur INT(11) NOT NULL,
					CONSTRAINT pk_vehicule PRIMARY KEY(vehMat)
				)engine=InnoDB default charset=utf8");
}

function creationTableTournee()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS tournee
				(
					trnNum INT(11) NOT NULL AUTO_INCREMENT,
					chfId INT(11) NOT NULL,
					vehMat VARCHAR(10) NOT NULL,
					trnCommentaire VARCHAR(255) NULL,
					trnDepChf DATETIME NOT NULL,
					CONSTRAINT pk_tournee PRIMARY KEY(trnNum),
					CONSTRAINT fk_tournee_empl FOREIGN KEY(chfId) REFERENCES employe(emplId) ON DELETE CASCADE ON UPDATE CASCADE,
					CONSTRAINT fk_tournee_vehicule FOREIGN KEY(vehMat) REFERENCES vehicule(vehMat) ON DELETE CASCADE ON UPDATE CASCADE
				)engine=InnoDB default charset=utf8");
}

function creationTableCommune()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS commune
				(
					comId VARCHAR(11) NOT NULL,
					comNom VARCHAR(32) NOT NULL,
					CONSTRAINT pk_commune PRIMARY KEY(comId)
				)engine=InnoDB default charset=utf8");
}

function creationTableLieu()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS lieu
				(
					lieuId INT(11) NOT NULL AUTO_INCREMENT,
					comId VARCHAR(11) NOT NULL,
					lieuNom VARCHAR(30) NOT NULL,
					lieuAdresse VARCHAR(60) NOT NULL,
					lieuTel VARCHAR(10) NOT NULL,
					lieuMail VARCHAR(30) NOT NULL,
					lieuGps VARCHAR(255) NULL,
					CONSTRAINT pk_lieu PRIMARY KEY(lieuId),
					CONSTRAINT fk_lieu_commune FOREIGN KEY(comId) REFERENCES commune(comId) ON DELETE CASCADE ON UPDATE CASCADE
				)engine=InnoDB default charset=utf8");
}

function creationTableEtape()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS etape
				(
					etpId INT(11) NOT NULL,
					trnNum INT(11) NOT NULL,
					lieuId INT(11) NOT NULL,
					etpRDV DATETIME NOT NULL,
					etpRDVMin DATETIME NULL,
					etpRDVMax DATETIME NULL,
					etpHreArrivee DATETIME NULL,
					etpHreFin DATETIME NULL,
					etpCommentaire VARCHAR(255) NULL,
					etpNbPalLiv TINYINT(4) NULL,
					etpNbPalLivEur TINYINT(4) NULL,
					etpNbPalChg TINYINT(4) NULL,
					etpNbPalChgEur TINYINT(4) NULL,
					etpEtat TINYINT(1) NOT NULL DEFAULT 0,
					etpCheque TINYINT(4) NULL,
					CONSTRAINT pk_etape PRIMARY KEY(etpId, trnNum),
					CONSTRAINT fk_etape_lieu FOREIGN KEY(lieuId) REFERENCES lieu(lieuId) ON DELETE CASCADE ON UPDATE CASCADE,
					CONSTRAINT fk_etape_tournee FOREIGN KEY(trnNum) REFERENCES tournee(trnNum) ON DELETE CASCADE ON UPDATE CASCADE
				)engine=InnoDB default charset=utf8");
}

function creationTablePhoto()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TABLE IF NOT EXISTS photo
				(
					photoId INT(11) NOT NULL AUTO_INCREMENT,
					etpId INT(11) NOT NULL,
					trnNum INT(11) NOT NULL,
					etpPhoto VARCHAR(60) NOT NULL,
					CONSTRAINT pk_photo PRIMARY KEY(photoId) ,
					CONSTRAINT fk_photo_etape FOREIGN KEY(etpId, trnNum) REFERENCES etape(etpId, trnNum) ON DELETE CASCADE ON UPDATE CASCADE
				) engine=InnoDB default charset=utf8");
}

function triggerEtape()
{
	require('divers/connectionPDOInsert.php');

	$bdd->exec("CREATE TRIGGER triggerEtape 
			    	BEFORE INSERT ON etape  
			    	FOR EACH ROW
				BEGIN
					DECLARE num INTEGER;
					IF (NEW.etpId IS NULL OR NEW.etpId=0) THEN
						SET num= 
					    (
					    	SELECT COALESCE(MAX(etpId), 0)+1
					    	FROM etape
					    	WHERE trnNum=NEW.trnNum
					    );
				    	SET NEW.etpId=num;
				  	END IF;
				END");
}
?>