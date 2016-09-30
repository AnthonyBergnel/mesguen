<?php
require('structure/creationBase.php');
require('divers/divers.php');
require('peuplement/employe.php');
require('peuplement/vehicule.php');
require('peuplement/tournee.php');
require('peuplement/commune.php');
require('peuplement/lieu.php');
require('peuplement/etape.php');
require('peuplement/photo.php');


function creationStructureBase()
{
	creationBase();
	creationTableEmploye();
	creationTableVehicule();
	creationTableTournee();
	creationTableCommune();
	creationTableLieu();
	creationTableEtape();
	creationTablePhoto();
	triggerEtape();
}

function ajoutValeurBase()
{
	ajoutEmploye();
	ajoutVehicule();
	ajoutTournee();
	ajoutCommune();
	ajoutLieu();
	ajoutEtape();
	ajoutPhoto();
}

function main()
{
	creationStructureBase();
	ajoutValeurBase();
}

set_time_limit(6000);

main();
echo "Fin de la génération !";
?>