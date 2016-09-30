function isDateKey(evt)
{
	var charCode=(evt.which)?evt.which:event.keyCode;
	if(charCode>0 && (charCode<0 || charCode>128 || charCode))
		return false;
	return true;
}

function isValidFormTourneeUpdate()
{
	var valeur1=document.getElementById('trnDepChf').value;
	var valeur2=document.getElementById('chauffeur').value;
	var valeur3=document.getElementById('vehicule').value;
	var valeur4=document.getElementById('trnNumInfo').value;
	if(valeur1!='' && valeur2!='' && valeur3!='' && valeur4!='')
	{
		return true;
	}
	else
	{
		document.getElementById('erreur').innerHTML="Veuillez completer tous les champs obligatoires";
		document.getElementById('erreur').style.color='red';
		return false;
	}
}

function isValidFormTourneeCreate()
{
	var valeur1=document.getElementById('trnDepChf').value;
	var valeur2=document.getElementById('chauffeur').value;
	var valeur3=document.getElementById('vehicule').value;
	if(valeur1!='' && valeur2!='' && valeur2!='NAN' && valeur3!='' && valeur3!='NAN')
	{
		return true;
	}
	else
	{
		document.getElementById('erreur').innerHTML="Veuillez completer tous les champs obligatoires";
		document.getElementById('erreur').style.color='red';
		return false;
	}
}

function isValidFormEtapeUpdate()
{
	var valeur1=document.getElementById('lieu').value;
	var valeur2=document.getElementById('etpRDV').value;
	var valeur3=document.getElementById('trnNumInfo').value;
	var valeur4=document.getElementById('etpIdInfo').value;
	if(valeur1!='' && valeur1!='NAN' && valeur2!='' && valeur3!='' && valeur4!='')
	{
		return true;
	}
	else
	{
		document.getElementById('erreur').innerHTML="Veuillez completer tous les champs obligatoires";
		document.getElementById('erreur').style.color='red';
		return false;
	}
}

function isValidFormEtapeCreate()
{
	var valeur1=document.getElementById('lieu').value;
	var valeur2=document.getElementById('etpRDV').value;
	var valeur3=document.getElementById('trnNumInfo').value;
	if(valeur1!='' && valeur1!='NAN' && valeur2!='' && valeur3!='')
	{
		return true;
	}
	else
	{
		document.getElementById('erreur').innerHTML="Veuillez completer tous les champs obligatoires";
		document.getElementById('erreur').style.color='red';
		return false;
	}
}