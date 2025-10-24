$(document).ready(function() {
	vincularEventosRastroPopup();
});

function vincularEventosRastroPopup() {
	$(document).on("click", "#mais_opc_email", function(){$("#container_mais_opc").slideToggle(200);});
	$(document).on("click", "#botaoSincronizarRastro", function() {
		displayWait("sincronizarRastroWait", false, "Consultando rastro na logística, aguarde...");
		xajax_sincronizarRastroObjeto($("#idObjetoPlp").val(), $("#numeroPedidoLoja").val());
		iterativeWait("sincronizarRastroWait", ["Aguardando resposta...", "Ainda trabalhando..."], [10]);
	});
	$(document).on("click", "#enviarEmailRastro", function() {
		$.get("templates/form.correios.email.rastro.popup.php?idContato=" + $("#idObjetoContato").val() + '&idObjeto=' + $("#idObjetoPlp").val() + '&idOrigemObjeto=' + $("#idOrigemObjeto").val(), function(data) {
			$(data).dialog({
				title: 'Envio de acompanhamento por email',
				resizable: false,
				modal: true,
				width: 425,

				close: function(){
					$(this).dialog('destroy');
				}
			});
			$(".inf").tipsy({trigger: "click", gravity: "w", delayIn: 500, delayOut: 1000});
		});
	});
	$(document).on("click", "#enviarEmailAcompanhamentoRastro", function() {
		displayWait("envioEmailWait");
		xajax_enviarEmailAcompanhamentoRastro(xajax.getFormValues("form_envio_acompanhamento_rastro_email_popup"), obterDadosRastro(), $("#objEtiqueta").val());
	});
	$(document).on("change", "#volumeSelecionado", function() {
		displayWait("sincronizarRastroWait");
		xajax_obterDadosRastroObjeto($(this).val());
	});
}

function atualizaDadosObjeto(dadosObj) {
	LogisticFactory.create(dadosObj.tipoIntegracao).updateFormObjectTracking(dadosObj);
}

function atualizaDadosRastroObjeto(dadosObj) {
	var dadosRastro = dadosObj.rastreamento;
	var logisticaDriver = LogisticFactory.create(dadosObj.tipoIntegracao);

	if(dadosRastro.objetoPostado) {
		$("#dataSincronizacao").val(dadosRastro.dataSincronizacao);
		$("#situacaoRastro").val(dadosRastro.situacaoRastro);
		$("#dataAlteracao").val(dadosRastro.dataAlteracao);
		$("#localizacao").val(logisticaDriver.showObjectLocation(dadosRastro.movimentacao));
		$("#dadosRastro").show();
		$("#mensagemRastro").hide();
	} else {
		$("#mensagemRastro").html("<div class='col-lg-12 alert-box alert-box-info alert-box-transparent margin-top0'><p>Ainda não existem rastros desse objeto. Sincronize novamente mais tarde.</p></div><div class='linha_form wh100'><input id='botaoSincronizarRastro' class='btn-secundary-novo btn-wh-100 float_right " + (logisticaDriver.hideBtnTrackingSync ? "display-none" : "") + "' type='button' value='Sincronizar' /></div>");
		$("#dadosRastro").hide();
		$("#mensagemRastro").show();
	}

	verificarSyncErro(dadosRastro.syncErro);
	closeWait("sincronizarRastroWait");
}

function obterDadosRastro() {
	return {
		"dataSincronizacao" : $("#dataSincronizacao").val(),
		"situacaoRastro" : $("#situacaoRastro").val(),
		"dataAlteracao" : $("#dataAlteracao").val(),
		"localizacao" : $("#localizacao").val(),
	}
}

function atualizaStatusEnvioAcompanhamentoRastreio(msg, enviou) {
	if(enviou) {
		$("#mensagemStatusEnvio").addClass("sucess");
	} else {
		$("#mensagemStatusEnvio").addClass("warn");
	}
	$("#mensagemStatusEnvio").html(msg);
	$("#mensagemStatusEnvio").show();
}

function abrirPopupRastroObjeto(idOrigem, idContato) {
	$.get("templates/form.correios.rastro.objeto.popup.php", function(data) {
		var dialog = {
			content: data,
			config: {
				title: "Rastro do Objeto",
				width: 600,
				minHeight: 260
			},
			hideCancel: true,
			hideOk: true,
			fnCreate: function() {
				displayWait("sincronizarRastroWait");
				$("#idOrigemObjeto").val(idOrigem);
				$("#idObjetoContato").val(idContato);

				xajax_obterRastreamentosOrigem(idOrigem, function(volumes){
					$("#volumeSelecionado").empty();

					$.each(volumes, function(i, volume) {
						$("#volumeSelecionado").append(
							$("<option>", {"value": volume.id, "text": i + 1})
						);
					});

					xajax_obterDadosRastroObjeto(volumes[0].id);
				});
			}
		};

		createDialog(dialog);
	});
}