var XMLNFe = null;
var enviandoNFe = false;
var serie = 0;
var enviarEmailCliente = "N";
var sgtContas = 0;
var sgtEstoque = 0;
var appAcessoHardware = "appJava";

function desabilitarLinks() {
	$("#nfe-servicos").css("display", "none");
}

function habilitarLinks() {
	$("#nfe-servicos").css("display", "inline");
	enviandoNFe = false;
}

function mostrarResultado() {
	$("#justificativa_contingencia").val("");
	$("#divContingencia").hide();

	$("#slotAcompanhamento").hide();
	$("#divCancelarNota").hide();
	$("#divConsultaNFe").hide();
	$("#imgResultado").html("");
	$("#slotResultado").show();
}

function mostrarAcompanhamento() {
	$("#slotResultado").hide();
	$("#slotAcompanhamento").show();
}

function init() {
	limparInformacoes();

	$("#divCancelarNota, #slotAvisoCorrecao").hide();
	$("#divConsultaNFe").hide();
	$("#chaveAcesso").html("");
	$("#imgResultado").html("");
	$("#enviarContingencia").prop("checked", false);
	$("#divCartaCorrecao").hide();
	xajax_getSenhaCartao($("#idNota").val());
	xajax_getSerieNota($("#idNota").val());

	if ($("#parametro_marcado_para_envio_email").val() == "S") {
		$("#enviarNotaPorEmail").prop("checked", true);
		$("#exibirDetalhesEmail").show();
		enviarEmailCliente = "S";
	} else {
		enviarEmailCliente = "N";
		$("#exibirDetalhesEmail").hide();
		$("#enviarNotaPorEmail").prop("checked", false);
	}

	if ($("#parametro_marcado_para_lancar_estoque").val() == "S"){
		$("#enviarNotaLancarEstoque").prop("checked", true);
	} else {
		$("#enviarNotaLancarEstoque").prop("checked", false);
	}
	mostrarDivEstoque();

	if ($("#parametro_marcado_para_lancar_contas").val() == "S"){
		$("#enviarNotaLancarContas").prop("checked", true);
	} else {
		$("#enviarNotaLancarContas").prop("checked", false);
	}
	xajax_obterOpcoesDepositos("deposito_estoque_nfe_popup", "", "N");

	sgtEstoque = 0;
	sgtContas = 0;

	$("#liConsularRecibo").click(function(){
		if(sgtContas > 1) {
			debugEmail("Evento disparado no consultar recibo - Conta a receber duplicada", "Situação da NFE: " + $("#nfeSituacaoNF").val());
		}
	});

	$("#lojaInutilizacao").html($("#loja").html());
}

function callbackSerieNota(aSerie) {
	serie = aSerie;
	if ((serie >= 900) && ($("#opcao").val() == "envio")) {
		$("#divContingencia").show();
	} else {
		$("#divContingencia").hide();
	}
}

function exibirChaveAcesso(chaveAcesso) {
	$("#chaveAcesso").html(chaveAcesso);
}

function callbackErroValidacao() {
	$("div#form-nfe").hide();
	$("div#erros").show();
}

function voltar() {
	$("div#erros").hide();
	$("div#form-nfe").show();
}

function mostraJustificativa() {
	$("#slotResultado").hide();
	$("#slotAcompanhamento").hide();
	$("#divConsultaNFe").hide();
	$("#divCancelarNota").show();
}

function exibirConsulta() {
	if ($("#divConsultaNFe").css("display") == "none") {
		xajax_getChaveAcessoPorIdNota($("#idNota").val());
		$("#slotResultado").hide();
		$("#slotAcompanhamento").hide();
		$("#divCancelarNota").hide();
		$("#divConsultaNFe").show();
	} else {
		$("#divConsultaNFe").hide();
	}
}

function limparInformacoes() {
	$("#ulAcompanhamento > li").remove();
	$("#divResultado").html("");
	$("#nfe-inf").html("");
}

/* Inicialização do applet */
function configApplet(config) {
	try {
		if ($("#certificadoArmazenado").val() == "N") {
			var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
			var parameters = [{"senha": config.senha,"tipoCertificado": config.tipoCertificado,"certificado": config.certificado, "certs": config.certs, "alias" : config.alias}];
			jc.config(parameters, function(err, data){});
		}
		xajax_getEnvelopeConfig(serie);
		xajax_getSeriePadraoInutilizacao(tipo);
	} catch (e) {
		callbackConfig(null);
	}
}

function exibirMensagemCertificadoVencendo(mensagem, dataRef) {
	if ((mensagem != "") && (mensagem != "00/00/00")) {
		var posBarra = mensagem.indexOf("/");
		var dataV = mensagem.substr((posBarra-2), 8);
		var dataVH = mensagem.substr((posBarra-2));
		var dif = diferencaDatas("d", dataRef, dataV, "");
		if (dif <= 30) {
			$("#msgValCert").html('Seu certificado vence em : ' + dataVH).addClass('alert-box-warning').removeClass('success');
		}
	}
}

function callbackConfigArmazenado(envelope, status , val) {
	if ((status == "1") || (status == true)) {
		$('#msgValCert').html('Certificado válido até ' + val).addClass('success');
		exibirMensagemCertificadoVencendo(val, $("#data_atual").val());
		callbackConfig(envelope);
	} else {
		$("<div class='problem text_left'><p>Falha na obtenção do certificado digital<br/>Não foi possível acessar seu certificado</p><input class='button-default' type='button' value='Tentar novamente' onclick='init();' /></div>").appendTo("#divResultado");
		callbackConfig(envelope);
	}
}

function habilitarOpcoesNfe(){
	ocultarOpcoesNfe();
	if ($("#opcao").val() == "envio") {
		$("#msg_layout").show();
		$("#divConsultaNFe").hide();
		$("#liBaixarXmlCancelamento").hide();
		$("#liEnviar").show();
		$("#liEnviarContainer").show();
		$("#liEnviarEmail").parent().show();
		$("#liEnviarContingencia").show();
		$("#liBaixarXmlCarta").hide();
	} else if ($("#opcao").val() == "cancelamento") {
		$("#divConsultaNFe").hide();
		$("#liBaixarXmlCancelamento").hide();
		$("#divCancelarNota").show();
		$("#liBaixarXmlCarta").hide();
	} else if ($("#opcao").val() == "cartaCorrecao") {
		$("#divConsultaNFe").hide();
		$("#liBaixarXmlCancelamento").hide();
		$("#divCartaCorrecao").show();
		$("#liBaixarXmlCarta").hide();
	} else if ($("#opcao").val() == "outras") {
		if (($("#nfeSituacaoNF").val() != "4") && ($("#nfeSituacaoNF").val() != "9") && ($("#nfeSituacaoNF").val() != "6") && ($("#nfeSituacaoNF").val() != "7") && ($("#nfeSituacaoNF").val() != "3")) {
			$("#liPreValidar").show();
		} else {
			$("#liBaixarXmlCancelamento").show();
		}
		$("#liXML").show();

		$("#liStatusServico").show();
		if ($("#nfeSituacaoNF").val() == "3" || $("#nfeSituacaoNF").val() == "6" || $("#nfeSituacaoNF").val() == "7" || $("#nfeSituacaoNF").val() == "10") {
			$("#exibeConsulta").show();
		}
	} else if ($("#opcao").val() == "consultaRecibo"){
		limparInformacoes();
		consultarRecibo($('#idNota').val());
	} else if ($("#opcao").val() == "consultaSituacao"){
		limparInformacoes();
		consultarSituacao($('#idNota').val());
	} else if ($("#opcao").val() == "manifestacao"){
		$("#divManifestacao").show();
	} else if ($("#opcao").val() == "inutilizacao"){
		$("#divInutilizacao").show();
	} else if ($("#opcao").val() == "downloadSefaz"){
		$("#divDownloadSefaz").show();
	}
}

function desabilitarOpcoesNfe(data){
	certificadoOk = false;
	var opcao = $('#opcao').val();
	if (opcao == 'outras' || opcao == 'consultaRecibo' || opcao == 'consultaSituacao') {
		habilitarOpcoesNfe();
	} else {
		ocultarOpcoesNfe();
		$("<div class='col-xs-12 alert-box alert-box-error'><p>Falha na obtenção do certificado digital<br/>" + data + "</p><input class='button-default' type='button' style='margin-top:20px !important;' value='Tentar novamente' onclick='init();' /></div>").appendTo("* #divResultado");
	}
}

function ocultarOpcoesNfe() {
	$("#liConsultaSituacao").hide();
	$("#liEnviarContingencia").hide();
	$("#divCartaCorrecao").hide();
	$("#msg_layout").hide();
	$("#liCancelarNota").hide();
	$("#divCancelarNota").hide();
	$("#liConsularRecibo").hide();
	$("#divManifestacao").hide();
	$("#divInutilizacao").hide();
	$("#divDownloadSefaz").hide();
	$("#liPreValidar").hide();
	$("#liEnviar").hide();
	$("#liEnviarContainer").hide();
	$("#liEnviarEmail").parent().hide();
	$("#liStatusServico").hide();
	$("#liXML").hide();
	$("#exibeConsulta").hide();
}

function callbackConfig(envelope) {
	if ($("#certificadoArmazenado").val() == "N") {
		$("* #divResultado").html("");
		var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
		jc.getMessages(function(err,data){
			if(!err){
				certificadoOk = true;
				jc.getAdditionalInformation(function(err, data){
					if(!err){
						$('#msgValCert').html('Certificado válido até ' + data).addClass('success');
						exibirMensagemCertificadoVencendo(data, $("#data_atual").val());
						habilitarOpcoesNfe();
					}else{
						desabilitarOpcoesNfe(data);
					}
				});
			}else{
				desabilitarOpcoesNfe(data);
			}
		});
	}else{
		habilitarOpcoesNfe();
	}

	$("div#nfe-wait").hide();
	$("div#nfe-servicos").show();
}

/* Status do serviço */
function statusServico() {
	limparInformacoes();
	desabilitarLinks();
	xajax_getEnvelopeStatus();
}

function callbackStatus(doc) {
	mostrarResultado();
	try {
		if (doc == "") throw "";
		xmlDoc = loadXMLString(doc);
		try {
			$("#divResultado").html("<p>" + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + "</p>");
			try {
				$("#divResultado").html($("#divResultado").html() + "<p>Tempo médio de resposta: " + xmlDoc.getElementsByTagName("tMed")[0].childNodes[0].nodeValue + "s</p>");
			} catch (e) {
				//
			}
			var cStat = 0;
			try {
				cStat = xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue;
			} catch (e1) {
			}
			if (cStat == 107) {
				$("<img src='images/ok.gif'/>").appendTo("#imgResultado");
			} else {
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			}
		} catch (e) {
			$("#divResultado").html("<p>Não foi possível obter o status do serviço.</p><p>" + doc + "</p><p>" + e + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		}
	} catch (e) {
		$("#divResultado").html("<p>O servidor do sefaz do seu estado não está respondendo.</p><p>Caso o servidor esteja fora do ar, você pode utilizar o ambiente de contingência, especial para este fim.</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
	}
	habilitarLinks();
}

/* Pré-validação de XML de nota fiscal */
function preValidar(idNota) {
	limparInformacoes();
	desabilitarLinks();
	mostrarResultado();
	xajax_preValidarXML(idNota, "");
}

/* Envio de nota fiscal */
function enviar(idNota) {
	if (! enviandoNFe) {
		var contingencia = (($("#enviarContingencia").prop("checked") == true)?"S":"N");
		if ((contingencia == "S") && ($("#justificativa_contingencia").val().length < 15)) {
			alert("A justificativa deve ter no mínimo 15 caracteres");
			$("#justificativa_contingencia").focus();
		} else {
			enviandoNFe = true;
			limparInformacoes();
			desabilitarLinks();
			mostrarAcompanhamento();
			xajax_preValidarXML(idNota, $("#justificativa_contingencia").val(), $("#data_contingencia").val(), $("#hora_contingencia").val(), "assinar()", contingencia);
		}
		$("#liEnviarEmail").parent().hide();
		$("#envioDocumentoEmail").hide();
	}
}

function assinar() {
	$("<li id='liAssinatura'>Assinatura</li>").appendTo("#ulAcompanhamento");
	xajax_getEnvelopeAssinatura($("#idNota").val(), $("#justificativa_contingencia").val(), $("#data_contingencia").val(), $("#hora_contingencia").val());
}

function callbackAssinar(idNota, xmlNF, cupom) {
	var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
	jc.signXml([{"xml" : xmlNF}], function(err, data){
		if(!err){
			$("#liAssinatura").html($("#liAssinatura").html() + "  <img src='images/check.gif'/>");
			setSignedXML(data);
			xajax_updateAssinatura(idNota, encode64(data), cupom, $("#idControl").val());
			$("<li id='liValidacao'>Validação</li>").appendTo("#ulAcompanhamento");
		}else{
			$("#liAssinatura").html($("#liAssinatura").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + data + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		}
	});
}

function setSignedXML(xml){
	XMLNFe = xml;
}

function callbackJaAssinado(idNota, xmlNF, cupom) {
	try {
		XMLNFe = xmlNF;
		xajax_updateAssinatura(idNota, encode64(xmlNF), cupom, $("#idControl").val());
		$("#liAssinatura").html($("#liAssinatura").html() + "  <img src='images/check.gif'/>");
		$("<li id='liValidacao'>Validação</li>").appendTo("#ulAcompanhamento");
	} catch (e) {
		$("#liAssinatura").html($("#liAssinatura").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

var passos = "";
function callbackEnviar(idLote, envelope, erro, cupom) {
	$("#liValidacao").html($("#liValidacao").html() + "  <img src='images/check.gif'/>");
	$("<li id='liEnvio'>Envio</li>").appendTo("#ulAcompanhamento");
	if ((erro != "") && (erro != undefined)){
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + erro + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		try {
			xmlDoc = loadXMLString(envelope);

			var tMed = 10;
			try {
				tMed = xmlDoc.getElementsByTagName("tMed")[0].childNodes[0].nodeValue;
			} catch (e) {}
			if (tMed > 10){
				tMed = 10;
			}

			var callback = null;
			if (xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue == 103) {
				callback = "consultarRecibo(" + $("#idNota").val() + ", " + cupom + ")";
				callback = "setTimeout('" + callback + "', " + (tMed * 1 + 1) * 1000 + ")";
				$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/check.gif'/>");
					var idNota = $("#idNota").val();
					if (idNota == undefined){
						mostrarResultado();
						$("#divResultado").html("<p style='color: red;'>Identificação da nota não localizada</p>");
						$("<img src='images/no.gif'/>").appendTo("#imgResultado");
					} else {
						try {
							xajax_updateEnvio(idLote, encode64(envelope), callback, idNota);
						} catch (e){
							alert("Ocorreu um problema no envio das informações para o servidor. Motivo: " + e);
						}
					}
			} else {
				$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/warn.gif'/>");
				mostrarResultado();
				$("#divResultado").html("<p style='color: red;'>" + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + "</p>");
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
				xajax_setarMensagemNFe($("#idNota").val(), encode64(xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue));
				xajax_alterarSituacaoNota($("#idNota").val(), 5);
				habilitarLinks();
			}
		} catch (e) {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		}
	}
}

function consultarRecibo(idNota, cupom) {
	$("<li id='liRetorno'>Obtenção do retorno</li>").appendTo("#ulAcompanhamento");
	xajax_getEnvelopeRecibo(idNota, cupom, function (data) {
		mostrarResultado();
		enviandoNFe = false;
		if (data.erros.rejeicao != '') {
			$("#divResultado").append('<div class="code" style="margin-bottom:30px;">' + data.erros.rejeicao + '<div>');
		}
		if (data.erros.msg != '') {
			switch (data.erros.tipo) {
				case 'error':
					$("#divResultado").append('<div class="container-fluid col-xs-12 alert-box alert-box-error" style="margin-bottom:30px;"><h3 class="alert-box-title">Falha na consulta</h3><p>' + data.erros.msg + '</p></div>');
					break;
				case 'warning':
					$("#divResultado").append('<div class="container-fluid col-xs-12 alert-box alert-box-warning" style="margin-bottom:30px;"><h3 class="alert-box-title">Envio rejeitado</h3><p>' + data.erros.msg + '</p></div>');
					break;
			}
		}
		if (data.erros.rejeicao == '' && data.erros.msg == '') {
			$("#divResultado").append('<div class="container-fluid col-xs-12 alert-box alert-box-ok" style="margin-bottom:30px;"><h3 class="alert-box-title">Sucesso no envio</h3><p>' + data.msgSucesso + '</p></div>');
			displayWait("waitNotas", true, "Atualizando dados da nota, aguarde...");

			var lancaContas = "";
			if ($("#enviarNotaLancarContas").prop("checked")){
				lancaContas = "S";
			} else {
				lancaContas = "N";
			}
			sgtContas += 1;
			if(sgtContas > 1) {
				debugEmail("Conta a receber duplicada ao emitir NFe", "nfe: " + idNota + "\nStatus do checkbox: " + $("#enviarNotaLancarContas").prop("checked"));
				lancaContas = 'N';
			}

			xajax_updateRecibo(data.idLote, encode64(data.xml), "S", xajax.getFormValues("formEnvioDocumento"), enviarEmailCliente, false, lancaContas);
		}
		if (data.erros.acao == 'consultarSituacao') {
			$("#divResultado").append('<a href="#" onclick="consultarSituacao($(\'#idNota\').val()); return false;">Clique aqui para atualizar a situação<a>.</p>');
		}
		if (data.xml != '') {
			$("#divResultado").append('<a onclick="$(\'#xml_retorno\').slideToggle();">Exibir XML retornado</a><div id="xml_retorno" class="code" style="margin-top:10px;display:none;">' + data.xml.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</div>');
		}
	});
}

/* Consulta situação da nota fiscal */
function consultarSituacao(idNota) {
	limparInformacoes();
	desabilitarLinks();
	mostrarAcompanhamento();
	$("<li id='liEnvio'>Envio</li>").appendTo("#ulAcompanhamento")
	xajax_getEnvelopeSituacao(idNota);
}

function callbackSituacao(idNota, envelope, erros) {
	try {
		if (erros != "") {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		} else {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/check.gif'/>");
			xmlDoc = loadXMLString(envelope);
			xajax_updateSituacao(idNota, encode64(envelope), xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, "S");
			mostrarResultado();
		}
	} catch (e) {
		alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + envelope);
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

/* Cancelamento de nota fiscal */
function cancelarNota(idNota) {
	if (($("#justificativa").val().length) >= 15) {
		limparInformacoes();
		desabilitarLinks();
		mostrarAcompanhamento();
		$("<li id='liEnvio'>Envio</li>").appendTo("#ulAcompanhamento")
		xajax_getEnvelopeCancelamento(idNota, $("#justificativa").val());
	} else {
		alert("A justificativa deve possuir no mínimo 15 caracteres!")
	}
}

function callbackAssinarCancelamento(idNota, xmlNF, erros, cupom) {
	if (erros != "") {
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
		jc.signXml([{ "xml": xmlNF}], function(err, data){
			if(!err){
				setSignedXML(data);
				xajax_cancelarNFe(idNota, encode64(data), cupom);
			}else{
				alert("Não foi possível concluir a assinatura do cancelamento. Motivo: " + data);
				$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
				mostrarResultado();
				$("#divResultado").html("<p style='color: red;'>" + data + "</p>");
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
				habilitarLinks();
			}
		});
	}
}

function callbackJaAssinadoCancelamento(idNota, xmlNF, erros, cupom) {
	try {
		if (erros != "") {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		} else {
			XMLNFe = xmlNF;
			xajax_cancelarNFe(idNota, encode64(xmlNF), cupom);
		}
	} catch (e) {
		alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + xmlNF.replace(/</g, "&lt;").replace(/>/g, "&gt;"));
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function callbackCancelamento(envelope, erros) {
	try {
		if (erros != "") {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		} else {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/check.gif'/>");
			xmlDoc = loadXMLString(envelope);
			xajax_updateCancelamento($("#idNota").val(), encode64(envelope), xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue);
			mostrarResultado();
		}
	} catch (e) {
		alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + envelope.replace(/</g, "&lt;").replace(/>/g, "&gt;"));
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function loadXMLString(txt) {
	try // Internet Explorer
	{
		xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async = "false";
		xmlDoc.loadXML(txt);
		return (xmlDoc);
	} catch (e) {
		try // Firefox, Mozilla, Opera, etc.
		{
			parser = new DOMParser();
			xmlDoc = parser.parseFromString(txt, "text/xml");
			return (xmlDoc);
		} catch (e) {
			alert(e.message)
		}
	}
	return (null);
}

function downloadXML() {
	$('#idNota1').val($('#idNota').val());
	$("#direcionador_nfe").attr("action", "relatorios/nfe.xml.php");
	$("#direcionador_nfe").attr("method", "post");
	$("#direcionador_nfe").attr("target", "_blank");
	$('#direcionador_nfe').submit();
	$("#direcionador_nfe").removeAttr("action");
	$("#direcionador_nfe").removeAttr("method");
	$("#direcionador_nfe").removeAttr("target");
	$("#idNota1").val("");
}

function baixarXmlCancelamento() {
	$('#idNotaCancelamento').val($('#idNota').val());
	$("#direcionador_cancelamento_nfe").attr("action", "relatorios/nfe.xml.cancelamento.php");
	$("#direcionador_cancelamento_nfe").attr("method", "post");
	$("#direcionador_cancelamento_nfe").attr("target", "_blank");
	$('#direcionador_cancelamento_nfe').submit();
	$("#direcionador_cancelamento_nfe").removeAttr("action");
	$("#direcionador_cancelamento_nfe").removeAttr("method");
	$("#direcionador_cancelamento_nfe").removeAttr("target");
	$("#idNotaCancelamento").val("");
}

// This code was written by Tyler Akins and has been placed in the
// public domain. It would be nice if you left this header intact.
// Base64 code from Tyler Akins -- http://rumkin.com

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

function inutilizar() {
	$("#imgResultado").html("");
	$("#divResultado").html("");
	xajax_getEnvelopeInutilizacao($("#serieInutilizacao").val(), $("#nroInicial").val(), $("#nroFinal").val(), $("#justificativaInutilizacao").val(), false, $("#lojaInutilizacao").val(), $("#unidadeNegocioInutilizacao").val());
}

function callbackAssinarInutilizacao(xmlNF, cupom) {
	var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
	jc.signXml([{"xml": xmlNF}], function(err, data){
		if(!err){
			xajax_inutilizarNFe(encode64(data), cupom);
		}else{
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + data + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		}
	});
}

function callbackJaAssinadoInutilizacao(xmlNF, cupom) {
	try {
		xmlDoc = loadXMLString(xmlNF);
		xajax_inutilizarNFe(encode64(xmlNF), cupom);
	} catch (e) {
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function callbackInutilizacao(xmlNF, erros) {
	if ((erros != "") && (erros != undefined)){
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		try {
			xmlDoc = loadXMLString(xmlNF);

			if (xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue == 102) {
				var nroIni = xmlDoc.getElementsByTagName("nNFIni")[0].childNodes[0].nodeValue;
				var nroFim = xmlDoc.getElementsByTagName("nNFFin")[0].childNodes[0].nodeValue;
				var protocolo = xmlDoc.getElementsByTagName("nProt")[0].childNodes[0].nodeValue;
				xajax_updateInutilizacao(nroIni, nroFim, protocolo, encode64(xmlNF));
			} else {
				$("#divResultado").html("<p style='color: red;'>Erro durante inutilização de números.\nDetalhes do erro:\n\n" + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + "</p>");
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
				habilitarLinks();
			}
		} catch (e) {
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		}
	}
}

function mostarOcultarEnvioEmail() {
	if ($("#enviarNotaPorEmail").prop("checked")) {
		$("#exibirDetalhesEmail").show();
		enviarEmailCliente = "S";
	} else {
		$("#exibirDetalhesEmail").hide();
		enviarEmailCliente = "N";
	}
}

function verificarLancamentoEstoque(idN) {
	var lancaEstoque = "";
	if ($("#enviarNotaLancarEstoque").prop("checked")){
		lancaEstoque = "S";
	} else {
		lancaEstoque = "N";
	}

	sgtEstoque += 1;
	if(sgtEstoque > 1){
		lancaEstoque = 'N';
	}
	xajax_lancarEstoques(idN, $("#deposito_estoque_nfe_popup").val(), lancaEstoque);
}

function voltarParaTelaEnvioNota() {
	$(".spanDadosNota").show();
	$("#envioDocumentoEmail").hide();
	$("#msg_layout").show();
	$("#liEnviarContingencia").show();
}

function mostarDivEmail() {
	$(".spanDadosNota").hide();
	$("#envioDocumentoEmail").show();
	$("#botaoEnvio").val("Confirmar e-mail");
	$("#botaoEnvio").attr("onclick", "voltarParaTelaEnvioNota();");
	$("#msg_layout").hide();
	$("#liEnviarContingencia").hide();
}

function mostrarDivEstoque() {
	if ($("#enviarNotaLancarEstoque").prop("checked")){
		$("#container_opcoes_estoque").css('display', 'inline-block');
	} else {
		$("#container_opcoes_estoque").hide();
	}
}

function verificaStatusServico(doc) {
	$("#imgResultado").html("");
	try {
		xmlDoc = loadXMLString(doc);
		try {
			var cStat = 0;
			try {
				cStat = xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue;
			} catch (e1) {
			}
			if (cStat == 107) {
				return true;
			} else {
				mostrarResultado();
				$("#divResultado").html("<p>" + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + "</p>");
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
				$("<br/><input class='button-default' type='button' value='Tentar novamente' onclick='init();' />").appendTo("#divResultado");
				return false;
			}
		} catch (e) {
			mostrarResultado();
			$("#divResultado").html("<p>Não foi possível obter o status do serviço.</p><p>" + doc + "</p><p>" + e + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			$("<br/><input class='button-default' type='button' value='Tentar novamente' onclick='init();' />").appendTo("#divResultado");
			return false;
		}
	} catch (e) {
		mostrarResultado();
		$("#divResultado").html("<p>O servidor do sefaz do seu estado não está respondendo.</p><p>Caso o servidor esteja fora do ar, você pode utilizar o ambiente de contingência, especial para este fim.</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		$("<br/><input class='button-default' type='button' value='Tentar novamente' onclick='init();' />").appendTo("#divResultado");
		return false;
	}
}

function enviarCartaCorrecao(idNotaCorrecao) {
	if (($("#correcao").val().length) >= 15) {
		limparInformacoes();
		desabilitarLinks();
		mostrarAcompanhamento();
		$("<li id='liEnvio'>Envio</li>").appendTo("#ulAcompanhamento");
		xajax_getEnvelopeCartaCorrecao(idNotaCorrecao, $("#correcao").val(), $('#sequencialEvento').val());
	} else {
		alert("A correção deve possuir no mínimo 15 caracteres!")
	}
}

function callbackAssinarCorrecao(idCarta, idNota, xmlNF, erros) {
	if (erros != "") {
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
		jc.signXml([{"xml": xmlNF}], function(err, data){
			if(!err){
				setSignedXML(data);
				xajax_corrigirNFe(idCarta, idNota, encode64(data));
			}else{
				alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + xmlNF);
				$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
				mostrarResultado();
				$("#divResultado").html("<p style='color: red;'>" + data + "</p>");
				$("<img src='images/no.gif'/>").appendTo("#imgResultado");
				habilitarLinks();
			}
		});
	}
}

function callbackJaAssinadaCorrecao(idCarta, idNota, xmlNF, erros) {
	try {
		if (erros != "") {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		} else {
			XMLNFe = xmlNF;
			xajax_corrigirNFe(idCarta, idNota, encode64(xmlNF));
		}
	} catch (e) {
		alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + xmlNF);
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function callbackCartaCorrecao(idCarta, envelope, erros) {
	const REJEICAO_CORRECAO_DUPEVENTO = 573;
	try {
		if (erros != "") {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		} else {
			$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/check.gif'/>");
			xmlDoc = loadXMLString(envelope);

			var callback = function(data) {
				if (data['error']) {
					$('#slotAvisoCorrecao')
						.show()
						.addClass('useful-info-danger')
						.find('h3').text('Erro ao enviar carta de correção');
					$('#slotAvisoCorrecao p, #slotAvisoCorrecao br').remove();
					if (data['cStat'] == REJEICAO_CORRECAO_DUPEVENTO) {
						var sequencialEvento = parseInt($('#sequencialEvento').val());
						$('#slotAvisoCorrecao').append(
							$('<p>', { text: data['error'] + ' (número sequêncial ' + sequencialEvento + ' já utilizado)'}), $('<br>')
						)
						sequencialEvento = sequencialEvento + 1;
						if (sequencialEvento <= 20) {
							$('#sequencialEvento').val(sequencialEvento)
							$('#slotAvisoCorrecao').append(
								$('<p>', { html: '<b>Envie novamente para tentar usar outro número sequêncial.</b>'})
							);
						}
					} else {
						$('#slotAvisoCorrecao').append($('<p>', { text: data['error']}))
					}
				} else {
					$('#divCartaCorrecao').hide();
					$('#divResultado').html('<p>Carta de correção enviada</p>');
					$('#imgResultado').html("<img src='images/ok.gif'/>");
				}
				habilitarLinks()
			}
			try {
				xajax_updateCartaCorrecao(idCarta, $("#idNota").val(), encode64(envelope), xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("nProt")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("dhRegEvento")[0].childNodes[0].nodeValue, callback);
			} catch (e) {
				xajax_updateCartaCorrecao(idCarta, $("#idNota").val(), encode64(envelope), xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, "", "", callback);
			}
			mostrarResultado();
		}
	} catch (e) {
		alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + envelope);
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + e + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function baixarXmlCarta() {
	$('#idNotaCarta').val($('#idNota').val());
	$("#direcionador_carta_nfe").attr("action", "relatorios/nfe.xml.carta.correcao.php");
	$("#direcionador_carta_nfe").attr("method", "post");
	$("#direcionador_carta_nfe").attr("target", "_blank");
	$('#direcionador_carta_nfe').submit();
	$("#direcionador_carta_nfe").removeAttr("action");
	$("#direcionador_carta_nfe").removeAttr("method");
	$("#direcionador_carta_nfe").removeAttr("target");
	$("#idNotaCarta").val("");
}

function mostarOcultarEnvioContingencia(){
	if ($("#enviarContingencia").prop("checked") == true){
		$("#divContingencia").show();
	} else {
		$("#divContingencia").hide();
	}
}

function setAppAcessoHardware(app){
	appAcessoHardware = app;
}

function abrirManifestacao(){
	abrirPopupNFe(0, 0, "", 0, "", "manifestacao");
}

function manifestar(){
	$("#divManifestacao").hide();
	xajax_getEnvelopeManifestacao($("#chaveManifestacao").val());
}

function callbackAssinarManifestacao(xmlNF, erros) {
	if (erros != "") {
		$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>" + erros + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		if ($("#certificadoArmazenado").val() == "N"){
			var jc = new JavaConnection(appAcessoHardware, "NFe").getInstance();
			jc.signXml([{ "xml": xmlNF}], function(err, data){
				if(!err){
					setSignedXML(data);
					xmlNF = data
				}else{
					alert("Erro. Copie e cole a mensagem abaixo e envie para o suporte.\n\n" + xmlNF);
					$("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
					mostrarResultado();
					$("#divResultado").html("<p style='color: red;'>" + data + "</p>");
					$("<img src='images/no.gif'/>").appendTo("#imgResultado");
					habilitarLinks();
				}
			});
		}
		try{
			xajax_updateManifestacao(encode64(xmlNF));
		} catch (e){
			("#liEnvio").html($("#liEnvio").html() + "  <img src='images/delete.gif'/>");
			mostrarResultado();
			$("#divResultado").html("<p style='color: red;'>Não foi possível comunicar com o servidor. Motivo: " + e + "</p>");
			$("<img src='images/no.gif'/>").appendTo("#imgResultado");
			habilitarLinks();
		}
	}
}
function callbackManifestacao(xmlNF, erros) {
	if (erros != '') {
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>Não foi possível manifestar essa chave.</p>" +
			"<p>Motivo: " + erros + "</p>" +
			(xmlNF != '' ? "<p><a onclick='$(\"#xmlManifestacao\").slideToggle();'>Exibir XML recebido</a></p><p id='xmlManifestacao' style='display:none;' class='code'>" + xmlNF.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</p>" : ''));
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
	xml = loadXMLString(xmlNF);
	if (xml.getElementsByTagName("cStat")[0].childNodes[0].nodeValue == 135){
		$("#divResultado").html("<p>Manifestação de recebimento realizada com sucesso.</p>" +
			"<p><a onclick='$(\"#xmlManifestacao\").slideToggle();'>Exibir XML recebido</a></p><p id='xmlManifestacao' style='display:none;' class='code'>" + xmlNF.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</p>");
		$("<img src='images/ok.gif'/>").appendTo("#imgResultado");
	} else {
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>Não foi possível manifestar essa chave.</p>" +
			"<p>Motivo: " + xml.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + "</p>" +
			"<p><a onclick='$(\"#xmlManifestacao\").slideToggle();'>Exibir XML recebido</a></p><p id='xmlManifestacao' style='display:none;' class='code'>" + xmlNF.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function abrirDownloadSefaz(){
	abrirPopupNFe(0, 0, "", 0, "", "downloadSefaz");
}

function downloadSefaz(){
	$("#divDownloadSefaz").hide();
	xajax_getEnvelopedownloadSefaz($("#chaveDownloadSefaz").val());
}

function callbackDownloadSefaz(numero, erro, xml){
	if (erro != ''){
		mostrarResultado();
		$("#divResultado").html("<p style='color: red;'>Não foi possível realizar a importação dessa nota.</p>" +
			"<p>Motivo: " + erro + "</p>" +
			"<p><a onclick='$(\"#xmlDownloadSefaz\").slideToggle();'>Exibir XML recebido</a></p><p id='xmlDownloadSefaz' style='display:none;' class='code'>" + xml.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</p>");
		$("<img src='images/no.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	} else {
		mostrarResultado();
		$("#divResultado").html("<p>Importação realizada com sucesso.</p>" +
			"<p><a onclick='$(\"#xmlDownloadSefaz\").slideToggle();'>Exibir XML recebido</a></p><p id='xmlDownloadSefaz' style='display:none;' class='code'>" + xml.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</p>");
		$("<img src='images/ok.gif'/>").appendTo("#imgResultado");
		habilitarLinks();
	}
}

function listarUnidadesDeNegocioInutilizacao(idLoja) {
	var select = document.getElementById("unidadeNegocioInutilizacao");
	select.options.length = 0;

	xajax_listarUnidadesDeNegocio(idLoja, function(data){
        data.forEach(function(unidade) {
			select.options[select.options.length] = new Option(unidade.text, unidade.value);
        })
    });
}
