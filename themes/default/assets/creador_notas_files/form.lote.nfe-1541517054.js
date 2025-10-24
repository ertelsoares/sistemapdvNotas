var XMLNFe = null;
var enviandoLote = false;
var proximoIdNota = 0;
var idNotaAtual = 0;
var idxNotaAtual = 0;
var qtdeNotas = 0;
var qtdeNotasEnviadas = 0;
var loteNotas;

var nroEnviadas = 0;
var nroRejeitadas = 0;
var nroValidacao = 0;

var enviarEmailNotas = "N";
var lancaLoteEstoque = "N";
var lancaLoteContas = "N";
var idsNotasSelecionadas = "";

function adicionarMensagemNaLista(mensagem, ehErro) {
	if (ehErro) {
		xajax_setarMensagemNFe(loteNotas[idxNotaAtual].id, encode64(mensagem));
	}
}

function adicionarMensagemNaListaJSON(mensagem, ehErro) {
	$.each(mensagem, function (nro, item) {
		if (ehErro == "S") {
			xajax_setarMensagemNFe(loteNotas[idxNotaAtual].id, encode64(item));
			nroValidacao ++;
		} else {
			xajax_setarMensagemNFe(loteNotas[idxNotaAtual].id, encode64("OK"));
			nroEnviadas ++;
		}
	})
}

function mostrarAcompanhamentoLote() {
	$("#slotLoteAcompanhamento").html("Enviando nota " + qtdeNotasEnviadas + " / " + qtdeNotas + "<br/><br/>Nota fiscal número " + loteNotas[proximoIdNota].numero + " para " + loteNotas[proximoIdNota].cliente);
}

function mostrarResultadoLote() {
	$("#slotLoteAcompanhamento").hide();

	nroRejeitadas = qtdeNotasEnviadas - nroEnviadas - nroValidacao;
	var resultado = "<table class='datatable'>" +
					"<tr style='color: black;'>" +
					"<td>Notas processadas</td>" +
					"<td>" + qtdeNotasEnviadas + "</td>" +
					"</tr>" +
					"<tr style='color: green;'>" +
					"<td>Notas enviadas</td>" +
					"<td>" + nroEnviadas + "</td>" +
					"</tr>" +
					"<tr style='color: red;'>" +
					"<td>Notas rejeitadas</td>" +
					"<td>" + nroRejeitadas + "</td>" +
					"</tr>" +
					"<tr style='color: orange;'>" +
					"<td>Notas com erro de validação</td>" +
					"<td>" + nroValidacao + "</td>" +
					"</tr>"
					"</table>";
	$("#slotLoteResultado").html(resultado);
	$("#slotLoteResultado").show();
}

function enviarNFesSelecionadas() {
	popupAvisoNfe400();
	var ids = getIdsSelectedItems({'asString': true});

	if (ids == '') {
		alert('Nenhuma nota fiscal selecionada!');
	} else{
		idsNotasSelecionadas = ids;
		enviandoLote = false;
		if ($('#certificadoArmazenado').val() != 'S') {
			if (!appletLoteCarregado && appAcessoHardware == 'applet') {
				$('#applet_lote').html('<applet code="NFe.class" name="LoteNFe" width="0" height="0" archive="applets/nfe-13.0.jar" MAYSCRIPT><param name="codebase_lookup" value="false"></applet>');
				appletLoteCarregado = true;
			}
		}

		var arguments = {action: "renderTemplate", link: "templates/form.lote.nfe.php", getArguments : "?tipoNota=" + tipo};
		getRenderedTemplate(arguments, function(data){
			new Boxy(data, {
				title: "Lote de notas fiscais eletrônicas",
				modal: true,
				unloadOnHide: true,
				afterShow: ajustarFormNFesSelecionadas,
				afterHide: listar
		})});
	}
}

function enviarNFesEmLote() {
	popupAvisoNfe400();
	enviandoLote = false;
	if ($("#certificadoArmazenado").val() != "S") {
		if (!appletLoteCarregado && appAcessoHardware == "applet") {
			$("#applet_lote").html('<applet code="NFe.class" name="LoteNFe" width="0" height="0" archive="applets/nfe-13.0.jar" MAYSCRIPT><param name="codebase_lookup" value="false"></applet>');
			appletLoteCarregado = true;
		}
	}

	var arguments = {action: "renderTemplate", link: "templates/form.lote.nfe.php", getArguments : "?tipoNota=" + tipo};

	getRenderedTemplate(arguments, function(data){
		new Boxy(data, {
			title: "Lote de notas fiscais eletrônicas",
			modal: true,
			unloadOnHide: true,
			afterShow: ajustarFormLoteNfe,
			afterHide: listar
	})});

}

function ajustarFormLoteNfe(){
	$(document).ready(function() {
		setTimeout("initFormLote()", 700);
	});
}

function ajustarFormNFesSelecionadas(){
	$(document).ready(function() {
		setTimeout("initFormNFesSelecionadas()", 700);
	});
}

function initFormLote() {
	limparInformacoesLote();
	if ($("#parametro_marcado_para_envio_email").val() == "S") {
		$("#enviarLoteNotaPorEmail").prop("checked", true);
		enviarEmailNotas = "S";
	} else {
		$("#enviarLoteNotaPorEmail").prop("checked", false);
		enviarEmailNotas = "N";
	}

	if ($("#parametro_marcado_para_lancar_estoque").val() == "S"){
		$("#enviarLoteNotaLancarEstoque").prop("checked", true);
	} else {
		$("#enviarLoteNotaLancarEstoque").prop("checked", false);
	}
	mostrarDivLoteEstoque();

	if ($("#parametro_marcado_para_lancar_contas").val() == "S"){
		$("#enviarLoteNotaLancarContas").prop("checked", true);
	} else {
		$("#enviarLoteNotaLancarContas").prop("checked", false);
	}

	xajax_obterOpcoesDepositos("deposito_estoque_lote_nfe_popup", "", "N");
	xajax_getSenhaCartaoLote();
	lancaLoteEstoque = "N";
	lancaLoteContas = "N";

	$("#idsNotasFiscais").val("");
	$("#liLoteEnviar").show();
	$("#liSelecionadasEnviar").hide();
}

function initFormNFesSelecionadas() {
	limparInformacoesLote();
	if ($("#parametro_marcado_para_envio_email").val() == "S") {
		$("#enviarLoteNotaPorEmail").prop("checked", true);
		enviarEmailNotas = "S";
	} else {
		$("#enviarLoteNotaPorEmail").prop("checked", false);
		enviarEmailNotas = "N";
	}

	if ($("#parametro_marcado_para_lancar_estoque").val() == "S"){
		$("#enviarLoteNotaLancarEstoque").prop("checked", true);
	} else {
		$("#enviarLoteNotaLancarEstoque").prop("checked", false);
	}
	mostrarDivLoteEstoque();

	if ($("#parametro_marcado_para_lancar_contas").val() == "S"){
		$("#enviarLoteNotaLancarContas").prop("checked", true);
	} else {
		$("#enviarLoteNotaLancarContas").prop("checked", false);
	}

	xajax_obterOpcoesDepositos("deposito_estoque_lote_nfe_popup", "", "N");
	xajax_getSenhaCartaoLote();
	lancaLoteEstoque = "N";
	lancaLoteContas = "N";

	$("#idsNotasFiscais").val(idsNotasSelecionadas);
	$("#liLoteEnviar").css("display","none");
	$("#liSelecionadasEnviar").show();
}

function limparInformacoesLote() {
	$("#ulLoteAcompanhamento > li").remove();
	$("#divLoteResultado").html("");
	$("#slotLoteResultado").html("");
	nroEnviadas = 0;
	nroRejeitadas = 0;
	nroValidacao = 0;
}

function mostrarDivLoteEstoque() {
	if ($("#enviarLoteNotaLancarEstoque").prop("checked") == true){
		$("#container_lote_opcoes_estoque").show();
	} else {
		$("#container_lote_opcoes_estoque").hide();
	}
}

function desabilitarLinksLote() {
	$("#nfe-lote-servicos").css("display", "none");
}

function configAppletLote(config) {
	try {
		if ($("#certificadoArmazenado").val() == "N") {
			var jc = new JavaConnection(appAcessoHardware, "LoteNFe").getInstance();
			var parameters = [{"senha": config.senha, "tipoCertificado": config.tipoCertificado, "certificado": config.certificado, "certs": config.certs, "alias" : config.alias}];

			jc.config(parameters, function(err, data){});
		}
		xajax_getEnvelopeConfigLote();
	} catch (e) {
		callbackConfigLote(null);
	}
}

function callbackConfigLoteArmazenado(envelope, status , val) {
	if ((status == "1") || (status == true)) {
		$('#msgValCert').html('Certificado válido até ' + val).addClass('success');
		exibirMensagemCertificadoVencendo(val, $("#data_atual_lote").val());
		callbackConfigLote(envelope);
	} else {
		$("<div class='problem text_left'><p>Falha na obtenção do certificado digital</p><input class='button-default' type='button-default' value='Tentar novamente' onclick='initFormLote();' /></div>").appendTo("#slotLoteResultado");
		callbackConfigLote(envelope);
	}
}

function callbackConfigLote(envelope) {
	if ($("#certificadoArmazenado").val() == "N") {
		var jc = new JavaConnection(appAcessoHardware, "LoteNFe").getInstance();
		jc.getAdditionalInformation(function(err, data){
			if(!err){
				$('#msgValCert').html('Certificado válido até ' + data).addClass('success');
				exibirMensagemCertificadoVencendo(data, $("#data_atual_lote").val());
			}else{
				$("<div class='problem text_left'><p>Falha na obtenção do certificado digital<br>" + data + "</p><input class='button-default' type='button' value='Tentar novamente' onclick='initFormLote();' /></div>").appendTo("#slotLoteResultado");
				//$("#liLoteEnviar").hide();
			}
		});
	}
	//$("#liLoteEnviar").show();
	$("div#nfe-lote-servicos").show();
	$("#nfe-lote-wait").hide();
}

function enviarLoteNFe() {
	$("#slotLoteAcompanhamento").html("");
	$("#slotLoteAcompanhamento").show();
	$("#controls-popup-lote").show();
	if (! enviandoLote) {
		enviandoLote = true;
		limparInformacoesLote();
		desabilitarLinksLote();
		xajax_obterNotasParaEnviarLote(tipo);
	}
}

function enviarNotasFiscaisSelecionadas() {
	$("#slotLoteAcompanhamento").html("");
	$("#slotLoteAcompanhamento").show();
	$("#controls-popup-lote").show();
	if (! enviandoLote) {
		enviandoLote = true;
		limparInformacoesLote();
		desabilitarLinksLote();
		xajax_obterNotasSelecionadasParaEnviar(tipo,$("#idsNotasFiscais").val());
	}
}

function setarArrayNotas(aQtdeNotas, aLoteNotas) {
	loteNotas = aLoteNotas;
	qtdeNotas = aQtdeNotas;
	qtdeNotasEnviadas = 0;

	proximoIdNota = 0;
	enviarProximaNFeDoLote();
}

function enviarProximaNFeDoLote() {
	try {
		if (proximoIdNota >= 0) {
			qtdeNotasEnviadas ++;
			idNotaAtual = loteNotas[proximoIdNota].id;
			idxNotaAtual = proximoIdNota;
			mostrarAcompanhamentoLote();
			proximoIdNota ++;
			setTimeout('xajax_preValidarXMLLote(idNotaAtual, "assinarLote()");', 1000);
		} else {
			$("#controls-popup-lote").hide();
			mostrarResultadoLote();
		}
	} catch(e) {
		qtdeNotasEnviadas --;
		$("#controls-popup-lote").hide();
		mostrarResultadoLote();
	}
}

function cancelarEnvioLote() {
	proximoIdNota = -2;
}

function assinarLote() {
	console.log("assinar lote");
	xajax_getEnvelopeAssinaturaLote(idNotaAtual);
}

function callbackAssinarLote(idNota, xmlNF, cupom) {
	var jc = new JavaConnection(appAcessoHardware, "LoteNFe").getInstance();
	jc.signXml([{"xml": xmlNF}], function(err, data){
		if(!err){
			setSignedXML(data);
			xajax_updateAssinaturaLote(idNota, encode64(data), cupom, $("#idControl").val());
		}else{
			adicionarMensagemNaLista("Problema na assinatura", true);
			enviarProximaNFeDoLote();
		}
	});
}

function setSignedXML(xml){
	XMLNFe = xml;
}

function callbackJaAssinadoLote(idNota, xmlNF, cupom, erro) {
	if (erro != '') {
		adicionarMensagemNaLista(erro, true);
		enviarProximaNFeDoLote();
		return false;
	}
	try {
		XMLNFe = xmlNF;
		xajax_updateAssinaturaLote(idNota, encode64(xmlNF), cupom, $("#idControl").val());
	} catch (e) {
		adicionarMensagemNaLista("Problema na assinatura", true);
		enviarProximaNFeDoLote();
	}
}

function callbackEnviarLote(idLote, envelope, cupom) {
	//envelope += XMLNFe + '</enviNFe>';
	try {
		xmlDoc = loadXMLString(envelope);
		var tMed = 10;
		try {
			var tMed = xmlDoc.getElementsByTagName("tMed")[0].childNodes[0].nodeValue;
		} catch (e) {}
		if (tMed > 10){
			tMed = 10;
		}

		var callback = null;
		if (xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue == 103) {
			callback = "consultarReciboLote(" + idNotaAtual + ", " + cupom + ")";
			callback = "setTimeout('" + callback + "', " + (tMed * 1 + 1) * 1000 + ")";
		} else {
			callback = "enviarProximaNFeDoLote()";
			adicionarMensagemNaLista("Problema no envio: " + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, true);
		}

		xajax_updateEnvioLote(idLote, encode64(envelope), callback, idNotaAtual);
	} catch (e) {
		//debugEmail("Problema no callback do lote da NF-e", e.message);
		adicionarMensagemNaLista("Problema no envio", true);
		enviarProximaNFeDoLote();
	}
}

function consultarReciboLote(idNota, cupom) {
	xajax_getEnvelopeRecibo(idNota, cupom, function (data) {
		callbackReciboLote(data.idLote, data.xml, data.erros.rejeicao + data.erros.msg);
	});
}

function callbackReciboLote(idLote, envelope, erros) {
	try {
		if (erros != "") {
			adicionarMensagemNaLista("Problema no envio: " + erros, true);
			enviarProximaNFeDoLote()
		} else {
			//var retorno = document.LoteNFeApplet.runService(envelope);
			//xmlDoc = loadXMLString(retorno);

			xmlDoc = loadXMLString(envelope);

			if (xmlDoc.getElementsByTagName("cStat")[0].childNodes[0].nodeValue == 104) {
				// lote processado
				if (xmlDoc.getElementsByTagName("cStat")[1].childNodes[0].nodeValue != 100) {
					try {
						adicionarMensagemNaLista("Problema no retorno: " + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + " - NFe: " + xmlDoc.getElementsByTagName("xMotivo")[1].childNodes[0].nodeValue, true);
					} catch (e) {
						adicionarMensagemNaLista("Problema no retorno: " + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, true);
					}
				}
			} else {
				// lote não processado
				try {
					adicionarMensagemNaLista("Problema no retorno: " + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue + " - NFe: " + xmlDoc.getElementsByTagName("xMotivo")[1].childNodes[0].nodeValue, true);
				} catch (e) {
					adicionarMensagemNaLista("Problema no retorno: " + xmlDoc.getElementsByTagName("xMotivo")[0].childNodes[0].nodeValue, true);
				}
			}
			if ($("#enviarLoteNotaLancarEstoque").prop("checked")){
				lancaLoteEstoque = "S";
			} else {
				lancaLoteEstoque = "N";
			}
			if ($("#enviarLoteNotaLancarContas").prop("checked")){
				lancaLoteContas = "S";
			} else {
				lancaLoteContas = "N";
			}
			xajax_updateReciboLote(idLote, encode64(envelope), enviarEmailNotas, lancaLoteEstoque, $("#deposito_estoque_lote_nfe_popup").val(), lancaLoteContas);
		}
	} catch (e) {
		adicionarMensagemNaLista("Problema no retorno", true);
		enviarProximaNFeDoLote()
	}
}

function setarVariavelControleEmail() {
	if ($("#enviarLoteNotaPorEmail").prop("checked")) {
		enviarEmailNotas = "S";
	} else {
		enviarEmailNotas = "N";
	}
}
