var idCampoValor = "";
var idCampoIPI = "";
var idCampoST = "";
var idCampoFrete = "";
var idCampoData = "";
var ehVendedor = false;
var selectFormasPagamento = "";
var formasPagamento = [];
var dataVendaFocus = "";
var exibirFormaPagamento;
var historicoParcelas = [];

var FramePayment = function(options) {
	this.afterChangeMethods = options['afterChangeMethods'];
	initFramePagamento(options['showPaymentMethods'], options['afterLoad'], options['afterLoadParams'], options['type']);

	Object.defineProperty(this, '_permissions', {
		'value': Object.freeze($.extend({
			'editPaymentMethod': true
		}, options['permissions']))
	});
};
FramePayment.prototype = Object.create({
	'getPermission': function(permission) {
		return this._permissions[permission];
	},

	'getInstallmentId': function(element) {
		return parseInt($(element).attr('id').replace(/\D/g, ''), 10);
	},

	'getLastInstallmentId': function(elements) {
		return parseInt(elements.eq(-1).attr('id').replace(/\D/g, ''), 10);
	},

	'getFullPrice': function() {
		return nroUsaFloat($('#' + idCampoValor).val());
	},

	'getInstallmentPricesElements': function() {
		return $('input[name="parcelas[valor][]"]');
	},

	'getInstallmentPrice': function(element) {
		return nroUsaFloat($(element).val());
	},

	'getProportionalInstallmentPrice': function(elementCurrentPrice, proportionalFullPrice, difference) {
		return (proportionalFullPrice != 0 ? toFixedFix(this.getInstallmentPrice(elementCurrentPrice) / proportionalFullPrice * difference, 2, true) : 0);
	},

	'getFullInstallmentPrices': function(installmentEditedId) {
		var _this = this;
		var prices = [];

		prices['full'] = _this.getFullPrice();
		prices['beforeInstallments'] = prices['currentInstallment'] = prices['afterInstallments'] = 0;

		_this.getInstallmentPricesElements().each(function() {
			var id = _this.getInstallmentId(this);
			var price = _this.getInstallmentPrice(this);

			if (id < installmentEditedId) {
				prices['beforeInstallments'] += price;
			} else if (id > installmentEditedId) {
				prices['afterInstallments'] += price;
			} else {
				prices['currentInstallment'] = price;
			}
		});

		return prices;
	},

	'getInstallments': function() {
		var _this = this;
		var installments = [];

		$('#pag_grparcelas tr[id^=tr_]').each(function() {
			installments.push(_this.getInstallment(_this.getInstallmentId(this)));
		});

		return installments;
	},

	'getInstallment': function(installmentNum) {
		var fields = ['nroDias', 'dataVencimento', 'valor', 'idFormaPagamento', 'obs'];
		var tr = $('#pag_grparcelas tr[id^=tr_]')[installmentNum];
		var installment = { taxa: 0 }, paymentMethod = {};
		fields.forEach(function(field) {
			installment[field] = $('[name="parcelas[' + field + '][]"]', tr).val();
		});
		installment['dataVencimento'] = formatDate(installment['dataVencimento']);
		installment['valor'] = parseFloat(nroUsa(installment['valor']));

		if (paymentMethod = formasPagamento[installment['idFormaPagamento']]) {
			installment['formaPagamento'] = paymentMethod;
			installment['taxa'] = paymentMethod['aliquotaTaxa'] / 100 * installment['valor'] + paymentMethod['valorTaxa'];
		}

		return installment;
	},

	'getTotalTaxes': function() {
		return this.getInstallments().reduce(function(totalTaxes, installment) {
			return totalTaxes + installment['taxa'];
		}, 0);
	},

	'fireEvent': function(eventName) {
		if (this[eventName]) {
			this[eventName]();
		}
	}
});


function initFramePagamento(exibirForma, callback, paramsCallback, tipoForma) {
	exibirFormaPagamento = (exibirForma || $.inArray($("#modulo").val(), ["ControleMesas", "PDV"]) > -1);

	if (exibirFormaPagamento == true) {
		xajax_getFormasPagamento(function(formas) {
			if (jQuery.isEmptyObject(formas)) {
				$('#aviso_sem_forma').show();
				$('#frame_pagamento_inner').hide();
			} else {
				montarSelectFormasPagamento(formas, tipoForma);
			}

			if (callback) {
				callback(paramsCallback);
			}
		});
		vincularEventosFormasPagamento(tipoForma);
	} else if(ehVendedor) {
		xajax_getFormasPagamento(false, true, function(forma) {
			if(! jQuery.isEmptyObject(forma)) {
				formasPagamento[forma[0]['id']] = forma[0];
			}
			if (callback) {
				callback(paramsCallback);
			}
		});
	} else {
		if (callback) {
			callback(paramsCallback);
		}
	}

	$('#pag_botaoCalcular, #btn_gerar_parcelas').on('click', function() {
		$('#idFormaPagamento').val(0);
		gerarParcelasManualmente();
		return false;
	});

	$('#pag_condicao').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#pag_botaoCalcular, #btn_gerar_parcelas').trigger('click');
		}
	});
}

function vincularEventosFormasPagamento(tipoForma) {
	var termAtual = '';
	var dados = [];
	$("#pag_condicao").autocomplete({
		source: function(request, response) {
			if (tipoForma > 0) {
				request.tipos = [tipoForma, 3];
			}
			if (termAtual == request.term) {
				response(dados);
			} else {
				termAtual = request.term
				$.getJSON('services/lookups.php?lookup=formasPagamento', request, function(data, status, xhr) {
					response(dados = data);
				});
			}
		},
		select: function(event, ui) {
			$.extend(formasPagamento[ui['item']['id']], {
				'id': ui['item']['id'],
				'modDestino': ui['item']['modDestino'],
				'condicao': ui['item']['condicao'],
				'idDestino': ui['item']['idDestino']
			});
			$('#idFormaPagamento').val(ui['item']['id']);
			$('#pag_condicao').val(ui['item']['condicao']);
			gerarParcelasManualmente();
			return false;
		},
		fnRenderItemUi: UiAutocompleteItem.condition,
		delay: 250,
		minLength: 0,
		selectOnly: true
	}).click(function() {
		if (! $(this).attr('readonly')) {
			$(this).data("uiAutocomplete").search('%%');
		}
	});
}

function montarSelectFormasPagamento(formas, tipoForma){
	selectFormasPagamento = "";
	var selected;
	$.each(formas, function (key, forma){
		formasPagamento[forma['id']] = forma;
		selected = false;
		selectFormasPagamento += '<option ';
		if ((tipoForma && forma['tipo'] != tipoForma && forma['tipo'] != 3) || forma['situacao'] == 0) {
			selectFormasPagamento += 'style="display: none" disabled';
		} else {
			selected = (forma['padrao'] == 1);
		}
		selectFormasPagamento += ' value="' + forma['id'] + '" codigoFiscal="' + forma['codigoFiscal'] + '" idDestino="' +
		forma['idDestino'] + '" tband="' + forma['tband'] + '" ' + 'cnpjCredenciadora="' + forma['cnpjCredenciadora'] +
		'" tpIntegra="' + forma['tpIntegra'] + '" ' + (selected ? 'selected="selected" ' : '') + '>' + forma['descricao'] + '</option>';
	});
}

function setIdCampoValor(aIdCampoValor, campoIPI, campoST, frete) {
	idCampoValor = aIdCampoValor;
	if ((campoIPI != null) && (campoIPI != "")) {
		idCampoIPI = campoIPI;
	}
	if ((campoST != null) && (campoST != "")) {
		idCampoST = campoST;
	}
	if ((frete != null) && (frete != "")) {
		idCampoFrete = frete;
	}
}

function setIdCampoData(aIdCampoData) {
	idCampoData = aIdCampoData;
}

function setLabelContaCreditoDebito(label, labelConta) {
	$("#pag_conta > option").each(function() {
		$(this).html($(this).html().replace("TIPO_CONTA", labelConta));
		$(this).html($(this).html().replace("TIPO", label));
	})
}

/*Rotinas para geração de parcelas*/
function gerarParcelas() {
	apagarParcelas();
	$("#parcelaNumber").val(0);
	if (parseFloat(nroUsa($("#" + idCampoValor).val())) > 0) {
		var valorExtra = 0;
		if (idCampoIPI != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoIPI).val()));
		}
		if (idCampoST != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoST).val()));
		}
		if (idCampoFrete != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoFrete).val()));
		}
		gerarParcela(parseFloat(nroUsa($("#" + idCampoValor).val())), $("#pag_condicao").val(), valorExtra);

		if (framePagamento.getInstallmentPricesElements().length > 0) {
			$("#pag_parcelas, #pag_grparcelas").show();
		} else {
			$("#pag_parcelas, #pag_grparcelas").hide();
		}
	}
}

function gerarParcela(valor, condicao, valorIPI) {
	var parcelas;
	parcelas = criaArrayParcelas(condicao);
	createParcelas(parcelas, valor, valorIPI, verificaCondicao(condicao));
}

function criaArrayParcelas(parcelas) {
	var parc = new Array();
	var condicao, nroVezes, pos;
	condicao = verificaCondicao(parcelas);
	if (condicao == 'condicaoXvezes') {
		parcelas = retiraEspacos(parcelas);
		pos = parcelas.toLowerCase().indexOf("x");
		nroVezes = parcelas.substring(0, pos);
		var dias = 30;
		for (var i = 0; i < nroVezes; i++) {
			parc[i] = dias;
			dias = dias + 30;
		}
	} else if (condicao == 'condicaoSeparadaVirgula') {
		parcelas = retiraEspacos(parcelas);
		parc = parcelas.split(',');
	} else if (condicao == 'condicaoSeparadaEspacos') {
		parc = parcelas.split(' ');
	} else if (condicao == 'condicaoSeparadaMaisX') {
		parc[0] = retiraEspacos(parcelas.substr(0, parcelas.toLowerCase().indexOf("+")));
		parcelas = parcelas.substr(parcelas.toLowerCase().indexOf("+"));
		parcelas = retiraEspacos(parcelas);
		pos = parcelas.toLowerCase().indexOf("x");
		nroVezes = parcelas.substring(0, pos);
		var dias = 30;
		for (var i = 0; i < nroVezes; i++) {
			parc[i+1] = dias;
			dias = dias + 30;
		}
	} else {
		parc[0] = parcelas;
	}
	return parc;
}

function gerarParcelasManualmente() {
	if ($("#pag_condicao").val() == "") {
		$("#pag_condicao").val("0");
	}
	if (parseFloat(nroUsa($("#" + idCampoValor).val())) == 0) {
		createDialog({
			'htmlTitle': 'Geração de parcelas',
			'content': $('<div>', {'class': 'container-fluid'}).append(
				$('<div>', {'class': 'alert-box alert-box-warning alert-box-transparent margin-top0'}).append(
					$('<p>', {'text': 'Não foi possível gerar as parcelas, pois o valor faturado está zerado.'})
				)
			),
			'hideCancel': true,
			'width': 440
		});
	} else {
		calcularParcelas();
		framePagamento.fireEvent('afterChangeMethods');
	}
}

function createParcelas(parcelas, valorTotal, valorIPI, condicao) {
	var portadorPadrao = null;
	var formaPagamento = 1;
	var dataVenda = new Date(formatdate($("#" + idCampoData).val()));
	var resto, valor, parcelaNumber, novoTotal = 0;
	parcelas = validarParcelas(parcelas);
	var nroParcelas = parcelas.length;
	valor = (valorTotal - valorIPI) / nroParcelas;
	valor = valor.toFixed(2);
	resto = (valorTotal - (nroParcelas * valor));
	for (var i = 0; i < nroParcelas; i ++) {
		if (i == 0) {
			valorParcela = nroBra(parseFloat(valor) + parseFloat(valorIPI));
		} else {
			valorParcela = nroBra(valor);
		}
		novoTotal += parseFloat(nroUsa(valorParcela));
		if(typeof $("#idIntegracaoPagamento") != 'undefined' && $("#id").val() == 0 && historicoParcelas.length == 0){
			portadorPadrao  = $("#idIntegracaoPagamento").val();
		}
		if(portadorPadrao == null){
			if(typeof historicoParcelas[i] != 'undefined'){
				portadorPadrao = historicoParcelas[i].conta;
				formaPagamento = historicoParcelas[i].forma;
			}else{
				if(typeof $("#idIntegracaoPagamento") != 'undefined' && $("#id").val() == 0){
					portadorPadrao  = $("#idIntegracaoPagamento").val();
				}
				portadorPadrao = null;
			}
		}
		if ($('#idFormaPagamento').val() > 0) {
			var id = $('#idFormaPagamento').val();
			addParcela($("#parcelaNumber").val(), parcelas[i], valorParcela, formasPagamento[id].idDestino, "", condicao, formasPagamento[id].codigoFiscal, "", formasPagamento[id].id, formasPagamento[id]['tband'], formasPagamento[id]['cnpjCredenciadora'], formasPagamento[id]['tpIntegra'], formasPagamento[id].modDestino);
		} else {
			addParcela($("#parcelaNumber").val(), parcelas[i], valorParcela, portadorPadrao, "", condicao, formaPagamento);
		}
		parcelaNumber = $("#parcelaNumber").val();
		parcelaNumber++;
		portadorPadrao = null;
		$("#parcelaNumber").val(parcelaNumber);
	}
	if (novoTotal != parseFloat(valorTotal)) {
		if ($("#parcelaNumber").val() > 0) {
			var dif = nroBra(parseFloat(nroUsa($("#valor0").val())) + parseFloat(valorTotal) - novoTotal);
			$("#valor0").val(dif);
		}
	}
}

function calcularDiasReais(aNumeroParcelaAnterior, condicao) {
	var proximoDia = 0;
	if(condicao == "condicaoXvezes") {
		if(aNumeroParcelaAnterior != -1) {
			var diaAnterior = $("#dataVencimento"+aNumeroParcelaAnterior).val();
			var mes = diaAnterior.split("/")[1];
			if((mes == "01")||(mes == "03")||(mes == "05")||(mes == "07")||(mes == "08")||(mes == "10")||(mes == "12")) {
				proximoDia = 31 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
			} else if(mes == "02") {
				if(diaAnterior.split("/")[2]%4 == 0) {
					proximoDia = 29 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
				} else {
					proximoDia = 28 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
				}
			} else {
				proximoDia = 30 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
			}
		} else {
			var diaAtual = new Date();
			var mes = diaAtual.getMonth()+1;
			if((mes == 1)||(mes == 3)||(mes == 5)||(mes == 7)||(mes == 8)||(mes == 10)||(mes == 12)) {
				proximoDia = 31;
			} else if(mes == 2) {
				if(diaAtual.getFullYear()%4 == 0) {
					proximoDia = 29;
				} else {
					proximoDia = 28;
				}
			} else {
				proximoDia = 30;
			}
		}
	} else if(condicao == "condicaoSeparadaMaisX") {
		var proximoDia = 0;
		if(aNumeroParcelaAnterior == 0) {
			var diaAtual = new Date();
			var mes = diaAtual.getMonth()+1;
			if((mes == 1)||(mes == 3)||(mes == 5)||(mes == 7)||(mes == 8)||(mes == 10)||(mes == 12)) {
				proximoDia = 31;
			} else if(mes == 2) {
				if(diaAtual.getFullYear()%4 == 0) {
					proximoDia = 29;
				} else {
					proximoDia = 28;
				}
			} else {
				proximoDia = 30;
			}
		} else {
			var diaAnterior = $("#dataVencimento"+aNumeroParcelaAnterior).val();
			var mes = diaAnterior.split("/")[1];
			if((mes == "01")||(mes == "03")||(mes == "05")||(mes == "07")||(mes == "08")||(mes == "10")||(mes == "12")) {
				proximoDia = 31 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
			} else if(mes == "02") {
				if(diaAnterior.split("/")[2]%4 == 0) {
					proximoDia = 29 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
				} else {
					proximoDia = 28 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
				}
			} else {
				proximoDia = 30 + parseInt($("#nroDias"+aNumeroParcelaAnterior).val());
			}
		}
	}
	return proximoDia;
}

function addParcela(aNumeroParcela, aNroDias, aValor, aContaContabil, aObs, condicao, forma, nroBanco, idFormaPagamento, tband, cnpj, tpIntegra, destino) {
	if (! aObs) {
		aObs = "";
	}
	if (! nroBanco || nroBanco == undefined) {
		nroBanco = "";
	}

	var nroDias = 0;
	var dataVencimento = '';
	if(typeof condicao != "undefined") {
		if((condicao != "condicaoSeparadaEspacos")&&(condicao != "condicaoSeparadaVirgula")) {
			if(condicao == "condicaoSeparadaMaisX") {
				if(aNumeroParcela == 0) {
					nroDias = aNroDias;
					dataVencimento = somaDias(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
				} else {
					nroDias = calcularDiasReais(aNumeroParcela-1, condicao);
					dataVencimento = somaDiasParcelas(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
				}
			} else {
				if((aNroDias%30) != 0) {
					nroDias = aNroDias;
					dataVencimento = somaDias(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
				} else {
					nroDias = calcularDiasReais(aNumeroParcela-1, condicao);
					dataVencimento = somaDiasParcelas(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
				}
			}
		} else {
			nroDias = aNroDias;
			dataVencimento = somaDias(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
		}
	} else {
		nroDias = aNroDias;
		dataVencimento = somaDias(aNroDias, new Date(formatdate($("#" + idCampoData).val())));
	}

	var linha = montarHtmlParcela(aNumeroParcela, nroDias, dataVencimento, aValor, aObs, nroBanco, tband, cnpj, tpIntegra, destino);

	if (aNumeroParcela > 1) {
		beforeElement = "tr_" + (aNumeroParcela - 1);
		$(linha).insertAfter($("#" + beforeElement));
	} else {
		beforeElement = "pag_trh";
		$(linha).insertBefore($("#" + beforeElement));
	}

	if (idFormaPagamento != undefined && idFormaPagamento != 0) {
		$("#idFormaPagamento" + aNumeroParcela).val(idFormaPagamento);
	}
	$("#selectForma" + aNumeroParcela).val(forma);
	clonarContaContabil(aNumeroParcela, aNroDias, aContaContabil);
	$("#nroDias" + aNumeroParcela).attr("name", "parcelas[nroDias][]");
	$("#dataVencimento" + aNumeroParcela).attr("name", "parcelas[dataVencimento][]");
	$("#valor" + aNumeroParcela).attr("name", "parcelas[valor][]");
	$("#pag_conta" + aNumeroParcela).attr("name", "parcelas[conta][]");
	$("#obs" + aNumeroParcela).attr("name", "parcelas[obs][]");

	$("#nroDias" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#dataVencimento" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#valor" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#valor" + aNumeroParcela).bind("blur", function(){recalcularParcelasAbaixo(aNumeroParcela);});
	$("#nroDias" + aNumeroParcela).bind("change", function() {addDiasNew($(this).val(), "dataVencimento" + aNumeroParcela, idCampoData);});
	$("#exclui" + aNumeroParcela).bind("click", function() {removeParcela(aNumeroParcela);});
	$("#dataVencimento" + aNumeroParcela).bind("change", function() {diferencaDatas("d", $("#" + idCampoData).val(), $(this).val(), "nroDias" + aNumeroParcela);});
	$("#pag_conta" + aNumeroParcela).on("change", function() { alterarForma(this); });
	$("#idFormaPagamento" + aNumeroParcela).on("change", function() { alterarForma(this) });
	$("#pag_conta" + aNumeroParcela).bind("blur", function() {terribleHack(this); });
	$("#obs" + aNumeroParcela).bind("blur", function() {terribleHack(this);});

	var controleMesas = $("#modulo").val() == "ControleMesas" || $("#modulo").val() == "PDV";
	$(".cupom").hide();
	$(".controleMesas").hide();
	if (exibirFormaPagamento == true){
		$(".cupom").show();
	} else if (controleMesas) {
		$(".controleMesas").show();
	}
	alterarFormaPagamento(aNumeroParcela, (idFormaPagamento == undefined || idFormaPagamento == 0) && (exibirFormaPagamento || controleMesas));
	initFormatterField(2, $("#valor" + aNumeroParcela));
}

function alterarFormaPagamento(nro, atualizarAtributos) {
	atualizarAtributos = (atualizarAtributos == undefined ? true : atualizarAtributos)
	var elem = $('#idFormaPagamento' + nro + ' option:selected');
	var formaPagamento = formasPagamento[elem.val()];
	$("#pag_conta" + nro).show();
	if(formaPagamento) {
		if (formaPagamento['modDestino'] != 0) {
			$("#pag_conta" + nro).hide();
		}
		if(atualizarAtributos) {
			$('#pag_conta' + nro).val(elem.attr('idDestino'));
			$('#selectForma' + nro).val(elem.attr('codigoFiscal'));
			$('#tband' + nro).val(elem.attr('tband'));
			$('#cnpjCredenciadora' + nro).val(elem.attr('cnpjCredenciadora'));
			$('#tpIntegra' + nro).val(elem.attr('tpIntegra'));
			$('#destino' + nro).val(formaPagamento['modDestino']);
		}
	}
	if(! hideColunaEnviarPara()) {
		$('#pag_grparcelas td[id^=conta], #str_frame_pagamento_enviar_para').show();
		if($("#pag_grparcelas select[id^='pag_conta']").filter(function() { return $(this).css("display") == "block" }).length == 0) {
			$('#pag_grparcelas td[id^=conta], #str_frame_pagamento_enviar_para').hide();
		}
	}
}

function alterarForma(element) {
	if ($(element).attr('id').indexOf('idFormaPagamento') != -1) {
		var data = {'id': 'idFormaPagamento', 'descricao': 'todas as formas de pagamento'};
	} else if ($(element).attr('id').indexOf('pag_conta') != -1) {
		var data = {'id': 'pag_conta', 'descricao': 'todos os destinos'};
	}

	if ($('#pag_grparcelas [id^=' + data.id + ']:visible').length > 1) {
		createDialog({
			'content': $('<div>', {'class': 'container-fluid'}).append(
				$('<div>', {'class': 'alert-box alert-box-question margin-top0'}).append(
					$('<p>', {'html': 'Deseja alterar ' + data.descricao + ' das parcelas para <b>' + $(element).find(':selected').text() + '</b>?'})
				)
			),
			'htmlTitle': 'Alterar parcelas',
			'width': 440,
			'textOk': 'Sim',
			'textCancelar': 'Não',
			'fnOk': function() {
				$('#pag_grparcelas [id^=' + data.id + ']:visible').val($(element).find(':selected').val());

				if (data.id == 'idFormaPagamento') {
					$.each($('#pag_grparcelas [id^=' + data.id + ']:visible'), function(i, el) {
						alterarFormaPagamento($(el).attr('id').replace('idFormaPagamento', ''));
					});
				}
				framePagamento.fireEvent('afterChangeMethods');
			},
			'fnCancelar': function() {
				framePagamento.fireEvent('afterChangeMethods');
			}
		});
	} else {
		framePagamento.fireEvent('afterChangeMethods');
	}
}

function verificaCondicao(condicao) {
	var pos, result;
	pos = condicao.indexOf(" ");
	if (pos > -1) {
		result = 'condicaoSeparadaEspacos';
	}

	pos = condicao.toLowerCase().indexOf("x");
	if (pos > -1) {
		result = 'condicaoXvezes';
	}

	pos = condicao.indexOf(",");
	if (pos >- 1) {
		result = 'condicaoSeparadaVirgula';
	}

	pos = condicao.indexOf("+");
	if (pos >- 1) {
		result = 'condicaoSeparadaMaisX';
	}
	return result;
}

function retiraEspacos(parcela) {
	while (parcela.indexOf(" ") > -1) {
		parcela = parcela.replace(' ', '');
	}
	return parcela;
}

/*Rotinas para exclusão de parcelas*/
function apagarParcelas() {
	historicoParcelas = []
	var i = 0;
	$("#pag_grparcelas > tbody > tr").each(function() {
		if (($(this).attr("id") != "pag_trh") && ($(this).attr("id") != "pag_parcelas_header")) {
			historicoParcelas.push({'conta':$("#pag_conta"+i).val(), 'forma':$("#idFormaPagamento"+i).val()});
			$(this).remove();
			i++;
		}
	})
}

function removeParcela(np) {
	$("#tr_" + np).remove();

	if (np != "0") {
		var valorExtra = 0;
		if (idCampoIPI != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoIPI).val()));
		}
		if (idCampoST != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoST).val()));
		}
		if (idCampoFrete != "") {
			valorExtra += parseFloat(nroUsa($("#" + idCampoFrete).val()));
		}
		if (($("#parcelaNumber").val() > 0) && (valorExtra > 0)){
			var valorPrimParc = nroBra(parseFloat(nroUsa($("#valor0").val())) - valorExtra);
			$("#valor0").val(valorPrimParc);
		}
	}
	atualizarParcelas();

	$('#pag_aNovaLinhaParcela, #pag_parcelas')[framePagamento.getInstallmentPricesElements().length > 0 ? 'show' : 'hide']();
	framePagamento.fireEvent('afterChangeMethods');
}

/*Rotinas para clonar contas contábeis*/
function clonarContaContabil(aNumeroParcela, aNroDias, aContaContabil) {
	if (aContaContabil) {
		$("#pag_conta").val(aContaContabil);
	} else {
		$("#pag_conta").val(-1);
	}
	$("#pag_conta").clone(true).appendTo($("#conta" + aNumeroParcela)).attr("id", "pag_conta" + aNumeroParcela).attr("name", "pag_conta" + aNumeroParcela).val($("#pag_conta").val()).css("border", "none");

}

function adicionarLinhaParcela() {
	var nroParcela = $("#parcelaNumber").val();
	var aNumeroParcela = nroParcela;

	var linha = montarHtmlParcela(aNumeroParcela, '', '', '0,00', '', '', '', '', '', '');

	$(linha).insertBefore($("#pag_trh"));

	clonarContaContabil(aNumeroParcela, 0, null);

	$("#nroDias" + aNumeroParcela).attr("name", "parcelas[nroDias][]");
	$("#dataVencimento" + aNumeroParcela).attr("name", "parcelas[dataVencimento][]");
	$("#valor" + aNumeroParcela).attr("name", "parcelas[valor][]");
	$("#pag_conta" + aNumeroParcela).attr("name", "parcelas[conta][]");
	$("#obs" + aNumeroParcela).attr("name", "parcelas[obs][]");

	$("#nroDias" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#dataVencimento" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#valor" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#valor" + aNumeroParcela).bind("blur", function(){recalcularParcelasAbaixo(aNumeroParcela);});
	$("#obs" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#conta" + aNumeroParcela).bind("blur", function() {terribleHack(this);});
	$("#nroDias" + aNumeroParcela).bind("change", function() {addDiasNew($(this).val(), "dataVencimento" + aNumeroParcela, idCampoData);});
	$("#pag_conta" + aNumeroParcela).on("change", function() { alterarForma(this) });
	$("#idFormaPagamento" + aNumeroParcela).on("change", function() { alterarForma(this) });
	$("#exclui" + aNumeroParcela).bind("click", function() {removeParcela(aNumeroParcela);});
	$("#dataVencimento" + aNumeroParcela).bind("change", function() {diferencaDatas("d", $("#" + idCampoData).val(), $(this).val(), "nroDias" + aNumeroParcela);});

	initFormatterField(2, $("#valor" + aNumeroParcela));

	nroParcela++;
	$("#parcelaNumber").val(nroParcela);

	if (exibirFormaPagamento == true){
		alterarFormaPagamento(aNumeroParcela);
		$(".cupom").show();
	} else if ($("#modulo").val() == "ControleMesas" || $("#modulo").val() == "PDV") {
		$(".controleMesas").show();
	}
}

function montarHtmlParcela(aNumeroParcela, nroDias, dataVencimento, aValor, aObs, nroBanco, tband, cnpj, tpIntegra, destino) {
	var linha = "<tr id=tr_" + aNumeroParcela + ">" +
		"<td><input type='text' class='input_text editgrid' id=nroDias" + aNumeroParcela + " value=" + nroDias + "></td>" +
		"<td><input type='text' class='input_text editgrid' id=dataVencimento" + aNumeroParcela + " onchange='formatDateField(this);' value=" + dataVencimento + "></td>" +
		"<td><input type='text' class='input_text editgrid' id=valor" + aNumeroParcela + " value=" + aValor + "></td>" +
		'<td style="display:none;"><input type="hidden" id="selectForma' + aNumeroParcela + '" value="1" name="parcelas[forma][]" /></td>';
	if ($("#modulo").val() == "ControleMesas" || $("#modulo").val() == "PDV" || exibirFormaPagamento == true) {
		linha += '<td class="tdFormasPagamento cupom controleMesas">' +
					'<select id="idFormaPagamento' + aNumeroParcela + '" name="parcelas[idFormaPagamento][]" class="wh100 input_text browser-default" style="border: none;"' + (framePagamento.getPermission('editPaymentMethod') ? '' : ' readonly="readonly"') + ' onchange="alterarFormaPagamento(' + aNumeroParcela + ');">' + selectFormasPagamento + '</select>' +
				'</td>';
	} else {
		var idFormaPagamentoPadrao = 0;
		if(! jQuery.isEmptyObject(formasPagamento)) {
			idFormaPagamentoPadrao = Object.keys(formasPagamento)[0];
		}
		linha += '<td class="tdFormasPagamento cupom"><input type="hidden" value=' + idFormaPagamentoPadrao + ' id="idFormaPagamento' + aNumeroParcela + '" name="parcelas[idFormaPagamento][]" /></td>';
	}
	if(hideColunaEnviarPara()){
		linha += "<td style='display:none' id=conta" + aNumeroParcela + "></td>";
	} else {
		linha += "<td id=conta" + aNumeroParcela + "></td>";
	}

	if($("#modulo").val() != "ControleMesas"){
		linha += "<td><input type='text' class='input_text editgrid' id=obs" + aNumeroParcela + " value='" + aObs + "'></td>";
	}

	linha += "<input type='hidden' id=nroBanco" + aNumeroParcela + " value='" + nroBanco + "' name='parcelas[nroBanco][]'>";
	linha += '<input type="hidden" id="tband' + aNumeroParcela + '" value="' + tband + '" name="parcelas[tband][]">';
	linha += '<input type="hidden" id="cnpjCredenciadora' + aNumeroParcela + '" value="' + cnpj + '" name="parcelas[cnpj][]">';
	linha += '<input type="hidden" id="tpIntegra' + aNumeroParcela + '" value="' + tpIntegra + '" name="parcelas[tpIntegra][]">';
	linha += '<input type="hidden" id="destino' + aNumeroParcela + '" value="' + destino + '" name="parcelas[destino][]">';

	if ($("#modulo").val() != "PDV") {
		linha += '<td class="text_center"><a title="Ver detalhes" class="tableIcon" onclick="visualizarParcela(' + aNumeroParcela + ')"><i class="icon-info-sign"></i></a></td>';
	}

	linha += '<td id="exclui' + aNumeroParcela + '">' +
		'<a title="Remover parcela" class="tableIcon"><i class="icon-trash"></i></a>' +
		'</td>' +
		'</tr>';

	return linha;
}

function hideColunaEnviarPara() {
	return (ehVendedor || $("#modulo").val() == "ControleMesas" || $("#modulo").val() == "PDV" || $('#nfe_versao').val() > 3.10)
}

function validarParcelas(parcelas){
	var nroParcelas = parcelas.length;
	var parcelasValidadas = new Array;
	var cont = 0;
	for (var i = 0; i < nroParcelas; i++){
		if(parcelas[i] == ""){
			continue;
		}
		if(!isFinite(parcelas[i])){
			continue;
		}
		if(cont > 47){
			break;
		}
		parcelasValidadas[cont] = parcelas[i];
		cont ++;
	}
	return parcelasValidadas;
}

function atualizarParcelas() {
	if ($('#parcelaNumber').val() != '' || $('#parcelaNumber').val() != '0') {
		var totalTransacao = framePagamento.getFullPrice();
		var total = 0;
		var valoresParcela = $('input[name="parcelas[valor][]"]');
		var somaTotalParcelas = 0;
		var valorExtra = 0;

		if (valoresParcela.length > 0) {
			for (var i = 0; i < valoresParcela.length; i++) {
				total += parseFloat(nroUsa(valoresParcela[i].value));
			}
			$.each([idCampoIPI, idCampoST, idCampoFrete], function() {
				if ($.trim(this) != '') {
					valorExtra += nroUsaFloat($('#' + this).val());
				}
			});
			var valorExtraOriginal = 0;
			if (valorExtra > 0) {
				valorExtraOriginal = totalTransacao - total;
			}

			var multiplicador = totalTransacao - valorExtraOriginal;

			for (var i = 0; i < valoresParcela.length; i++) {
				if (total == 0) {
					valoresParcela[i].value = 0;
				} else {
					valoresParcela[i].value = nroBra((((nroUsaFloat(valoresParcela[i].value) - valorExtraOriginal) / total * multiplicador) + valorExtra).toFixed(2));
					valorExtraOriginal = 0;
					valorExtra = 0; // Somado apenas na primeira parcela
					somaTotalParcelas += nroUsaFloat(valoresParcela[i].value);
				}
			}

			var diferenca = totalTransacao - somaTotalParcelas;
			valoresParcela[0].value = nroBra(nroUsaFloat(valoresParcela[0].value) + parseFloat(diferenca.toFixed(2)));
		}
	}
}

/*Rotina executada na edição*/
function addDetailsParcelas(parcelas) {
	apagarParcelas();
	var proxParcela = 0;
	$.each(parcelas, function(nro, item) {
		addParcela(nro, item.nroDias, item.valor, item.conta, item.obs, undefined, item.forma, item.nroBanco, item.idFormaPagamento, item.tband, item.cnpj, item.tpIntegra, item.destino);
		proxParcela++;
	});
	$("#parcelaNumber").val(proxParcela);
	$("#pag_parcelas").show();
	$("#pag_grparcelas").show();
}

/*Rotina executada pelo botao "Calcular parcelas"*/
function calcularParcelas() {
	gerarParcelas();
}

function salvarParcelas() {
	if ($("#pag_parcelas").css("display") == "none") {
		gerarParcelas();
	}
}

function recalcularParcelasAbaixo(idParcelaEditada) {
	var valorParcela, novoTotal, diferenca, parcelaAjusteDiferenca;
	var totais = framePagamento.getFullInstallmentPrices(idParcelaEditada);

	if (framePagamento.getLastInstallmentId(framePagamento.getInstallmentPricesElements()) != idParcelaEditada) {
		novoTotal = totais['beforeInstallments'] + totais['currentInstallment'];
		diferenca = totais['full'] - totais['currentInstallment'] - totais['beforeInstallments'];

		framePagamento.getInstallmentPricesElements().each(function() {
			if (framePagamento.getInstallmentId(this) > idParcelaEditada) {
				valorParcela = framePagamento.getProportionalInstallmentPrice(this, totais['afterInstallments'], diferenca);
				$(this).val(nroBra(valorParcela));
				novoTotal += valorParcela;
			}
		});

		parcelaAjusteDiferenca = framePagamento.getInstallmentPricesElements().eq(-1);
	} else {
		novoTotal = totais['currentInstallment'];
		diferenca = totais['full'] - totais['currentInstallment'];

		framePagamento.getInstallmentPricesElements().each(function() {
			if (framePagamento.getInstallmentId(this) < idParcelaEditada) {
				valorParcela = framePagamento.getProportionalInstallmentPrice(this, totais['beforeInstallments'], diferenca);
				$(this).val(nroBra(valorParcela));
				novoTotal += valorParcela;
			}
		});


		parcelaAjusteDiferenca = framePagamento.getInstallmentPricesElements().eq(0);
	}

	diferenca = totais['full'] - novoTotal;
	parcelaAjusteDiferenca.val(nroBra(framePagamento.getInstallmentPrice(parcelaAjusteDiferenca) + diferenca));
	framePagamento.fireEvent('afterChangeMethods');
}

function limparParcelas() {
	apagarParcelas();
	$("#parcelaNumber").val(0);
	$("#pag_parcelas").hide();
	$("#pag_grparcelas").hide();
}

function existeParcelas() {
	return ($("#pag_grparcelas > tbody > tr[id^='tr_']").length > 0);
}

function bindDataVendaAlterada(campoData) {
	campoData.on('focus', function () {
		dataVendaFocus = $(this).val();
	});

	campoData.on('change', function() {
		if (existeParcelas()) {
			var funcRetonaData = function() {
				campoData.val(dataVendaFocus);
			};

			showDialogMessage({
				'status': 'info',
				'description': 'As datas e valores das suas parcelas serão recalculadas',
				'htmlTitle': 'Pagamento',
				'hideCancel': false,
				'fnOk': function() {
					gerarParcelasManualmente();
				},
				'fnCancelar': function() {
					funcRetonaData();
				},
				'fnBeforeClose': function() {
					funcRetonaData();
				}
			});
		}
	});
}

function visualizarParcela(numeroParcela) {
	var dialog = {
		config: {
			title: 'Dados da parcela',
			width: 648,
			open: ajustarFormDetalhesParcela(numeroParcela)
		},
		content: $('#dados_parcela'),
		hideOk: true,
		hideCancel: true,
		textCancelar: 'Fechar'
	};
	createDialog(dialog);
}

function ajustarFormDetalhesParcela(numeroParcela) {
	var valorEp = nroUsa($('#valor' + numeroParcela).val());
	$('#nroDiasEP').val($('#nroDias' + numeroParcela).val()).prop('readonly', true);
	$('#dataVencimentoEP').val($('#dataVencimento' + numeroParcela).val()).prop('readonly', true);
	$('#valorEP').val(nroBra(valorEp)).prop('readonly', true);
	$('#formaPagamentoEP').val($('#idFormaPagamento' + numeroParcela + ' option:selected').text()).prop('readonly', true);
	var destino = $('#pag_conta' + numeroParcela + ' option:selected').text();
	switch (parseInt($('#destino' + numeroParcela).val())) {
		case 1:
			destino = 'Contas a receber/pagar - ' + destino;
			break;
		case 2:
			destino = 'Ficha financeira';
			break;
		case 3:
			destino = 'Caixas e bancos - ' + destino;
			break;
	}
	$('#pag_contaEP').val(destino).prop('readonly', true);
	$('#obsEP').val($('#obs' + numeroParcela).val()).prop('readonly', true);
	var codigoFiscal = $('#selectForma' + numeroParcela).val();
	$('#selectFormaEP').val(infoFormas.codigoFiscal[codigoFiscal]).prop('readonly', true);
	$('#tpIntegraEP').val(infoFormas.tpIntegra[$('#tpIntegra' + numeroParcela).val()]).prop('readonly', true);
	$('#cnpjCredenciadoraEP').val(aplicarMascara($('#cnpjCredenciadora' + numeroParcela).val(), {'mask':'cnpjcpf'})).prop('readonly', true);
	$('#tbandEP').val(infoFormas.tband[$('#tband' + numeroParcela).val()]).prop('readonly', true);
	if (codigoFiscal == 3 || codigoFiscal == 4) {
		$('#tpIntegraEP').parent().show();
		$('#cnpjCredenciadoraEP').parent().show();
		$('#tbandEP').parent().show();
	} else {
		$('#tpIntegraEP').parent().hide();
		$('#cnpjCredenciadoraEP').parent().hide();
		$('#tbandEP').parent().hide();
	}
	if (exibirFormaPagamento) {
		var idFormaPagamento = $('#idFormaPagamento' + numeroParcela + ' option:selected').val();
		var formaPagamento = formasPagamento[idFormaPagamento];
		$('#valorTaxaEP').val(nroBra((formaPagamento['aliquotaTaxa'] / 100 * valorEp + formaPagamento['valorTaxa']).toFixed(2))).prop('readonly', true);
	} else {
		$('#valorTaxaEP').parent().hide();
	}
}