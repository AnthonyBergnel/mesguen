<?php
function motAleatoire()
{
    $taille=rand(1, 12);
    $c1=array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z', 'dd', 'ff', 'll', 'mm', 'nn', 'pp', 'rr', 'ss', 'tt');
    $c2=array('a', 'a', 'a', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'y');

    $code="";
    for($i=1; $i<$taille; $i++)
    {
    	$code.=($i%2==0)?$c1[rand(0, count($c1)-1)]:$c2[rand(0, count($c2)-1)];
    }

    return $code;
}

function lipsum ($nbParag, $nbMotMin, $nbMotMax)
{
	$nbMotParag=rand($nbMotMin, $nbMotMax);

	$texte="";
	for($i=0; $i<$nbParag; $i++)
	{
		$texte.="";
        for($j=1; $j<$nbMotParag; $j++)
        {
        	$texte.=motAleatoire()." ";
        }
    }

	return ($texte);
}

function nomAleatoire()
{
    $taille=rand(3, 12);
    $c1=array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z', 'dd', 'ff', 'll', 'mm', 'nn', 'pp', 'rr', 'ss', 'tt');
    $c2=array('a', 'a', 'a', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'y');

    $code="";
    for($i=1; $i<$taille; $i++)
    {
    	$code.=($i%2==0)?$c1[rand(0, count($c1)-1)]:$c2[rand(0, count($c2)-1)];
    }

    return $code;
}

function triFichier($fichierATrier)
{
	$liste=file('ressources/'.$fichierATrier.'.txt');
	$taille=sizeof($liste);

	sort($liste);
	$liste=array_unique($liste);

	$listeTri=array();
	$i=0;
	$j=0;

	while($taille>=$i)
	{
		if(isset($liste[$i]))
		{
			$listeTri[$j]=$liste[$i];
			$j++;
		}
		$i++;	
	}

	return $listeTri;
}
?>