/*
* CEP -> 99.999-999
* CPF -> 999.999.999-99
* CNPJ -> 99.999.999/9999-99
* Data -> 99/99/9999
* Tel Resid -> (99) 999-9999
* Tel Cel -> (99) 9999-9999
* Processo -> 99.999999999/999-99
* C/C -> 999999-!
***/
var lastChecked = null;

$(document).ready(function() {
	if ($('#datatable').length > 0) {
		selectMultipleCheckboxes('#datatable');
	}

	BrowserDetect.init();
	Toast.init();
	UiAutocompleteItem.init();
});

function terribleHack(hackObject) {
	hackObject.setAttribute('value',hackObject.value);
}

function txtBoxFormat(objForm, strField, sMask, evtKeyPress) {
	var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;

		nTecla = evtKeyPress.which;
	if (nTecla == 8 || evtKeyPress.ctrlKey) {
		return true;
	}

	sValue = objForm[strField].value;
	sValue = sValue.toString().replace( "-", "" );
	sValue = sValue.toString().replace( "-", "" );
	sValue = sValue.toString().replace( ".", "" );
	sValue = sValue.toString().replace( ".", "" );
	sValue = sValue.toString().replace( "/", "" );
	sValue = sValue.toString().replace( "/", "" );
	sValue = sValue.toString().replace( "(", "" );
	sValue = sValue.toString().replace( "(", "" );
	sValue = sValue.toString().replace( ")", "" );
	sValue = sValue.toString().replace( ")", "" );
	sValue = sValue.toString().replace( " ", "" );
	sValue = sValue.toString().replace( " ", "" );
	fldLen = sValue.length;
	mskLen = sMask.length;

	i = 0;
	nCount = 0;
	sCod = "";
	mskLen = fldLen;

	while (i <= mskLen) {
		bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ":") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/"))
		bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))

		if (bolMask) {
			sCod += sMask.charAt(i);
			mskLen++;
		} else {
			sCod += sValue.charAt(nCount);
			nCount++;
		}
		i++;
	}

	objForm[strField].value = sCod;
	if (nTecla != 8) {
		if (sMask.charAt(i-1) == "9") {
			return ((nTecla > 47) && (nTecla < 58));
		} else {
		return true;
	}
	} else {
		return true;
	}
}


function formatReal( int ) {
	var tmp = int+'';
	tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
	if( tmp.length > 6 )
	tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
	return tmp;
}

var isNN = (navigator.appName.indexOf("Netscape")!=-1);
function autoTab(input,len, e) {
	var keyCode = (isNN) ? e.which : e.keyCode;
	var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];

	if(input.value.length >= len && !containsElement(filter,keyCode)) {
		input.value = input.value.slice(0, len);
		//input.form[(getIndex(input)+1) % input.form.length].focus();
	}


function containsElement(arr, ele) {
	var found = false, index = 0;
	while(!found && index < arr.length)
	if(arr[index] == ele)
		found = true;
	else
		index++;
		return found;
	}

function getIndex(input) {
	var index = -1, i = 0, found = false;
	while (i < input.form.length && index == -1)
		if (input.form[i] == input)index = i;
		else i++;
		return index;
	}
	return true;
}
//FIM DA FUN��O AUTO TAB

//COMEÇO FUNÇÕES PARA FORMATAR REAIS
documentall = document.all;
function formatamoney(c) {
	var t = this; if(c == undefined) c = 2;
	var p, d = (t=t.split("."))[1].substr(0, c);
	for(p = (t=t[0]).length; (p-=3) >= 1;) {
			t = t.substr(0,p) + "." + t.substr(p);
	}
	return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){
	var val2 = '';
	var strCheck = '0123456789';
	var len = valor.length;
		if (len== 0){
			return 0.00;
		}

		if (currency ==true){
			/* Elimina os zeros � esquerda
			* a vari�vel  <i> passa a ser a localiza��o do primeiro caractere ap�s os zeros e
			* val2 cont�m os caracteres (descontando os zeros � esquerda)
			*/

			for(var i = 0; i < len; i++)
				if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;

			for(; i < len; i++){
				if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
			}

			if(val2.length==0) return "0.00";
			if (val2.length==1)return "0.0" + val2;
			if (val2.length==2)return "0." + val2;

			var parte1 = val2.substring(0,val2.length-2);
			var parte2 = val2.substring(val2.length-2);
			var returnvalue = parte1 + "." + parte2;
			return returnvalue;

		}
		else{
				/* currency � false: retornamos os valores COM os zeros � esquerda,
				* sem considerar os �ltimos 2 algarismos como casas decimais
				*/
			var val3 ="";
			for(var k=0; k < len; k++){
					if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}

			return val3;
		}
}

function reais(obj,event){
	var whichCode = (window.Event) ? event.which : event.keyCode;
	if (whichCode == 8 ) {
		if (event.preventDefault){
				event.preventDefault();
			}else{
				event.returnValue = false;
		}
		var valor = obj.value;
		var x = valor.substring(0,valor.length-1);
		obj.value= demaskvalue(x,true).formatCurrency();
		return false;
	}
}


function backspace(obj,event){
	var whichCode = (window.Event) ? event.which : event.keyCode;
	if (whichCode == 8 && documentall) {
		var valor = obj.value;
		var x = valor.substring(0,valor.length-1);
		var y = demaskvalue(x,true).formatCurrency();

		obj.value ="";
		obj.value += y;

		if (event.preventDefault){
				event.preventDefault();
			}else{
				event.returnValue = false;
		}
		return false;

		}
}

//FIM FUNÇÕES PARA FORMATAR REAIS


function addDias(nroDias, campo){
	if(document.getElementById('dataEmissao').value==""){
		alert("A data de emissão não foi informada!");
	}else{
		document.getElementById(campo).value = addDays(document.getElementById('dataEmissao').value, nroDias);
	}
}

function addDiasNew(nroDias, idCampoDestino, idCampoOrigem) {
	if ($("#" + idCampoOrigem).val() == "") {
		alert("A data base não foi informada!");
	} else {
		$("#" + idCampoDestino).val(addDays($("#" + idCampoOrigem).val(), nroDias));
	}
}

function addDays(date, days) {
	var dataVenda = new Date(formatdate(date));
	var dia, mes, ano, dataResult;
	dataVenda.setDate(dataVenda.getDate() + parseInt(days));
	dia = dataVenda.getDate();
	dia = dia.toString();
	if ((dia.length) == 1) {
		dia = "0" + dia;
	}
	mes = dataVenda.getMonth() + 1;
	mes = mes.toString();
	if ((mes.length) == 1) {
		mes = "0" + mes;
	}
	ano = dataVenda.getFullYear();
	dataResult = dia + "/" + mes + "/" + ano;
	return dataResult;
}

function diferencaDatas(per,d1,d2,campo) {

	d1 = formatdate(d1);
	d2 = formatdate(d2);
	d1 = new Date(d1)
	d2 = new Date(d2)

	var d = (d2.getTime() - d1.getTime())/1000

	switch(per) {
		case "yyyy": d/=12
		case "m": d*=12*7/365.25
		case "ww": d/=7
		case "d": d/=24
		case "h": d/=60
		case "n": d/=60
	}
	if (campo != "") {
		document.getElementById(campo).value = Math.round(d);
	} else {
		return Math.round(d);
	}
}

function formatdate(date){
	var result;
	result = date.substr(3,2) + '/'+date.substr(0,2) + '/' + date.substr(6,4);
	return(result.toString());
}

function formatTime(time){
	var timef = time.replace(/\D+/g, '');
	timef = timef.substring(0,4);
	if (timef.length > 2){
		timef = timef.substring(0,2) + ":" + timef.substring(2,4);
	}
	return timef;
}

function nroUsa(nro){
	var pos;
	nro = nro + "";
	pos = nro.indexOf(".");
	while (pos != -1){
		nro = nro.substring(0,pos) + nro.substring(pos+1,nro.length);
		pos = nro.indexOf(".");
	}
	pos = nro.indexOf(",");
	if (pos != -1){
		nro = nro.substring(0,pos) + '.' + nro.substring(pos+1,nro.length);
	}
	return nro;
}

function nroBra(nro) {
	return nroBraDecimais(nro, 2);
}

function nroBraDecimais(nro, decimais) {
	try {
		var options = {style: 'decimal', currency: 'BRL', minimumFractionDigits: decimais, maximumFractionDigits: decimais};
		var formatter = new Intl.NumberFormat('pt-br', options);
		return formatter.format(nro);
	} catch (e) {
		nro = (nro + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = (!isFinite(+nro) ? 0 : +nro);
		var	prec = (!isFinite(+decimais) ? 0 : Math.abs(decimais));
		var s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, '.');
		}

		s[1] = s[1] || '';
		if (s[1].length < prec) {
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}

		return s.join(prec ? ',' : '');
	}
}

function toFixedFix(n, prec, parse) {
	var k = Math.pow(10, prec);
	var number = Math.round(Math.abs(n) * k) / k;
	var s = (n < 0 && number != 0 ? '-' : '') + number;

	return (parse ? parseFloat(s) : s);
}

function float2moeda(num) {
	x = 0;
	if(num<0) {
		num = Math.abs(num);
		x = 1;
	}
	if(isNaN(num))
	num = "0";
	cents = Math.floor((num*100+0.5)%100);
	num = Math.floor((num*100+0.5)/100).toString();

	if(cents < 10)
		cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) {
			num = num.substring(0,num.length-(4*i+3))+'.'
			+num.substring(num.length-(4*i+3));
		}
		ret = num + ',' + cents;
		if (x == 1) {
			ret = ' - ' + ret;
		}
		return ret;
}

function somaDias(nroDias, data){
	var data = new Date(data);
	//alert(parseInt(nroDias,10));
	var dia, mes, ano, dataResult;
	data.setDate(data.getDate()+parseInt(nroDias, 10));
	dia = data.getDate();
	dia = dia.toString();
	if((dia.length)==1){
		dia = '0'+dia;
	}
	mes = data.getMonth()+1;
	mes = mes.toString();
	if((mes.length)==1){
		mes = '0'+mes;
	}

	ano = data.getFullYear();
	dataResult = dia +'/'+ mes +'/'+ ano;
	return dataResult;
}

function ocultar(){
	document.getElementById('desaparece').style.visibility = 'hidden';
}

function mostrar(){
	document.getElementById('desaparece').style.visibility = '';
}

function imprimir(){
	window.print();
	tempo();
}

function tempo(){
	window.setTimeout("mostrar()", 1000);
}

function dataUsa(data,separador){
	var aData = data.split(separador.toString());
	var novaData = aData[2]+'/'+aData[1]+'/'+aData[0];
	return novaData;
}

function dataBr(data,separador){
	var aData = data.split(separador.toString());
	var novaData = aData[2]+'/'+aData[1]+'/'+aData[0];
	return novaData;
}

function dataBD(data,separador){
	var aData = data.split(separador.toString());
	var novaData = aData[2]+'-'+aData[1]+'-'+aData[0];
	return novaData;
}

function dataHoraBr(data,separador,exibirHora){
	var aData = data.split(separador.toString());
	var dataHora = aData[2].split(" ");
	var novaData = dataHora[0]+'/'+aData[1]+'/'+aData[0];
	if(exibirHora == "S"){
		novaData += " " + (dataHora[1] ? dataHora[1].substr(0, 8) : "00:00:00");
	}
	return novaData;
}

function openPopup(url,width,height) {
	myPopup = window.open(url,"popupWindow","scrollbars=yes,status=yes,width="+width+",height="+height+"");
}

function displayWait(div, modal, msg) {
	if ($.trim(msg) != '' && msg != undefined) {
		$('#' + div).empty().append(
			$('<div>', {'class': 'container_loading_' + div}).append(
				$('<div>', {'class': 'loading loading-modal'}),
				$('<span>', {'html': msg})
			)
		).css({
			'margin-left': '-' + ($('.container_loading_' + div).width() + 48) / 2 + 'px',
			'margin-top': '-' + ($('.container_loading_' + div).height() + 20) / 2 + 'px'
		});
	} else {
		$('#' + div).empty().append(
			$('<div>', {'class': 'loading loading-small'})
		).css({
			'margin-left': '0',
			'margin-top': '0'
		});
	}

	if (modal) {
		$('#modalWait').remove();
		$('body').append(
			$('<div>', {'id': 'modalWait'})
		)
	}
}

function closeWait(div){
	document.getElementById(div).innerHTML = "";
	$("#modalWait").remove();
}

function changeMsgWait(div, msg) {
	if ($(".container_loading_" + div).length) {
		$(".container_loading_" + div + " > span").text(msg);
	}
}

function iterativeWait(div, msgs, times, index) {
	index = (index == undefined ? 0 : index);
	if ($(".container_loading_" + div).length && msgs.length) {
		setTimeout(function() {
			changeMsgWait(div, msgs.shift());
			iterativeWait(div, msgs, times, ++index);
		}, times[(times.length - 1 < index ? times.length -1 : index)] * 1000);
	}
}

function closeWaitByClass(className){
  var elem = document.getElementsByClass(className);
	for(i=0; i++; i<len(elem)) {
		elem[i].innerHTML = "";
	}
}

function formatDateField(field){
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var anoStr = year.toString();
	var str = field.value;
	var mySplitResult = str.split("/");
	var dataNumber = '';
	for(i = 0; i < mySplitResult.length; i++){
		var temp = mySplitResult[i];
		if (temp.length==1){
			temp = '0' + temp;
		}
		dataNumber += temp;
	}
	dataNumber = formatNumber(dataNumber);
	if (dataNumber.length == 0){
		return false;
	}
	if (dataNumber.length == 2){
		var dia = dataNumber.substr(0,2);
		var mes = month.toString();
		if (mes.length==1){
			mes = '0' + mes;
		}
		var ano = anoStr.substr(0,4);
	}else if(dataNumber.length == 4){
		var dia = dataNumber.substr(0,2);
		var mes = dataNumber.substr(2,2);
		var ano = anoStr.substr(0,4);
	}else if(dataNumber.length == 6){
		var dia = dataNumber.substr(0,2);
		var mes = dataNumber.substr(2,2);
		var ano = anoStr.substr(0,2) + dataNumber.substr(4,2);
	}if (dataNumber.length == 8){
		var dia = dataNumber.substr(0,2);
		var mes = dataNumber.substr(2,2);
		var ano = dataNumber.substr(4,4);
	}
	field.value = dia + '/' + mes + '/' + ano;
}

function formatNumber(sText){
	var ValidChars = "0123456789";
	var result = "";
	var Char;
	for (i = 0; i < sText.length; i++) {
		Char = sText.charAt(i);
		if (ValidChars.indexOf(Char) != -1){
			result += Char;
		}
	}
	return result;
}

function getElementsByClassName(className){
	var arr = new Array();
	var elems = document.getElementsByTagName("*");
	for(var i = 0; i < elems.length; i++){
		var elem = elems[i];
		var id = elem.getAttribute("id");
		var cls = elem.getAttribute("class");
		if(cls == className){
			arr[arr.length] = id;
		}
	}
	return arr;
}

function marcarDesmarcarTodosCheckbox(field,nameClass,atrivarOnclick){
	var aItens = document.getElementsByClassName(nameClass);
	for(var i=0; i < aItens.length; i++) {
		if(field.checked==true){
			aItens[i].checked = true;
			if(atrivarOnclick=='S'){
				aItens[i].onclick()
			}
		}else{
			aItens[i].checked = false;
			if(atrivarOnclick=='S'){
				aItens[i].onclick()
			}
		}
	}
}

function rowHighLight(elementId) {
   //alert(elementId);
   new Effect.Highlight($(elementId));
}

function removeAcentos(Campo){
	var Acentos = "áàãââÁÀÃÂéêÉÊíÍóõôÓÔÕúüÚÜçÇ";
	var Traducao ="aaaaaAAAAeeEEiIoooOOOuuUUcC";
	var Posic, Carac;
	var TempLog = "";
	for (var i=0; i < Campo.length; i++){
		Carac = Campo.charAt(i);
		Posic = Acentos.indexOf(Carac);
		if(Posic > -1){
			TempLog += Traducao.charAt(Posic);
		}else{
			TempLog += Campo.charAt(i);
		}
	}
	TempLog = TempLog.replace("'", "");
	return (TempLog);
}

function formatNumeroBra(field){

	var tam;
	var valor;
	var achou = false;
	var ValidChars = "0123456789";

	//valor = field.value;
	valor = field.val();
	tam = valor.length;

	cont = 0;
	strTemp = '';
	for (i=tam-1;i>=0;i--){

		Char = valor.charAt(i);

		if (ValidChars.indexOf(Char) != -1){
			strTemp += Char;
			cont ++;
		}

		if (Char=='.'){

			if ((!achou) && (cont>2)){
				strTemp = '00,'+strTemp;
				achou = true;
			}

			if (!achou){
				strTemp += ',';
				achou = true;
				if (cont==0){
					strTemp = '00'+strTemp;
				}
				if (cont==1){
					strTemp = '0'+strTemp;
				}
				cont = 0;
			}
		}else if (Char==','){

			if ((!achou) && (cont>2)){
				strTemp = '00,'+strTemp;
				achou = true;
			}

			if (!achou){
				strTemp += Char;
				achou = true;
				if (cont==0){
					strTemp = '00'+strTemp;
				}
				if (cont==1){
					strTemp = '0'+strTemp;
				}
				cont = 0;
			}
		}
	}

	if (strTemp==''){
		strTemp = '0';
	}

	if (!achou){
		strTemp = '00,'+strTemp;
	}

	str= '';
	for(i = 0; i < strTemp.length; i++){
		Char = strTemp.charAt(i);
		str = Char + str;
	}

	if (str.charAt(0)==','){
		str = '0'+str;
	}

	field.value = str;

}


function efeitoHighLight(elementId) {
   new Effect.Highlight($(elementId));
}


function debug(s) {
	if (typeof console != "undefined" && typeof console.debug != "undefined") {
		console.log(s);
	}
}

function debugEmail(subject, message) {
	$.ajax({
		type: "post",
		url: "utils/requestMethods.php",
		data: {action: 'debugEmail', arguments: [subject, message]},
	});
}

function isEmailAddress(email){
	var s = email;
	var filter=/^[A-Za-z][A-Za-z0-9_.-]*@[A-Za-z0-9_.-]+\.[A-Za-z0-9_.]+[A-za-z]$/;

	if (s.length == 0 ){
		return false;
	}

	if (filter.test(s)){
		return true;
	}else{
		return false;
	}
}

function hideSelects(visibility){
	selects = document.getElementsByTagName('select');
	for(i = 0; i < selects.length; i++) {
		selects[i].style.visibility = visibility;
	}
}

function getUrlParameter(name) {
	  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	  var regexS = "[\\?&]"+name+"=([^&#]*)";
	  var regex = new RegExp( regexS );
	  var results = regex.exec( window.location.href );
	  if( results == null )
		return "";
	  else
		return results[1];
}

/*********  FUNÇÕES DE DATA    **************/

function getDataAtual(){
	return new Date().asString();
}

function getMesAtual(dataServ) {
	var data = new Date.fromString(dataServ)
	return zerosEsquerda(data.getMonth()+1,2);
	/*
	var data = new Date();
	var m = data.getMonth() + 1;
	return zerosEsquerda(m, 2);
	*/
}

function getAnoAtual(dataServ) {
	var data = new Date.fromString(dataServ)
	return data.getFullYear();
	/*
	var data = new Date();
	var y = data.getFullYear();
	return zerosEsquerda(y, 4);
	*/
}

function getHoraAtual(segundos){
	var data = new Date();
	var h = data.getHours();
	var m = data.getMinutes();
	var s = data.getSeconds();
	return zerosEsquerda(h, 2) + ":" + zerosEsquerda(m, 2) + (segundos ? ":" + zerosEsquerda(s, 2) : "");
}

function getDataInicialMes(dataServ){ // dd/mm/yyyy
	var dataInicial = Date.fromString('01/' + dataServ.substring(3));
	return dataInicial.asString();
}

function getDataFinalMes(dataServ){ // dd/mm/yyyy
	var dataFinal = Date.fromString('01/' + dataServ.substring(3)).addMonths(1).addDays(-1);
	return dataFinal.asString();
}

function getDataInicialSemana(dataServ){ // dd/mm/yyyy
	data = Date.fromString(dataServ).addHours(12);
	inicioSemana = data.addDays(-data.getDay());
	return inicioSemana.asString();
}

function getDataFinalSemana(dataServ){ // dd/mm/yyyy
	data = Date.fromString(dataServ).addHours(12);
	fimSemana = data.addDays(6-data.getDay());
	return fimSemana.asString();
}

function incDias(dataServ, n){ // dd/mm/yyyy
	data = dataServ.split("/");
	var dia = data[0];
	var mes = data[1];
	var ano = data[2];
	data = Date.UTC(ano, mes-1, dia);
	data += n * 24 * 60 * 60 * 1000;
	data = new Date(data);
	dia = data.getUTCDate();
	if (dia < 10){
		dia = "0" + dia;
	}
	mes = data.getUTCMonth()+1;
	if (mes < 10){
		mes = "0" + mes;
	}
	ano = data.getUTCFullYear();
	return dia + "/" + mes + "/" + ano;
	/*data = Date.fromString(dataServ);
	data.addDays(n);
	return data.asString();*/
}

function incMeses(dataServ, n){ // dd/mm/yyyy

	if ((n == 0) && (dataServ !="")){
			while ((dataServ.substr(2,1) != "/") && (dataServ.length < 10)) {
				dataServ = "0" + dataServ;
			}

			while ((dataServ.substr(5,1) != "/") && (dataServ.length < 10)) {
					dataServ = (dataServ.substr(0, 3) + "0" + dataServ.substr(3));
			}

			while (dataServ.length < 10) {
				dataServ += "0";
			}
	}

	var data = Date.fromString(dataServ);
	data.addMonths(n);
	return data.asString();
	/*
	var data = Date.fromString('01/' + inputField.val());
	data.addMonths(n);
	inputField.val(data.asString().substring(3));
	*/
}

function zerosEsquerda(valor, tamanho){
	valor = '' + valor;
	var sTam = valor.length;
	while (sTam < tamanho) {
		valor = "0" + valor;
		sTam = valor.length;
	}
	return valor;
}

function activateCheckSend(){
	$(".checkSend").bind('click',function(){
		$(this).attr("disabled", true);
		setTimeout("activateClick()",500);
	});
}

function activateClick(obj){
	$(".checkSend").removeAttr("disabled");
}

function initFormatters(decQtde, decValor) {
	$(".edt-number").blur(function() {
		$(this).val($(this).val().replace(/-*[^\d.,-]/g, '')).format({format:"#,###.00", locale:"br"});
		if ($(this).val().indexOf("NaN") != -1) {
			$(this).val('0,00');
		}
	});

	var sDecQtde = "";
	for (i = 1; (i <= decQtde); i ++) {
		sDecQtde += "0";
	}
	$(".edt-qtde").blur(function() {
		$(this).val($(this).val().replace(/-*[^\d.,-]/g, '')).format({format:"#,###." + sDecQtde, locale:"br"});
		if ($(this).val().indexOf("NaN") != -1) {
			$(this).val('0,' + sDecQtde);
		}
	});

	var sDecValor = "";
	for (i = 1; (i <= decValor); i ++) {
		sDecValor += "0";
	}
	$(".edt-valor").blur(function() {
		$(this).val($(this).val().replace(/-*[^\d.,-]/g, '')).format({format:"#,###." + sDecValor, locale:"br"});

		if ($(this).val().indexOf("NaN") != -1) {
			$(this).val('0,' + sDecValor);
		}
	});
}
function initFormatterField(dec, obj) {
	var sDec = "";
	for (i = 1; (i <= dec); i ++) {
		sDec += "0";
	}
	obj.blur(function() {
		$(this).val($(this).val().replace(/-*[^\d.,-]/g, '')).format({format:"#,###." + sDec, locale:"br"});

		if ($(this).val().indexOf("NaN") != -1) {
			$(this).val('0,' + sDec);
		}
	});
}

function testCompleter(inputSearch, inputResult, msg, validateEmptyValue){
	var removeInfos = function() {
		$(inputSearch).removeClass("ac_warning");
		$(inputSearch).addClass("tipsyOff");
		$(inputSearch).attr("title", "");
	}

	if (typeof validateEmptyValue != "undefined") {
		if (! validateEmptyValue && $(inputSearch).val().length == 0) {
			removeInfos();
			return;
		}
	}

	if(($(inputResult).val() == 0) || ($(inputResult).val() == "")){
		$(inputSearch).addClass("ac_warning");
		$(inputSearch).attr("title",msg);
		$(inputSearch).removeClass("tipsyOff");
	} else {
		removeInfos();
	}
}

function testCompleterWarning(inputTest, iconSearch){
	if(inputTest.val() == 0 || inputTest.val() == ""){
		iconSearch.attr('class', 'icon-warning-sign');
	} else {
		iconSearch.attr('class', '');
	}
}


function clearHidenResult(event, field) {
	var teclaCTRLPressionada = false;
	if ((event.ctrlKey) || (event.keyCode == 17))  {
		teclaCTRLPressionada = true;
	}
	if ((! teclaCTRLPressionada) || (event.keyCode == 86)) {
		if ((event.keyCode <= 8) ||	((event.keyCode >= 46) && (event.keyCode <= 111)) || (event.keyCode >= 186) || event.type == 'paste') {
			$(field).val('0');
		}
	}
}

function openPopupTestJava(){
	$("<div></div>").load("templates/form.java.tester.php").dialog({
		title: "Teste java",
		modal: true,
		resizable: false,
		width: 530
	});
}

function roundValue(original,decimals){

	var val =1;
	var i=0;
	for (i=1;i<=decimals;i++){
		val = val * 10;
	}
	var result=Math.round(original*val)/val;
	return result;
}

function limparNumero(arNumero) {
	var onlyNumber = "";
	for (var i = 0; i < arNumero.length; i ++) {
		var caracter = arNumero.charAt(i);
		switch (caracter) {
			case "0":
			case "1":
			case "2":
			case "3":
			case "4":
			case "5":
			case "6":
			case "7":
			case "8":
			case "9": {
				onlyNumber += caracter;
				break;
		}
		}
	}

	return onlyNumber;
}

function formatarTelefone(fone) {
	var foneFormatado = "", foneOnlyNumber = "", ramal = "", localR, length;
	var arFones = fone.split(",");
	for (var c = 0; c < arFones.length; c ++) {
		arFones[c] = arFones[c].toUpperCase();
		localR = arFones[c].indexOf("R");
		if (localR >= 0) {
			ramal = " R." + limparNumero(arFones[c].substr(localR));
			arFones[c] = arFones[c].substr(0, localR);
		} else {
			ramal = "";
		}

		foneOnlyNumber = limparNumero(arFones[c]);
		if (foneOnlyNumber != "") {
			if (foneOnlyNumber.substr(0,4) == "0800") {
				while (foneOnlyNumber.length < 11) {
					foneOnlyNumber = foneOnlyNumber + "x";
				}
				foneOnlyNumber = foneOnlyNumber.substr(0, 11);
				if (foneFormatado != "") {
					foneFormatado += ", ";
				}
				foneFormatado += foneOnlyNumber.substr(0, 4) + " " + foneOnlyNumber.substr(4, 3) + " " + foneOnlyNumber.substr(7, 4) + ramal;
			} else if ((foneOnlyNumber.length == 9) || (foneOnlyNumber.length == 11)) {
				while (foneOnlyNumber.length < 11) {
					foneOnlyNumber = "x" + foneOnlyNumber;
				}
				if (foneOnlyNumber[0] == "0") {
					foneOnlyNumber = foneOnlyNumber.substr(-10);
					length = 4;
				} else {
					foneOnlyNumber = foneOnlyNumber.substr(-11);
					length = 5;
				}
				if (foneFormatado != "") {
					foneFormatado += ", ";
				}
				foneFormatado += "(" + foneOnlyNumber.substr(0, 2) + ") " + foneOnlyNumber.substr(2, length) + "-" + foneOnlyNumber.substr((length + 2), 4) + ramal;
			} else {
				while (foneOnlyNumber.length < 10) {
					foneOnlyNumber = "x" + foneOnlyNumber;
				}
				if (foneOnlyNumber[0] == "0") {
					foneOnlyNumber = foneOnlyNumber.substr(-11);
					length = 5;
				} else {
					foneOnlyNumber = foneOnlyNumber.substr(-10);
					length = 4;
				}
				if (foneFormatado != "") {
					foneFormatado += ", ";
				}
				foneFormatado += "(" + foneOnlyNumber.substr(0, 2) + ") " + foneOnlyNumber.substr(2, length) + "-" + foneOnlyNumber.substr((length + 2), 4) + ramal;
			}
		}
	}
	return foneFormatado;
}

function somaHora(hrA, minutos) {
	if(hrA.length != 5) return "00:00";
	var temp = 0;
	var nova_h = 0;
	var novo_m = 0;

	var hora1 = hrA.substr(0, 2) * 1;
	var minu1 = hrA.substr(3, 2) * 1;

	temp = minu1 + minutos;
	while(temp > 59) {
			nova_h++;
			temp -= 60;
	}
	nova_h = nova_h * 1;
	hora1 = hora1 * 1;
	nova_h += hora1;
	novo_m = temp.toString().length == 2 ? temp : ("0" + temp);
	if (nova_h.toString().length == "1") {
		nova_h = "0" + nova_h;
	}
	return nova_h + ":" + novo_m;
}

function isImage(ext) {
	switch (ext.toUpperCase()) {
		case "PNG":
		case "JPG":
		case "JPEG":
		case "GIF":
			return true;
			break;
		default: return false;
	}
}

function somaDiasParcelas(nroDias, data){
	var data = new Date(data);
	var dia, mes, ano, dataResult;
	dia = data.getDate();
	mes = (data.getMonth()+1)+(nroDias/30);
	ano = data.getFullYear();

	if(mes > 12) {
		if(mes%12 == 0) {
			ano += (Math.floor(mes/12)) - 1;
			mes = 12;
		} else {
			ano += Math.floor(mes/12);
			mes = (mes%12);
		}
	}

	if(dia > 28) {
		if((dia == 29)&&(ano%4 != 0)&&(mes == 2)) {
			dia = 28;
		} else if(dia > 29) {
			if(mes == 2) {
				if(ano%4 == 0) {
					dia = 29;
				} else {
					dia = 28;
				}
			} else if((mes == 4)||(mes == 6)||(mes == 9)||(mes == 11)) {
				dia = 30;
			}
		}
	}

	dia = dia.toString();
	if((dia.length)==1){
		dia = '0'+dia;
	}

	mes = mes.toString();
	if((mes.length)==1){
		mes = '0'+mes;
	}

	dataResult = dia +'/'+ mes +'/'+ ano;
	return dataResult;
}

function handlerCallBackEndereco(callback, cep, camposJSON, camposValidar) {
        var obj = $.parseJSON(callback);

        if(obj.status.codigo !== 200) {

              $.each(camposJSON, function(indice, valor) {
                      $("#"+indice).val(obj[valor]);
              });
					if(camposValidar != null){
              $.each(camposValidar, function(key, value) {
                      if(camposJSON[value] !== undefined) {

                              $("#"+value).addClass("ac_error")
                                          .tipsy({gravity: $.fn.tipsy.autoWE})
                                          .removeClass('tipsyOff');

                              switch(camposJSON[value]) {
                                  case 'cep' : $("#"+value).attr("original-title", obj.status.msg).val(cep); break;
                                  default : $("#"+value).attr("original-title", "Não encontrado no sistema");
                              }
                      }
              });
						}
        } else {
                $.each(camposJSON, function(indice, valor) {
                        $("#"+indice).val(obj[valor])
                                     .removeClass("ac_error")
                                     .removeAttr("original-title")
                                     .addClass('tipsyOff');
                });
        }

        $(".buscaCep").show();
}

$.processaControle = function(cep, campos, camposValidar) {
	var camposJSON = $.parseJSON(campos);

	$.each(camposJSON, function(key) {
		$("#"+key).val('carregando...');
	});

	$.ajax({
                type: "POST",
                url: "services/cep.lookup.php",
                data: 'cep='+cep,
                success: function(data) {
                        var callback = data;
                        handlerCallBackEndereco(callback, cep, camposJSON, camposValidar);
                },
                error: function(request) {
                        alert(request.responseText);
                }
        });
}

function alterarLink(id) {
	$("#"+id).html("Importando");
	$("#"+id).attr("onclick", "");
	$("#"+id).attr("style", "color: #44464C; cursor: default; text-decoration: none;");
	$("#"+id).attr("disabled", "disabled");
}

alert = function (mensagem, titulo) {
	DialogMessage.warning({
		'htmlTitle': titulo,
		'description': mensagem
	});
}

function getIconeIntegracao(tipoIntegracao) {
    switch (tipoIntegracao) {
        case "AllNations":
            return "images/icone_allnations.png";
        case "Ciashop":
            return "images/icone_ciashop.png";
        case "LojaIntegrada":
            return "images/icone_loja_integrada.png";
        case "iShopping":
            return "images/icone_ishopping.png";
        case "Prestashop":
            return "images/icone_prestashop.png";
        case "MaximaWeb":
            return "images/icone_MaximaWeb.png";
        case "Rakuten":
            return "images/icone_rakuten.png";
        case "EzCommerce":
            return "images/icone_ezcommerce.png";
        case "FastCommerce":
            return "images/icone_fastcommerce.png";
        case "Fbits":
            return "images/icone_fbits.png";
        case "FbitsWS":
            return "images/icone_fbits.png";
        case "Jet":
            return "images/icone_jet.png";
        case "Tray":
            return "images/icone_tray.png";
        case "Adena":
            return "images/icone_adena.png";
        case "MeusPedidos":
            return "images/icone_meuspedidos.png";
        case "Maxistore":
            return "images/icone_maxistore.png";
        case "Interspire":
            return "images/icone_interspire.png";
        case "Likestore":
            return "images/icone_likestore.png";
        case "MarketplaceExtra":
            return "images/icone_extra.png";
        case "MarketplaceWalmart":
            return "images/icone_walmart.png";
        case "Facileme":
            return "images/icone_facileme.jpg";
        case "Dotstore":
            return "images/icone_dotstore.png";
        case "eBay":
            return "images/icone_ebay.png";
        case "WooCommerce":
            return "images/icone_WooCommerce.png";
        case "LojaMestre":
            return "images/icone_lojamestre.png";
        case "OpenCart":
            return "images/icone_OpenCart.png";
        case "PerasLoja":
            return "images/icone_perasloja.png";
        case "VTex":
            return "images/icone_VTex.png";
        case "Submarino":
            return "images/icone_Submarino.png";
        case "B2W":
            return "images/icone_B2W.png";
        case "Extra":
            return "images/icone_Extra.png";
        case "MercadoLivre":
            return "images/icone_MercadoLivre.png";
        case "MercadoShops":
            return "images/icone_MercadoShops.png";
        case "Mkx":
            return "images/icone_mkx.png";
        case "Xtech":
            return "images/icone_xtech.png";
        case "Z3":
            return "images/icone_z3.png";
        case "Magento":
            return "images/icone_magento.png";
        case "Kanui":
            return "images/icone_kanui.png";
        case "OOK":
            return "images/icone_OOk.png";
        case "Groupon":
            return "images/icone_groupon.png";
        case "Nuvemshop":
            return "images/icone_nuvemshop.png";
        case "CNova":
            return "images/icone_cnova.png";
        case "Starter":
            return "images/icone_starter.png";
        case "PeixeUrbano":
            return "images/icone_peixeurbano.png";
        case "Shopify":
            return "images/icone_shopify.png";
        case "SkyHub":
            return "images/icone_SkyHub.png";
        case "Bling":
            return "images/icone_Bling.png";
        case "Irroba":
            return "images/icone_irroba.png";
        case "RakutenOne":
            return "images/icone_rakuten.png";
        case "iShoppingWS":
            return "images/icone_ishopping.png";
        case "RakutenGenesis":
            return "images/icone_rakuten.png";
        case "KanuiWS":
            return "images/icone_kanui.png";
        case "TricaeWS":
            return "images/icone_tricae.png";
        case "DafitiWS":
            return "images/icone_dafiti.png";
        case "Walmart":
            return "images/icone_walmart.png";
        case "Netshoes":
            return "images/icone_netshoes.png";
        case "Zattini":
            return "images/icone_zattini.png";
        case "VendasExternas":
            return "images/icone_vendas_externas.png";
        case "WooCommerceWH":
            return "images/icone_woocommerce_wh.png";
        case "SisEcommerce":
            return "images/icone_sisecommerce.png";
        case "JetUol":
            return "images/icone_jetuol.png";
        case 'Buscape' :
            return 'images/icone_buscape.png';
        case 'LojaModular' :
            return 'images/icone_loja_modular.png';
        case "MagentoV2":
            return "images/icone_magento_v2.png";
        case "Amazon":
        	return "images/icone_amazon.png";
        case "Olist":
        	return "images/icone_olist.png";
        case "PlataformaNeo":
        	return "images/icone_plataformaneo.png";
        case "Carrefour":
            return "images/icone_carrefour.png";
        case "ViaVarejo":
            return "images/icone_viavarejo.png";
        case "IntegraCommerce":
            return "images/icone_integracommerce.png";
        case "Bis2Bis":
            return "images/icone_bis2bis.png";
        case "OtimoNegocio":
            return "images/icone_otimonegocio.png";
        case "WideCommerce":
            return "images/icone_widecommerce.png";
        case "Hibrido":
            return "images/icone_hibrido.png";
        case "LeroyMerlin":
            return "images/icone_leroy_merlin.png";
		case "CoreCommerce":
	        return "images/icone_corecommerce.png";
        default:
            return "images/ico_bling.png";
    }
}

function montarListaLojasAtivasPorTipoIntegracao(confs, attributoIdForm, tipoIntegracao) {
	confs = decodeURIComponent(confs)
	confs = $.parseJSON(confs);

	var html		= "<option value='' style='padding-left:20px; margin-bottom:4px;'>Selecione</option>";
	var selected	= "";

	if (confs.length == 1){
		selected = 'selected="selected"';
	}

	$.each(confs, function(index, conf){
		html += "<option " + selected + " value='" + conf.idIntegracao + "' style='background:url(" + getIconeIntegracao(tipoIntegracao) + ") no-repeat; padding-left: 20px; margin-bottom: 4px; background-size: 18px 18px;' tipoIntegracao='" + conf.tipoIntegracao + "' idLoja='" + conf.id + "'>" + conf.nomeLoja + "</option>";
	});

	$("#" + attributoIdForm).html(html);
	closeWait('wait');
}

function montarListaLojasAtivasAvancado(confs, attributoIdForm, textOption) {
	confs = decodeURIComponent(confs)
	confs = $.parseJSON(confs);

	var html = "<option value='' style='padding-left:20px; margin-bottom:4px;'>" + textOption + "</option>";

	$.each(confs, function(index, conf){
		html += "<option value='" + conf.idIntegracao + "' style='background:url(" + getIconeIntegracao(conf.tipoIntegracao) + ") no-repeat; padding-left: 20px; margin-bottom: 4px; background-size: 18px 18px;' tipoIntegracao='" + conf.tipoIntegracao + "' idLoja='" + conf.id + "'>" + conf.nomeLoja + "</option>";
	});

	$("#" + attributoIdForm).html(html);
	closeWait('wait');
}

function initFileUploader(params){
	tamMax = 0;
	if(typeof params.tamMax != "undefined"){
		tamMax = params.tamMax;
	}

	var options = {
		action: params.acao,
		multipart: true,
		element : params.elemento,
		multiple: false,
		sizeLimit: tamMax,
		allowedExtensions: params.extensoes,
		labels: {
			drop: "Arraste e solte o arquivo aqui para upload",
			button: "Procurar arquivo",
			cancel: "Cancelar",
			failed: "Falhou"
		},
		onSubmit: function(){
			$(".qq-upload-list").show();
		},
		onComplete: function(id, fileName, responseJSON){
			if (responseJSON.success == true){
				params.callback(responseJSON);

				$(".qq-upload-list").hide();
				$(".qq-upload-list").empty();
			}
		},
		messages: {
			typeError: "Extensão de arquivo inválida. Selecione um arquivo com a extensão '{extensions}'.",
			sizeError: "{file} excede o limite máximo de {sizeLimit}.",
			minSizeError: "{file} precisa ter no mínimo {minSizeLimit}.",
			emptyError: "{file} está vazio, por favor, selecione outro arquivo.",
			onLeave: "O upload dos arquivos não finalizou, se você sair desta página o processo será cancelado."
		},
		debug: true
	};

	if (params.button) {
	 	options.button = params.button;
	}

	if(params.templateHTML){
		options.templateHTML = params.templateHTML;
	}

	new qq.FileUploader(options);
}

function directPrint(params, callback){
	$.ajax({
		url: params.acao,
		type : params.metodo,
		dataType: "text",
		data : ((typeof params.dados != "undefined") ? params.dados : {}),
		success : function(res){
			callback(res);
		},
		error : function(req, status, error){
			callback(error);
		}
	});
}

function doTruncateStr(str, size){
	if (str==undefined || str=='undefined' || str =='' || size==undefined || size=='undefined' || size ==''){
		return str;
	}

	var shortText = str;
	if(str.length >= size+3){
		shortText = str.substring(0, size).concat('...');
	}
	return shortText;
}

//This code was written by Tyler Akins and has been placed in the
//public domain. It would be nice if you left this header intact.
//Base64 code from Tyler Akins -- http://rumkin.com

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function encode64(input) {
	input += "";
	var output = "";
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	do {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
	} while (i < input.length);

	return output;
}

function decode64(input) {
	var output = "";
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

	do {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output = output + String.fromCharCode(chr1);

		if (enc3 != 64) {
			output = output + String.fromCharCode(chr2);
		}
		if (enc4 != 64) {
			output = output + String.fromCharCode(chr3);
		}
	} while (i < input.length);

	return output;
}

function permissaoErro(msg) {
	msgHtml = "<div class='aviso-permissao' id='error-messages' style='display: block;''><h3>Permissão</h3><p><i class='icon-lock'></i>Você não possui permissão para <b>"+msg.toLowerCase()+"</b></p></div>";
	$("#top-container").append(msgHtml);

	setTimeout(function() {
		$("#top-container #error-messages[class='aviso-permissao']").fadeOut("slow", function() {
			$(this).remove();
		});
	}, 4000);
}

function listenerPermissaoForm() {
	xajax.doneLoadingFunction = function () {
		setTimeout(function() {
			form = $("form[data-system-readonly='true']");

			form.find("input[type!='checkbox'][type!='button'], textarea").prop("readonly", true);
			form.find("input[type='checkbox'], select").prop("disabled", true);
		}, 1);
	}
}

function getRenderedTemplate(arguments, callback) {
	$.ajax({
		type: "post",
		url: "utils/requestMethods.php" + (arguments.getArguments !== undefined ? arguments.getArguments : ""),
		data: {action: arguments.action, link: arguments.link, field: arguments.field, afterUpdate: arguments.afterUpdate, busca: arguments.busca, html: arguments.html},
		success: function(result) {
			callback($.parseHTML(result, document, true));
		}
	});
}

function nroUsaFloat(nro) {
	return (parseFloat(nroUsa(nro)) || 0);
}

function mergeJsonObjects(obj1, obj2) {
	var finalObj = {};

	for(var _obj in obj1) finalObj[_obj] = obj1[_obj];
	for(var _obj in obj2) finalObj[_obj] = obj2[_obj];

	return finalObj;
}

function formatReal(int){
	var tmp = int+'';
	tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
	if( tmp.length > 6 )
	tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

	return tmp;
}

/**
 * Função equivalente ao strpos do PHP.
 */
function strpos(haystack, needle, offset) {
	  var i = (haystack + '')
		.indexOf(needle, (offset || 0));
	  return i === -1 ? false : i;
}

function getModalError(msg, dialogId){
	$('#modalExceptionMsg').html(msg);
	$('#'+dialogId).dialog({
		modal: true,
		resizable: false,
		width: 350,
		modal: true,
		title: 'Ocorreu um erro inesperado!',
			buttons: {
				"OK": function() {
						$(this).dialog("close");
				}
			}, create:function() {
				$(this).closest('.ui-dialog').find('.ui-button').eq(1).addClass('button-default');
				$(this).closest('.ui-dialog').find('.ui-button').eq(1).css("min-width", "45px").css("height", "26px");
				$(this).closest('.ui-dialog').find('.ui-button').eq(2).addClass('button-default');
				$(this).closest('.ui-dialog').find('.ui-button').eq(2).css("min-width", "45px").css("height", "26px");
			 },
			 open:function(){
					$(".ui-widget-overlay").css({
						background:"#999999",
						opacity: ".30 !important",
						filter: "Alpha(Opacity=30)",
					});
				},
			 close:function(){
				$(this).dialog("destroy");
			}
		});
}

function isInt(number){
	return (number % 1 === 0) ? true : false;
}

$.fn.cpClipboard = function(inputElement) {
	inputElement.click(function(){
		$(this).select();
	});

	try {
		document.queryCommandState("copy");
	} catch (e) {
		throw "Navegador não suporta evento de cópia.";
	}

	this.click(function() {
		inputElement.focus().select();
		document.execCommand('copy');
	});

	return this;
}

function modalException(msg){
	$('#modalExceptionMsg').html(msg);
	$('#modalException').dialog({modal: true, resizable: false, width: 400, modal: true, title: 'Ocorreu um erro inesperado!',
			buttons: {
				"OK": function() {
						$(this).dialog("close");
				}
			}, create:function() {
				$(this).closest('.ui-dialog').find('.ui-button').eq(1).addClass('button-default');
				$(this).closest('.ui-dialog').find('.ui-button').eq(1).css("min-width", "45px").css("height", "26px");
				$(this).closest('.ui-dialog').find('.ui-button').eq(2).addClass('button-default');
				$(this).closest('.ui-dialog').find('.ui-button').eq(2).css("min-width", "45px").css("height", "26px");
			 },
			 open:function(){
					$(".ui-widget-overlay").css({
						background:"#999999",
						opacity: ".30 !important",
						filter: "Alpha(Opacity=30)",
					});
				},
			 close:function(){
				$(this).dialog("destroy");
			}
		});
}

function setFieldValue(field, val) {
	if (field.length) {
		field.val(val);
	}
}

function arrayJsonSearch(aObj, key, value) {
	searchObj = {};

	if (Array.isArray(aObj)) {
		$.each(aObj, function(i) {
			if (this[key] == value) {
				searchObj = this;
				return;
			}
		});
	}

	return searchObj;
}

function createDialog(dialog) {
	if ($('#' + dialog.single).length > 1) {
		return;
	}

	var buttons = [];

	if (!dialog.hideOk) {
		buttons[0] = {
			id: dialog.idOk || null,
			text: (!dialog.textOk ? 'Ok' : dialog.textOk),
			click: function() {
				var response = true;
				if (dialog.fnOk) {
					response = dialog.fnOk(dialog.fnArgumentosOk);
				}
				if (response || typeof response === 'undefined') {
					destroyDialog();
				}
			}
		}
	}

	if (!dialog.hideCancel) {
		buttons[1] = {
			id: dialog.idCancelar || null,
			text: (!dialog.textCancelar ? 'CANCELAR' : dialog.textCancelar),
			click: function() {
				var response = true;
				if (dialog.fnCancelar) {
					response = dialog.fnCancelar(dialog.fnArgumentosCancelar);
				}
				if (response || typeof response === 'undefined') {
					destroyDialog();
				}
			}
		}
	}

	configDefault = {
		resizable: false,
		modal: true,
		buttons: buttons,
		beforeClose: function() {
			if (dialog.fnBeforeClose) {
				return dialog.fnBeforeClose(dialog.fnArgumentosBeforeClose);
			}
		},
		create: function() {
			var uiDialog = $(this).closest('.ui-dialog');
			var uiButtons = $(uiDialog).find('.ui-button');

			$(uiButtons).eq(1).addClass(!dialog.classOk ? 'button-default' : dialog.classOk);
			$(uiButtons).eq(2).addClass(!dialog.classCancelar ? 'button-cancel' : dialog.classCancelar);

			$(uiDialog).find('.ui-dialog-title').html(dialog.htmlTitle);
			$(uiDialog).addClass('ui-dialog-new').removeClass('ui-corner-all');

			if (dialog.fnCreate) {
				dialog.fnCreate(dialog.fnArgumentosCreate);
			}
		},
		close: function() {
			destroyDialog();
		}
	};

	if (dialog['height']) {
		configDefault['height'] = dialog['height'];
	}

	if (dialog['width']) {
		configDefault['width'] = getMobileWidthForDialogs(dialog['width']);
	}

	var dialogObj = $(dialog.content).dialog($.extend(configDefault, dialog.config));
	$(dialogObj).find('a.close-on-click').on('click', function() {
		$(dialogObj).dialog().dialog('destroy');
	});

	function destroyDialog() {
		$(dialogObj).dialog('destroy');
		if (dialog.fnAfterDestroy) {
			dialog.fnAfterDestroy();
		}
	}

	$('.ui-widget-overlay').css({
		'background': 'none',
		'background-color': '#333'
	});

	if (dialog.classTitle) {
		$('.ui-dialog-titlebar').addClass(dialog.classTitle);
	}

	return dialogObj;
}

$.fn.autocompleteCest = function(params) {
	var cestField = $(this);

	cestField.autocomplete({
		minLength: 0,
		source: [],
		fnRenderItemUi: UiAutocompleteItem.cest,
		change: function(event, ui) {
			cestField.trigger("change");
		}
	});

	params.ncmField.change(function() {
		$.get("services/cest.lookup.php", {term: params.ncmField.val()}, function(data) {
			if (! cestField.is(":visible")) {
				return false;
			}

			var listaCest = JSON.parse(data);
			var reqFocus = false;
			cestField.val("");
			if (listaCest.length == 1) {
				cestField.val(listaCest[0].value);
				cestField.autocomplete("widget").hide();
			} else if (listaCest.length > 1) {
				reqFocus = true;
			} else {
				cestField.autocomplete("widget").hide();
			}

			cestField.autocomplete("option", "source", listaCest);

			if(reqFocus) {
				cestField.autocomplete("search", "");
				cestField.focus();
			}

			cestField.trigger("change");
		});
	});

	return this;
}

$.fn.contCaractere = function() {
	var inputTextFields = this.find("input[type='text']");

	inputTextFields.on("focusin blur keyup", function(e) {
		var numChars = $(this).val().length;

		if (e.type == "focusin") {
			$(this).css("padding-right", "25px");
			$("<div class='conta-caracteres-display display-default display-none'>" + numChars + "</div>").insertAfter($(this)).fadeIn(200);
		} else if(e.type == "keyup") {
			$(".conta-caracteres-display").text(numChars);
		} else {
			$(this).css("padding-right", "0");
			$(".conta-caracteres-display").fadeOut(200, function() {
				$(this).remove();
			});
		}
	});

	return this;
}

if ($.ui) {
	$.widget('ui.autocomplete', $.ui.autocomplete, {
		'_renderItem': function(ul, item) {
			var fnRenderItemUi = (typeof this.options.fnRenderItemUi === 'function' ? this.options.fnRenderItemUi : window[this.options.fnRenderItemUi] || UiAutocompleteItem.default);

			return $('<li>', {'class': 'ui-autocomplete-custom'}).data('item.autocomplete', item).append(
				$('<a>').append(
					fnRenderItemUi(item)
				)
			).appendTo(ul);
		}
	});
}

var UiAutocompleteItem = {
	'init': function() {
		Object.defineProperty(this, 'DEFAULT_CLASS', {'value': 'display-block ui-autocomplete-custom-item'});

		delete this.init;
	},

	'mainStruct': function(height) {
		return $('<div>', {'style': 'min-height:' + (height || 18) + 'px;'});
	},

	'default': function(item, height) {
		return UiAutocompleteItem.mainStruct(height).append(
			$('<span>', {'text': item.label, 'class': 'display-block'})
		);
	},

	'code': function(item, height) {
		return UiAutocompleteItem.default(item, height || 30).append(
			$('<span>', {'text': 'Cód.: ' + item.codigo, 'class': UiAutocompleteItem.DEFAULT_CLASS})
		);
	},

	'cest': function(item, height) {
		return UiAutocompleteItem.mainStruct().append(
			$('<span>', {'text': item.label, 'title': item.title})
		);
	},

	'condition': function(item, height) {
		return UiAutocompleteItem.default(item, height || 30).append(
			$('<span>', {'text': 'Condição: ' + item.condicao, 'class': UiAutocompleteItem.DEFAULT_CLASS})
		);
	},

	'cnpj': function(item, height) {
		return UiAutocompleteItem.default(item, height || 30).append(
			$('<span>', {'text': 'CNPJ/CPF.: ' + item.cnpj, 'class': UiAutocompleteItem.DEFAULT_CLASS})
		);
	},
};

function getDefaultValues() {
	var defaultValues = {
		'monetary': 0.0,
		'text': '-',
		'percentage': 0,
		'number': 0,
		'date': '0000-00-00',
		'decimal': 0.0,
		'bool': 0,
		'cep': '00000000'
	};
	return defaultValues;
}

function getDefaultValue(mask) {
	var defaults = getDefaultValues();
	return defaults[mask];
}

function aplicarMascara(value, tipo) {
	var valueMasked = '';
	var defaultValues = getDefaultValues();
	if(typeof tipo !== "undefined" && (! jQuery.isEmptyObject(tipo))) {
		valueMasked = value;

		if(! jQuery.isEmptyObject(tipo['values'])) {
			valueMasked = tipo['values'][valueMasked];
		}

		if((! valueMasked) && (typeof valueMasked !== 'number' && valueMasked != 0)) {
			if(! (tipo.mask in defaultValues)) {
				return valueMasked;
			}
			valueMasked = defaultValues[tipo.mask];
		}

		switch (tipo.mask){
			case "decimal":
			case "monetary":
				valueMasked = nroBraDecimais(valueMasked, 2);
				break;
			case "percentage":
				valueMasked = valueMasked + '%'
				break;
			case "date":
			case "datetime":
				valueMasked = formatDate(valueMasked);
				break;
			case "bool":
				var conditions = {
					"1": "Sim",
					"0": "Não"
				}
				if(conditions[valueMasked]) {
					valueMasked = conditions[valueMasked];
				} else {
					valueMasked = conditions["0"];
				}
				break;
			case "cnpjcpf":
				valueMasked = cpfCnpj(valueMasked);
				break;
			case 'cep':
				if(valueMasked > 0) {
					valueMasked = cep(String(zerosEsquerda(valueMasked, 8)));
				} else {
					valueMasked = cep(defaultValues[tipo.mask]);
				}
				break;
		}
	}
	return valueMasked;
}

function formatDate(date) {
	if(typeof date !== "string") {
		return date;
	}
	var separator = (date.indexOf('-') > 0) ? '-' : '/';

	var containsHour = (date.indexOf(' ') > 0 && date.indexOf(':') > 0);
	var aDateHour = (containsHour) ? date.split(' ') : '';
	var hour = (containsHour) ? aDateHour[1] : '';
	date = (containsHour) ? aDateHour[0] : date;

	var aDate = date.split(separator);
	separator = (separator == '-') ? '/' : '-';
	var output = '';

	if(aDate[2]) {
		output += aDate[2].trim() + separator;
	}

	if(aDate[1]) {
		output += aDate[1].trim() + separator;
	}

	if(aDate[0]) {
		output += aDate[0].trim();
	}

	if(containsHour) {
		output += ' ' + hour.substr(0, 8);
	}

	return output;
}

function cpfCnpj(v){
	v = v.replace(/\D/g,"");

	if (v.length <= 11) {
		v = v.replace(/(\d{3})(\d)/,"$1.$2");
		v = v.replace(/(\d{3})(\d)/,"$1.$2");
		v = v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
	} else {
		v = v.replace(/^(\d{2})(\d)/,"$1.$2");
		v = v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");
		v = v.replace(/\.(\d{3})(\d)/,".$1/$2");
		v = v.replace(/(\d{4})(\d)/,"$1-$2");
	}

	return v;
}

function exportarTabelaPDF(data, fileName) {
	var columns = [];
	$.each(data[0], function(idx, val) {
		columns.push({dataKey: idx, title: val});
	});

	delete data[0];

	var pdf = new jsPDF('p', 'pt', 'a4');
	pdf.autoTable(columns, data, {
		theme: 'grid',
		headerStyles: {
			fillColor: [187,187,187],
			textColor: 255,
			rowHeight: 15,
			valign: 'middle'
		},
		styles: {
			overflow: 'linebreak',
			fontSize: 8,
		},
		margin: 20
	});

	if (!fileName) {
		fileName = 'exportacao-' + new Date().toJSON().slice(0,10);
	}

	pdf.save(fileName + '.pdf');
}

function openExportDialog(dialogOptions) {
	displayWait('pleaseWait', true);
	$.get("templates/form.exportador.popup.php", function(content) {
		var dialog = {
			content: content,
			config: {
				title: dialogOptions['title'],
				width: 300
			},
			textOk: 'Exportar',
			fnOk: function() {
				var format = $('#popup-exportar-relatorio #formato').val();
				var customOptions = {};
				$('#options-container input').each(function() {
					customOptions[$(this).attr('data-option')] = $(this).prop('checked');
				})
				dialogOptions['callback'](format, customOptions);
				$('body').append($(dialogOptions['customOptions']));
			},
			fnCreate: function() {
				var d = $('#popup-exportar-relatorio').dialog();
				d.find('#formato').on('change', function() {
					d.find('#xls-cell-limit-info').hide();
					if($(this).val() == 'xls') {
						d.find('#xls-cell-limit-info').show();
					}
				});
				if(dialogOptions['customOptions']) {
					$('#options-container').append($(dialogOptions['customOptions']).html());
					$(dialogOptions['customOptions']).remove();
				}
				$('body').append($(dialogOptions['customOptions']));
			}
		}
		dialog['fnCancelar'] = dialog['fnBeforeClose'] = function() {
			$('body').append($(dialogOptions['customOptions']));
		}
		createDialog(dialog);
		closeWait('pleaseWait');
	});
}

function downloadArquivoExport(filename) {
	filename = "download.arquivo.tmp.php?file=" + filename;
	simulateClick(filename, 'export-download-file');
}

function simulateClick(url, obj) {
	aElement = document.getElementById(obj);
	aElement.href = url;
	aElement.click();
}

var BrowserDetect = {
	init: function() {
		this.browser = this.searchString(this.dataBrowser) || "Other";
		this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "Unknown";
		this.osName  = this.searchString(this.dataOS) || "Windows";
	},
	searchString: function(data) {
		for (var i = 0; i < data.length; i++) {
			var dataString = data[i].string;
			this.versionSearchString = data[i].subString;

			if (dataString.indexOf(data[i].subString) !== -1) {
				return data[i].identity;
			}
		}
	},
	searchVersion: function(dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index === -1) {
			return;
		}

		var rv = dataString.indexOf("rv:");
		if (this.versionSearchString === "Trident" && rv !== -1) {
			return parseFloat(dataString.substring(rv + 3));
		} else {
			return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
		}
	},
	dataBrowser: [
		{string: navigator.userAgent, subString: "Edge", identity: "Edge"},
		{string: navigator.userAgent, subString: "Chrome", identity: "Chrome"},
		{string: navigator.userAgent, subString: "MSIE", identity: "Explorer"},
		{string: navigator.userAgent, subString: "Trident", identity: "Explorer"},
		{string: navigator.userAgent, subString: "Firefox", identity: "Firefox"},
		{string: navigator.userAgent, subString: "Safari", identity: "Safari"},
		{string: navigator.userAgent, subString: "Opera", identity: "Opera"}
	],
	dataOS: [
		{string: navigator.appVersion, subString: "Win", identity: "Windows"},
		{string: navigator.appVersion, subString: "Mac", identity: "MacOS"},
		{string: navigator.appVersion, subString: "X11", identity: "Linux/UNIX"},
		{string: navigator.appVersion, subString: "Linux", identity: "Linux/UNIX"}
	]
};

//para vendas e notas, enviar dados loja virtual
function limparSetTipoIntegracaoModal(){
	//Esconde
	$("#sp_enviar_numero_nfe_loja_virtual").css("display","none");
	$("#sp_enviar_serie_loja_virtual").css("display","none");
	$("#sp_enviar_situacao_loja_virtual").css("display","none");
	$("#sp_enviar_chave_acesso_loja_virtual").css("display","none");
	$("#sp_enviar_rastreamento_loja_virtual").css("display","none");
	$("#sp_enviar_link_danfe_loja_virtual").css("display","none");
	$("#sp_enviar_status_loja_virtual").css("display","none");
	$("#sp_enviar_nfe_loja_virtual").css("display","none");
	$("#sp_enviar_status_loja_virtual").css("display","none");
	$("#sp_enviar_numero_venda_loja_virtual").css("display","none");
	$("#sp_enviar_pedido_loja_virtual").css("display","none");
	$("#infoWarn").css("height", "auto").css("margin-top", 92).css("padding-top", 10);
	//seta padrão "Não"
	$("#sp_enviar_numero_nfe_loja_virtual").val('false');
	$("#sp_enviar_serie_loja_virtual").val('false');
	$("#sp_enviar_situacao_loja_virtual").val('false');
	$("#sp_enviar_chave_acesso_loja_virtual").val('false');
	$("#sp_enviar_rastreamento_loja_virtual").val('false');
	$("#sp_enviar_link_danfe_loja_virtual").val('false');
	$("#sp_enviar_status_loja_virtual").val('false');
	$("#sp_enviar_nfe_loja_virtual").val('false');
	$("#sp_enviar_status_loja_virtual").val('false');
	$("#sp_enviar_numero_venda_loja_virtual").val('false');
	$("#sp_enviar_pedido_loja_virtual").val('false');
}

function validateCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g,'');
    if(cpf == '') return false;
    if (cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999")
            return false;
    var add = 0;
    for (i=0; i < 9; i ++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;
    add = 0;
    for (i = 0; i < 10; i ++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(10)))
        return false;
    return true;
}

function getAction() {
	return location.hash.replace( /^#/, "" ).split('/')[0];
}

function cloneObject(obj) {
    if (obj === null || typeof obj !== 'object') {
        return obj;
    }

    var temp = obj.constructor();
    for (var key in obj) {
        temp[key] = cloneObject(obj[key]);
    }

    return temp;
}

function alterarOpcoesFiltro(){
	var tipo = $("#filtroDinamico").val();
	$("[id^=filtroDinamico_]").parent().hide();
	$("#filtroDinamico_" + tipo + '\\[value\\]').parent().show();
	$("#filtroDinamico_" + tipo + '\\[value\\]').focus();
}

function inserirParFiltro(elemento){
	var tipo = $("#filtroDinamico").val();
	var tipoDescricao = $("#filtroDinamico option:selected").text();
	if (elemento.tagName == "SELECT"){
		var valor = $(elemento).val();
		var valorDescricao = $(elemento).find("option:selected").text();;
	} else {
		var valor = $(elemento).val();
		var valorDescricao = valor;
	}
	$("#filtroDinamico option:selected").prop("disabled", true);
	$(elemento).prop("disabled", true);
	var html = '<div class="tag-filtro">' +
		'<input type="hidden" name="filtroDinamico[' + tipo + '][value]" value="' + valor + '" >' +
		tipoDescricao + ':' + valorDescricao +
		'<a href="#" onclick="removerFiltroDinamico(this, \'' + tipo + '\');"><img src="images/remove.png" alt="" style="vertical-align:middle;margin-left:5px;" /></a></div>';
	$("#elementosFiltroDinamico").append(html);
	listar();
}

function removerFiltroDinamico(elemento, tipo){
	$("#filtroDinamico option[value=" + tipo + "]").prop("disabled", false);
	$("#filtroDinamico_" + tipo + '\\[value\\]').prop("disabled", false);
	$("#filtroDinamico_" + tipo + '\\[value\\]').val("");
	$(elemento).parent().remove();
	listar();
}

function capitalize(str) {
    return str.substr(0, 1).toUpperCase() + str.substr(1);
}

function validateNumbers(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && !e.ctrlKey) {
        return false;
    }
}

var Toast = {
	init: function() {
		Object.defineProperties(this, {
			'S': {'value': 'success'},
			'I': {'value': 'info'},
			'W': {'value': 'warning'},
			'E': {'value': 'error'}
		});

		var defaultTitles = [];
		defaultTitles[this.S] = 'Sucesso';
		defaultTitles[this.W] = 'Atenção';
		defaultTitles[this.I] = 'Informação';
		defaultTitles[this.E] = 'Erro';

		Object.defineProperty(this, 'DEFAULT_TITLES', {'value': defaultTitles});

		delete this.init;
	},
	_setConfig: function(config) {
		toastr.options = {
			'closeButton': true,
			'debug': false,
			'newestOnTop': true,
			'progressBar': false,
			'positionClass': 'toast-top-right',
			'preventDuplicates': false,
			'onclick': null,
			'showDuration': 1000,
			'hideDuration': 1000,
			'timeOut': 5000,
			'extendedTimeOut': 5000,
			'showEasing': 'swing',
			'hideEasing': 'linear',
			'showMethod': 'fadeIn',
			'hideMethod': 'fadeOut'
		}

		$.extend(toastr.options, config || {});
	},
	create: function(toast) {
		var type = (this.DEFAULT_TITLES[toast.type] != undefined ? toast.type : this.W);
		var msg = (toast.msg || '');
		var title = (toast.title || this.DEFAULT_TITLES[type]);

		this._setConfig(toast.config);

		toastr[type](msg, title);
	}
}

// COOKIE.
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name,"",-1);
}

function hopscotchInfo(id, step, cookie, modal) {
	modal = modal || false;

	var info = {
		id: id,
		steps: [
			{
				multipage: true,
				showNextButton: false
			}
		],
		onEnd: function() {
			if (cookie != undefined) {
				createCookie(cookie, true, 180);
			}

			if (modal) {
				$('#hopscotch_overlay').remove();
				$('#' + $(step['target']).attr('id')).css('z-index', 'initial');
			}
		},
		onStart: function() {
			if (modal) {
				$('body').append($('<div>', {'id': 'hopscotch_overlay'}));
				$('#' + $(step['target']).attr('id')).css('z-index', 101);
			}
		}
	};

	$.extend(info.steps[0], step);

	hopscotch.listen('show', function() {
		$(".hopscotch-bubble-number").addClass("hopscotch-info").removeClass("hopscotch-bubble-number").html("<i class='icon-info-sign'></i>");
	});

	hopscotch.startTour(info);
}

function endTour() {
	if (hopscotch.getState() != null) {
		hopscotch.endTour();
	}
}

function initFonts(){
	calcularTamanhoFonte(".resize-font");
	$(window).resize(function() {
		calcularTamanhoFonte(".resize-font");
	});
}

function calcularTamanhoFonte(seletor){
	$.each($(seletor), function(key, el){
		var h = $(el).height();
		$(el).css("font-size", (h*3)/4+"px");
	});
}

function strcasecmp(fString1, fString2) {
    var string1 = (fString1 + '').toLowerCase();
    var string2 = (fString2 + '').toLowerCase();
    if (string1 > string2) {
        return 1;
    } else if (string1 === string2) {
        return 0;
    }
    return -1;
}

function isAdblockEnabled(callback) {
	var testAdBait = $('<div>', { html: '&nbsp;', class: 'adsbox'});
	$('#footer-container').append(testAdBait);
	window.setTimeout(function() {
		callback((testAdBait && testAdBait[0].offsetHeight === 0) ? true : false);
		$(testAdBait).remove();
	}, 100);
}

function autoClose(){
	if(getUrlParameter('autoClose') == 1){
		window.close();
	}else if (window.location.href.indexOf("?autoClose=1") > -1) {
		window.close();
	}
}

function mostrarPreviewEmail(tipo) {
	createDialog({
		'config': {
			'width': 648
		},
		'htmlTitle': 'Visualização de Layout',
		'content': $('<div>').append(
			$.parseHTML(tinymce.get(tipo).getContent())
		),
		'hideOk': true,
		'hideCancel': true
	});
}

function copyText(id, message, title) {
	message = message || 'Texto copiado com sucesso';
	title = title || 'Texto copiado';

	document.getSelection().removeAllRanges();
	if (document.selection) {
	    var range = document.body.createTextRange();
	    range.moveToElementText(document.getElementById(id));
	    range.select().createTextRange();
	    document.execCommand("Copy");
	} else if (window.getSelection) {
		if(document.getElementById(id).tagName == 'INPUT') {
			var field = document.getElementById(id);
			field.focus();
			field.select();
		} else {
			var range = document.createRange();
			range.selectNode(document.getElementById(id));
			window.getSelection().addRange(range);
		}
		document.execCommand("Copy");
	}

	Toast.create({
		type: Toast.S,
		title: title,
		msg: message,
		config: {
			'closeButton': false,
			'timeOut': 3000
		}
	});
}

$.fn.populateSelect = function(options) {
	options['placeholder'] = options['placeholder'] || '';
    populateSelect(this.selector, options['values'], options['placeholder'], options['selected']);
    return this;
}

function populateSelect(select, values, placeholder, selectedOption) {
	$(select).html('');
	if(placeholder != '') {
		$(select).append(
			$('<option>', { value: '', text: placeholder, disabled: true, selected: true, class: 'display_none' })
		);
	}
	$.each(values, function(key, value) {
		$(select).append(
			$('<option>', { value: (typeof key == 'string' ? key.trim() : key), text: value})
		);
	});
	if(selectedOption) {
		$(select).find("option[value='" + selectedOption + "']").attr('selected', 'selected');
	}
}

$.fn.initInputSelector = function() {
	$.each($(this), function(key, element) {
		var label = $('label[for="' + element.id + '"]');

		$(element).wrap($('<div>', {'class': 'input-selector'}));
		$('<div>', {'class': 'triangle'}).insertAfter(element);
		label.insertAfter(element);
	});
}

$.fn.initTableHeaderFixed = function(headerClass) {
	var headerClass = headerClass || 'table-fixed-heading';
	var tableId = $(this).attr('id');

	$(this).wrap($('<div>', {'class': 'fixed-header'}));
	$('<div>', {'class': headerClass}).insertBefore($(this));

	var observer = new MutationObserver(function() {
		fixTableHeader(tableId);
	});

	observer.observe(document.querySelector('#' + $(this).attr('id') + ' .table-body'), {childList: true});
}

function fixTableHeader(tableId) {
	var tamanhos = [];
	var tableHeading = $('#' + tableId + ' .table-heading');

	$.each($('#' + tableId + ' .table-body .table-row:first .table-cell'), function() {
		tamanhos.push($(this).width() + parseFloat($(this).css('padding-left')) +  parseFloat($(this).css('padding-right')));
	});

	$.each($('#' + tableId + ' .table-heading .table-row:first .table-head'), function(key) {
		$(this).css('width', tamanhos[key] + 'px');
	});

	tableHeading.parents('.fixed-header').find('.table-fixed-heading').css('height', tableHeading.children().height() + 'px');
}

$.fn.createToggles = function() {
	this.create = function(element) {
		var idElement = $(element).children('input[type="checkbox"]').attr('id');
		if ($(element).find('label[for="' + idElement + '"]').length) {
			$(element).append(
				$(element).children('label[for="' + idElement + '"]'),
				$('<div>', {'class': 'toogle-checkbox'}).append(
					$('<label>').append(
						$(element).children('input[type="checkbox"]'),
						$('<span>')
					)
				)
			);
		}
	};

	$.each($(this), function(key, element) {
		this.create(element);
	}.bind(this));

	return this;
}

$.fn.createCheckBoxes = function() {
	this.create = function(element) {
		var idElement = $(element).children('input[type="checkbox"]').attr('id');

		if ($(element).find('label[for="' + idElement + '"][class="label-item-form-input"]').length) {
			$(element).wrap('<div class="vertical-align-inline"></div>')
			.parent().append(
				$(element).find('label[for="' + idElement + '"]')
			)
			.find('input[id="' + idElement + '"]').parent().append(
				$('<label>', {'for': idElement})
			);
		}
	};

	$.each($(this), function(key, element) {
		this.create(element);
	}.bind(this));

	return this;
}

function adicionarEventosMenuCompacto() {
	$(document).on('click touchstart', function(element) {
		if (!$(element.target).parents('#menu_container_compacto').length == 1 && element.target.id != 'menu_container_compacto') {
			if ($('#menu_container_compacto').hasClass('show') && $(element.target).parents('#top_menu').length != 1) {
				$('#menu_container_compacto').removeClass('show');
				$('#top_menu a').removeClass('expanded');
			}
		}
	});
}

function somenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla>47 && tecla<58)) {
    	return true;
    } else {
    	if (tecla==8 || tecla==0) {
    		return true;
    	} else {
    		return false;
    	}
    }
}

function formataCep(cep) {
	cep = formatNumber(String(cep));

	if(cep) {
		cep = (cep.substr(0, 2) + '.' + cep.substr(2, 3) + '-' + cep.substr(5, 3));
	}

	return cep;
}

function initPopovers(options) {
	(options.elements || $('[data-toggle="popover"]')).popover().on('show.bs.popover', function(e) {
		$(this).data('bs.popover').tip().addClass((options.classe || 'popover-ajuda'));
	});

	$(document).click(function(e) {
		if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('[data-toggle="popover"]').length === 0) {
			(options.elements || $('[data-toggle="popover"]')).popover('hide');
		}
	});
}

function setFieldFeedback(field, status, msg) {
	$('#' + field + '-message').attr('class', 'text-' + (status == 'error' ? 'danger' : status)).html(msg);
	$('#' + field).parent().addClass('has-' + status);
	if(status == 'error') {
		$('#' + field).parent().removeClass('has-success has-warning');
		$('#' + field + '-status').removeClass('fa fa-check fa-exclamation').addClass('fa fa-times');
	}else if(status == 'success') {
		$('#' + field + '-status').removeClass('fa fa-exclamation fa-times').addClass('fa fa-check');
		$('#' + field).parent().removeClass('has-error has-warning');
	}else if(status == 'warning') {
		$('#' + field + '-status').removeClass('fa fa-times fa-check').addClass('fa fa-exclamation');
		$('#' + field).parent().removeClass('has-error has-success');
	}
}

function unsetFieldFeedback(field) {
	$('#' + field + '-message').attr('class', '').html('');
	$('#' + field).parent().removeClass('has-success has-warning has-error');
	$('#' + field + '-status').removeClass('fa-check fa-exclamation fa-times fa');
}

function vincularMarcadoresListagem(dadosMarcadores) {
	$.each(dadosMarcadores, function(id, dados) {
		var linhaItem = $("#datatable tr[data-id='" + id + "']");
		var refDesc = "";
		var titleEstoque = "Estoque lançado";
		var existeEstoque = "S";
		var iconeEstoque = "images/e.gif";

		if (dados.origemRef) {
			iconeEstoque = "images/en.gif";
			titleEstoque += " pela referência";
			existeEstoque = "R";
		}

		if (dados.deposito.length > 0) {
			titleEstoque += " - Depósito: " + dados.deposito;
		}

		if (linhaItem.attr("estoque")) {
			linhaItem.attr("estoque", existeEstoque);
		} else {
			linhaItem.attr("existeEstoque", existeEstoque);
		}

		linhaItem.find("span[data-marcador='estoque']").append("<img style='float:left;' src='" + iconeEstoque + "' alt='Estoque lançado' title='" + titleEstoque + "'>");
	});
}

function testDuplicatedItens(findFunction) {
	var lista = [];
	var duplicados = [];
	var field;

	var removeInfos = function(field) {
		$(field).removeClass("ac_warning");
		$(field).addClass("tipsyOff");
		$(field).attr("title", "");
		$(field).closest("td").next().next().css("background-color", "#FFFFFF");
	}
	var addInfos = function(field) {
		$(field).addClass("ac_warning");
		$(field).attr("title", "Item repetido");
		$(field).removeClass("tipsyOff");
		$(field).closest("td").next().next().css("background-color", "#ffffcf");
	}

	$("input[name*='itens[produtoId][]']").each(function() {
		lista.push(this);
	});

	for (var i = 0; i < lista.length; i++) {
		for (var j = i + 1; j < lista.length; j++) {
			if (lista[i].value != 0 && lista[j].value != 0) {
				if (lista[i].value == lista[j].value) {
					duplicados.push(lista[i]);
					duplicados.push(lista[j]);
				}
			}
		}
	}

	if(typeof findFunction === "function") {
		$.each(duplicados, function(index, value) {
			duplicados[index] = findFunction($(this));
		});
	}

	$("#gritens input[id*='produto']").each(function() {
		field = "#" + $(this).attr("id");
		if (duplicados.indexOf(field) !== -1) {
			addInfos(field);
		} else if ($(field+"Id").val() > 0) {
			removeInfos(field);
		}
	});
}

function opcoesSaldo() {
	return JSON.stringify({
		"maior": $("#chbx_maior_zero").is(":checked"),
		"igual": $("#chbx_igual_zero").is(":checked"),
		"menor": $("#chbx_menor_zero").is(":checked")
	});
}

function selectMultipleCheckboxes(staticAncestorTable) {
	$(staticAncestorTable).on('click', "input[type='checkbox']", function(e){
		var checkboxes = document.querySelectorAll(staticAncestorTable + ' input[type="checkbox"]');

		if (lastChecked && e.shiftKey) {
			var indexLastChecked = $(staticAncestorTable + ' input[type="checkbox"]').index($(lastChecked));
			var indexCurrentChecked = $(staticAncestorTable + ' input[type="checkbox"]').index($(this));
			var check_or_uncheck = this.checked;

			if (indexLastChecked > indexCurrentChecked) {
				var low = indexCurrentChecked;
				var high = indexLastChecked;
			} else {
				var low = indexLastChecked;
				var high = indexCurrentChecked;
			}

			for (var i = 0; i < checkboxes.length; i++) {
				if (low <= i && i <= high) {
					var element = $(staticAncestorTable + ' input[type="checkbox"]').eq(i);
					element.prop('checked', check_or_uncheck);

					if (typeof selectedItems != 'undefined') {
						addSelectedCheckboxesToArray(element, selectedItems);
					}
				}
			}
		}
		lastChecked = this;
	});
}

function isCep(cep) {
	var formatCep = /^[0-9]{5}-[0-9]{3}$/;
	cep = cep.trim();
	if(cep.length > 0) {
		if(formatCep.test(cep)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function validateCNPJ(cnpj) {
	var b = [6,5,4,3,2,9,8,7,6,5,4,3,2];

    if((cnpj = cnpj.replace(/[^\d]/g,"")).length != 14) {
        return false;
    }

    if(/0{14}/.test(cnpj)) {
        return false;
    }

    for (var i = 0, n = 0; i < 12; n += cnpj[i] * b[++i]);
    if(cnpj[12] != (((n %= 11) < 2) ? 0 : 11 - n)){
        return false;
    }

    for (var i = 0, n = 0; i <= 12; n += cnpj[i] * b[i++]);
    if(cnpj[13] != (((n %= 11) < 2) ? 0 : 11 - n)){
        return false;
    }

    return true;
}

var DialogMessage = {
	'success': function(dialog) {
		dialog.htmlTitle = (dialog.htmlTitle || 'Sucesso');
		return this.show(dialog, 'ok');
	},
	'warning': function(dialog) {
		dialog.htmlTitle = (dialog.htmlTitle || 'Atenção');
		return this.show(dialog, 'warning');
	},
	'question': function(dialog) {
		dialog.htmlTitle = (dialog.htmlTitle || 'Atenção');
		dialog.textOk = (dialog.textOk || 'Sim');
		dialog.textCancelar = (dialog.textCancelar || 'Não');
		dialog.hideCancel = (dialog.hideCancel == undefined ? false : dialog.hideCancel);

		return this.show(dialog, 'question');
	},
	'info': function(dialog) {
		dialog.htmlTitle = (dialog.htmlTitle || 'Informação');
		return this.show(dialog, 'info');
	},
	'error': function(dialog) {
		dialog.htmlTitle = (dialog.htmlTitle || 'Erro');
		return this.show(dialog, 'error');
	},
	'show': function(dialog, type) {
		dialog.hideCancel = (dialog.hideCancel == undefined ? true : dialog.hideCancel);
		dialog.alertTitle = (dialog.alertTitle || '');
		return createDialog($.extend(dialog, {
			'content': $('<div>', {'class': 'container-fluid'}).append(
				$('<div>', {'class': 'alert-box alert-box-' + type + ' alert-box-transparent margin-top0'}).append(
					(dialog.alertTitle ? $('<h3>', {text: dialog.alertTitle, class: 'alert-box-title'}) : ''),
					$('<p>', {'html': dialog.description}),
					(dialog.content ? dialog.content : '')
				)
			),
			'width': getMobileWidthForDialogs(dialog.width || 440)
		}));
	}
};

function showDialogMessage(dialog) {
	return DialogMessage[dialog.status || 'question'](dialog);
}

function destacarLinks(texto) {
	return texto.replace(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=()]*)/g, function(url) {
		return '<a target="_blank" href="' + url + '">' + url + '</a>';
	})
}

function carregarTransicoes() {
	var deferred = $.Deferred();

	xajax_carregarTransicoes(function(data) {
		transicoes = data;

		deferred.resolve();
	});

	return deferred.promise();
}

function getConfiguredTransitions(situacao, usarId) {
	var configuredTransitions = [];
	var key = (typeof usarId == 'undefined' || !usarId) ? 'valor' : 'id';

	$.each(transicoes, function(index, transicao) {
		if (transicao[key + 'Origem'] == situacao) {
			configuredTransitions.push(transicao[key + 'Destino']);
		}
	});

	return configuredTransitions;
}

function setCircleColor(usarId) {
	var key = (typeof usarId == 'undefined' || !usarId) ? 'valor' : 'id';
	var attribute = (typeof usarId == 'undefined' || !usarId) ? 'situacao' : 'idSituacao';

	$('.linhaItem').each(function(index, item) {
		$.each(situacoes, function(index, situacao){
			if (situacao[key] == $(item).attr(attribute)) {
				$(item).find('.icon-circle').css('color', situacao.cor).attr('title', situacao.nome);
				return false;
			}
		});
	});
}

function reportErrorFsm(errors) {
	if (errors.length > 0) {
		createDialog({
			'content': $('<div>').append(
				$('<p>', {'text': 'Problema na alteração da situação:'}),
				$('<ul>').append(
					$.map(errors, function(error) {
						return $('<li>', {'text': error});
					})
				)
			),
			'htmlTitle': 'Alteração de situação',
			'width': 400,
			'hideCancel': true
		});
	}
}

function htmlId(id) {
	return (editandoVariacao ? id + htmlIdPos : id);
}

function setSearchFilter(returnAsArray) {
	if(returnAsArray === undefined) {
		returnAsArray = true;
	}
	var filter = [];
	$('input[data-filter], select[data-filter]').each(function(){
		filter[this.getAttribute('data-filter')] = this.value.trim();
	});
	if(returnAsArray) {
		return filter;
	}else {
		return arrayToQueryString(filter);
	}
}

function arrayToQueryString(array_in){
    var out = new Array();

    for(var key in array_in){
        out.push(key + '=' + encodeURI(array_in[key]));
    }
    return out.join('&');
}

function getAllUrlParams(url) {
	if(url === undefined) {
		url = window.location.href;
	}
	var queryString = url.split('?')[1];
	var obj = {};
	if (queryString) {
		queryString = queryString.split('#')[0];
		var arr = queryString.split('&');
		for (var i = 0; i < arr.length; i++) {
			var a = arr[i].split('=');
			var paramNum = undefined;
			var paramName = a[0].replace(/\[\d*\]/, function(v) {
				paramNum = v.slice(1,-1);
				return '';
			});
			var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];
			if (obj[paramName]) {
				if (typeof obj[paramName] === 'string') {
					obj[paramName] = [decodeURIComponent(obj[paramName])];
				}
				if (typeof paramNum === 'undefined') {
					obj[paramName].push(decodeURIComponent(paramValue));
				}
				else {
					obj[paramName][paramNum] = decodeURIComponent(paramValue);
				}
			}
			else {
				obj[paramName] = decodeURIComponent(paramValue);
			}
		}
	}

	return obj;
}

function popoverDescricaoProduto(params) {
	if (hasOverflow(params.selector + params.nro, params.styles)) {
		$(params.selector + params.nro).attr('data-id', 'popover' + params.nro).attr('data-toggle', 'popover').attr('data-trigger', ($(window).width() < 769 ? 'click' : 'hover')).attr('data-container', params.dataContainer).attr('data-html', true).attr('data-content', params.descricao).attr('data-placement', 'top');
		$(params.selector + params.nro).popover().data('bs.popover').tip().addClass('popover-ajuda');
	}
}

function hasOverflow(selector, styles) {
	styles = styles || '';
	var res = false;
	var style = '';

	$.each(styles, function(attr, value) {
		style += attr + ':' + value + ';';
	});

	$('body').append('<span class="tmp-element" style="display:none;' + style + '">' + $(selector).val() + '</span>');

	if ($(selector).width() < $('.tmp-element').width()) {
		res = true;
	}
	$('body .tmp-element').remove();

	return res;
}

function createDialogPessoasContato(idContato) {
	idContato = idContato || document.getElementById('idContato').value;
	displayWait('pleasewait', true, 'Carregando informações');
	xajax_buscarPessoasContato(idContato, function(data) {
		if (data.length) {
			var dialog = {
				'htmlTitle': 'Pessoas de contato',
				'width': 500,
				'content': $('<div>', {id: 'contatosVinculados'}),
				'idOk': 'btnOk',
				'textCancelar': 'Fechar',
				'fnCreate': function(){
					$('#btnOk').remove();
				}
			};
			createDialog(dialog);
			$('<table>', {id: 'tableContato', class: 'grid'}).appendTo('#contatosVinculados').append(
				$('<tr>', {id: 'header-tabela'}).append(
					$('<th>', {style: 'width:25%;padding:2pt;text-align:center;'}).html('Nome'),
					$('<th>', {style: 'width:25%;padding:2pt;text-align:center;'}).html('Celular'),
					$('<th>', {style: 'width:20%;padding:2pt;text-align:center;'}).html('Fone'),
					$('<th>', {style: 'width:35%;padding:2pt;text-align:center;'}).html('E-mail')
					)
				);
			data.forEach(function(item) {
				$('<tr>', {}).appendTo('#contatosVinculados tbody').append(
					$('<td>', {style: 'padding: 2pt;text-align:center;'}).html(item.nome),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.celular),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.fone),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.email)
				);
			});
		}else {
			DialogMessage.info({
				'htmlTitle': 'Informação',
				'description': 'Não foram localizadas pessoas de contato.'
			});
		}
		closeWait('pleasewait');
	});
}

$.fn.datepickerHolidays = function() {
    var feriados = [];
    var holidaysRequest = function(year) {
        if (feriados[year] == undefined) {
            $.ajax({
                type: 'POST',
                url: 'utils/requestMethods.php',
                dataType: 'json',
                data: {action: 'obterFeriados', year: year},
            }).done(function(f) {
                feriados[year] = f;
                $('.date-pick').datepicker('refresh');
            });
        }
    };
    $(this).datepicker({
        dateFormat: "dd/mm/yy",
        startDate: '01/01/1970',
        beforeShow: function() {
            holidaysRequest(new Date().getFullYear());
        },
        onChangeMonthYear: function(year) {
            holidaysRequest(year);
        },
        beforeShowDay: function(dateText) {
            var day = ('0' + dateText.getDate()).slice(-2);
            var month = ('0' + (dateText.getMonth() + 1)).slice(-2);
            var year = dateText.getFullYear();
            var date = year + '-' + month + '-' + day;
            if (! $.isEmptyObject(feriados[year])) {
                var feriado = feriados[year]['dates'].indexOf(date);
                if (feriado != -1) {
                    return [true, "holiday-background-color", feriados[year]['holidays'][feriado]];
                }
            }
            return [true, "", ""];
        }
    });
}

$.fn.cep = function() {
	$(this).mask('00000-000')
		.on('focus', function() {
			$(this).val($(this).masked($(this).val()));
		});
    return this;
}

$.fn.uncep = function() {
	$(this).off().unmask();
    return this;
}

$.fn.initSelectedCheckboxes = function(fnOnClick) {
    selectedItems = [];
	var element = $(this);

	$(document).on('click', element.selector + ' :checkbox', function() {
		addSelectedCheckboxesToArray($(this), selectedItems);

		if (fnOnClick) {
			fnOnClick();
		}
	});
}

function addSelectedCheckboxesToArray(element, array, values) {
	var reg = new RegExp('^\\d+$');
	var id = parseInt($(element).val());
	var i = getIndexSelectedItem(array, id);
	var isChecked = $(element).is(':checked');
	var isDigit = reg.test(id);
	var attrValue = parseFloat($(element).parents('tr').attr('valor'));

	if (isDigit && typeof id != 'undefined') {
		if (isChecked && i == -1 && id != 0) {
			var item = {};
            if (values != undefined) {
                item[id] = values;
            } else {
                item[id] = isNaN(attrValue) ? 0 : attrValue;
            }
			array.push(item);
		} else if (!isChecked && i != -1) {
			array.splice(i, 1);
		}
	}
}

function renderCheckedItems(array, selector) {
	$.each($('#' + selector + ' :checkbox'), function() {
		if (getIndexSelectedItem(array, $(this).val()) != -1) {
			$(this).attr('checked', true);
		} else {
			$(this).attr('checked', false);
		}
	});
}

function getIndexSelectedItem(array, idItem) {
	var i = -1;

	$.each(array, function(key, item) {
		if (!$.isEmptyObject(item) && Object.keys(item)[0] == idItem) {
			i = key;
		}
	});

	return i;
}

function uncheckSelectedItems() {
	selectedItems = [];

	$('input:checkbox:checked').each(function() {
		$(this).prop('checked', false);
	});

	countChecked2();
	$('.warn-search').hide();

	return false;
}

function getIdsSelectedItems(params) {
	var asString = (params && params.asString) || false;
	var arrayItems = (params && params.array) || selectedItems;
	var ids = (asString ? '' : []);

	$.each(arrayItems, function() {
		var id = Object.keys(this)[0];

		if (asString) {
			if (ids == '') {
				ids = id;
			} else {
				ids += ',' + id;
			}
		} else {
			ids.push(id);
		}
	});

	return ids;
}

var whatsApp = {
	uri: 'https://web.whatsapp.com/send?',
	create: function(phone, text) {
		var dialog = {
			content:
				 $('<div>', {class: 'container'}).append(
				 	$('<div>', {class: 'row form-group'}).append(
				 		$('<div>', {class: 'col-xs-12'}).append(
				 			$('<label>', {class: 'titulo_input', text: 'Celular', for: 'whatsAppPhone'}),
				 			$('<input>', {class:'input_text', type: 'text', id: 'whatsAppPhone', placeholder: 'Informe o nº do celular do contato'}).val(phone).on('blur', function() {
				 				this.value = formatarTelefone(this.value);
				 			})
				 		)
				 	),
				 	$('<div>', {class: 'row'}).append(
				 		$('<div>', {class: 'col-xs-12'}).append(
				 			$('<label>', {class: 'titulo_input', text: 'Mensagem', for: 'whatsAppMessage'}),
				 			$('<textarea>', {class:'input_textarea', type: 'text', id: 'whatsAppMessage', rows: 8, placeholder: 'Mensagem que deseja enviar'}).val(text)
				 		)
				 	)
				),
			config: {
				"draggable": false,
				"resizable": false,
				"modal": true,
				"title": 'Enviar por WhatsApp',
				"width": 460,
				"height": 360
			},
			textOk: "Enviar",
			fnOk: function() {
				if ($('#whatsAppPhone').val().trim().length) {
					whatsApp.sendMessage($('#whatsAppPhone').val(), $('#whatsAppMessage').val())
				} else {
					Toast.create({"type": Toast.W, "msg": "É necessário informar o n° do celular."});
					return false;
				}
			},
			hideCancel: true
		};
		createDialog(dialog);
	},
	sendMessage: function(phone, text) {
		phone = limparNumero(phone);
		if (phone.length != 13) {
			phone = '55' + phone;
		}
		text = encodeURIComponent(text);
		window.open(this.uri + 'phone=' + phone + '&text=' + text);
	}
}

$.fn.listContextMenu = function(options) {

	function bindEvents(e) {
		$.each(options['bindings'], function(i) {
			$('#jqContextMenu #' + i).on('click', function() {
				$('#jqContextMenu').hide();
				var attrs = {};
				$.each(options['attrs'], function(i, attr) {
					attrs[attr] = $(e.target).closest('tr').attr(attr);
				});
				$('.hightlight-context').removeClass('hightlight-context');
				options['bindings'][this.id](attrs);
			});
		});
	}

	$(this).contextMenu(options['menu'], {
		onShowMenu: function(e, menu) {
			var tr = $(e.target).closest('tr');
			var dataActions = tr.attr('data-actions');
			if (dataActions) {
				var actions = (dataActions.split(' '));
				$('#listaMenu li:not(.context-menu-separator)', menu).hide();
				for (var action in actions) {
					$('#' + actions[action], menu).show();
				}
			}
			bindEvents(e);
			if (options['onShowMenu']) {
				menu = options['onShowMenu'](e, menu);
			}
			$('li:not(.context-menu-separator)', menu).each(function() {
				if($(this).css('display') == 'block') {
					$(this).addClass('context-menu-enabled');
				}
			})
			$('.context-menu-separator', menu).show();
			if ($('.context-menu-separator', menu).nextAll('.context-menu-enabled').length == 0 ||
				$('.context-menu-separator', menu).prevAll('.context-menu-enabled').length == 0) {
				$('.context-menu-separator', menu).hide();
			}
			$('.hightlight-context').removeClass('hightlight-context');
			tr.addClass('hightlight-context');
			return menu;
		},
		onCloseMenu: function() {
			$('.hightlight-context').removeClass('hightlight-context');
		}
	});
}