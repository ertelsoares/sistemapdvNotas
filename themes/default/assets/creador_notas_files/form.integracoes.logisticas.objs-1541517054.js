var LogisticFactory = function() {};
LogisticFactory.create = function(logistic) {
	try {
		var obj = new window[logistic + 'Integration'];
	} catch(e) {
		var obj = new CorreiosIntegration();
	}

	return obj;
}

var IntegrationBase = function() {}
IntegrationBase.prototype.hideBtnTrackingSync = false;
IntegrationBase.prototype.renderObjectList = function(obj) {
	aSituacao = LogisticFactory.create('Logistic').getObjectStatus(obj.situacaoEvento);

	var isSelected = (getIndexSelectedItem(objetosSelecionados, obj.id.toString()) != -1);
	var numeroVenda = obj.numero + ' (' + obj.tipo + ')';
	var linkRemessa = '<a href="integracoes.logisticas.php#edit/' + (obj.idPlp != null ? obj.idPlp : '') + '">' + (obj.descricaoRemessa != null ? obj.descricaoRemessa : '') + '</a>';
	var iconeIntegracao = '<i class="icone ' + obj.classeIcone + '" title="' + obj.tipoIntegracaoInfo + '"></i><span class="hidden-mobile">' + obj.descricaoIntegracao + '</span>';

	if (typeof(obj.numeroPedidoOrigem) != 'undefined' && obj.numeroPedidoOrigem != null) {
		numeroVenda += '<br/ >' + obj.numeroPedidoOrigem + ' (Venda)';
	}

	var trObj = $('<tr>', {id: obj.id, style: 'height: 43px;'}).append(
		$('<td>', {class: 'tcheck'}).append($('<input>', {name: 'objetos[]', value: obj.id, class: 'tcheck', type: 'checkbox'}).prop('checked', isSelected)),
		$('<td>', {class: 'tline'}).append(numeroVenda),
		$('<td>', {class: 'hidden-mobile'}).append(linkRemessa),
		$('<td>', {text: obj.contato, class: 'tline'}),
		$('<td>', {class: 'tline'}).append(iconeIntegracao),
		$('<td>', {text: obj.nomeServico, class: 'tline'})
	);

	return trObj;
}
IntegrationBase.prototype.renderObjectShipmentList = function(obj) {
	infoErros = '';
	var lineAttr = {id: obj.id, 'data-correios-codservico': obj.codigoServico};

	if (typeof(obj.erro) != 'undefined') {
		lineAttr['style'] = 'background-color: #f5deb3;'
		lineAttr['data-erro'] = 'warn';
		infoErros = (obj.erro).join('');
	}

	var numeroVenda = obj.numero + ' (' + obj.tipo + ')';
	if (typeof(obj.numeroPedidoOrigem) != 'undefined' && obj.numeroPedidoOrigem != null) {
		numeroVenda += '<br />' + obj.numeroPedidoOrigem + ' (Venda)';
	}

	var trObj = $('<tr>', lineAttr).append(
		$('<td>', {class: 'tcheck'}).append(
			$('<input>', {name: 'etiquetas[]', value: obj.id, type: 'checkbox'}),
			$('<input>', {name: 'objetosTempPlp[]', value: obj.id, type: 'hidden'})
		),
		$('<td>', {class: 'tline objplp'}).append(numeroVenda),
		$('<td>', {text: obj.contato_destinatario.contato, class: 'tline objplp'}),
		$('<td>', {text: obj.nomeServico, class: 'tline objplp'})
	);

	return trObj;
}
IntegrationBase.prototype.initFormObj = function(show) {
	$('#link_servicos_adicionais, #marcador_espacamento, #link_dimensoes, #info_dimensao, #suspenderEntrega, #botaoObterPontosRetirada').hide();
	$('#info_dimensao').html('Serão enviadas as dimensões mínimas, caso não sejam preenchidas.');
	$(show).show();
}
IntegrationBase.prototype.showObjectLocation = function(locations) {
	var content = '';

	if (locations.origem) {
		content += 'De ' + locations.origem + ' ';
	}

	if (locations.destino) {
		content += 'Para ' + locations.destino;
	}

	$('label[for="localizacao"]').html('Localização');
	return content;
}
IntegrationBase.prototype.initFormShipmentActions = function(specificActionsBtn) {
	specificActionsBtn += '<li id="im_4" data-visible-status="0,-1"><img src="images/document_delete.gif"/>Excluir</li>';

	$('#popUpMenu ul').html(specificActionsBtn);
	$('#listaPlps .button-navigate, #gerarPlp').show();
	$('#box_psq_tipo_log, #enviarXmlNfes').hide();
	$('#psqTipoLog').val("");
}
IntegrationBase.prototype.initFormObjectShipment = function() {
	$(document).off('click', '.act-imprimir-etiquetas');
	$('.act-imprimir-etiquetas').removeClass(function(i, css) {
		return (css.match (/(^|\s)ico-\S+/g) || []).join(' ');
	});
}
IntegrationBase.prototype.updateFormObjectTracking = function(obj) {
	$('#idObjetoPlp').val(obj.id);
	$('#objServico').val(obj.nomeServico);
	$('#numeroPedidoLoja').val(obj.numeroLoja);
	if (obj.idIntegracao > 0) {
		$('#descricaoIntegracao').val(obj.descricaoIntegracao);
	} else {
		$('#descricaoIntegracao').val('Correios Faixa');
	}

	$('#link_acompanhamento_correios').unbind('mousedown');
	$('#link_acompanhamento_correios').attr('target', '_blank').show();
	$('#botaoSincronizarRastro').show();
}
IntegrationBase.prototype.initFiltersFormShipmentList = function(filtersAvaliable) {
	$('#box_filtro_situacao_plp a').each(function() {
		fieldStatus = $(this).attr('data-situacao');
		if (filtersAvaliable.indexOf(fieldStatus) != -1 || fieldStatus == '') {
			$(this).parent().show();
		} else {
			$(this).parent().hide();
		}
	});

	$('#box_btn_imprimir_etiqueta').hide();
}
IntegrationBase.prototype.initFiltersFormObjectList = function() {
	$('#box_btn_gerar_plp').show();
	$('#box_btn_imprimir_etiqueta, #box_btn_vincular_remessa').hide();
}
IntegrationBase.prototype.initFormSendShipmentOrder = function(title, btnTxt) {
	$('span.ui-dialog-title').text(title);
	$('button.button-default span.ui-button-text').text(btnTxt);
}
IntegrationBase.prototype.allowCustomTracking = function() {
	return false;
}
IntegrationBase.prototype.hasQuotationMethod = function() {
	return true;
}
IntegrationBase.prototype.initQuotationForm = function(addBtnPickup) {
	$('#cotacaoAr, #cotacaoMp, #cotacaoTipoObjeto, #cotacaoValorDeclarado, #cotacaoDiametro, #cotacaoTotalProdutos, #cotacaoFretePorConta').hide();

	if (addBtnPickup) {
		$('#btnObterCotacoes').removeClass('col-xs-offset-8 col-sm-offset-8');
		$('#btnObterPontosRetirada').show();
	}
}
IntegrationBase.prototype.updateObjectType = function() {
	return false;
}
IntegrationBase.prototype.externalLabel = function() {
	return false;
}
IntegrationBase.prototype.individualLabelPrint = function() {
	return false;
}

var LogisticIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
LogisticIntegration.prototype = Object.create(IntegrationBase.prototype);
LogisticIntegration.prototype.constructor = LogisticIntegration;
LogisticIntegration.prototype.clearFormShipmentActions = function() {
	$('#listaPlps .button-navigate, #gerarPlp, #fecharPlps, #prontoEnvioPlps, #despachadoPlps, #enviarXmlNfes').hide();
	$('#popUpMenu ul').empty();
}
LogisticIntegration.prototype.getObjectStatus = function(statusCode) {
	var situacaoInfo;
	var situacaoHtml;

	switch(parseInt(statusCode)) {
		case 0:
			situacaoInfo = 'Objeto postado';
			situacaoHtml = '<i class="icon-circle icon-circle-yellow" title="Postado"></i>';
			break;
		case 1:
			situacaoInfo = 'Em andamento';
			situacaoHtml = '<i class="icon-circle icon-circle-blue" title="Em andamento"></i>';
			break;
		case 2:
			situacaoInfo = 'Não Entregue'
			situacaoHtml = '<i class="icon-circle icon-circle-red" title="Não entregue"></i>';
			break;
		case 3:
			situacaoInfo = 'Entregue'
			situacaoHtml = '<i class="icon-circle icon-circle-green" title="Entregue"></i>';
			break;
		default:
			situacaoInfo = 'Não Postado';
			situacaoHtml = '<i class="icon-circle icon-circle-gray" title="Não postado"></i>';
			break;
	}

	return {'situacaoInfo' : situacaoInfo, 'situacaoHtml': situacaoHtml};
}
LogisticIntegration.prototype.getShipmentStatus = function(statusCode) {
	var situacaoInfo;
	var situacaoHtml;

	switch(parseInt(statusCode)) {
		case -1:
			situacaoInfo = 'Cancelado';
			situacaoHtml = '<i class="icon-circle icon-circle-red" title="Cancelado"></i>';
			break;
		case 0:
			situacaoInfo = 'Em aberto';
			situacaoHtml = '<i class="icon-circle icon-circle-yellow" title="Em aberto"></i>';
			break;
		case 1:
			situacaoInfo = 'Emitida';
			situacaoHtml = '<i class="icon-circle icon-circle-green" title="Emitida"></i>';
			break;
		case 2:
			situacaoInfo = 'Pronto para envio';
			situacaoHtml = '<i class="icon-circle icon-circle-pink" title="Pronto para envio"></i>';
			break;
		case 3:
			situacaoInfo = 'Despachado';
			situacaoHtml = '<i class="icon-circle icon-circle-blue" title="Despachado"></i>';
			break;
		case 4:
			situacaoInfo = 'Pronto para envio';
			situacaoHtml = '<i class="icon-circle icon-circle-blue" title="Pronto para envio"></i>';
			break;
		default:
			situacaoInfo = '';
			situacaoHtml = '';
			break;
	}

	return {'situacaoInfo' : situacaoInfo, 'situacaoHtml': situacaoHtml};
}
LogisticIntegration.getEmailIcon = function(dataEnvioCodRastro) {
	var htmlIcon = '';
	if (dataEnvioCodRastro != null) {
		var title = 'Código de rastreio enviado em ' + formatDate(dataEnvioCodRastro);
		htmlIcon = '<i class="icon-envelope tableIcon" title="' + title + '" alt="' + title + '"></i>';
	}

	return htmlIcon;
}
LogisticIntegration.getTrackingCode = function(obj) {
	return (obj.codigoEtiqueta.length == 0 ? 'N/A' : obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade);
}

var CorreiosIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
CorreiosIntegration.prototype = Object.create(IntegrationBase.prototype);
CorreiosIntegration.prototype.constructor = CorreiosIntegration;
CorreiosIntegration.prototype.TITLE_ERROR_FIELD = 'Dados SIGEP WEB';
CorreiosIntegration.prototype.initFiltersFormObjectList = function(obj) {
	$('#box_btn_vincular_remessa').show();
}
CorreiosIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade, class: 'tline'}),
		$('<td>', {text: float2moeda(obj.valorDeclarado), class: 'tline center hidden-mobile'}),
		$('<td>', {class: 'tline text_left hidden-mobile'}).append(CorreiosIntegration.getServiceIcon(obj) + LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	)

	return trObj;
}
CorreiosIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade, class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(CorreiosIntegration.getServiceIcon(obj) + LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
CorreiosIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj('#link_servicos_adicionais, #marcador_espacamento, #link_dimensoes, #info_dimensao, #suspenderEntrega');
	$('#info_dimensao').html('Serão enviadas as dimensões mínimas, padrão dos Correios, caso não sejam preenchidas.');
}
CorreiosIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_1" data-visible-status="0"><img src="images/impressaoEtiquetasCorreios.png"/>Fechar PLP</li> \
				<li id="im_2" data-visible-status="1"><img src="images/printer2.gif"/>Voucher</li> \
				<li id="im_3" data-visible-status="1"><img src="images/printer2.gif"/>Lista detalhada</li>';
	$('#fecharPlps').text('Fechar PLP"s selecionadas').show();
	$('#imprimirPlps').show();
	$('#prontoEnvioPlps, #despachadoPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
CorreiosIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det', $('#div_side_acoes')).show();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-correios');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		imprimirRotulosSelecionados('etiqueta');
		return false;
	});
}
CorreiosIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'images/impressaoEtiquetasCorreios.png');
}
CorreiosIntegration.prototype.getIcoSrc = function() {
	return {src: 'images/impressaoEtiquetasCorreios.png', class: 'ico-correios'};
}
CorreiosIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	var tracking = obj.prefixoServico+obj.codigoEtiqueta+obj.nacionalidade;

	$('#objEtiqueta').val(tracking);
	$('#link_acompanhamento_correios').removeAttr('target');
	$('#link_acompanhamento_correios').mousedown(function(e) {
		e.preventDefault();

		$('#consultaRastroCorreios input[name="objetos"]').val(tracking);
		$('#consultaRastroCorreios').submit();
	});
}
CorreiosIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').hide();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
CorreiosIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Fechar PLP', 'Fechar PLP');
}
CorreiosIntegration.getServiceIcon = function(service) {
	var arIcon = service.ar == 1 ? '<img title="Aviso de Recebimento" alt="Aviso de Recebimento" src="images/icon-ar.png" />' : '';
	var mpIcon = service.mp == 1 ? '<img title="Mão Própria" alt="Mão Própria" src="images/icon-mp.png" />' : '';

	return arIcon + mpIcon;
}
CorreiosIntegration.prototype.initQuotationForm = function() {
	IntegrationBase.prototype.initQuotationForm.call();
	$('#cotacaoAr, #cotacaoMp, #cotacaoTipoObjeto, #cotacaoValorDeclarado, #cotacaoDiametro').show();
}
CorreiosIntegration.prototype.updateObjectType = function(objectType) {
	switch(objectType) {
		case '001':
			$('#cotacaoLargura, #cotacaoAltura, #cotacaoComprimento, #cotacaoDiametro').hide();
			break;
		case '002':
			$('#cotacaoLargura, #cotacaoAltura, #cotacaoComprimento').show();
			$('#cotacaoDiametro').hide();
			break;
		case '003':
			$('#cotacaoComprimento, #cotacaoDiametro').show();
			$('#cotacaoLargura, #cotacaoAltura').hide();
			break;
	}
}

var IntelipostIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
IntelipostIntegration.prototype = Object.create(IntegrationBase.prototype);
IntelipostIntegration.prototype.constructor = IntelipostIntegration;
IntelipostIntegration.prototype.renderObjectList = function(obj) {
	var trObj  = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', {text: '-', class: 'line center hidden-mobile'}),
		$('<td>', {class: 'tline text_left'}).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
IntelipostIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline objplp' }),
		$('<td>', { text: '-', class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }),
		$('<td>', { class: 'tline' })
	);

	return trObj;
}
IntelipostIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj('#link_dimensoes');
}
IntelipostIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_5" data-visible-status="0"><img src="styles/images/impressaoEtiquetasIntelipost.png"/>Enviar</li> \
				<li id="im_6" data-visible-status="1"><img src="images/checkgray.gif"/>Pronto para envio</li> \
				<li id="im_7" data-visible-status="1,2"><img src="images/checkgray.gif"/>Despachado</li> \
				<li id="im_8" data-visible-status="2,3"><img src="images/delete_gray.gif"/>Cancelar</li>';
	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#prontoEnvioPlps, #despachadoPlps').show();
	$('#imprimirPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
IntelipostIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det, .act-desvincular-objetos', $('#div_side_acoes')).hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-intelipost');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		if (imprimirRotulosSelecionados('etiquetaExterna')) {
			printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
		}
		return false;
	});
}
IntelipostIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasIntelipost.png');
}
IntelipostIntegration.prototype.getIcoSrc = function() {
	return {src: 'styles/images/impressaoEtiquetasIntelipost.png', class: 'ico-intelipost'}
}
IntelipostIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').attr('href', 'https://status.intelipost.com.br/tracking/' + obj.confAutenticacao.codigoCliente + '/' + (obj.numeroPlp || 0));
}
IntelipostIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1', '2', '3', '-1'];
	$('#box_filtro_outros_plp').show();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
IntelipostIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa de postagem para sua integração Intelipost?</p>');
}
IntelipostIntegration.prototype.initQuotationForm = function() {
	IntegrationBase.prototype.initQuotationForm.call();
	$('#cotacaoTipoObjeto, #cotacaoTotalProdutos').show();
}
IntelipostIntegration.prototype.externalLabel = function() {
	return true;
}
IntelipostIntegration.prototype.individualLabelPrint = function() {
	return true;
}

var CustomLogisticIntegration = function() {
	CorreiosIntegration.apply(this, arguments);
}
CustomLogisticIntegration.prototype = Object.create(CorreiosIntegration.prototype);
CustomLogisticIntegration.prototype.hideBtnTrackingSync = true;
CustomLogisticIntegration.prototype.initFiltersFormObjectList = function(obj) {
	$('#box_btn_vincular_remessa').show();
}
CustomLogisticIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#botaoSincronizarRastro').hide();

	var url = obj.rastreamento.url;

	if (url == null || url.length == 0) {
		$('#link_acompanhamento_correios').hide();
	} else {
		$('#link_acompanhamento_correios').attr('href', url);
	}
}
CustomLogisticIntegration.prototype.initFormShipmentActions = function() {
	$('#prontoEnvioPlps, #despachadoPlps, #fecharPlps').hide();
	$('#box_psq_tipo_log, #imprimirPlps').show();
	$('#psqTipoLog').val(0);
	$('#box_btn_fechar_plp').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, '');
}
CustomLogisticIntegration.prototype.initFormObj = function() {
	CorreiosIntegration.prototype.initFormObj.call(this, arguments);
	$('#suspenderEntrega').hide();
}
CustomLogisticIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('#div_side_acoes .act-imprimir-discriminacao-conteudo, #div_side_acoes .act-imprimir-lista-det').show();
	$('#div_side_acoes .act-imprimir-ars, #div_side_acoes .act-imprimir-voucher').hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-print');
	$(document).on('click', '.act-imprimir-etiquetas', function() {
		imprimirRotulosSelecionados('etiqueta');
		return false;
	});
}
CustomLogisticIntegration.prototype.allowCustomTracking = function() {
	return true;
}
CustomLogisticIntegration.prototype.hasQuotationMethod = function() {
	return false;
}

var CorreiosLogIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
CorreiosLogIntegration.prototype = Object.create(IntegrationBase.prototype);
CorreiosLogIntegration.prototype.constructor = CorreiosLogIntegration;
CorreiosLogIntegration.prototype.TITLE_ERROR_FIELD = 'Dados Correios Log';
CorreiosLogIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', {text: float2moeda(obj.valorDeclarado), class: 'tline center hidden-mobile'}),
		$('<td>', {class: 'tline text_left'}).append(CorreiosIntegration.getServiceIcon(obj)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
CorreiosLogIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(CorreiosIntegration.getServiceIcon(obj)),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
CorreiosLogIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj('#link_servicos_adicionais');
}
CorreiosLogIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa de pedidos para sua integração Correios Log?</p>');
}
CorreiosLogIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_5" data-visible-status="0">'
		+ '<img src="styles/images/impressaoEtiquetasCorreiosLog.png"/>Enviar</li>'
		+ '<li id="im_9" data-visible-status="1"><img src="images/document_ok.gif"/>Enviar NFE</li>';
	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#enviarXmlNfes').show();
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
CorreiosLogIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det, .act-imprimir-etiquetas, .act-desvincular-objetos', $('#div_side_acoes')).hide();
}
CorreiosLogIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasCorreiosLog.png');
}
CorreiosLogIntegration.prototype.getIcoSrc = function() {
	return {src: 'styles/images/impressaoEtiquetasCorreiosLog.png', class: 'ico-correioslog'}
}
CorreiosLogIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').unbind('mousedown').mousedown(function(e) {
		e.preventDefault();

		$('#consultaRastroCorreios input[name="objetos"]').val(LogisticIntegration.getTrackingCode(obj));
		$('#consultaRastroCorreios').submit();
	});
}
CorreiosLogIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').show();
	$('#enviarXmlNfes').show();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
CorreiosLogIntegration.prototype.initQuotationForm = function() {
	CorreiosIntegration.prototype.initQuotationForm.call();
}
CorreiosLogIntegration.prototype.hasQuotationMethod = function() {
	return false;
}

var MercadoEnviosIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
MercadoEnviosIntegration.prototype = Object.create(IntegrationBase.prototype);
MercadoEnviosIntegration.prototype.constructor = MercadoEnviosIntegration;
MercadoEnviosIntegration.prototype.initFiltersFormObjectList = function(obj) {
	$('#box_btn_gerar_plp, #box_btn_vincular_remessa').hide();
	$('#box_btn_imprimir_etiqueta').show();
}
MercadoEnviosIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', {text: '-', class: 'tline center hidden-mobile'}),
		$('<td>', {class: 'tline text_left'}).append((obj.fulfillment ? '<img title="Modo de operação Fulfillment" alt="Modo de operação Fulfillment" src="images/icon-fulfillment.png" />' : '')),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
MercadoEnviosIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline objplp' }),
		$('<td>', { text: '-', class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }),
		$('<td>', { class: 'tline' })
	);

	return trObj;
}
MercadoEnviosIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj();
}
MercadoEnviosIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_9" data-visible-status="1"><img src="images/document_ok.gif"/>Enviar NFE</li>';
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps, #fecharPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);

	$('#box_psq_tipo_log').show();
	$('#psqTipoLog').val(0);
}
MercadoEnviosIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det, .act-imprimir-etiquetas, .act-desvincular-objetos', $('#div_side_acoes')).hide();
}
MercadoEnviosIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasME.png');
}
MercadoEnviosIntegration.prototype.getIcoSrc = function() {
	return {src: 'styles/images/impressaoEtiquetasME.png', class: 'ico-mercadoenvios'}
}
MercadoEnviosIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').hide();
}
MercadoEnviosIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1', '4'];
	$('#box_filtro_outros_plp').show();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
MercadoEnviosIntegration.prototype.hasQuotationMethod = function() {
	return false;
}
MercadoEnviosIntegration.prototype.externalLabel = function() {
	return true;
}
MercadoEnviosIntegration.prototype.individualLabelPrint = function() {
	return true;
}

var TotalExpressIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
TotalExpressIntegration.prototype = Object.create(IntegrationBase.prototype);
TotalExpressIntegration.prototype.constructor = TotalExpressIntegration;
TotalExpressIntegration.prototype.TITLE_ERROR_FIELD = 'Validação de coleta Total Express';
TotalExpressIntegration.prototype.hideBtnTrackingSync = true;
TotalExpressIntegration.prototype.initFiltersFormObjectList = function(obj) {
	$('#box_btn_vincular_remessa').show();
}
TotalExpressIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline center hidden-mobile' }),
		$('<td>', {class: 'tline text_left'}).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
TotalExpressIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
TotalExpressIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj('#link_servicos_adicionais, #marcador_espacamento, #link_dimensoes, #info_dimensao');
	$('#ar, #mp, label[for="ar"], label[for="mp"]').hide();
}
TotalExpressIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_10" data-visible-status="0"><img src="styles/images/impressaoEtiquetasTotalExpress.png"/>Enviar</li>';
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps').hide();

	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#box_psq_tipo_log').show();
	$('#psqTipoLog').val(0);
	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
TotalExpressIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('#div_side_acoes .act-imprimir-ars, .act-imprimir-discriminacao-conteudo, #div_side_acoes .act-imprimir-voucher, #div_side_acoes .act-imprimir-lista-det').hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-totalexpress');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		imprimirRotulosSelecionados('etiqueta');
		return false;
	});
}
TotalExpressIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	var pedido = obj.id;
	if (obj.confAutenticacao.tipoIdentificadorColeta != 0) {
		pedido = (obj.confAutenticacao.tipoIdentificadorColeta == 1 ? obj.numero : obj.numeroLoja);
	}

	$('#botaoSincronizarRastro').hide();
	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').attr('href', 'http://tracking.totalexpress.com.br/poupup_track.php?reid=' + obj.confAutenticacao.codigoEmpresa + '&pedido=' + pedido + '&nfiscal=' + obj.numero);
}
TotalExpressIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').hide();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
TotalExpressIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa para sua integração Total Express?</p>');
}
TotalExpressIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasTotalExpress.png');
}
TotalExpressIntegration.prototype.getIcoSrc = function() {
	return { src: 'styles/images/impressaoEtiquetasTotalExpress.png', class: 'ico-totalexpress' };
}
TotalExpressIntegration.prototype.initQuotationForm = function() {
	IntegrationBase.prototype.initQuotationForm.call();
	$('#cotacaoValorDeclarado').show();
}

var FrenetIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
FrenetIntegration.prototype = Object.create(IntegrationBase.prototype);
FrenetIntegration.prototype.constructor = FrenetIntegration;
FrenetIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', {text: '-', class: 'tline center hidden-mobile'}),
		$('<td>', {class: 'tline text_left'}).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
FrenetIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade, class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(''),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
FrenetIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj('#link_dimensoes, #info_dimensao');
}
FrenetIntegration.prototype.initFormShipmentActions = function() {
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps, #fecharPlps').hide();

	$('#box_psq_tipo_log').show();
	$('#psqTipoLog').val(0);
	IntegrationBase.prototype.initFormShipmentActions.call(this);
}
FrenetIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('#div_side_acoes .act-imprimir-ars, .act-imprimir-discriminacao-conteudo, #div_side_acoes .act-imprimir-voucher, #div_side_acoes .act-imprimir-lista-det').hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-frenet');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		imprimirRotulosSelecionados('etiqueta');
		return false;
	});
}
FrenetIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').hide();
}
FrenetIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').hide();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
FrenetIntegration.prototype.initFiltersFormObjectList = function() {
	$('#box_btn_gerar_plp, #box_btn_vincular_remessa').hide();
}
FrenetIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasFrenet.png');
}
FrenetIntegration.prototype.getIcoSrc = function() {
	return { src: 'styles/images/impressaoEtiquetasFrenet.png', class: 'ico-frenet' }
}
FrenetIntegration.prototype.getIcoSrc = function() {
	$('#quantidadeCotacao').show();
}
FrenetIntegration.prototype.allowCustomTracking = function() {
	return CustomLogisticIntegration.prototype.allowCustomTracking.call();
}
FrenetIntegration.prototype.initQuotationForm = function() {
	IntegrationBase.prototype.initQuotationForm.call();
	$('#cotacaoTotalProdutos').show();
}

var B2WEntregaIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
B2WEntregaIntegration.prototype = Object.create(IntegrationBase.prototype);
B2WEntregaIntegration.prototype.constructor = B2WEntregaIntegration;
B2WEntregaIntegration.prototype.TITLE_ERROR_FIELD = 'Validação de remessa B2W Entrega';
B2WEntregaIntegration.prototype.initFiltersFormObjectList = function(obj) {
	$('#box_btn_imprimir_etiqueta, #box_btn_vincular_remessa').show();
}
B2WEntregaIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', {text: LogisticIntegration.getTrackingCode(obj), class: 'tline'}),
		$('<td>', {text: '-', class: 'tline center hidden-mobile'}),
		$('<td>', {class: 'tline text_left'}).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', {class: 'tline center'}).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
B2WEntregaIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
B2WEntregaIntegration.prototype.initFormObj = function() {
	IntegrationBase.prototype.initFormObj();
}
B2WEntregaIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_10" data-visible-status="0"><img src="styles/images/impressaoEtiquetasB2WEntrega.png"/>Enviar</li>' +
		'<li id="im_11" data-visible-status="1"><i class="fa fa-times-rectangle" style="color: #DD6F77; padding-right: 10px;" title="Desagrupa a PLP na SkyHub"></i>Desagrupar PLP</li>';

	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps').hide();

	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#box_psq_tipo_log').show();
	$('#psqTipoLog').val(0);
	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
B2WEntregaIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa para sua integração B2W Entrega?</p>');
}
B2WEntregaIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('#div_side_acoes .act-imprimir-ars, .act-imprimir-discriminacao-conteudo, #div_side_acoes .act-imprimir-voucher, #div_side_acoes .act-imprimir-lista-det').hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-b2wentrega');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		if (imprimirRotulosSelecionados('etiquetaExterna')) {
			printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
		}
		return false;
	});
}
B2WEntregaIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasB2WEntrega.png');
}
B2WEntregaIntegration.prototype.getIcoSrc = function() {
	return {src: 'styles/images/impressaoEtiquetasB2WEntrega.png', class: 'ico-b2wentrega'}
}
B2WEntregaIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').hide();
}
B2WEntregaIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1', '4'];
	$('#box_filtro_outros_plp').show();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
B2WEntregaIntegration.prototype.hasQuotationMethod = function() {
	return false;
}
B2WEntregaIntegration.prototype.externalLabel = function() {
	return true;
}
B2WEntregaIntegration.prototype.individualLabelPrint = function() {
	return true;
}

var JadlogIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
JadlogIntegration.prototype = Object.create(IntegrationBase.prototype);
JadlogIntegration.prototype.constructor = JadlogIntegration;
JadlogIntegration.prototype.TITLE_ERROR_FIELD = 'Validação de pedido JADLOG';
JadlogIntegration.prototype.initFiltersFormObjectList = function() {
	$('#box_btn_imprimir_etiqueta').show();
}
JadlogIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline center hidden-mobile' }),
		$('<td>', { class: 'tline text_left' }).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', { class: 'tline center' }).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
JadlogIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade, class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(''),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
JadlogIntegration.prototype.initFormObj = function(serviceCode) {
	var showElements = '#link_servicos_adicionais, #marcador_espacamento, #link_dimensoes, #info_dimensao';
	if (serviceCode == 40) {
		showElements += ', #botaoObterPontosRetirada';
	}

	IntegrationBase.prototype.initFormObj(showElements);
	$('#ar, #mp, label[for="ar"], label[for="mp"]').hide();
}
JadlogIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa de postagem para sua integração JADLOG?</p>');
}
JadlogIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_5" data-visible-status="0">'
		+ '<img src="styles/images/impressaoEtiquetasJadlog.png"/>Enviar</li>'
		+ '<li id = "im_11" data-visible-status="1"><i class="fa fa-times-rectangle" style="color: #DD6F77; padding-right: 10px;" title="Cancelar pedido na JADLOG"></i>Cancelar pedido</li >';

	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
JadlogIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det, .act-desvincular-objetos', $('#div_side_acoes')).hide();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-jadlog');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		if (imprimirRotulosSelecionados('etiquetaExterna')) {
			printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
		}
		return false;
	});
}
JadlogIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').attr('href', 'http://www.jadlog.com.br/trackingcorp.jsp?cnpj=' + formatNumber(obj.contato_remetente.cnpj) + '&nota=' + parseInt(obj.numero));
}
JadlogIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').hide();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
JadlogIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasJadlog.png');
}
JadlogIntegration.prototype.getIcoSrc = function() {
	return { src: 'styles/images/impressaoEtiquetasJadlog.png', class: 'ico-jadlog' }
}
JadlogIntegration.prototype.initQuotationForm = function(logisticService) {
	IntegrationBase.prototype.initQuotationForm.call(this, (logisticService.codigo == 40));
	$('#cotacaoTotalProdutos, #cotacaoFretePorConta').show();
}
JadlogIntegration.prototype.externalLabel = function() {
	return true;
}

var MandaeIntegration = function() {
	IntegrationBase.apply(this, arguments);
}
MandaeIntegration.prototype = Object.create(IntegrationBase.prototype);
MandaeIntegration.prototype.constructor = MandaeIntegration;
MandaeIntegration.prototype.TITLE_ERROR_FIELD = 'Validação de pedido Mandaê';
MandaeIntegration.prototype.initFiltersFormObjectList = function() {
	$('#box_btn_vincular_remessa, #box_btn_imprimir_etiqueta').show();
}
MandaeIntegration.prototype.renderObjectList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectList.call(this, obj);

	trObj.append(
		$('<td>', { text: LogisticIntegration.getTrackingCode(obj), class: 'tline' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline center hidden-mobile' }),
		$('<td>', { class: 'tline text_left' }).append(LogisticIntegration.getEmailIcon(obj.dataEnvioCodRastro)),
		$('<td>', { class: 'tline center' }).append(aSituacao.situacaoHtml)
	);

	return trObj;
}
MandaeIntegration.prototype.renderObjectShipmentList = function(obj) {
	var trObj = IntegrationBase.prototype.renderObjectShipmentList.call(this, obj);
	var htmlBtnErros = '';

	if (infoErros.length > 0) {
		htmlBtnErros = '<input type="button" class="inf-cliente-produto" title="' + infoErros + '" />';
	}

	trObj.append(
		$('<td>', { text: obj.prefixoServico + obj.codigoEtiqueta + obj.nacionalidade, class: 'tline objplp' }),
		$('<td>', { text: float2moeda(obj.valorDeclarado), class: 'tline objplp hidden-mobile' }),
		$('<td>', { class: 'tline objplp' }).append(''),
		$('<td>', { class: 'tline' }).append(htmlBtnErros)
	);

	return trObj;
}
MandaeIntegration.prototype.initFormObj = function() {
	var showElements = '#link_servicos_adicionais, #marcador_espacamento, #link_dimensoes, #info_dimensao';

	IntegrationBase.prototype.initFormObj(showElements);
	$('#ar, #mp, label[for="ar"], label[for="mp"]').hide();
}
MandaeIntegration.prototype.showObjectLocation = function(locations) {
	$('label[for="localizacao"]').html('Observação');
	return locations.origem;
}
MandaeIntegration.prototype.initFormSendShipmentOrder = function() {
	IntegrationBase.prototype.initFormSendShipmentOrder.call(this, 'Enviar Remessa', 'Enviar');
	$('#box_acoes_correios').empty().html('<p>Deseja enviar a remessa de postagem para sua integração Mandaê?</p>');
}
MandaeIntegration.prototype.initFormShipmentActions = function() {
	var actionsBtn = '<li id="im_5" data-visible-status="0">'
		+ '<img src="styles/images/impressaoEtiquetasMandae.png"/>Enviar</li>';

	$('#fecharPlps').text('Enviar remessas selecionadas').show();
	$('#prontoEnvioPlps, #despachadoPlps, #imprimirPlps').hide();

	IntegrationBase.prototype.initFormShipmentActions.call(this, actionsBtn);
}
MandaeIntegration.prototype.initFormObjectShipment = function() {
	IntegrationBase.prototype.initFormObjectShipment.call(this, arguments);

	$('.act-imprimir-ars, .act-imprimir-discriminacao-conteudo, .act-imprimir-voucher, .act-imprimir-lista-det, .act-desvincular-objetos', $('#div_side_acoes')).hide();
	$('.act-desvincular-objetos').show();
	$('#div_side_acoes .act-imprimir-etiquetas').addClass('ico-mandae');

	$(document).on('click', '.act-imprimir-etiquetas', function() {
		if (imprimirRotulosSelecionados('etiquetaExterna')) {
			printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
		}
		return false;
	});
}
MandaeIntegration.prototype.updateFormObjectTracking = function(obj) {
	IntegrationBase.prototype.updateFormObjectTracking.call(this, obj);

	$('#objEtiqueta').val(LogisticIntegration.getTrackingCode(obj));
	$('#link_acompanhamento_correios').attr('href', 'https://rastreae.com.br/resultado/' + LogisticIntegration.getTrackingCode(obj));
}
MandaeIntegration.prototype.initFiltersFormShipmentList = function() {
	filtersAvaliable = ['0', '1'];
	$('#box_filtro_outros_plp').hide();
	IntegrationBase.prototype.initFiltersFormShipmentList(filtersAvaliable);
}
MandaeIntegration.prototype.initFormOrderFiscal = function() {
	$('#im_27 img').attr('src', 'styles/images/impressaoEtiquetasMandae.png');
}
MandaeIntegration.prototype.getIcoSrc = function() {
	return { src: 'styles/images/impressaoEtiquetasMandae.png', class: 'ico-mandae' }
}
MandaeIntegration.prototype.initQuotationForm = function() {
	IntegrationBase.prototype.initQuotationForm.call();
	$('#cotacaoValorDeclarado').show();
}
MandaeIntegration.prototype.externalLabel = function() {
	return true;
}