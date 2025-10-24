var integracoesLogisticas = [];
var servicosLogistica = [];
var servicosLogisticaEx = [];
var volumesLogistica = [];
var atualizarCamposTransportadora = true;
var cotacoes = [];

$(function() {
	$(document).on('change', '#integracaoLogistica', function() {
		xajax_obterServicosLogistica($(this).val(), obterRequisitosCotacao());
		clearVolumes();

		toggleVerDisponibilidade();
		toggleLogisticaPersonalizada();
		checkDisableLogistic();
	})
	.on('change', '#cep, #etiqueta_cep, #loja', function() {
		validarDisponibilidadeServicos();
	})
	.on('click', '#dispo_servico_log', function(e) {
		e.preventDefault();
		validarDisponibilidadeServicos();
	})
	.on('change', 'select[name="servicosLogistica[]"], input[name="trackings[]"]', function() {
		var iVolume = $(this).attr('data-volume-i');
		var idServico = $('select[name="servicosLogistica[]"][data-volume-i="' + iVolume + '"]').val();
		var tracking = $('input[name="trackings[]"][data-volume-i="' + iVolume + '"]').val();

		atualizarDadosVolume(iVolume, idServico, tracking);
	})
	.on('change', 'select[name="servicosLogistica[]"]', function() {
		atualizarCodigoCorreios('S');
	})
	.on('click', '#add_novo_volume', function(e) {
		e.preventDefault();

		var novoVolume = {
			'id': 0,
			'idServico': '-1',
			'tracking': ''
		};

		addNovoVolume(novoVolume, volumesLogistica.length, true);
	})
	.on('click', 'a[name="excluir_volume"]', function() {
		excluirVolume($(this).attr('data-volume-i'));
	})
	.on('click', 'a[name="consultarCotacao"]', function() {
		abrirPopupCotacao($(this).attr('data-volume-i'));
	})
	.on('change', '#objectType', function() {
		var tipoIntegracao = $('#integracaoLogistica option:selected').attr('data-tipointegracao');
		LogisticFactory.create(tipoIntegracao).updateObjectType($(this).val());
	});
});

function obterRequisitosCotacao() {
	var requisitosCotacao = {
		'idLojaVirtual': $('#loja').val(),
		'idConfUnidadeNegocio': $('#idConfUnidadeNegocio').val(),
		'contato_destinatario': {
			'cep': ($('#etiqueta_mostrar').is(':checked') ? $('#etiqueta_cep').val() : $('#cep').val())
		},
		'pesoBruto': $('#pesoBruto').val(),
		'tipoObjeto': '002',
		'integracaoLogistica': $('#integracaoLogistica option:selected').attr('data-tipointegracao'),
		'totalProdutos': $('#divSubtotal').val()
	};

	return requisitosCotacao;
}

function toggleVerDisponibilidade() {
	if ($('#integracaoLogistica').children('option:selected').attr('data-tipointegracao') === 'Correios') {
		$('#dispo_servico_log').show();
	} else {
		$('#dispo_servico_log').hide();
	}
}

function toggleLogisticaPersonalizada() {
	if ($('#integracaoLogistica').children('option:selected').attr('data-tipointegracao') == 'CustomLogistic') {
		$('#trackingCode').prop('disabled', false).parent().show();
	} else {
		$('#trackingCode').prop('disabled', true).parent().hide();
	}
}

function validarDisponibilidadeServicos() {
	if ($('#integracaoLogistica option:selected').attr('data-tipointegracao') === 'Correios') {
		cepDestinatario = ($('#etiqueta_mostrar').prop('checked') ? $('#etiqueta_cep').val() : $('#cep').val());
		xajax_validarDisponibilidadeServicoTela(volumesLogistica, {'loja': $('#loja').val() || 0, 'idConfUnidadeNegocio': $('#idConfUnidadeNegocio').val() || 0}, cepDestinatario);
	}
}

function mostrarDisponibilidade(disponibilidade) {
	$.each(disponibilidade.volumes, function() {
		var info = '';

		if (!disponibilidade.erro) {
			info = (this.disponivel ?
				$('<i>', {'class': 'icon-ok', 'title': 'Serviço disponível'}) :
				$('<i>', {'class': 'icon-remove', 'title': 'Serviço indisponível de ' + disponibilidade.cepRemetente + ' para ' + disponibilidade.cepDestinatario}));
		} else {
			info = $('<i>', {'class': 'icon-warning-sign', 'title': 'Não foi possível consultar disponibilidade do serviço, integração logística não respondendo.'});
		}

		$('[data-info-servico="' + this.idServico + '"]').empty().append(info);
	});
}

function mostrarLogisticas(logisticas) {
	var selectIntegracoesLogistica = limparSelectISLogistica($('#integracaoLogistica'));
	integracoesLogisticas = [];

	if (logisticas.length > 0) {
		integracoesLogisticas = logisticas;
		$.each(logisticas, function() {
			if (this.status == 'H') {
				selectIntegracoesLogistica.append('<option value="' + this.id + '" data-statusintegracao="' + this.status + '" data-tipointegracao="' + this.tipoIntegracao + '">' + this.descricao + '</option>');
			}
		});
	}
}

function mostrarVolumes(volumes, copia) {
	$('#lista_volumes .table-body').empty();
	servicosLogisticaEx = [];

	$.each(volumes, function(i, volume) {
		if (i == (volumes.length - 1)) {
			var integracaoLogisticaSelecionada = arrayJsonSearch(integracoesLogisticas, 'id', volume.idIntegracao);
			if (!jQuery.isEmptyObject(integracaoLogisticaSelecionada)) {
				if (integracaoLogisticaSelecionada.status == 'D' || integracaoLogisticaSelecionada.status == 'E') {
					var situacao = (integracaoLogisticaSelecionada.status == 'D' ? 'desabilitada' : 'excluída');
					$('#integracaoLogistica').append('<option value="' + integracaoLogisticaSelecionada.id + '" data-statusintegracao="' + integracaoLogisticaSelecionada.status + '" data-tipointegracao="' + integracaoLogisticaSelecionada.tipoIntegracao + '" disabled>' + integracaoLogisticaSelecionada.descricao + ' (' + situacao + ')</option>');
				}
			}

			$('#integracaoLogistica').val(volume.idIntegracao);
		}

		if (!volume.ativo && volume.idEtiqueta != -1 && jQuery.isEmptyObject(arrayJsonSearch(servicosLogisticaEx, 'id', volume.idEtiqueta))) {
			servicosLogisticaEx.push({
				'id': volume.idEtiqueta,
				'descricao': volume.nomeServico,
			});
		}

		var tracking = '';
		if (volume.codigoEtiqueta.length > 0) {
			tracking = volume.prefixoServico + volume.codigoEtiqueta + volume.nacionalidade;
		}

		var volumeParsed = {
			'integracaoLogistica': volume.tipoIntegracao,
			'id': (copia == 'S' ? 0 : volume.id),
			'idServico': volume.idEtiqueta,
			'tracking': tracking,
			'pesoBruto': volume.pesoBruto,
			'largura': volume.largura,
			'altura': volume.altura,
			'comprimento': volume.comprimento,
			'ar': volume.ar,
			'mp': volume.mp,
			'tipoObjeto': volume.tipoObjeto,
			'valorDeclarado': volume.valorDeclarado
		};

		addNovoVolume(volumeParsed, i, false);
	});

	if (volumesLogistica.length > 0) {
		$('#box_volumes').show();
	}

	permissaoEdicaoTracking();
	toggleVerDisponibilidade();
	checkDisableLogistic();
}

function addNovoVolume(volume, i, atualizarQtdVol) {
	volumesLogistica.push(volume);
	var integracaoLogistica = ($('#integracaoLogistica').children('option:selected').attr('data-tipointegracao') || volume.integracaoLogistica || 'CorreiosFaixa');
	var hasQuotationMethod = (LogisticFactory.create(integracaoLogistica).hasQuotationMethod() && integracaoLogistica != 'CorreiosFaixa');
	var fieldServico = $('<select>', {'name': 'servicosLogistica[]', 'data-volume-i': i, 'class': 'no-border input_text tipsyOff'}).append(obterOpcoesServicos());
	var quotationField = '';

	if (hasQuotationMethod) {
		quotationField = $('<div>', {'class': 'table-cell', 'style': 'width:1%;'}).append(
			$('<a>', { 'name': 'consultarCotacao', 'data-volume-i': i, 'type': 'button', 'class': 'editgridh tableIcon'}).append(
				$('<i>', {'class': 'icon-truck', 'title': 'Consultar cotações'})
			)
		);
	}

	$('#lista_volumes .table-body').append(
		$('<div>', {'data-volume-row': i, 'class': 'table-row'}).append(
			$('<div>', {'name': 'volume_indice', 'text': i + 1, 'class': 'table-cell text_center', 'style': 'width:2%;'}),
			$('<div>', {'class': 'table-cell', 'style': 'width:35%;'}).append(
				fieldServico
			),
			$('<div>', {'class': 'table-cell'}).append(
				$('<input>', {'name': 'trackings[]', 'data-volume-i': i, 'class': 'no-border input_text', 'type': 'text', 'maxlength': '30', 'value': volume.tracking, 'placeholder': 'N/A'}),
				$('<div>', {'name': 'volume_info', 'data-info-servico': volume.idServico})
			),
			quotationField,
			$('<div>', {'class': 'table-cell btn-delete-box', 'style': 'width:1%;'}).append(
				$('<a>', {'name': 'excluir_volume', 'data-volume-i': i, 'type': 'button', 'class': 'editgridh tableIcon'}).append($('<i>', {'class': 'icon-trash', 'title': 'Remover objeto'}))
			)
		)
	);

	permissaoEdicaoTracking();
	fieldServico.val(volume.idServico);

	if (atualizarQtdVol) {
		atualizarQtdVolumes();
	}
}

function permissaoEdicaoTracking() {
	var allowCustomTracking = LogisticFactory.create($('#integracaoLogistica').children('option:selected').attr('data-tipointegracao')).allowCustomTracking();
	$('input[name*="trackings"]').prop('readonly', !allowCustomTracking);
}

function obterOpcoesServicos() {
	var options = [$('<option>', {'value': '-1', 'text': 'Selecione o serviço'})];

	$.each(servicosLogistica, function(i, servicoLogistica) {
		options.push($('<option>', {'value': servicoLogistica.servico.id, 'text': servicoLogistica.servico.descricao}));
	});

	$.each(servicosLogisticaEx, function(i, servicoLogisticaEx) {
		options.push($('<option>', {'value': servicoLogisticaEx.id, 'text': servicoLogisticaEx.descricao + ' (excluído)', 'disabled': true}));
	});

	return options;
}

function mostrarServicosLogisticas(servicos) {
	servicosLogistica = servicos;

	$('select[name="servicosLogistica[]"]').each(function() {
		$(this).empty().append(obterOpcoesServicos());
		$(this).val('-1');
	});
}

function mostrarRastro(trackingCodeElement, codigoEtiqueta, trackingCode, possuiServico) {
	trackingCodeElement.parent().show();

	if (codigoEtiqueta.indexOf('S/F') != -1) {
		trackingCodeElement.val('Sem tracking');
	} else {
		var nacionalidadeServico = (possuiServico ? trackingCode.servico.nacionalidade : 'BR');
		trackingCodeElement.val(trackingCode.tracking.prefixoServico + codigoEtiqueta + nacionalidadeServico);
	}
}

function selecionarServico(servico) {
	if (servico.msgErro) {
		$('[data-info-servico="-1"]').append(
			$('<i>', {'class': 'icon-info-sign', 'style': 'color: #e3a541', 'title': 'Regras de frete: ' + servico.msgErro.toLowerCase()})
		);
	} else {
		$('#lista_volumes select[name="servicosLogistica[]"]').val(servico.id).change();

		$('[data-info-servico="' + servico.id + '"]').append(
			$('<i>', {'class': 'icon-ok', 'title': 'Regras de frete: serviço selecionado a partir das regras de frete'})
		);
	}
}

function atualizarCodigoCorreios(atualizarTransportadora) {
	$('select[name="servicosLogistica[]"]').each(function() {
		var logistica = JSON.search(servicosLogistica, '//*[servico[contains(id, "' + $(this).val() + '")]]');
		var logisticaEncontrada = logistica.length;
		var idServico = -1;

		if (atualizarTransportadora == 'S' && atualizarCamposTransportadora) {
			limparDadosTransportador();
			if (logisticaEncontrada) {
				atualizarDadosTransportadora(logistica[0].transportadora);
			}
		}

		var qtdTrackingFaixa;
		if (logisticaEncontrada) {
			qtdTrackingFaixa = logistica[0].servico.quantidade;
			idServico = logistica[0].servico.id;
		}

		mostrarAvisoFaixas(qtdTrackingFaixa, idServico);
	});
}

function mostrarAvisoFaixas(qtdTrackingFaixa, idServico) {
	var msg = '';
	var color = '';

	if (qtdTrackingFaixa == 0) {
		msg = 'Atenção: Você utilizou todos os códigos cadastrados para este serviço.';
		color = 'red';
	} else if (qtdTrackingFaixa > 0 && qtdTrackingFaixa <= 50) {
		msg = 'Atenção: Restam ' + qtdTrackingFaixa + ' códigos para este serviço.';
		color = '#e3a541';
	}

	$('[data-info-servico="' + idServico + '"]').empty();
	if (msg.length > 0) {
		$('[data-info-servico="' + idServico + '"]').append(
			$('<i>', {'class': 'icon-info-sign', 'style': 'color: ' + color, 'title': msg})
		);
	}
}

function limparSelectISLogistica(select) {
	select.find('option[value!="-1"]').remove();
	return select;
}

function atualizarDadosTransportadora(transportadora) {
	if (typeof transportadora != 'undefined') {
		$('#idTransportador').val(transportadora.idTransportadora);
		$('#transportador').val(transportadora.descricao);

		for (var k in transportadora) {
			setFieldValue($('#' + k + 'Transportador'), transportadora[k]);
		}
	}
}

function limparDadosTransportador() {
	$('#idTransportador').val(0);
	$('#transportador, #cnpjTransportador, #ieTransportador, #enderecoTransportador, #municipioTransportador, #ufTransportador').val('');
}

function clearVolumes() {
	volumesLogistica = [];

	var volumeVazio = {
		'id': 0,
		'idEtiqueta': '-1',
		'prefixoServico': '',
		'codigoEtiqueta': '',
		'nacionalidade': '',
		'idIntegracao': $('#integracaoLogistica').val()
	};

	mostrarVolumes([volumeVazio]);
	limparDadosTransportador();
	atualizarQtdVolumes();
}

function vincularLogisticaTracking(logisticaTracking) {
	$.each(logisticaTracking, function(i, logisticaObj) {
		var linhaItem = $('#datatable tr[data-origem-logistica="' + i + '"]');
		var tracking = logisticaObj.logisticaTracking;

		if (logisticaObj.logisticaTipoIntegracao == 'Intelipost' && tracking == 'N/A') {
			tracking = (linhaItem.attr('numero') ? linhaItem.attr('numero') : 'N/A');
		}

		linhaItem.attr('etiquetacorreios', tracking);
		linhaItem.attr('data-objetoplp', logisticaObj.logisticaIdObj);
		linhaItem.attr('data-tipo-integracao-logistica', logisticaObj.logisticaTipoIntegracao);
		linhaItem.attr('data-url-rastreamento', logisticaObj.url);

		if (logisticaObj.mostraRastro) {
			linhaItem.find('td[name="marcadores"]').append('<img src="images/icone_correios_rastro.png" data-action="abrirPopupRastroObjeto" title="Rastro do objeto" style="width: 18px; height: 18px;" />');
		}
	});
}

function clearFormLogistica() {
	$('#trackingCode').prop('disabled', true).val('');
	servicosLogistica = volumesLogistica = integracoesLogisticas = [];
	atualizarCamposTransportadora = true;
	$('#box_volumes').hide();
}

function atualizarDadosVolume(iVolume, idServico, tracking) {
	volumesLogistica[iVolume] = {
		'id': 0,
		'idServico': idServico,
		'tracking': tracking
	};

	$('[data-volume-row="' + iVolume + '"]').find('[name="volume_info"]').attr('data-info-servico', idServico);
	atualizarQtdVolumes();
}

function excluirVolume(iVolume) {
	volumesLogistica.splice(iVolume, 1);

	$('div[data-volume-row="' + iVolume + '"]').remove();
	$('div[data-volume-row]').each(function(i) {
		$('[data-volume-i="' + $(this).attr('data-volume-row') + '"]').attr('data-volume-i', i);
		$(this).find('[name="volume_indice"]').text(i + 1);
		$(this).attr('data-volume-row', i);
	});

	atualizarQtdVolumes();
}

function atualizarQtdVolumes() {
	if ($('#volume_calculado').val() == 'S') {
		return;
	}

	var totalVolumes = 0;
	$.each(volumesLogistica, function() {
		if (this.idServico > 0) {
			totalVolumes++;
		}
	});

	$('#qtdVolumes').val(totalVolumes);
}

function erroGeracaoTracking(msg) {
	var dialog = {
		content: '<div>Alguns trackings não foram gerados.<br/><br/> <b>Motivo:</b> ' + msg + '</div>',
		config: {
			title: 'Geração de tracking'
		},
		hideCancel: true
	};

	createDialog(dialog);
}

function checkDisableLogistic() {
	var statusLogistica = $('#integracaoLogistica').children('option:selected').attr('data-statusintegracao');

	if (statusLogistica == 'D' || statusLogistica == 'E') {
		$('#add_novo_volume').hide();
		$('#lista_volumes').children().children().find(':input').attr('disabled', true);
	} else {
		$('#add_novo_volume').show();
		$('#lista_volumes').children().children().find(':input').attr('disabled', false);
	}
}

function abrirPopupCotacao(iVolume) {
	$.get('templates/form.logisticas.cotacao.frete.php', function(data) {
		var dialog = {
			content: data,
			config: {
				title: 'Consulta de cotações',
				width: getMobileWidthForDialogs(648),
				height: 530
			},
			textOk: 'Salvar',
			fnOk: function() {
				salvarCotacao();
			},
			fnCreate: function() {
				displayWait('sincronizarRastroWait');
				updateQuotationFields(iVolume);
			}
		};

		createDialog(dialog);
	});
}

function updateQuotationFields(iVolume) {
	var tipoIntegracao = $('#integracaoLogistica option:selected').attr('data-tipointegracao');
	var logisticDriver = LogisticFactory.create(tipoIntegracao);
	var params = {'loja': $('#loja').val() || 0, 'idConfUnidadeNegocio': $('#idConfUnidadeNegocio').val() || 0};
	logisticDriver.initQuotationForm(getLogisticServiceById(volumesLogistica[iVolume].idServico, 'id'));
	logisticDriver.updateObjectType('002');

	xajax_obterRequisitosCotacoes(params, function(requisitos) {
		var enderecoRemetente = requisitos.enderecoRemetente;
		var parametros = requisitos.parametros;

		$('#iVolume').val(iVolume || 0);
		$('#idObjeto').val(volumesLogistica[iVolume].id);
		$('#cnpjRemetente').val(enderecoRemetente.cnpj);

		var cidade = '-';
		if ($('#etiqueta_mostrar').is(':checked')) {
			cidade = $('#etiqueta_municipio').val();
		} else if ($('#cidade_contato_rapido').length > 0) {
			cidade = $('#cidade_contato_rapido').val();
		} else if ($('#municipio').length > 0) {
			cidade = $('#municipio').val();
		}

		$('#cidadeContato').val(cidade);

		var endereco = ($('#etiqueta_mostrar').is(':checked') ? $('#etiqueta_endereco').val() : $('#endereco').val());
		$('#enderecoContato').val(endereco);

		var fretePorConta = $('#fretePorConta').val();
		fretePorConta = (fretePorConta == 'D' || fretePorConta == '4' ? 'D' : 'R');
		$('#freightOn').val(fretePorConta);

		var cepDestino = ($('#etiqueta_mostrar').is(':checked') ? $('#etiqueta_cep').val() : $('#cep').val());
		$('#cepDestino').val(formataCep(cepDestino));
		$('#cepOrigem').val(formataCep(enderecoRemetente.cep));

		var totalProdutos = nroUsaFloat($('#divSubtotal').length > 0 ? $('#divSubtotal').val() : $('#valorProdutos').val());
		$('#totalProdutos').val(totalProdutos);

		var volume = volumesLogistica[iVolume];

		$('#alturaCotacao').val(volume.altura || 0);
		$('#larguraCotacao').val(volume.largura || 0);
		$('#comprimentoCotacao').val(volume.comprimento || 0);

		var peso = nroUsaFloat($('#pesoBruto').val());
		if (volume.pesoBruto) {
			peso = volume.pesoBruto;
		}

		$('#pesoCotacao').val(peso);

		if (volume.id > 0) {
			$('#objectType').val(volume.tipoObjeto);
			$('#valorDeclarado').val(volume.valorDeclarado);
			$('#ar').prop('checked', volume.ar);
			$('#mp').prop('checked', volume.mp);
		} else {
			var valConsiderar = totalProdutos;
			if (parametros.valorConsiderar == 'F') {
				valConsiderar = nroUsaFloat($('#divTotal').length > 0 ? $('#divTotal').val() : $('#totalFaturado').val());
			}

			if (parametros.ar == 'S' && valConsiderar >= nroUsaFloat(parametros.arMin)) {
				$('#ar').prop('checked', true);
			}

			if (parametros.mp == 'S' && valConsiderar >= nroUsaFloat(parametros.mpMin)) {
				$('#mp').prop('checked', true);
			}

			var valDeclarado = 0;
			if (parametros.valorDeclarado == 'S' && valConsiderar >= nroUsaFloat(parametros.valorDeclaradoMin)) {
				valDeclarado = valConsiderar;
			}

			$('#valorDeclarado').val(valDeclarado);
		}

		closeWait('sincronizarRastroWait');
	});
}

function consultarCotacoes() {
	displayWait('sincronizarRastroWait', true);

	var tipoIntegracao = $('#integracaoLogistica option:selected').attr('data-tipointegracao');
	var dadosIntegracao = {
		'idIntegracao': $('#integracaoLogistica option:selected').val(),
		'tipoIntegracao': tipoIntegracao,
		'servicos': servicosLogistica.map(function(i) {
			return i.servico;
		})
	};

	xajax_consultarCotacoes(dadosIntegracao, xajax.getFormValues('formRequisitosCotacao', true), function(quotes) {
		listarCotacoes(quotes, tipoIntegracao);
	});
}

function getLogisticServiceById(needed, keyType, logisticServices) {
	logisticServices = (logisticServices || servicosLogistica);
	keyType = (keyType || 'idServico');

	var logisticService = {};
	$.each(logisticServices, function() {
		if (this.servico[keyType] == needed) {
			logisticService = this.servico;
			return false;
		}
	});

	return logisticService;
}

function listarCotacoes(quotes, tipoIntegracao) {
	cotacoes = [];
	closeWait('sincronizarRastroWait');

	if (quotes.error) {
		displayQuotationError(quotes.msg);
		return;
	} else {
		$('#erroConsultaCotacao, #resultadoCotacao').html('');
		$('#tabelaCotacoes').show();

		$.each(quotes.quotes, function(key) {
			var disabled = (this.errorCode != 0);
			var keyType = (['Correios', 'Mandae'].indexOf(tipoIntegracao) !== -1 ? 'codigo' : 'idServico');
			var servico = getLogisticServiceById(this.serviceCode, keyType);
			var info = '';

			if (!disabled) {
				cotacoes.push({'indice': key, 'cotacao': this, 'servico': servico});
			}

			if (this.errorMsg) {
				info = $('<a>', {'class': 'tableIcon', 'name': '[popoverAvisoCotacao]', 'data-container': '#popupCotacao', 'data-placement': 'left', 'data-trigger': 'click', 'data-toggle': 'popover', 'data-content': this.errorMsg}).append(
					$('<i>', {'class': 'icon-info-sign'})
				);
			}

			$('#resultadoCotacao').append(
				$('<tr>').append(
					$('<td>').append(
						$('<div>', { 'class': 'input-radio' }).append(
							$('<input>', { 'type': 'radio', 'name': 'quotes', 'id': 'quoteOption' + key, 'value': key, 'disabled': disabled }),
							$('<label>', { 'for': 'quoteOption' + key }),
							$('<label>', { 'class': 'label-item-form-input', 'for': 'quoteOption' + key, 'text': ' ' })
						)
					),
					$('<td>', {'text': (servico.descricao || this.serviceCode)}),
					$('<td>', {'text': (this.businessTime ? this.businessTime + ' dias' : '-')}),
					$('<td>', {'text': (this.price > 0 ? float2moeda(this.price) : '-')}),
					$('<td>', {'id': 'infoCotacao' + key}).append( info )
				)
			);
		});

		if ($('#tabelaCotacoes tr').length <= 1) {
			displayQuotationError('Nenhum resultado foi retornado.');
		}
	}

	initPopovers({'elements': $('a[name*=popoverAvisoCotacao]')});
}

function displayQuotationError(msg) {
	$('#tabelaCotacoes').hide();
	$('#erroConsultaCotacao').show();

	$('#erroConsultaCotacao').html(
		$('<div>', {'id': 'aviso', 'class': 'col-xs-12 alert-box alert-box-warning'}).append(
			$('<p>', {'text': msg})
		)
	);
}

function salvarCotacao() {
	var quoteSelected = $('input[name=quotes]:checked').val();
	var iVolume = $('#iVolume').val();

	if (quoteSelected) {
		var quote = arrayJsonSearch(cotacoes, 'indice', quoteSelected);
		var idServico = quote.servico.id;

		$('select[data-volume-i="' + iVolume + '"]').val(idServico).trigger('change');

		var volume = volumesLogistica[iVolume];
		if (volume) {
			volume.pesoBruto = $('#pesoCotacao').val();
			volume.largura = $('#larguraCotacao').val();
			volume.altura = $('#alturaCotacao').val();
			volume.comprimento = $('#comprimentoCotacao').val();
			volume.diametro = $('#diametroCotacao').val();
			volume.fretePrevisto = nroBra(quote.cotacao.price);
			volume.prazoEntregaPrevisto = quote.cotacao.businessTime;
			volume.tipoObjeto = $('#objectType').val();
			volume.ar = ($('#ar').is(':checked') ? 1 : 0);
			volume.mp = ($('#mp').is(':checked') ? 1 : 0);
			volume.valorDeclarado = nroBra($('#valorDeclarado').val());
			volume.cotacaoObtida = 1;

			if ($('#idCotacao').val()) {
				volume.idCotacao = $('#idCotacao').val();
			}

			volumesLogistica[iVolume] = volume;

			Toast.create({
				'type': Toast.S,
				'msg': 'Cotação gravada e serviço selecionado com sucesso!'
			});
		}
	} else {
		Toast.create({ msg: 'Nenhum serviço foi selecionado!' });
	}
}