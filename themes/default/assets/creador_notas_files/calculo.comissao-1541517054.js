var comissaoIdVendedor = 0;
var comissaoTipo = "F";
var comissaoAliquota = 0;
var comissaoOpcRecalcularTudo = true;
var comissaoDescontos = null;
var comissaoLinhas = null;

function comissaoSetIdVendedor(aComissaoIdVendedor, aComissaoOpcRecalcularTudo) {
	var deferredObj = $.Deferred();

	comissaoIdVendedor = aComissaoIdVendedor;
	comissaoOpcRecalcularTudo = aComissaoOpcRecalcularTudo
	xajax_carregarDadosComissaoVendedor(comissaoIdVendedor, function() {
		deferredObj.resolve();
	});

	return deferredObj.promise();
}

function comissaoSetDadosComissaoVendedor(aComissaoTipo, aComissaoAliquota, aComissaoDescontos, aComissaoLinhas) {
	if (! aComissaoLinhas) {
		aComissaoLinhas = null;
	}
	comissaoTipo = aComissaoTipo;
	comissaoAliquota = aComissaoAliquota;
	comissaoDescontos = aComissaoDescontos;
	comissaoLinhas = aComissaoLinhas;
	if (comissaoOpcRecalcularTudo) {
		comissaoCalcularComissoes();
	}
}

function comissaoGetAliquotaComissao(comissaoValorLista, comissaoValor, idLinhaProduto) {
	if (! idLinhaProduto) {
		idLinhaProduto = 0;
	}

	/*Calcular alíquota de desconto*/
	var comissaoAlqDesconto = 0;
	comissaoAlqDesconto = (comissaoValorLista - comissaoValor);
	if (comissaoAlqDesconto > 0) {
		comissaoAlqDesconto = (comissaoAlqDesconto / comissaoValorLista * 100);
	} else {
		comissaoAlqDesconto = 0;
	}

	var encontrouComissao = false;
	var comissaoAliquotaComissao = 0;

	/*Linha de produto*/
	if (idLinhaProduto > 0) {
		$.each(comissaoLinhas, function (key, value) {
			if (value.idLinha == idLinhaProduto) {
				/*Busca alíquota de comissão - linha de produto*/
				encontrouComissao = true;
				comissaoAliquotaComissao = comissaoProcessar(value.comissaoTipo, value.comissaoAliquota, value.descontos, comissaoAlqDesconto)
			}
		})
	}

	if (encontrouComissao) {
		return comissaoAliquotaComissao;
	} else {
		/*Busca alíquota de comissão - vendedor*/
		return comissaoProcessar(comissaoTipo, comissaoAliquota, comissaoDescontos, comissaoAlqDesconto);
	}
}

function comissaoGetAliquotaComissaoPorAlqDesconto(comissaoAlqDesconto, idLinhaProduto) {
	if (! idLinhaProduto) {
		idLinhaProduto = 0;
	}

	var encontrouComissao = false;
	var comissaoAliquotaComissao = 0;

	/*Linha de produto*/
	if (idLinhaProduto > 0) {
		if (comissaoLinhas) {
			$.each(comissaoLinhas, function (key, value) {
				if (value.idLinha == idLinhaProduto) {
					/*Busca alíquota de comissão - linha de produto*/
					encontrouComissao = true;
					comissaoAliquotaComissao = comissaoProcessar(value.comissaoTipo, value.comissaoAliquota, value.descontos, comissaoAlqDesconto)
				}
			})
		}
	}

	if (encontrouComissao) {
		return comissaoAliquotaComissao;
	} else {
		/*Busca alíquota de comissão - vendedor*/
		return comissaoProcessar(comissaoTipo, comissaoAliquota, comissaoDescontos, comissaoAlqDesconto);
	}
}

function comissaoProcessar(pComissaoTipo, pComissaoAliquota, pArrayDescontos, pComissaoAlqDesconto) {
	try {
		if (! ((pComissaoAlqDesconto > 0) || (pComissaoAlqDesconto < 0))) {
			pComissaoAlqDesconto = 0;
		}
	} catch (e) {
		pComissaoAlqDesconto = 0;
	}

	/*Fixo*/
	if (pComissaoTipo == "F") {
		return pComissaoAliquota;
	}

	/*Desconto*/
	pComissaoAlqDesconto = roundValue(pComissaoAlqDesconto, 2);
	var achouComissao = false;
	var comissaoAliquotaTmp = 0;

	if (pArrayDescontos) {
		$.each(pArrayDescontos, function (key, value) {
			if (! achouComissao) {
				if (pComissaoAlqDesconto <= value.desconto) {
					achouComissao = true;
					comissaoAliquotaTmp = value.comissao;
				}
			}
		})
	}

	return comissaoAliquotaTmp;
}

function atualizarComissoes(total, desconto) {
	var subTotal = 0;
	var precosTotais = $('input[name="itens[precototal][]"]');
	var adicionaisRateado = $('input[name="itens[adicionais_base][]"]');
	var baseComissaoItem = $('input[name="itens[base_comissao][]"]');
	var alqComissaoItem = $('input[name="itens[alq_comissao][]"]');
	var vlrComissaoItem = $('input[name="itens[vlr_comissao][]"]');
	var parametroAdicionaisBase = $('input[name="parametro_adicionais_comissao"]').val().split(',');

	var considerarDesconto = false;

	var valorComissao = 0;
	var adicionalComissao = 0;
	var totalAdicionalComissaoSemDesconto = 0;

	var descontoRateado = 0;
	var adicionalComissaoRateado = 0;

	$.each(parametroAdicionaisBase, function(key, value) {
		switch (value) {
			case '0':
				considerarDesconto = true;
				break;
			case '1':
				totalAdicionalComissaoSemDesconto += nroUsaFloat($('#frete').val());
				break;
			case '2':
				totalAdicionalComissaoSemDesconto += nroUsaFloat($('#totalICMS').val());
				break;
			case '3':
				totalAdicionalComissaoSemDesconto += nroUsaFloat($('#totalIPI').val());
				break;
			case '4':
				totalAdicionalComissaoSemDesconto -= framePagamento.getTotalTaxes();
				break;
		}
	});

	if (!considerarDesconto) {
		desconto = 0;
	}

	for (i = 0; i < precosTotais.length; i++) {
		descontoRateado = (nroUsaFloat(precosTotais[i].value) / total) * desconto;
		adicionalComissaoRateado = (nroUsaFloat(precosTotais[i].value) / total) * totalAdicionalComissaoSemDesconto;
		adicionalComissao = adicionalComissaoRateado - descontoRateado;
		adicionaisRateado[i].value = nroBra(adicionalComissao);
		valorComissao = (nroUsaFloat(baseComissaoItem[i].value) + adicionalComissao) * nroUsaFloat(alqComissaoItem[i].value) / 100;
		vlrComissaoItem[i].value = nroBra(valorComissao);
	}
}