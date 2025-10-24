var printWin;
var pontosRetirada = [];

function updatePrintWin(url, downloadFiles) {
	if (url.length > 0) {
		printWin.location = url;

		if (downloadFiles) {
			var context = $(printWin.document).find('body');
			var content = $('<div>').append(
				$('<p>', {'text': 'Preparando arquivos para o download...'}),
				$('<p>', {'text': 'Após finalizar clique em '}).append(
					$('<a>', {'href': '#', 'html': 'retornar', 'onclick': 'window.close()'})
				)
			);

			context.html('');
			context.append(content);
		}
	} else {
		printWin.close();
	}
}

function mostrarAviso(erro) {
	createDialog({
		content: '<div>' + erro.msg + '</div>',
		config: {
			title: 'Aviso'
		},
		textOk: 'Fechar',
		hideCancel: true
	});
}

function imprimirRotulo(idOrigem, idsObjetoPlp, tipoOrigem, tipoIntegracaoLogistica, rotulo) {
	var integracaoLogistica = LogisticFactory.create(tipoIntegracaoLogistica);

	if (parseInt(idsObjetoPlp) == 0) {
		xajax_imprimirRotulo({ 'etiquetas': [{ 'idObjetoPlp': idsObjetoPlp, 'idOrigem': idOrigem, 'possuiEtiqueta': 0 }]}, 'etiqueta', tipoOrigem);
	} else {
		var dadosObj = {
			'etiquetas': []
		};

		if ($.isArray(idsObjetoPlp)) {
			$.each(idsObjetoPlp, function() {
				dadosObj.etiquetas.push({
					'idObjetoPlp': this.logisticaIdObj,
					'possuiEtiqueta': 1
				});
			});
		} else {
			dadosObj.etiquetas.push({
				'idObjetoPlp': idsObjetoPlp,
				'possuiEtiqueta' : 1
			});
		}

		if (integracaoLogistica.externalLabel()) {
			if (tipoIntegracaoLogistica == 'MercadoEnvios') {
				choosePrintFormat(dadosObj);
			} else {
				printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
				xajax_imprimirRotuloExterno(dadosObj, 1);
			}
		} else {
			xajax_imprimirRotulo(dadosObj, rotulo, tipoOrigem, 1);
		}
	}
}

function montarPaginaImpressao(tipoRelatorio) {
	switch(tipoRelatorio) {
		case 'voucher':
			params = {
				'acao' : './impressao/voucherPlp.php',
				'metodo' : 'post'
			};
			break;
		case 'listaPostagem':
			params = {
				'acao' : './impressao/listaPostagemPlp.php',
				'metodo' : 'post'
			};
			break;
		case 'ars':
			params = {
				'acao' : './impressao/emissaoAR.php',
				'metodo' : 'post'
			};
			break;
		case 'etiquetas':
			params = {
				'acao' : './impressao/etiquetaCorreios.php',
				'metodo' : 'post'
			};
			break;
		case 'etiquetasNovoLayout':
			params = {
				'acao' : './impressao/etiquetaCorreiosNovoLayout.php',
				'metodo' : 'post'
			};
			break;
		case 'etiquetasModelo3':
			params = {
				'acao' : './impressao/etiquetaCorreiosModelo3.php',
				'metodo' : 'post'
			};
			break;
		case 'discriminacaoConteudo':
			params = {
				'acao' : './impressao/discriminacaoConteudo.php',
				'metodo' : 'post'
			};
			break;
		case 'declaracaoConteudo':
			params = {
				'acao' : './impressao/declaracaoConteudo.php',
				'metodo' : 'post'
			};
			break;
	}

	directPrint(params, function(res) {
		sandbox = document.createElement('div');
		$(sandbox).html($.parseHTML(res));
		$(sandbox).css('min-width', '500px');
		$(sandbox).print();
	});
}

function enviarCodigoRastreamentoPorEmail(idVenda, numeroVenda, codigoEtiqueta, idContato, numeroLoja, idObjeto, tipoIntegracaoLoja) {
	if (tipoIntegracaoLoja == 'WooCommerceWH') {
		numeroLoja = numeroLoja.split('_');
		numeroLoja = numeroLoja[0];
	}

	$.get('templates/form.envio.rastreio.email.popup.php?idVenda='+idVenda+'&numeroPedido='+numeroVenda+'&codEtiqueta='+codigoEtiqueta+'&idDoContato='+idContato+'&numeroLoja='+numeroLoja+'&idObjeto='+idObjeto, function(data) {
		$(data).dialog({
			title: 'Envio de rastreio por email',
			resizable: false,
			modal: true,
			width: 425,

			close: function() {
				$(this).dialog('destroy');
			}
		});
		$('.inf').tipsy({trigger: 'click', gravity: 'w', delayIn: 500, delayOut: 1000});
	});
}

function mostrarMaisOpcoesEnvioRastreio() {
	$('#dadosRemetenteEnvioRastreio').slideToggle(200);
}

function enviarEmailRastreioCliente() {
	displayWait('waitPopup');
	var params = {
		'nomeDestinatario': $('#rastreioNomeDestinatario').val(),
		'emailDestinatario': $('#rastreioEmailDestinatario').val(),
		'codRastreio': $('#codRastreioEmail').val(),
		'idOrigem': $('#idOrigemRastreio').val(),
		'dadosEmail': {
			'idDoc': $('#idObjetoEmail').val(),
			'assunto': $('#rastreioAssunto').val(),
			'mensagem': $('#rastreioMensagemEnvio').val(),
			'nomeRemetente': $('#rastreioNomeRemetente').val(),
			'emailCopia': $('#rastreioEmailCopia').val(),
			'emailResposta': $('#rastreioEmailResposta').val(),
			'numeroPedido': $('#numeroPedidoEmail').val(),
			'numeroLoja': $('#numeroLojaEmail').val()
		}
	};

	xajax_enviarEmailRastreioCliente(params);
}

function atualizaStatusEnvioRastreio(msg, enviou) {
	if (enviou) {
		$('#mensagemStatusRastreio').addClass('sucess');
	} else {
		$('#mensagemStatusRastreio').addClass('warn');
	}

	$('#mensagemStatusRastreio').html(msg);
	$('#mensagemStatusRastreio').show();
}

function choosePrintFormat(dadosObj) {
	var content = $('<div>', {
		class: 'text_left margin_left',
	}).append(
		$('<label>', {class: 'label-item-form wh100', for: 'printFormat', text: 'Tipo de impressão'}),
		$('<select>', {class: 'item-form w18 campo-cfg', id: 'printFormat', type: 'select'}).append(
			$('<option>', {value: 'pdf', text: 'Impressão normal (PDF)'}),
			$('<option>', {value: 'zpl2', text: 'Impressora térmica (ZPL2)'})
		)
	);

	createDialog({
		content: content,
		config: {title: 'Selecione o formato da impressão'},
		fnOk: function() {
			if ($('#impressaoWait').length > 0) {
				displayWait('impressaoWait', true, 'Imprimindo as etiquetas selecionadas, aguarde...');
			}

			formatoImpressao = $('#printFormat option:selected').val();
			printWin = window.open('./impressao/etiquetasEnvio.php', '_blank');
			xajax_imprimirRotuloExterno(dadosObj, 1, formatoImpressao);
		},
	});
}

function chooseDeclarationModel(objsLogisticos) {
	var content = $('<div>', {
		class: 'text_left margin_left',
	}).append(
		$('<label>', {class: 'label-item-form wh70', for: 'declarationModel', text: 'Escolha o modelo de declaração de conteúdo para impressão.'}),
		$('<select>', {class: 'item-form w18 campo-cfg', id: 'declarationModel', type: 'select'}).append(
			$('<option>', {value: 'declaracaoConteudo', text: 'Via única'}),
			$('<option>', {value: 'discriminacaoConteudo', text: 'Duas vias'})
		)
	);

	createDialog({
		content: content,
		config: {
			title: 'Declaração de Conteúdo'
		},
		width: 440,
		fnOk: function() {
			if ($('#impressaoWait').length > 0) {
				displayWait('impressaoWait', true, 'Imprimindo declarações de conteúdo selecionadas, aguarde...');
			}

			var declarationModel = $('#declarationModel option:selected').val();

			if (!objsLogisticos) {
				imprimirRotulosSelecionados(declarationModel);
			} else {
				xajax_imprimirRotulo({'etiquetas': objsLogisticos}, declarationModel);
			}
		}
	});
}

function abrirPopupPontosRetirada() {
	$.get('templates/form.logisticas.pontos.retirada.php', function(data) {
		var dialog = {
			content: data,
			config: {
				title: 'Pontos de retirada',
				width: getMobileWidthForDialogs(648),
				height: 450
			},
			textOk: 'Escolher ponto',
			fnOk: function() {
				displayWait('sincronizarRastroWait');

				if ($('#etiqueta_mostrar').length > 0) {
					if ($('#etiqueta_mostrar').prop('checked') == false) {
						$('#etiqueta_mostrar').click();
					}

					salvarPontoRetirada();
				} else {
					if ($('#enderecoAlternativo').prop('checked') == false) {
						$('#enderecoAlternativo').click();
					}
					salvarPontoRetirada();
				}
			},
			fnCreate: function() {
				displayWait('pickupPointsWait');
				obterPontosRetirada();
			}
		};

		createDialog(dialog);
	});
}

function obterPontosRetirada() {
	var integracaoLogistica = {
		'idIntegracao': ($('#idIntegracao').val() ? $('#idIntegracao').val() : $('#integracaoLogistica option:selected').val()),
		'tipoIntegracao': ($('#tipoIntegracaoLogistica').val() ? $('#tipoIntegracaoLogistica').val() : $('#integracaoLogistica option:selected').attr('data-tipointegracao'))
	};

	var data;
	if ($('#formRequisitosCotacao').length > 0) {
		data = xajax.getFormValues('formRequisitosCotacao', true);
	} else {
		data = {
			'idObj': $('#idObjetoPlp').val()
		};
	}

	xajax_consultarPontosRetirada(integracaoLogistica, data, function(pickupPoints) {
		listarPontosRetirada(pickupPoints);
	});
}

function listarPontosRetirada(pickupPoints) {
	closeWait('pickupPointsWait');

	$('#erroConsultaPontosRetirada, #resultadoPontosRetirada').html('');
	$('#tabelaPontosRetirada').show();

	$.each(pickupPoints, function(key) {
		pontosRetirada.push(this);

		var endereco = this.street + ', ' + this.streetNumber + (this.complement.trim() != '' ? ' (' + this.complement + '), ' : ', ') + (this.neighborhood.trim() != '' ?this.neighborhood.trim() + ', ' : '') + this.city;
		var info = $('<a>', { 'class': 'tableIcon', 'name': '[popoverInfoPontoRetirada]', 'data-container': 'body', 'data-placement': 'left', 'data-trigger': 'click', 'data-toggle': 'popover', 'data-content': prepararHorariosFuncionamento(this.workingHours), 'data-html': true }).append(
			$('<i>', { 'class': 'icon-info-sign' })
		);

		$('#resultadoPontosRetirada').append(
			$('<tr>').append(
				$('<td>').append(
					$('<div>', { 'class': 'input-radio' }).append(
						$('<input>', { 'type': 'radio', 'name': 'pontosRetirada', 'id': 'pontosRetirada' + key, 'value': key }),
						$('<label>', { 'for': 'pontosRetirada' + key }),
						$('<label>', { 'class': 'label-item-form-input', 'for': 'pontosRetirada' + key, 'text': ' ' })
					)
				),
				$('<td>', { 'text': this.name }),
				$('<td>', { 'text': endereco }),
				$('<td>', { 'text': formataCep(this.zipCode) }),
				$('<td>', { 'text': this.distance + 'm' }),
				$('<td>', { 'id': 'infoPontoRetirada' + key }).append(info)
			)
		);
	});

	if ($('#tabelaPontosRetirada tr').length <= 1) {
		$('#tabelaPontosRetirada').hide();
		$('#erroConsultaPontosRetirada').show();

		$('#erroConsultaPontosRetirada').html(
			$('<div>', { 'id': 'aviso', 'class': 'col-xs-12 alert-box alert-box-warning' }).append(
				$('<p>', { 'html': 'Nenhum ponto de retirada foi retornado.<br>Verifique se o CEP, cidade e endereço do destinatário são válidos.' })
			)
		);
	}

	initPopovers({ 'elements': $('a[name*=popoverInfoPontoRetirada]') });
}

function prepararHorariosFuncionamento(horariosFuncionamento) {
	var conteudo = $('<tbody>');
	var diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];

	$.each(horariosFuncionamento, function(key) {
		conteudo.append(
			$('<tr>').append(
				$('<th>', { 'html': diasSemana[key] }),
				$('<td>', { 'html': (this.morning ? this.morning[0] : '') + ' - ' + (this.morning ? this.morning[1] : '') }),
				$('<td>', { 'html': (this.afternon ? this.afternon[0] : '') + ' - ' + (this.afternon ? this.afternon[1] : '') })
			)
		);
	});

	var html = $('<div>').append(
		$('<h4>', { 'class': 'text-center', 'text': 'Horários de funcionamento', 'style': 'color: white;' }),
		$('<table>', { 'class': 'table table-responsive table-horario-pontos-retirada' }).append(
			$('<thead>').append(
				$('<tr>').append(
					$('<th>', { 'text': 'Dia da semana' }),
					$('<th>', { 'text': 'Manhã' }),
					$('<th>', { 'text': 'Tarde' })
				)
			),
			conteudo
		)
	);

	return $('<div>').append(html).html();
}

function salvarPontoRetirada() {
	var idPontoEscolhido = $('input[name=pontosRetirada]:checked').val();

	if (idPontoEscolhido) {
		var dadosPontoRetirada = arrayJsonSearch(pontosRetirada, 'id', idPontoEscolhido);
		$('#idCotacao').val(idPontoEscolhido);

		if ($('#etiqueta_mostrar').length > 0) {
			if ($('#etiqueta_mostrar').prop('checked') == false) {
				$('#etiqueta_mostrar').click();
			}

			if (volumesLogistica.length > 0) {
				$.each(volumesLogistica, function(i, volume) {
					volume.idCotacao = idPontoEscolhido;
					volumesLogistica[i] = volume;
				});
			}
		} else if ($('#enderecoAlternativo').prop('checked') == false) {
			$('#enderecoAlternativo').click();
		}

		$('#etiqueta_nome').val(dadosPontoRetirada.name);
		$('#etiqueta_endereco').val(dadosPontoRetirada.street);
		$('#etiqueta_numero').val(dadosPontoRetirada.streetNumber);
		$('#etiqueta_complemento').val(dadosPontoRetirada.complement);
		$('#etiqueta_municipio').val(dadosPontoRetirada.city);
		$('#etiqueta_cep, #cepDestino').val(formataCep(dadosPontoRetirada.zipCode));
		$('#etiqueta_bairro').val(dadosPontoRetirada.neighborhood);

		$.ajax({
			'type': 'POST',
			'url': 'services/cep.lookup.php',
			'data': { 'cep': dadosPontoRetirada.zipCode },
			'dataType': 'json'
		}).done(function(data) {
			closeWait('sincronizarRastroWait');

			if (data.status.codigo == 200) {
				$('#etiqueta_id_municipio').val(data.codCidade);
				$('#etiqueta_uf').val(data.uf);
				$('#etiqueta_municipio').val(data.cidade);

				Toast.create({
					'type': Toast.S,
					'msg': 'Ponto de retirada selecionado com sucesso!<br>O endereço de entrega foi atualizado.'
				});
			} else if (data.status.codigo == 0) {
				exibirMsgEndereco('CEP do estabelecimento não foi encontrado pela consulta dos Correios.<br><br>Por favor, preencha os campos de endereço marcados.');
			} else {
				exibirMsgEndereco('A consulta de CEP dos Correios não está respondendo.<br><br>Por favor, tente obter os pontos de retirada novamente ou preencha manualmente os campos de endereço marcados.');
			}
		}).fail(function() {
			closeWait('sincronizarRastroWait');
			exibirMsgEndereco();
		});
	} else {
		closeWait('sincronizarRastroWait');
		Toast.create({ 'msg': 'Nenhum ponto de retirada foi selecionado!' });
	}
}

function exibirMsgEndereco(msg) {
	var content = $('<div>', { 'class': 'container-fluid' }).append(
		$('<div>', { 'class': 'col-xs-12 alert-box alert-box-info margin-top0' }).append(
			$('<p>', { 'html': 'O ponto de retirada foi escolhido com sucesso, porém não foi possível setar automaticamente o endereço de entrega.<br><strong>Motivo:</strong> ' + (msg || '<br><br>Por favor, preencha os campos de endereço marcados.') })
		)
	);

	var dialog = {
		'content': content,
		'hideCancel': true,
		'width': getMobileWidthForDialogs(440),
		'config': {
			'title': 'Aviso'
		}
	};

	createDialog(dialog);
	$('#etiqueta_cep').addClass('ac_error');
	$('#etiqueta_uf').addClass('ac_error');
	$('#etiqueta_municipio').addClass('ac_error');
}