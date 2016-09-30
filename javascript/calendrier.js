function jsSimpleDatePickr(id){
	var me = this;
	me.dateDisp = new Date();
	me.dateSel = new Date();
	me.dayOrder = '1234560';
	me.dayName = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
	me.id = id;
	me.funcDateClic = me.classTable = me.classTd = me.classSelection = '';
	me.setDate = function(dateStr){
		if(!dateStr) return 0;
		var dateArr = dateStr.split('/');
		if(isNaN(dateArr[0])) return 0;
		today = new Date();
		if(isNaN(dateArr[1])) dateArr[1] = today.getMonth();
		else dateArr[1] = parseInt(dateArr[1], 10)-1;
		if(isNaN(dateArr[2])) dateArr[2] = today.getFullYear();
		else if(parseInt(dateArr[2], 10)<100) dateArr[2] = parseInt(dateArr[2], 10)+2000;
		me.dateSel = new Date(dateArr[2], dateArr[1], dateArr[0]);
		me.dateDisp = new Date(dateArr[2], dateArr[1], dateArr[0]);
	}
	me.setMonth = function(val){
		var v = parseInt(val, 10);
		if(val.charAt(0)=='+' || val.charAt(0)=='-') v = me.dateDisp.getMonth()+v;
		me.dateDisp.setMonth(v);
	}
	me.setYear = function(val){
		var v = parseInt(val, 10);
		if(val.charAt(0)=='+' || val.charAt(0)=='-') v = me.dateDisp.getFullYear()+v;
		me.dateDisp.setFullYear(v);
	}
	me.show = function(){
		var nb = today = 0;
		var month = me.dateDisp.getMonth();
		var year = me.dateDisp.getFullYear();
		if(month==me.dateSel.getMonth() && year==me.dateSel.getFullYear()) today = me.dateSel.getDate();
		var h = '<table class="'+me.classTable+'"><tr>';
		for(var i=0; i<7; i++){
			h += '<th>'+me.dayName[me.dayOrder[i]]+'</th>';
		}
		h += '</tr><tr>';
		var d = new Date(year, month, 1);
		for(nb=0; nb<me.dayOrder.indexOf(d.getDay()); nb++){
			h += '<td> </td>';
		}
		d.setMonth(month+1, 0);
		for(i=1; i<=d.getDate(); i++){
			nb++;
			if(nb>7){
				nb = 1;
				h += '</tr><tr>';
			}
			h += '<td class="'+(i==today ? me.classSelection:me.classTd)+'"><a href="#"'+(me.funcDateClic!='' ? ' onclick="'+me.funcDateClic+'(\''+i+'/'+(month+1)+'/'+year+'\', \''+me.id+'\');return false;"':'')+'>'+i+'</a></td>';
		}
		for(i=nb; i<7; i++){
			h += '<td> </td>';
		}
		h += '</tr></table>';
		document.getElementById(me.id).innerHTML = h
	}
}
// calInit
//
// initialise 
//
// divId = identifiant du bloc <div> qui va contenir le calendrier
// btName = nom qui sera afcihÃ© sur le bouton pour afficher / masquer la calendrier (facultatif)
// fieldId = identifiant du champ dans lequel sera affichÃ© la date
// classTable = class du tableau
// classTable = class des <tr>
// classSel = class de la date sÃ©lectionnÃ©
//
function calInit(divId, btName, fieldId, classTable, classDay, classSel){
	calDiv = document.getElementById(divId);
	dateEl = document.getElementById(fieldId);
	// vÃ©rifie l'existance de divId et fieldId
	if(calDiv==undefined || dateEl==undefined) return 0;
	var h = "";
	// si btName est dÃ©finit, un bouton est crÃ©er. En cliquant sur ce bouton le calendrier est affichÃ© / masquÃ©
	// si btName n'est pas dÃ©finit, on attache la fonction calToogle au champ de texte qui contiendra la date
	if(btName=="") dateEl.addEventListener('click', function(){	calToogleFromField(fieldId); }, false);
	else h = '<input type="button" value="'+btName+'" onclick="calToogle('+jsSDPId+');" />';
	// crÃ©er un bloc div qui contient des boutons de navigation, le titre et le bloc dans lequel sera affichÃ© le calendrier
	h += '<div id="calendarWrap'+jsSDPId+'" class="calendarWrap"><ul><li><input type="button" value="&lsaquo;" onclick="calMonthNav('+jsSDPId+', \'-1\');" /></li><li id="calendarTitle'+jsSDPId+'" class="calendarTitle"></li><li><input type="button" value="&rsaquo;" onclick="calMonthNav('+jsSDPId+', \'+1\');" /></li></ul><div id="calendar'+jsSDPId+'"></div></div><div class="spacer"></div>';
	// ajoute le code HTML
	calDiv.innerHTML = h;
	// initialise l'objet jsSimpleDatePickr
	obj = new jsSimpleDatePickr('calendar'+jsSDPId);
	obj.funcDateClic = 'calClick';
	obj.classTable = classTable;
	obj.classTd = classDay;
	obj.classSelection = classSel;
	// sauvegarde l'objet, le champ de texte rattachÃ© et l'id envoyÃ© Ã  jsSimpleDatePickr
	jsSDPObj[jsSDPId] = [obj, fieldId, 'calendar'+jsSDPId];
	jsSDPId++;
	return 1;
}
//
// affiche / masque le calendrier
//
function calToogle(id){
	if(jsSDPObj[id] == undefined) return 0;
	var el = document.getElementById('calendarWrap'+id);
	field = document.getElementById(jsSDPObj[id][1]);
	if(el.style.display=="block"){
		el.style.display = "none";
	}else{
		jsSDPObj[id][0].setDate(String(field.value));
		jsSDPObj[id][0].show('calendar');
		calShowTitle(id);
		el.style.display = "block";
	}
}
//
// affiche / masque le calendrier (clic depuis un champ de texte)
//
function calToogleFromField(fieldId){
	for(var i = 0; i<jsSDPObj.length; i++){
		if(jsSDPObj[i][1]==fieldId){
			calToogle(i);
			break;
		}
	}
}
//
// navigation par mois
//
function calMonthNav(id, val){
	if(jsSDPObj[id] == undefined) return 0;
	jsSDPObj[id][0].setMonth(val);
	jsSDPObj[id][0].show();
	calShowTitle(id);
}
//
// navigation par annÃ©e
//
function calYearNav(id, val){
	if(jsSDPObj[id] == undefined) return 0;
	jsSDPObj[id][0].setYear(val);
	jsSDPObj[id][0].show();
	calShowTitle(id);
}
//
// callback : gÃ¨re une clic sur une date
//
function calClick(dateStr, id){
	// cherche l'objet
	for(var i = 0; i<jsSDPObj.length; i++){
		if(jsSDPObj[i][2]==id){
			id = i;
			break;
		}
	}
	if(jsSDPObj[id] == undefined) return 0;
	var dateArr = dateStr.split('/');
	if(parseInt(dateArr[0], 10)<10) dateArr[0] = '0'+dateArr[0];
	if(parseInt(dateArr[1], 10)<10) dateArr[1] = '0'+dateArr[1];
	field = document.getElementById(jsSDPObj[id][1]);
	field.value = dateArr[0]+'/'+dateArr[1]+'/'+dateArr[2];
	document.getElementById('calendarWrap'+id).style.display = "none";
}
//
// affiche le titre
//
function calShowTitle(id){
	if(jsSDPObj[id] == undefined) return 0;
	document.getElementById('calendarTitle'+id).innerHTML = ' '+jsSDPMonthName[jsSDPObj[id][0].dateDisp.getMonth()]+' '+jsSDPObj[id][0].dateDisp.getFullYear()+' ';
}
//
// crÃ©e l'objet jsSimpleDatePickr
var jsSDPObj = Array();
var jsSDPId = 0;
var jsSDPMonthName = ['Jan', 'FÃ©v', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];