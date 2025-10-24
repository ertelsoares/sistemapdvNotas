var arrayItens = {};
var itemTemp = {};
var itemTempEdicao = {};
var contItens = 0;
var destinoCefop = "";
var idOperacaoAnterior = 0;
var arrayStIcms = {};
var arrayStSimples = {};
var arrayStIpi = {};
var arrayStIssqn = {};
var arrayStPis = {};
var arrayStCofins = {};
var arrayStII = {};

var arraySpedBaseCalculoCredito = {};
var arraySpedTipoCredito = {};
var arraySpedTipoItem = {};

var t;
var contatoNovo = true;
var itemClicado = false;
var mensagemValidacao = "";
var tipoNota;
var estoqueAtual = "0";
var estoqueMinimo = "0";
var estoqueMaximo = "0";
var tipoProduto = "P";
var flagMouseOver = false;
var idItemEdicao = 0;
var requisicaoEmAndamento = false;

function adicionarItemAoTemp(aItem){
	itemTemp = aItem;
	itemTemp["quantidade"] = "1,00";
	itemTemp["valorTotal"] = "0,00";
}

function adicionarItemAoTempEdicao(aItem){
	itemTempEdicao["idProduto"] = aItem.id;
	itemTempEdicao["descricao"] = aItem.nome;
	itemTempEdicao["valorUnitario"] = aItem.valorUnitario;
	itemTempEdicao["cf"] = aItem.cf;
	itemTempEdicao["codigo"] = aItem.codigo;
	itemTempEdicao["un"] = aItem.un;
	itemTempEdicao["tipo"] = aItem.tipo;
	itemTempEdicao["valorIpiFixo"] = nroBra(aItem.valorIpiFixo);
	itemTempEdicao["descricaoCadastro"] = aItem.descricaoCadastro;
	itemTempEdicao["cest"] = aItem.cest;
	itemTempEdicao["origem"] = aItem.origem;
	itemTempEdicao['gtin'] = aItem.gtin;
	itemTempEdicao['gtinEmbalagem'] = aItem.gtinEmbalagem;
	xajax_obterImpostosDoItem($("#edIdOperacaoFiscal").val(),$("#idMunicipio").val(),$("#uf").val(),idItemEdicao,itemTempEdicao, tipo, $("#calculaImpostos").val(), $("#crt").val(), $('#loja').val(), $('#idConfUnidadeNegocio').val(), function (data) {
		if (data.error != '') {
			requisicaoEmAndamento = false;
			alert(data.error);
			return false;
		}
		setarImpostosDoItemTempEdicao(data.item, data.impostos)
		requisicaoEmAndamento = false;
	});
}

/*
function adicionarItemAoTempEquivalente(aItem) {
	itemTemp["produtoEquivalente"] = aItem.produtoEquivalente;
	itemTemp["unProdutoEquivalente"] = aItem.unProdutoEquivalente;
}
*/

function adicionarItemAoTempEdicaoEquivalente(aItem) {
	itemTempEdicao["idProduto"] = aItem.idProduto;
	itemTempEdicao["produtoEquivalente"] = aItem.produtoEquivalente;
	itemTempEdicao["unProdutoEquivalente"] = aItem.equivalenteUn;
//	itemTempEdicao["qtdeProdutoEquivalente"] = aItem.equivalenteQuantidade;
	itemTempEdicao["pesoLiq"] = aItem.pesoLiq;
	itemTempEdicao["pesoBruto"] = aItem.pesoBruto;

	$("#produtoEquivalente").val(itemTempEdicao["produtoEquivalente"]);
	$("#unProdutoEquivalente").val(itemTempEdicao["unProdutoEquivalente"]);
//	$("#qtdeProdutoEquivalente").val(itemTempEdicao[itemTempEdicao["qtdeProdutoEquivalente"]]);
	if ($("#edCf").val() == "") {
		itemTempEdicao["cf"] = aItem.cf;
		$("#edCf").val(itemTempEdicao["cf"]);
	}
}

function alterarItemTemp(valor,campo){
	if (valor == "0" && campo == "quantidade"){
		$('#' + campo).val("1");
	} else {
		itemTemp[campo] = valor;
	}
}

function setIdMunicipio(param){
	$("#municipio").removeClass("warning");
	$("#municipio").removeClass("ac_error");
	$("#municipio").addClass("tipsyOff");
	$("#municipio").removeAttr("title");
	$("#idMunicipio").val(param.id);
	$("#uf").val(param.uf);
	marcarTodosItensParaSeremRecalculados();
	if ($("#calculaImpostos").val() == "S"){
		calcularImpostos("N");
	}
	atualizarCamposPorDestino();
}

function setIdMunicipioEtiqueta(param){
	$("#etiqueta_municipio").removeClass("warning");
	$("#etiqueta_municipio").removeClass("ac_error");
	$("#etiqueta_municipio").addClass("tipsyOff");
	$("#etiqueta_municipio").removeAttr("title");
	$("#etiqueta_id_municipio").val(param.id);
	$("#etiqueta_uf").val(param.uf);
}

function setIdPais(param){
	$("#idPais").val(param.id);
	$("#nomePais").removeClass("ac_error");
	$("#nomePais").addClass("tipsyOff");
	$("#nomePais").removeAttr("title");
	$("#nomePais").focus();
}

function marcarTodosItensParaSeremRecalculados(){
	if(arrayItens.length >0){
		$.each(arrayItens, function(id,item){
			if (arrayItens[id]) {
				arrayItens[id]["buscarImpostos"] = "S";
			}
		});
	}
}

function setarArrayItens(aItens, montarTela) {
	arrayItens = aItens;
	if (montarTela != "N") {
		montarItensTela();
	}
	$.each(arrayItens, function(id, item) {
		if (item) {
			$("#grid_cf_" + id).html('<p class="w7 p2">' + item.cf + '</p>');
		}
	});
}

function atualizarItemNumber(itemNumber){
	contItens = itemNumber;
}

function montarItensTela(){
	$.each(arrayItens, function(id,item){
		if(item != null){
			adicionarItemNaTela(id,item);
		}
	});
	atribuirEventoHighlight();

	if(arrayItens.length > 0){
		$("#linhaInclusaoItem").hide();
	}
}

function adicionarItemNaTela(id, item) {
	$("<tr style='cursor:pointer' class='linhaItemNota'>").attr("id","item"+id).insertBefore("#linhaInclusaoItem");
    var linha = $("#item"+id);
    linhaItem(id, item, linha);
    //validarLinhaItem(item);
}

function validarLinhaItem(item){
	for (i=0;i<item.length;i++){
		if(item[i].cf == ""){
			$("table#tItensNota tbody tr#item"+i+" td.cf").addClass("ac_error");
			$("table#tItensNota tbody tr#item"+i+" td.cf").attr("original-title","É necessário preencher o NCM");
			$("table#tItensNota tbody tr#item"+i+" td.cf").tipsy({gravity: $.fn.tipsy.autoWE});
			$("table#tItensNota tbody tr#item"+i+" td.cf").removeClass("tipsyOff");
		}
	}

}

function linhaItem(id, item, linha, atuNroItens) {
	var estoque = getEstoqueInfo({
		"quantidade": item.quantidade,
		"estoqueAtual": nroBraDecimais((item.estoqueAtual || 0), 6),
		"estoqueMinimo": nroBraDecimais((item.estoqueMinimo || 0), 6),
		"estoqueMaximo": nroBraDecimais((item.estoqueMaximo || 0), 6),
		"tipo": (tipo == "E" ? "C" : "V"),
		"tipoProduto": item.tipoProduto
	});

	if (item.idProduto > 0) {
		$("<td onclick='editarItem("+id+")'>").html('<p class="w20 p2">' + item.descricao + '</p>').appendTo(linha);
	} else {
		$("<td onclick='editarItem("+id+")'>").html('<p class="w20 p2">' + item.descricao + '&nbsp; <sup class="new">Novo - Será cadastrado</sup></p>').appendTo(linha);
	}
	//CODIGO
	if(item.codigo != null)
		var codigoImpresso = item.codigo;
	else
		var codigoImpresso = "";
	//UNIDADE
	if(item.un != null)
		var unidade = item.un;
	else
		var unidade = "";
	//QUANTIDADE
	if(item.quantidade != null)
		var quantidade = item.quantidade;
	else
		var quantidade = "";
	//VALOR UNITARIO
	if(item.valorUnitario != null)
		var valorUnitario = item.valorUnitario;
	else
		var valorUnitario = "";
	//VALOR TOTAL
	if(item.valorTotal != null)
		var valorTotal = item.valorTotal;
	else
		var valorTotal = "";
	//CLASSIFICAÇÃO FISCAL
	if(item.cf != null)
		var cf = item.cf;
	else
		var cf = "";

	$("<td onclick='editarItem("+id+")'>").html('<p class="w7 p2">' + codigoImpresso + '</p>').appendTo(linha);
	$("<td onclick='editarItem("+id+")'>").html('<p class="w7 p2">' + unidade + '</p>').appendTo(linha);
	$("<td onclick='editarItem("+id+")'>").html('<p class="w7 p2">' + quantidade + '</p>').appendTo(linha);
	$("<td onclick='editarItem("+id+")'>").html('<p class="w7 p2">' + valorUnitario + '</p>').appendTo(linha);
	$("<td class='hidden-mobile' onclick='editarItem("+id+")'>").html('<p class="w7 p2">' + valorTotal + '</p>').appendTo(linha);
	$("<td class='hidden-mobile' onclick='editarItem("+id+")' class='cf tipsyOff'>").html('<p class="w7 p2">' + cf + '</p>').appendTo(linha);

	$("<td style='display:none'>").html("&nbsp; "+item.precoLista).appendTo(linha);

	$("<td style='min-width:50px; height:30px;' class='w4 editgridh center'>").html("<a title='Visualizar histórico do produto para o cliente' id='mostrarAsInfProdutoCliente" + id +"' class='tableIcon hidden-mobile'><i class='icon-info-sign'></i></a>" +
		"<a style='float: right;' title='Editar componentes do produto' class='tableIcon' onclick='editarItem(" + id + ")'><i class='icon-pencil'></i>").appendTo(linha);
	$("<td class='w1 center'>").html(estoque).appendTo(linha);
	$("<td class='editgridh center'>").html("<a style='float: none;' title='Remover item do pedido' class='tableIcon' onclick='removeItemProduto(" + id + ")'><i class='icon-trash'></i></a>").appendTo(linha);
	$("#mostrarAsInfProdutoCliente" + id).bind("click", function() {visualizarAsInformacoesProdutoCliente(arrayItens[id]["idProduto"])});
	$("#mostrarAsInfProdutoCliente" + id).bind("onclick", function() {terribleHack(this);});

	if(atuNroItens != "N"){
		atualizarNroItens("soma");
	}
}

function dividirCodigoParaImpressao(codigoOriginal) {
	var codigoImpresso = codigoOriginal;
	if (codigoOriginal.length > "15") {
		codigoImpresso = "";
		var strCodigo = codigoOriginal;
		while (strCodigo != "") {
			if ($.trim(codigoImpresso) != "") {
				codigoImpresso += " ";
			}
			codigoImpresso += strCodigo.substr(0, 14);
			strCodigo = strCodigo.substr(14);
		}
	}
	return codigoImpresso;
}


function editarImposto(id, tipo) {
	if (! itemClicado) {
		itemClicado = true;
		delete itemTempEdicao;
		itemTempEdicao = jQuery.extend(true, {}, arrayItens[id]); //clona o objeto
		idItemEdicao = id;
		if (tipo == "IPI") {
			new Boxy($("#form_edicao_item"), {
				title: "Item da nota fiscal - IPI",
				afterShow: ajustarFormEdicaoItemIPI,
				modal: true,
				unloadOnHide: false,
				afterHide: cancelarEdicaoItem
			});
		} else {
			new Boxy($("#form_edicao_item"), {
				title: "Item da nota fiscal - ICMS",
				afterShow: ajustarFormEdicaoItemICMS,
				modal: true,
				unloadOnHide: false,
				afterHide: cancelarEdicaoItem
			});
		}
		initFormatterField("2", $("#valor"));
	}
}

function ajustarFormEdicaoItemICMS() {
	limparFormEdicaoItem();
	itemClicado = false;
	estoqueAtual = itemTempEdicao["estoqueAtual"];
	estoqueMinimo = itemTempEdicao["estoqueMinimo"];
	estoqueMaximo = itemTempEdicao["estoqueMaximo"];
	tipoProduto = itemTempEdicao["tipoProduto"];
	montarDadosParaEdicaoItem();
	if ($("#peso_calculado").val() == "S") {
		$("#edPesoBruto").parent().show();
		$("#edPesoLiq").parent().show();
	} else {
		$("#edPesoBruto").parent().hide();
		$("#edPesoLiq").parent().hide();
	}

	$("#tabnav > li").each(function() {
		$(this).hide();
	});

	$("#link_aba_prod").parent().show();
	$("#link_aba_icms").parent().show();

	configurarAba("icms", $("#link_aba_icms"), $("#selectStIcms"));
	//$("#aba_estoque").hide();
}

function ajustarFormEdicaoItemIPI() {
	limparFormEdicaoItem();
	itemClicado = false;
	estoqueAtual = itemTempEdicao["estoqueAtual"];
	estoqueMinimo = itemTempEdicao["estoqueMinimo"];
	estoqueMaximo = itemTempEdicao["estoqueMaximo"];
	tipoProduto = itemTempEdicao["tipoProduto"];
	montarDadosParaEdicaoItem();
	if ($("#peso_calculado").val() == "S") {
		$("#edPesoBruto").parent().show();
		$("#edPesoLiq").parent().show();
	} else {
		$("#edPesoBruto").parent().hide();
		$("#edPesoLiq").parent().hide();
	}

	$("#tabnav > li").each(function() {
		$(this).hide();
	});

	$("#link_aba_prod").parent().show();

	$("#link_aba_ipi").parent().show();
	configurarAba("ipi", $("#link_aba_ipi"), $("#selectStIpi"));
}

function editarItem(id){
	if (! itemClicado) {
		itemClicado = true;
		delete itemTempEdicao;
		itemTempEdicao = jQuery.extend(true, {}, arrayItens[id]);
		idItemEdicao = id;
		new Boxy($("#form_edicao_item"),{
			title: "Item da nota fiscal",
			afterShow: ajustarFormEdicaoItem,
			modal: true,
			unloadOnHide: false,
			afterHide: cancelarEdicaoItem
		});
		initFormatterField("2", $("#valor"));
	}
}

function ajustarFormEdicaoItem() {
	$("#tabnav > li").each(function() {
		$(this).show();
	});

	limparFormEdicaoItem();

	itemClicado = false;
	estoqueAtual = itemTempEdicao["estoqueAtual"];
	estoqueMinimo = itemTempEdicao["estoqueMinimo"];
	estoqueMaximo = itemTempEdicao["estoqueMaximo"];
	tipoProduto = itemTempEdicao["tipoProduto"];
	montarDadosParaEdicaoItem();

	if ($("#peso_calculado").val() == "S") {
		$("#edPesoBruto").parent().show();
		$("#edPesoLiq").parent().show();
	} else {
		$("#edPesoBruto").parent().hide();
		$("#edPesoLiq").parent().hide();
	}

	if ($("#volume_calculado").val() == "S") {
		$("#edVolumes").parent().show();
	} else {
		$("#edVolumes").parent().hide();
	}

	if ($('#nfe\\:desconto_calculado').val() == 'S') {
		$('#edValorDescontoItem').prop('readonly', false);
	} else {
		$('#edValorDescontoItem').prop('readonly', true);
	}
	/*
	initFormatterField($("#dec_valor").val(), $("#edValorUnitario"));
	initFormatterField($("#dec_qtde").val(), $("#edQuantidade"));
	initFormatterField("3", $(".edPesoDecimal"));
	initFormatterField("4", $(".edQuatroDecimais"));
	initFormatterField("2", $(".edDuasDecimais"));
	*/

	configurarAba("prod", $("#link_aba_prod"), $("#edOpFiscal"));

	if ($("#sistema").val() != "TiraNota") {
		$("#aba_estoque").show();
	} else {
		$("#aba_estoque").hide();
	}
	habilitarFCI(itemTempEdicao["origem"]);
	$("#edDescricao").focus();

	if ($("#finalidade").val() == "4"){
		$("#devolucaoIPI").show();
	} else {
		$("#devolucaoIPI").hide();
	}

	if (($('#notaTipo option:selected').val() != 'E' || $('#tipo').val() != 'S') && itemTempEdicao['cfop'] != '3503') {
		$('#aba_exportacao').hide();
	}

	montarSelectAdicoes(itemTempEdicao["II"]["nAdicao"]);
	filtrarCodEnqIPI(itemTempEdicao["IPI"]["st"]);
	exibirTribPartilha();
	exibirCnpjFab();
	exibirAbasICMSISS();
	exibirCamposII();
	if ($('#nfe_versao').val() != 4.00) {
		$('.icon-info-novo').hide();
	} else {
		initPopovers({'elements': $('.icon-info-novo')});
	}

	$("#gtin, #gtinEmbalagem").trigger("change");
}

function habilitarFCI(origem) {
	if(origem == 3 || origem == 5 || origem == 8){
		$("#divFCI").show();
	}else{
		$("#divFCI").hide();
	}
}

function montarDadosParaEdicaoItem(id) {
	$("#link_aba_icms_geral").click();

	$.each(itemTempEdicao, function(key, value){
		$("#ed" + key).val(value);
	});

	// Dados do item
	$("#edCodigo").val(itemTempEdicao["codigo"]);
	$("#edDescricao").val(itemTempEdicao["descricao"]);
	$("#edCf").val(itemTempEdicao["cf"]);
	$("#edPesoLiq").val(itemTempEdicao["pesoLiq"]);
	$("#edPesoBruto").val(itemTempEdicao["pesoBruto"]);
	$("#edVolumes").val(itemTempEdicao["volumes"]);
	$("#edIdProduto").val(itemTempEdicao["idProduto"]);
	$("#edIdOperacaoFiscal").val(itemTempEdicao["idOperacaoFiscal"]);
	$("#edCfop").val(itemTempEdicao["cfop"]);
	$("#edUn").val(itemTempEdicao["un"]);
	$("#edQuantidade").val(itemTempEdicao["quantidade"]);
	$("#edValorUnitario").val(itemTempEdicao["valorUnitario"]);
	$("#edValorTotal").val(itemTempEdicao["valorTotal"]);
	$("#edFaturada").val(itemTempEdicao["faturada"]);
	$("#edOrigem").val(itemTempEdicao["origem"]);
	$("#edOrigemSimples").val(itemTempEdicao["origem"]);
	$("#edTipo").val(itemTempEdicao["tipo"]);
	$("#edConsumidorFinal").val(itemTempEdicao["consumidor_final"]);
	$("#edSomaIcmsTotalNota").val(itemTempEdicao["somaIcmsTotalNota"]);
	$("#edSomaImpostosTotalNota").val(itemTempEdicao["somaImpostosTotalNota"]);
	$("#considerarPresumidoBasePisCofins").val(itemTempEdicao["considerarPresumidoBasePisCofins"]);
	$("#edAliquotaFunrural").val(itemTempEdicao["alqFunrural"]);
	$("#edObsItem").val(itemTempEdicao["obs"]);
	$("#edValorFrete").val(itemTempEdicao["valorFrete"]);
	$("#edAVAImpostos").val(itemTempEdicao["alqValorAproxImpostos"]);
	$("#edVAImpostos").val(itemTempEdicao["valorAproxImpostos"]);
	$('#uTrib').val(itemTempEdicao['uTrib']);
	$('#qTrib').val(nroBraDecimais(itemTempEdicao['qTrib'], 4));
	$('#vUnTrib').val(nroBraDecimais(itemTempEdicao['vUnTrib'], 10));
	$("#descricaoComplementar").val(itemTempEdicao["descricaoComplementar"]);
	$("#produtoCadastro").val(itemTempEdicao["descricaoCadastro"]).prop("readonly", true);

	$("#gtin").val(itemTempEdicao["gtin"]);
	$("#gtinEmbalagem").val(itemTempEdicao["gtinEmbalagem"]);
	$("#selectSpedTipoItem").val(itemTempEdicao["spedTipoItem"]);

	$("#edCodAnp").val(itemTempEdicao["codAnp"]);
	$("#edCodCodif").val(itemTempEdicao["codCodif"]);
	$("#edUfConsumo").val(itemTempEdicao["ufConsumo"]);
	$("#edQtdeCombAmbiente").val(nroBraDecimais(itemTempEdicao["qtdeCombAmbiente"], 4));
	$("#edBaseCide").val(nroBraDecimais(itemTempEdicao["baseCide"], 4));
	$("#edAlqCide").val(nroBraDecimais(itemTempEdicao["alqCide"], 4));
	$("#edValorCide").val(nroBraDecimais(itemTempEdicao["valorCide"], 2));
	$('#descANP').val(itemTempEdicao['descANP']);
	$('#pGLP').val(nroBraDecimais(itemTempEdicao['pGLP'], 4));
	$('#pGNn').val(nroBraDecimais(itemTempEdicao['pGNn'], 4));
	$('#pGNi').val(nroBraDecimais(itemTempEdicao['pGNi'], 4));
	$('#vPart').val(nroBraDecimais(itemTempEdicao['vPart'], 2));

	$("#edTpArma").val(itemTempEdicao["arma_tipo"]);
	$("#edDescrArma").val(itemTempEdicao["arma_descricao"]);

	$("#ednFCI").val(itemTempEdicao["nFCI"]);

	$("#numeroPedidoCompra").val(itemTempEdicao["numeroPedidoCompra"]);
	$("#numeroItemPedidoCompra").val(itemTempEdicao["numeroItemPedidoCompra"]);

	$("#edBaseComissao").val(itemTempEdicao["base_comissao"]);
	$("#edAlqComissao").val(itemTempEdicao["alq_comissao"]);
	$("#edValorComissao").val(itemTempEdicao["vlr_comissao"]);
	$("#edValorDescontoItem").val(itemTempEdicao["valorDescontoItem"]);
	$("#edDescontoCondicional").val(itemTempEdicao["descontoCondicional"]);

	setarTipoEquivalente(itemTempEdicao["equivalenteTipo"]);
	switch (itemTempEdicao["equivalenteTipo"]) {
	case "N": $("#unEstoque").val(itemTempEdicao["unEstoque"]);
			  $("#qtdeEstoque").val(itemTempEdicao["qtdeEstoque"]);
			  $("#qtdeProdutoEquivalente").val(itemTempEdicao["qtdeProdutoEquivalente"]);
			  break;
	case "E": $("#unProdutoEquivalente").val(itemTempEdicao["unProdutoEquivalente"]);
			  $("#qtdeProdutoEquivalente").val(itemTempEdicao["qtdeProdutoEquivalente"]);
			  $("#qtdeEstoque").val(itemTempEdicao["qtdeEstoque"]);
			  $("#produtoEquivalente").val(itemTempEdicao["produtoEquivalente"]);
			  break;
	default:
		itemTempEdicao["unEstoque"] = itemTempEdicao["un"];
		$("#unEstoque").val(itemTempEdicao["unEstoque"]);
		$("#unProdutoEquivalente").val(itemTempEdicao["un"]);
		itemTempEdicao["qtdeEstoque"] = itemTempEdicao["quantidade"];
		$("#qtdeEstoque").val(itemTempEdicao["qtdeEstoque"]);
		$("#qtdeProdutoEquivalente").val(itemTempEdicao["quantidade"]);
		break;
	}

	/* Visualização */
	$("#td_ii_base").html(nroBra(itemTempEdicao["II"]["valorBaseCalculo"]));
	$("#td_ii_alq").html(nroBra(itemTempEdicao["II"]["aliquota"]) + " %");
	$("#td_ii_valor").html(nroBra(itemTempEdicao["II"]["valorImposto"]));

	$("#td_ipi_base").html(nroBra(itemTempEdicao["IPI"]["valorBaseCalculo"]));
	$("#td_ipi_alq").html(nroBra(itemTempEdicao["IPI"]["aliquota"]) + " %");
	$("#td_ipi_valor").html(nroBra(itemTempEdicao["IPI"]["valorImposto"]));
	$("#edcSelo").val(itemTempEdicao["IPI"]["cSelo"]);
	$("#edcEnq").val(itemTempEdicao["IPI"]["cEnq"]);

	$("#td_pis_base").html(nroBra(itemTempEdicao["PIS"]["valorBaseCalculo"]));
	$("#td_pis_alq").html(nroBra(itemTempEdicao["PIS"]["aliquota"]) + " %");
	$("#td_pis_valor").html(nroBra(itemTempEdicao["PIS"]["valorImposto"]));

	$("#td_cofins_base").html(nroBra(itemTempEdicao["COFINS"]["valorBaseCalculo"]));
	$("#td_cofins_alq").html(nroBra(itemTempEdicao["COFINS"]["aliquota"]) + " %");
	$("#td_cofins_valor").html(nroBra(itemTempEdicao["COFINS"]["valorImposto"]));

	if ($('#crt').val() == 1) {
		$("#td_icms_base").html(nroBra(itemTempEdicao["SIMPLES"]["valorBase"]));
		$("#td_icms_alq").html(nroBra(itemTempEdicao["SIMPLES"]["aliquota"]) + " %");
		$("#td_icms_valor").html(nroBra(itemTempEdicao["SIMPLES"]["valorImposto"]));
		$("#td_icms_diferimento").html(nroBra(itemTempEdicao["SIMPLES"]["baseDiferimento"]) + " %");
	} else {
		$("#td_icms_base").html(nroBra(itemTempEdicao["ICMS"]["valorBaseCalculo"]));
		$("#td_icms_alq").html(nroBra(itemTempEdicao["ICMS"]["aliquota"]) + " %");
		$("#td_icms_valor").html(nroBra(itemTempEdicao["ICMS"]["valorImposto"]));
		$("#td_icms_diferimento").html(nroBra(itemTempEdicao["ICMS"]["baseDiferimento"]) + " %");
	}

	if ($("#valorUnitarioComII").prop("checked") == true) {
		$("#td_valor_aduaneiro").html("Valor aduaneiro = Valor produtos - Valor II");
	} else {
		$("#td_valor_aduaneiro").html("Valor aduaneiro = Valor produtos + Frete + Seguro (" + nroBra(itemTempEdicao.valorSeguro) + ")");
	}
	$('#formula_siscomex').html(nroBra(itemTempEdicao['II']['valorDespesaAduaneira']));

	var fatorAlqICMS = itemTempEdicao["ICMS"]["aliquota"] / 100;
	var fatorAlqII = itemTempEdicao["II"]["aliquota"] / 100;
	var fatorAlqIPI = itemTempEdicao["IPI"]["aliquota"] / 100;
	var fatorAlqPIS= itemTempEdicao["PIS"]["aliquota"] / 100;
	var fatorAlqCOFINS = itemTempEdicao["COFINS"]["aliquota"] / 100;
	var fator = (1 + fatorAlqICMS * (fatorAlqII + fatorAlqIPI * (1 + fatorAlqII))) / ((1 - fatorAlqPIS - fatorAlqCOFINS) * (1 - fatorAlqICMS));
	$("#fator").html("* Fator = (1 + " + nroBraDecimais(fatorAlqICMS, 4) + " * (" + nroBraDecimais(fatorAlqII, 4) + " + " + nroBraDecimais(fatorAlqIPI, 4) + " * (1 + " + nroBraDecimais(fatorAlqII, 4) + "))) / ((1 - " + nroBraDecimais(fatorAlqPIS, 4) + " - " + nroBraDecimais(fatorAlqCOFINS, 4) + ") * (1 - " + nroBraDecimais(fatorAlqICMS, 4) + ")) = " + nroBraDecimais(fator, 4));
	/* Visualização - End*/

	// Dados do icms
	$("#edBaseIcms").val(nroBraDecimais(itemTempEdicao["ICMS"]["base"], 4));
	$("#edBaseIcmsDiferimento").val(nroBraDecimais(itemTempEdicao["ICMS"]["baseDiferimento"], 4));
	$("#edAlqIcmsPresumido").val(nroBraDecimais(itemTempEdicao["ICMS"]["alqPresumido"], 4));
	$("#edValorBaseIcms").val(nroBra(itemTempEdicao["ICMS"]["valorBaseCalculo"]));
	$("#edAliquotaIcms").val(nroBraDecimais(itemTempEdicao["ICMS"]["aliquota"], 4));
	$("#edValorIcms").val(nroBra(itemTempEdicao["ICMS"]["valorImposto"]));
	$("#edStIcms").val(itemTempEdicao["ICMS"]["st"]);
	$("#edTributacaoIcms").val(itemTempEdicao["ICMS"]["tributacao"]);
	$("#edObsIcms").val(itemTempEdicao["ICMS"]["obs"]);
	$("#modalidadeBaseCalculo").val(itemTempEdicao["ICMS"]["modalidadeBaseCalculo"]);
	$("#valorPauta").val(nroBraDecimais(itemTempEdicao["ICMS"]["valorPauta"], 4));
	$("#edValorPresumido").val(nroBra(itemTempEdicao["ICMS"]["valorPresumido"]));
	$("#edAlqPosicao").val(itemTempEdicao["ICMS"]["alqPosicao"]);
	$('#vICMSDeson').val(nroBra(itemTempEdicao['ICMS']['vICMSDeson']));
	$('#motDesICMS').val(itemTempEdicao['ICMS']['motDesICMS']);

	$('#edpBCUFDest').val(nroBra(itemTempEdicao['ICMS']['pBCUFDest']));
	$('#edvBCUFDest').val(nroBra(itemTempEdicao['ICMS']['vBCUFDest']));
	$('#edpFCPUFDest').val(nroBraDecimais(itemTempEdicao['ICMS']['pFCPUFDest'], 4));
	$('#edpICMSUFDest').val(nroBraDecimais(itemTempEdicao['ICMS']['pICMSUFDest'], 4));
	$('#edpICMSInter').val(nroBraDecimais(itemTempEdicao['ICMS']['pICMSInter'], 4));
	$('#edpICMSInterPart').val(nroBraDecimais(itemTempEdicao['ICMS']['pICMSInterPart'], 4));
	$('#edvFCPUFDest').val(nroBra(itemTempEdicao['ICMS']['vFCPUFDest']));
	$('#edvICMSUFDest').val(nroBra(itemTempEdicao['ICMS']['vICMSUFDest']));
	$('#edvICMSUFRemet').val(nroBra(itemTempEdicao['ICMS']['vICMSUFRemet']));
	$('#edpFCP').val(nroBra(itemTempEdicao['pFCP']));
	$('#edvFCP').val(nroBra(itemTempEdicao['vFCP']));
	$('#tipoPartilha').val(itemTempEdicao['ICMS']['tipoPartilha']);

	configurarModalidadeBC(itemTempEdicao["ICMS"]["modalidadeBaseCalculo"]);

	// Dados do Ipi
	$("#edBaseIpi").val(nroBraDecimais(itemTempEdicao["IPI"]["base"], 4));
	$("#edValorBaseIpi").val(nroBra(itemTempEdicao["IPI"]["valorBaseCalculo"]));
	$("#edAliquotaIpi").val(nroBra(itemTempEdicao["IPI"]["aliquota"]));
	$("#edValorIpi").val(nroBra(itemTempEdicao["IPI"]["valorImposto"]));
	$("#edStIpi").val(itemTempEdicao["IPI"]["st"]);
	$("#edTributacaoIpi").val(itemTempEdicao["IPI"]["tributacao"]);
	if(itemTempEdicao["IPI"]["valorIpiFixoUnitario"] > 0){
		$("#edValorIpiFixoUnitario").val(nroBra(itemTempEdicao["IPI"]["valorIpiFixoUnitario"]));
	}
	if(itemTempEdicao["IPI"]["classeEnquadIpi"] != "null"){
		$("#edClasseEnquadIpi").val(itemTempEdicao["IPI"]["classeEnquadIpi"]);
	}
	$("#edObsIpi").val(itemTempEdicao["IPI"]["obs"]);
	if (itemTempEdicao["IPI"]["pDevol"] > 0){
		$("#edpDevol").val(nroBra(itemTempEdicao["IPI"]["pDevol"]));
		$("#edvIPIDevol").val(nroBra(itemTempEdicao["IPI"]["vIPIDevol"]));
	}
	$("#edCEnq").val(itemTempEdicao["IPI"]["cEnq"]);
	if ($('#notaTipo').val() == 'C') {
		$('#link_aba_ipi').hide();
	} else {
		$('#link_aba_ipi').show();
	}

	// Dados do Issqn
	$("#edBaseIssqn").val(nroBraDecimais(itemTempEdicao["ISSQN"]["base"], 4));
	$("#edValorBaseIssqn").val(nroBra(itemTempEdicao["ISSQN"]["valorBaseCalculo"]));
	$("#edAliquotaIssqn").val(nroBra(itemTempEdicao["ISSQN"]["aliquota"]));
	$("#edValorIssqn").val(nroBra(itemTempEdicao["ISSQN"]["valorImposto"]));
	$("#edStIssqn").val(itemTempEdicao["ISSQN"]["st"]);
	$("#edTributacaoIssqn").val(itemTempEdicao["ISSQN"]["tributacao"]);
	if(itemTempEdicao["ISSQN"]["codListaServicos"] != "null" && itemTempEdicao["tipo"] == "S"){
		$("#edCodListaServicos").val(itemTempEdicao["ISSQN"]["codListaServicos"]);
	}
	$("#edObsIssqn").val(itemTempEdicao["ISSQN"]["obs"]);
	$("#selectDescontaISSDoTotalDaNota").val(itemTempEdicao["ISSQN"]["descontaISSDoTotalDaNota"]);
	$('#reterIss').val(itemTempEdicao['ISSQN']['reterIss']);
	$('#vISSRet').val(nroBra(itemTempEdicao['ISSQN']['vISSRet']));

	// Dados do Pis
	$("#edBasePis").val(nroBraDecimais(itemTempEdicao["PIS"]["base"], 4));
	$("#edValorBasePis").val(nroBra(itemTempEdicao["PIS"]["valorBaseCalculo"]));
	$("#edAliquotaPis").val(nroBra(itemTempEdicao["PIS"]["aliquota"]));
	$("#edValorPis").val(nroBra(itemTempEdicao["PIS"]["valorImposto"]));
	$("#edStPis").val(itemTempEdicao["PIS"]["st"]);
	$("#edTributacaoPis").val(itemTempEdicao["PIS"]["tributacao"]);
	$("#edObsPis").val(itemTempEdicao["PIS"]["obs"]);
	if (itemTempEdicao["PIS"]["valorPisFixo"] > 0){
		$("#edValorPisFixo").val(nroBraDecimais(itemTempEdicao["PIS"]["valorPisFixo"], 4));
	}

	// Dados do Cofins
	$("#edBaseCofins").val(nroBraDecimais(itemTempEdicao["COFINS"]["base"], 4));
	$("#edValorBaseCofins").val(nroBra(itemTempEdicao["COFINS"]["valorBaseCalculo"]));
	$("#edAliquotaCofins").val(nroBra(itemTempEdicao["COFINS"]["aliquota"]));
	$("#edValorCofins").val(nroBra(itemTempEdicao["COFINS"]["valorImposto"]));
	$("#edStCofins").val(itemTempEdicao["COFINS"]["st"]);
	$("#edTributacaoCofins").val(itemTempEdicao["COFINS"]["tributacao"]);
	$("#edObsCofins").val(itemTempEdicao["COFINS"]["obs"]);
	if (itemTempEdicao["COFINS"]["valorCofinsFixo"] > 0){
		$("#edValorCofinsFixo").val(nroBraDecimais(itemTempEdicao["COFINS"]["valorCofinsFixo"], 4));
	}

	// Dados do icms ST
	$("#edBaseIcmsST").val(nroBraDecimais(itemTempEdicao["ICMSST"]["base"], 4));
	$("#edValorBaseIcmsST").val(nroBra(itemTempEdicao["ICMSST"]["valorBaseCalculo"]));
	$("#edAliquotaIcmsST").val(nroBra(itemTempEdicao["ICMSST"]["aliquota"]));
	$("#edValorIcmsST").val(nroBra(itemTempEdicao["ICMSST"]["valorImposto"]));
	$("#modalidadeBaseCalculoIcmsST").val(itemTempEdicao["ICMSST"]["modalidadeBaseCalculo"]);
	$("#percentualAdicionado").val(nroBra(itemTempEdicao["ICMSST"]["percentualAdicionado"]));
	$("#valorPautaST").val(nroBraDecimais(itemTempEdicao["ICMSST"]["valorPauta"], 4));
	configurarModalidadeBCST(itemTempEdicao["ICMSST"]["modalidadeBaseCalculo"]);
	//configurarModalidadeBC(itemTempEdicao["ICMS"]["modalidadeBaseCalculo"]);

	// Dados do Pis ST
	$("#edValorBasePisST").val(nroBra(itemTempEdicao["PISST"]["valorBaseCalculo"]));
	$("#edAliquotaPisST").val(nroBra(itemTempEdicao["PISST"]["aliquota"]));
	$("#edValorPisST").val(nroBra(itemTempEdicao["PISST"]["valorImposto"]));

	// Dados do Cofins ST
	$("#edValorBaseCofinsST").val(nroBra(itemTempEdicao["COFINSST"]["valorBaseCalculo"]));
	$("#edAliquotaCofinsST").val(nroBra(itemTempEdicao["COFINSST"]["aliquota"]));
	$("#edValorCofinsST").val(nroBra(itemTempEdicao["COFINSST"]["valorImposto"]));

	// Dados do II
	$("#aba_ii").hide();
	if (tipo == "S") {
		itemTempEdicao["II"]["st"] = "02";
		itemTempEdicao["II"]["valorBaseCalculo"] = 0;
		itemTempEdicao["II"]["aliquota"] = 0;
		itemTempEdicao["II"]["valorImposto"] = 0;
		itemTempEdicao["II"]["valorDespesaAduaneira"] = 0;
		itemTempEdicao["II"]["tributacao"] = "isento";
		itemTempEdicao["II"]["nAdicao"] = 0;
	} else if ($('#notaTipo').val() == 'E') {
		$("#aba_ii").show();
	}
	$("#edValorBaseII").val(nroBra(itemTempEdicao["II"]["valorBaseCalculo"]));
	$("#edAliquotaII").val(nroBra(itemTempEdicao["II"]["aliquota"]));
	$("#edValorII").val(nroBra(itemTempEdicao["II"]["valorImposto"]))
	$("#edStII").val(itemTempEdicao["II"]["st"]);
	$("#edValorDespesaAduaneira").val(nroBra(itemTempEdicao["II"]["valorDespesaAduaneira"]));
	$("#edTributacaoII").val(itemTempEdicao["II"]["tributacao"]);
	$("#edNAdicao").val(itemTempEdicao["II"]["nAdicao"]);

	/*$("#edNDI").val(itemTempEdicao["nDI"]);
	$("#edDDI").val(itemTempEdicao["dDI"]);
	$("#edXLocDesemb").val(itemTempEdicao["xLocDesemb"]);
	$("#edUFDesemb").val(itemTempEdicao["UFDesemb"]);
	$("#edDDesemb").val(itemTempEdicao["dDesemb"]);
	$("#edCExportador").val(itemTempEdicao["cExportador"]);

	$("#edNAdicao").val(itemTempEdicao["nAdicao"]);
	$("#edNSeqAdicC").val(itemTempEdicao["nSeqAdicC"]);
	$("#edCFabricante").val(itemTempEdicao["cFabricante"]);
	$("#edVDescDI").val(itemTempEdicao["vDescDI"]);
	$("#edXPed").val(itemTempEdicao["xPed"]);
	$("#edNItemPed").val(itemTempEdicao["nItemPed"]);*/

	/* Simples */
	// Dados do Simples
	$("#edBaseSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["base"], 4));
	$("#edValorBaseSimples").val(nroBra(itemTempEdicao["SIMPLES"]["valorBase"]));
	$('#edBaseSimplesDiferimento').val(nroBraDecimais(itemTempEdicao['SIMPLES']['baseDiferimento'], 4));
	$("#edAliquotaSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["aliquota"], 4));
	$("#edValorSimples").val(nroBra(itemTempEdicao["SIMPLES"]["valorImposto"]));
	$("#valorPautaSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["valorPauta"], 4));
	$("#valorPautaSTSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["valorPautaST"], 4));
	$("#percentualAdicionadoSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["percentualAdicionadoST"], 4));
	$("#edBaseSTSimples").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["baseCalculoST"], 4));
	$("#edValorBaseSTSimples").val(nroBra(itemTempEdicao["SIMPLES"]["valorBaseCalculoST"]));
	$("#edAliquotaSTSimples").val(nroBra(itemTempEdicao["SIMPLES"]["aliquotaST"]));
	$("#edValorSTSimples").val(nroBra(itemTempEdicao["SIMPLES"]["valorImpostoST"]));
	$("#edAliquotaCredito").val(nroBra(itemTempEdicao["SIMPLES"]["aliquotaCredito"]));
	$("#edValorCredito").val(nroBra(itemTempEdicao["SIMPLES"]["valorCredito"]));
	$("#modalidadeBaseCalculoSimples").val(itemTempEdicao["SIMPLES"]["modalidadeBaseCalculo"]);
	$("#modalidadeBaseCalculoSTSimples").val(itemTempEdicao["SIMPLES"]["modalidadeBaseCalculoST"]);
	$("#edTributacaoSimples").val(itemTempEdicao["SIMPLES"]["tributacao"]);
	$("#edObsSimples").val(itemTempEdicao["SIMPLES"]["obs"]);

	$("#edBaseSTRetido").val(nroBraDecimais(itemTempEdicao["SIMPLES"]["baseSTRetido"], 4));
	$("#edValorBaseSTRetido").val(nroBra(itemTempEdicao["SIMPLES"]["valorBaseSTRetido"]));
	$("#edAliquotaSTRetido").val(nroBra(itemTempEdicao["SIMPLES"]["aliquotaSTRetido"]));
	$("#edValorSTRetido").val(nroBra(itemTempEdicao["SIMPLES"]["valorImpostoSTRetido"]));

	/* Simples */
	montarSelectSTICMS(itemTempEdicao["ICMS"]["st"]);
	montarSelectSTSimples(itemTempEdicao["SIMPLES"]["st"]);
	montarSelectSTIPI(itemTempEdicao["IPI"]["st"]);
	montarSelectSTISSQN(itemTempEdicao["ISSQN"]["st"]);
	montarSelectSTPIS(itemTempEdicao["PIS"]["st"]);
	montarSelectSTCOFINS(itemTempEdicao["COFINS"]["st"]);
	montarSelectSTII(itemTempEdicao["II"]["st"]);

	montarSelectSPED(itemTempEdicao["PIS"]["spedBaseCalculoCredito"], itemTempEdicao["PIS"]["spedTipoCredito"], itemTempEdicao["spedTipoItem"]);

	if ($("#crt").val() == 1) {
		$(".regime_normal").hide();
		$(".simples").show();
		configurarModalidadeBCSimples(itemTempEdicao["SIMPLES"]["modalidadeBaseCalculo"]);
		configurarModalidadeBCSTSimples(itemTempEdicao["SIMPLES"]["modalidadeBaseCalculoST"]);
		configurarCamposImpostos("SIMPLES", itemTempEdicao["SIMPLES"]["st"]);
	} else {
		$(".regime_normal").show();
		$(".simples").hide();
		$("#link_aba_icms_retencao").hide();
		configurarModalidadeBC(itemTempEdicao['ICMS']['modalidadeBaseCalculo']);
		configurarCamposImpostos("ICMS", itemTempEdicao["ICMS"]["st"]);
	}

	configurarCamposImpostos("IPI", itemTempEdicao["IPI"]["st"]);
	configurarCamposImpostos("ISSQN", itemTempEdicao["ISSQN"]["st"]);
	configurarCamposImpostos("PIS", itemTempEdicao["PIS"]["st"]);
	configurarCamposImpostos("COFINS", itemTempEdicao["COFINS"]["st"]);

	// Retenções
	$("#edRetImpostoRetido").val(itemTempEdicao["retImpostoRetido"]);
	$("#edRetBaseIR").val(nroBra(itemTempEdicao["retBaseIR"]));
	$("#edRetValorIR").val(nroBra(itemTempEdicao["retValorIR"]));
	$("#edRetAliquotaIR").val(nroBraDecimais(itemTempEdicao["retAliquotaIR"], 4));
	$("#edRetValorCSLL").val(nroBra(itemTempEdicao["retValorCSLL"]));
	$("#edRetAliquotaCSLL").val(nroBraDecimais(itemTempEdicao["retAliquotaCSLL"], 4));

	var camposBloqueio = [
		"edValorBaseIcms",
		"edValorIcms",
		/*"edvBCUFDest",
		"edvFCPUFDest",
		"edpICMSInter",
		"edpICMSInterPart",
		"edvICMSUFDest",
		"edvICMSUFRemet",*/
		"edValorBaseIssqn",
		"edValorIssqn",
		"edValorBasePis",
		"edValorPis",
		"edValorBaseCofins",
		"edValorCofins",
		"edValorIpi",
		"edValorBaseIcmsST",
		"edValorIcmsST",
		"edValorBasePisST",
		"edValorPisST",
		"edValorBaseCofinsST",
		"edValorCofinsST",
		"edValorBaseIpi",
		"edValorBaseII",
		"edValorII",
		"edValorDescontoItem",
		"edValorTotal",
		"edRetBaseIR",
		"edRetValorIR",
		"edRetValorCSLL",
		"edValorBaseSimples",
		"edValorSimples",
		"edValorCredito",
		"edValorBaseSTSimples",
		"edValorSTSimples",
		"edValorBaseSTRetido",
		"edValorSTRetido",
		"edValorCide",
		'edvFCP'
	];

	if ($("#calcularDifal").val() != "N"){
		camposBloqueio.push("edvBCUFDest");
		camposBloqueio.push("edvFCPUFDest");
		camposBloqueio.push("edpICMSInter");
		camposBloqueio.push("edpICMSInterPart");
		camposBloqueio.push("edvICMSUFDest");
		camposBloqueio.push("edvICMSUFRemet");
	}

	if ($("#calculaImpostos").val().toUpperCase() == "S") {
		$.each(camposBloqueio, function(key, campo){
			$("#" + campo).attr("readonly", "readonly");
		});
	} else {
		$.each(camposBloqueio, function(key, campo){
			$("#" + campo).removeAttr("readonly");
		});
		$("#edAlqIcmsPresumido").val("0");
	}
	$("#edOpFiscal").val(itemTempEdicao["natureza"]);
	$("#codigoNoFabricante").val(itemTempEdicao["codigoNoFabricante"]);
	$("#edCest").val(itemTempEdicao["cest"]);
	$('#indEscala').val(itemTempEdicao['indEscala']);
	$('#cBenef').val(itemTempEdicao['cBenef']);
	$('#CNPJFab').val(itemTempEdicao['CNPJFab']);

	$('#nLote').val(itemTempEdicao['nLote']);
	$('#dFab').val(itemTempEdicao['dFab']);
	$('#dVal').val(itemTempEdicao['dVal']);
	$('#cAgreg').val(itemTempEdicao['cAgreg']);

	if(typeof itemTempEdicao["arma_serie"] != 'undefined'){
		var key;
		for (key in itemTempEdicao["arma_cano"]) {
			adicionarLinhaArma(itemTempEdicao["arma_serie"][key],itemTempEdicao["arma_cano"][key]);
		}
	}

	$("#edNDraw").val(itemTempEdicao["nDraw"]);
	$("#edNRE").val(itemTempEdicao["nRE"]);
	$("#edChNFe").val(itemTempEdicao["chNFe"]);
}

function configurarModalidadeBC(tipo) {
	if (tipo == "1") {
		$("#valorPauta").parent().show();
	} else {
		$("#valorPauta").parent().hide();
	}
}

function configurarModalidadeBCST(tipo) {
	if (tipo == "5") {
		$("#valorPautaST").parent().show();
	} else {
		$("#valorPautaST").parent().hide();
	}
}

function configurarModalidadeBCSimples(tipo) {
	if (tipo == "1") {
		$("#valorPautaSimples").parent().show();
	} else {
		$("#valorPautaSimples").parent().hide();
	}
}

function configurarModalidadeBCSTSimples(tipo) {
	if (tipo == "5") {
		$("#valorPautaSTSimples").parent().show();
	} else {
		$("#valorPautaSTSimples").parent().hide();
	}
}

function setarImpostosDoItem(contItem,aImpostosRegras){
	arrayItens[contItem]["ICMS"] = aImpostosRegras.ICMS;
	arrayItens[contItem]["IPI"] = aImpostosRegras.IPI;
	arrayItens[contItem]["ISSQN"] = aImpostosRegras.ISSQN;
	arrayItens[contItem]["PIS"] = aImpostosRegras.PIS;
	arrayItens[contItem]["COFINS"] = aImpostosRegras.COFINS;
	arrayItens[contItem]["ICMSST"] = aImpostosRegras.ICMSST;
	arrayItens[contItem]["PISST"] = aImpostosRegras.PISST;
	arrayItens[contItem]["COFINSST"] = aImpostosRegras.COFINSST;
	arrayItens[contItem]["II"] = aImpostosRegras.II;
	arrayItens[contItem]["SIMPLES"] = aImpostosRegras.SIMPLES;
	itemTempEdicao["faturada"] = aImpostosRegras.faturada;
}

function setarImpostosDoItemTempEdicao(idItem,aImpostosRegras){
	if (! aImpostosRegras.ok) {
		exibirMensagemValidacao(aImpostosRegras.mensagem);
	}
	aImpostosRegras = aImpostosRegras.dados;
	itemTempEdicao["ICMS"] = aImpostosRegras.ICMS;
	itemTempEdicao["IPI"] = aImpostosRegras.IPI;
	itemTempEdicao["ISSQN"] = aImpostosRegras.ISSQN;
	itemTempEdicao["PIS"] = aImpostosRegras.PIS;
	itemTempEdicao["COFINS"] = aImpostosRegras.COFINS;
	itemTempEdicao["ICMSST"] = aImpostosRegras.ICMSST;
	itemTempEdicao["PISST"] = aImpostosRegras.PISST;
	itemTempEdicao["COFINSST"] = aImpostosRegras.COFINSST;
	itemTempEdicao["II"] = aImpostosRegras.II;
	itemTempEdicao["SIMPLES"] = aImpostosRegras.SIMPLES;
	itemTempEdicao["faturada"] = aImpostosRegras.faturada;
	itemTempEdicao["obs"] = aImpostosRegras.obs;
	itemTempEdicao["cfop"] = aImpostosRegras.cfop;
	itemTempEdicao["cfop"] = aImpostosRegras.cfop;
	itemTempEdicao["alqValorAproxImpostos"] = aImpostosRegras.alqValorAproxImpostos;
	itemTempEdicao['base_comissao'] = aImpostosRegras.base_comissao;
	montarDadosParaEdicaoItem(idItem);
}

function incluirEEditarItem() {
	addDetailItem(false);
	displayWait("pleasewait");
	$('#botaoSalvar').attr('disabled', true);
	xajax_calcularImpostos(xajax.getFormValues('formNotaFiscal', true), JSON.stringify(arrayItens), (contItens - 1), "I");
}

function addDetailItem(validar){
	if (!validar || ((($("#produto").val() != "") && ($("#quantidade").val() != "") && (nroUsa($("#quantidade").val()) > 0) && ($("#precounitario").val() != "")))) {

		var valorDeduzir = 0;
		if ($("#parcelaNumber").val() > 0) {
			if (idCampoIPI != "") {
				valorDeduzir = parseFloat(nroUsa($("#" + idCampoIPI).val()));
			}
			if (idCampoST != "") {
				valorDeduzir += parseFloat(nroUsa($("#" + idCampoST).val()));
			}
			if (valorDeduzir > 0){
				var valorPrimParc = nroBra(parseFloat(nroUsa($("#valor0").val())) - valorDeduzir);
				$("#valor0").val(valorPrimParc);
			}
		}

		$("#produto").removeClass("ac_error");
		$("#produto").addClass("tipsyOff");
		$("#produto").removeAttr("title");
		$("#quantidade").change();
		$("#precounitario").change();
		$("#precoLista").change();

		$('#linhaInclusaoItem').hide();
		itemTemp["cf"] = $("#cf").val();
		itemTemp["cfop"] = $("#cfop").val();
		itemTemp["buscarImpostos"] = "S";
		itemTemp["faturada"] = $("#faturada").val();
		itemTemp["consumidor_final"] = $("#indFinal").prop("checked")?"S":"N";
		itemTemp["somaIcmsTotalNota"] = "N";
		itemTemp["somaImpostosTotalNota"] = "N";
		if(itemTemp["origem"] == undefined){
			itemTemp["origem"] = "0";
		}
		if(itemTemp["tipo"] == undefined){
			itemTemp["tipo"] = "P";
		}
		if (itemTemp["valorDescontoItem"] == undefined) {
			itemTemp["valorDescontoItem"] = "0,00";
		}
		if (itemTemp["alq_comissao"] == undefined) {
			itemTemp["alq_comissao"] = "0,00";
		}
		if (itemTemp["qtdeCombAmbiente"] == undefined) {
			itemTemp["qtdeCombAmbiente"] = "0.0000";
		}
		if (itemTemp["baseCide"] == undefined) {
			itemTemp["baseCide"] = "0.0000";
		}
		if (itemTemp["alqCide"] == undefined) {
			itemTemp["alqCide"] = "0.0000";
		}
		if (itemTemp["valorCide"] == undefined) {
			itemTemp["valorCide"] = "0.00";
		}

		var tmpComissao = comissaoGetComissao(itemTemp["precoLista"], itemTemp["valorUnitario"]);
		itemTemp["alq_comissao"] = nroBra(tmpComissao.toString());
		itemTemp["base_comissao"] = itemTemp["valorTotal"];

		var comisDesconto = parseFloat(nroUsa(itemTemp["valorDescontoItem"])) * parseFloat(nroUsa(itemTemp["alq_comissao"])) / 100;
		itemTemp["vlr_comissao"] = nroBra((parseFloat(nroUsa(itemTemp["base_comissao"])) * parseFloat(nroUsa(itemTemp["alq_comissao"])) / 100) - comisDesconto);

		if ($("#cadastrarProdutoAutomaticamente").val() == "S") {
			itemTemp["equivalenteTipo"] = "N";
		} else {
			itemTemp["equivalenteTipo"] = "X";
		}

		itemTemp["unEstoque"] = $("#un").val();
		itemTemp["qtdeEstoque"] = $("#quantidade").val();
		itemTemp["unProdutoEquivalente"] = "";
		itemTemp["qtdeProdutoEquivalente"] = $("#quantidade").val();

		itemTemp["estoqueAtual"] = estoqueAtual;
		itemTemp["estoqueMinimo"] = estoqueMinimo;
		itemTemp["estoqueMaximo"] = estoqueMaximo;
		itemTemp["tipoProduto"] = tipoProduto;

		adicionarItemNaTela(contItens,itemTemp);
		arrayItens[contItens] = itemTemp;
		arrayItens[contItens]['idOperacaoFiscal'] = $('#idTipoNota').val();
		arrayItens[contItens]['natureza'] = $("#natureza").val();

		if ($('#calculaImpostos').val() == 'N') {
			xajax_obterImpostosDoItem($("#edIdOperacaoFiscal").val(),$("#idMunicipio").val(),$("#uf").val(),contItens,itemTemp, tipo, $("#calculaImpostos").val(), $("#crt").val(), $('#loja').val(), $('#idConfUnidadeNegocio').val(), function (data) {
				if (data.error != '') {
					requisicaoEmAndamento = false;
					alert(data.error);
					return false;
				}
				setarImpostosDoItem(data.item, data.impostos.dados)
				requisicaoEmAndamento = false;
			});
		}

		contItens++;

		delete itemTemp;
		$("#produto").val("");
		$("#codigo").val("");
		$("#un").val("");
		$("#quantidade").val("");
		$("#precounitario").val("");
		$("#precoLista").val('0');
		$("#precototal").val("");

		calcularTotalProdutos();
		if (validar) {
			calcularImpostos("I");
		}

		atribuirEventoHighlight();
	}
}

function atualizarNroItens(acao){
	var nroItens = parseInt($("#nroItens").val());
	if(acao == "soma"){
		nroItens ++;
	}else{
		nroItens --;
	}
	$("#nroItens").val(nroItens);
}

function calcularTotalProdutos(){
	var totalProdutos = 0;
	var totalServicos = 0;
	//var nroItens
	$.each(arrayItens, function(id,objItem){
		if (objItem != undefined){
			if (objItem.tipo == "S"){
				if (objItem.valorTotal != undefined){
					totalServicos = totalServicos + parseFloat(nroUsa(objItem.valorTotal));
				} else {
					totalServicos = totalServicos + 0;
				}
			}else{
				if (objItem.valorTotal != undefined){
					totalProdutos = totalProdutos + parseFloat(nroUsa(objItem.valorTotal));
				} else {
					totalProdutos = totalProdutos + 0;
				}
			}
		}
	});
	$("#valorProdutos").val(nroBra(totalProdutos));
	$("#valorServicos").val(nroBra(totalServicos));
}

function removeItemProduto(id){
	if ($('#situacao').val() != 1 && $('#situacao').val() != 5 && $('#situacao').val() != 8) {
		return false;
	}
	if(id == -1){
		$("#linhaInclusaoItem").hide();
	}else{
		var valorDeduzir = 0;
		if ($("#parcelaNumber").val() > 0) {
			if (idCampoIPI != "") {
				valorDeduzir = parseFloat(nroUsa($("#" + idCampoIPI).val()));
			}
			if (idCampoST != "") {
				valorDeduzir += parseFloat(nroUsa($("#" + idCampoST).val()));
			}
			if (valorDeduzir > 0){
				var valorPrimParc = nroBra(parseFloat(nroUsa($("#valor0").val())) - valorDeduzir);
				$("#valor0").val(valorPrimParc);
			}
		}

		delete arrayItens[id];
		$("#item"+id).remove();
		atualizarNroItens("subtrai");
		calcularTotalProdutos();
		calcularImpostos("N");
	}

	var nroItens = $("#tItensNota > tbody > tr[class='linhaItemNota']:visible").length;
	if(nroItens == 0){
		$("#linhaInclusaoItem").show();
	}
}

function calcularValorParcialItem(){
	var qtd = parseFloat(nroUsa($("#quantidade").val()));
	var preco = parseFloat(nroUsa($("#precounitario").val()));
	var total = nroBra(qtd * preco);
	if($("#quantidade").val()!="" && $("#precounitario").val()){
		itemTemp["valorTotal"] = total;
		$("#precototal").val(total);
		itemTemp["base_comissao"] = total;
		//$("#base_comissao").val(total);
	}
}

function setIdOpFiscalEdicao(param){
	$("#edOpFiscal").val(param.value);
	$("#edOpFiscal").removeClass("ac_error");
	$("#edOpFiscal").addClass("tipsyOff");
	$("#edOpFiscal").removeAttr("title");
	$("#edIdOperacaoFiscal").val(param.id);
	itemTempEdicao["idOperacaoFiscal"] = param.id;
	itemTempEdicao["natureza"] = $("#edOpFiscal").val();
	xajax_obterImpostosDoItem($("#edIdOperacaoFiscal").val(),$("#idMunicipio").val(),$("#uf").val(),idItemEdicao,itemTempEdicao, tipo, $("#calculaImpostos").val(), $("#crt").val(), $('#loja').val(), $('#idConfUnidadeNegocio').val(), function (data) {
		if (data.error != '') {
			requisicaoEmAndamento = false;
			alert(data.error);
			return false;
		}
		setarImpostosDoItemTempEdicao(data.item, data.impostos)
		requisicaoEmAndamento = false;
	});
}

function atualizarImpostoItem(){
	if ($("#calculaImpostos").val() == "S"){
		itemTempEdicao["idOperacaoFiscal"] = $("#edIdOperacaoFiscal").val();
		itemTempEdicao["natureza"] = $("#edOpFiscal").val();
		requisicaoEmAndamento = true;
		xajax_obterImpostosDoItem($("#edIdOperacaoFiscal").val(),$("#idMunicipio").val(),$("#uf").val(),idItemEdicao,itemTempEdicao, tipo, $("#calculaImpostos").val(), $("#crt").val(), $('#loja').val(), $('#idConfUnidadeNegocio').val(), function (data) {
			if (data.error != '') {
				requisicaoEmAndamento = false;
				alert(data.error);
				return false;
			}
			setarImpostosDoItemTempEdicao(data.item, data.impostos)
			requisicaoEmAndamento = false;
		});
	}
}

function salvarItemEdicao(){
	if (requisicaoEmAndamento == true){
		setTimeout('salvarItemEdicao()', 200);
		return false;
	}
	$.each($("#div_veiculo [name]"), function(key, campo){
		itemTempEdicao[$(campo).attr("name")] = $(campo).val();
	});
	var valorDeduzir = 0;
	if ($("#parcelaNumber").val() > 0) {
		if (idCampoIPI != "") {
			valorDeduzir = parseFloat(nroUsa($("#" + idCampoIPI).val()));
		}
		if (idCampoST != "") {
			valorDeduzir += parseFloat(nroUsa($("#" + idCampoST).val()));
		}
		if (valorDeduzir > 0){
			var valorPrimParc = nroBra(parseFloat(nroUsa($("#valor0").val())) - valorDeduzir);
			$("#valor0").val(valorPrimParc);
		}
	}

	var tmpAlqComissao = itemTempEdicao["alq_comissao"];
	var tmpValorComissao = itemTempEdicao["vlr_comissao"];
	var tmpBaseComissao = itemTempEdicao["base_comissao"];

	$(".edQuatroDecimais").change();
	$(".edDuasDecimais").change();

	$("#edPesoBruto").change();
	$("#edPesoLiq").change();
	$("#edQuantidade").change();
	$("#edValorUnitario").change();

	itemTempEdicao["alq_comissao"] = tmpAlqComissao;
	itemTempEdicao["vlr_comissao"] = tmpValorComissao;
	itemTempEdicao["base_comissao"] = tmpBaseComissao;

	itemTempEdicao["estoqueAtual"] = estoqueAtual;
	itemTempEdicao["estoqueMinimo"] = estoqueMinimo;
	itemTempEdicao["estoqueMaximo"] = estoqueMaximo;
	itemTempEdicao["tipoProduto"] = tipoProduto;

	itemTempEdicao["II"]["nAdicao"] = $("#edNAdicao option:selected").val();

	var linhaEditada = idItemEdicao;
	delete arrayItens[linhaEditada];
	arrayItens[linhaEditada] = {};
	arrayItens[linhaEditada] = jQuery.extend(true, {}, itemTempEdicao);
	delete itemTempEdicao;
	linha = $("#item"+linhaEditada);
	linha.html("");
	linhaItem(linhaEditada,arrayItens[linhaEditada],linha,"N");
	closeMessage();
	//$("#aNovaLinhaItem").focus();

	var contFaturada = 0;
	$.each(arrayItens, function(key, value){
		if (value != null){
			if(value.faturada == "S"){
				contFaturada ++;
			}
		}
	});
	if(contFaturada == 0){
		limparParcelas();
	}

	if ($("#calculaImpostos").val() == "S"){
		calcularImpostos("I");
	}
}

function atualizarItemTemp(campo, valor){
	itemTempEdicao[campo] = valor;

	if(itemTempEdicao['faturada'] == 'S') {
		document.getElementById('edFaturada').checked = true;
	} else {
		document.getElementById('edFaturada').checked = false;
	}
}

function atualizarItemTempArma(campo, valor){
	if(typeof itemTempEdicao[campo] == 'undefined'){
		itemTempEdicao[campo] = [];
	}
	itemTempEdicao[campo][itemTempEdicao[campo].length] = valor;
}

function atualizarItemFaturada(campo, valor){
	if(valor.checked == true) {
		itemTempEdicao[campo] = 'S';
	} else if(valor.checked == false) {
		itemTempEdicao[campo] = 'N';
	}
}


function atualizarItemTempImposto(imposto, campo, valor){

	if(itemTempEdicao[imposto]==undefined){itemTempEdicao[imposto] = {};}

	itemTempEdicao[imposto][campo] = valor;
	if (campo == "st" && valor != "Selecione") {
		configurarCamposImpostos(imposto, valor);
	}
}

function configurarCamposImpostos(imposto, valor) {
	var arrayImposto = getArrayImposto(imposto);

	if ((valor == "") || (! valor)) {
		valor = "00";
	}

	var sImposto = "";
	switch (imposto) {
	case "ICMS":
		sImposto = "Icms";
		if (arrayImposto[valor].possuiST) {
			$("#tabnav_icms").parent().show();
		}
		$("#link_aba_icms_retencao").hide();
		break;
	case "IPI":
		sImposto = "Ipi";
		break;
	case "ISSQN":
		sImposto = "Issqn";
		break;
	case "PIS":
		sImposto = "Pis";
		break;
	case "COFINS":
		sImposto = "Cofins";
		break;
	case "ICMSST":
		sImposto = "IcmsST";
		break;
	case "PISST":
		sImposto = "PisST";
		break;
	case "COFINSST":
		sImposto = "CofinsST";
		break;
	case "II":
		sImposto = "II";
		break;
	case "SIMPLES":
		sImposto = "Simples";

		if ((valor == "") || (! valor) || (valor == "00")) {
			valor = "400";
		}

		break;
	}

	if ($("#calculaImpostos").val().toUpperCase() == "S") {
		if ((imposto != "PISST") && (imposto != "COFINSST") && (imposto != "II")) {
			try {
				if (arrayImposto[valor].reducaoBase) {
					if (!(($('#crt').val() == 1 && sImposto == 'ICMS') || ($('#crt').val() != 1 && sImposto == 'Simples'))) {
						$("#edBase" + sImposto).parent().show();
						$("#edValorBase" + sImposto).parent().show();
					}
				} else {
					$("#edBase" + sImposto).parent().hide();
					$("#edValorBase" + sImposto).parent().hide();
					$("#edBase" + sImposto).val("100,00");
				}

				if (imposto == 'ICMS') {
					$('#vICMSDeson').parent().hide();
					$('#motDesICMS').parent().hide();
					if (valor == '40') {
						$("#edBase" + sImposto).parent().hide();
						$("#edValorBase" + sImposto).parent().hide();
						$("#edBase" + sImposto).val("0,00");
						$("#edValorBase" + sImposto).val("0,00");
						$("#edAlqIcmsPresumido").parent().hide();
						$("#edValorPresumido").parent().hide();
						$('#edAliquotaIcms').parent().hide();
						$('#edValorIcms').parent().hide();
						$('#edAlqPosicao').parent().hide();
						$('#edpFCP').parent().hide();
						$('#edvFCP').parent().hide();
						$('#modalidadeBaseCalculo').parent().hide();
						$('#vICMSDeson').parent().show();
						$('#motDesICMS').parent().show();
					} else if ((valor == "30") || (valor == "41") || (valor == "50")) {
						$("#edBase" + sImposto).parent().hide();
						$("#edValorBase" + sImposto).parent().hide();
						$("#edBase" + sImposto).val("0,00");
						$("#edValorBase" + sImposto).val("0,00");
						$("#edAlqIcmsPresumido").parent().show();
						$("#edValorPresumido").parent().hide();
					} else {
						$("#edAlqIcmsPresumido").parent().show();
						$("#edValorPresumido").parent().hide();
					}
				}

				if (imposto == 'ICMS') {
					if ((valor == '51') || (valor == '90')) {
						$("#edBaseIcmsDiferimento").parent().show();
					} else {
						$("#edBaseIcmsDiferimento").parent().hide();
						$("#edBaseIcmsDiferimento").val("0,00");
					}
				} else if (imposto == 'SIMPLES') {
					if (valor == '900') {
						$('#edBaseSimplesDiferimento').parent().show();
					} else {
						$('#edBaseSimplesDiferimento').parent().hide();
						$('#edBaseSimplesDiferimento').val('0,00');
					}
				}

			} catch(err) {
				$("#edBase" + sImposto).parent().hide();
				$("#edValorBase" + sImposto).parent().hide();
				$("#edBase" + sImposto).val("100,00");
				if (imposto == 'ICMS') {
					$("#edBaseIcmsDiferimento").parent().hide();
					$("#edBaseIcmsDiferimento").val("0,00");
				} else if (imposto == 'SIMPLES') {
					$('#edBaseSimplesDiferimento').parent().hide();
					$('#edBaseSimplesDiferimento').val('0,00');
				}
			}
		} else {
			$("#edBase" + sImposto).parent().hide();
			$("#edValorBase" + sImposto).parent().hide();
			$("#edBase" + sImposto).val("100,00");
			if (imposto == 'ICMS' || imposto == 'SIMPLES') {
				$("#edBaseIcmsDiferimento").parent().hide();
				$("#edBaseIcmsDiferimento").val("0,00");
			} else if (imposto == 'SIMPLES') {
				$('#edBaseSimplesDiferimento').parent().hide();
				$('#edBaseSimplesDiferimento').val('0,00');
			}
		}
	} else {
		$("#edBase" + sImposto).parent().show();
		$("#edValorBase" + sImposto).parent().show();
		if ($('#crt').val() == 1) {
			$('#edBaseSimplesDiferimento').parent().show();
		} else {
			$("#edBaseIcmsDiferimento").parent().show();
		}
		$("#edValorBaseII").parent().show();
		$("#edAlqIcmsPresumido").parent().hide();
		$("#edValorPresumido").parent().show();
	}

	if ($('#indFinal').prop('checked') == true && $('#indIEDest').val() == 9) {
		$('#link_aba_icms_partilha').show();
	} else {
		$('#link_aba_icms_partilha').hide();
	}

	if ((imposto == "SIMPLES") && ($("#crt").val() == 1)) {
		if (valor == 0) {
			valor = 400;
		}
		if (arrayImposto[valor].reducaoBase) {
			$("#edBase" + sImposto).parent().show();
			$("#edValorBase" + sImposto).parent().show();
		} else {
			$("#edBase" + sImposto).parent().hide();
			$("#edValorBase" + sImposto).parent().hide();
			$("#edBase" + sImposto).val("100,00");
		}

		if (arrayImposto[valor].possuiST) {
			$(".st").parent().show();
			$("#link_aba_icms_st").show();
		} else {
			$("#link_aba_icms_st").hide();
			$("#edBaseSTSimples").val("0,0000");
			$("#edValorBaseSTSimples").val("0,0000");
			$("#percentualAdicionadoSimples").val("0,0000");
			$("#edValorBaseSTSimples").val("0,00");
			$("#edValorSTSimples").val("0,00");
			$(".st").parent().hide();
		}

		configurarModalidadeBCSTSimples($("#modalidadeBaseCalculoSTSimples").val());

		if (arrayImposto[valor].retencao) {
			$(".retido").parent().show();
			$("#link_aba_icms_retencao").show();
		} else {
			$("#link_aba_icms_retencao").hide();
			$("#edBaseSTRetido").val("0,0000");
			$("#edValorBaseSTRetido").val("0,00");
			$("#edAliquotaSTRetido").val("0,0000");
			$("#edValorSTRetido").val("0,00");
			$(".retido").parent().hide();
		}

		if (valor == 500) {
			$("#edBaseSTSimples").val("0,0000");
			$("#edValorBaseSTSimples").val("0,0000");
			$("#percentualAdicionadoSimples").val("0,0000");
			$("#edValorBaseSTSimples").val("0,00");
			$("#edValorSTSimples").val("0,00");
			$(".st").parent().hide();
			$("#link_aba_icms_st").hide();
		}

		if (arrayImposto[valor].credito) {
			$("#edAliquotaCredito").parent().show();
			$("#edValorCredito").parent().show();
		} else {
			$("#edAliquotaCredito").val("0,0000");
			$("#edValorCredito").val("0,00");
			$("#edAliquotaCredito").parent().hide();
			$("#edValorCredito").parent().hide();
		}

		if (arrayImposto[valor].icms) {
			$("#edAliquotaSimples").parent().show();
			$("#edValorSimples").parent().show();
			$("#modalidadeBaseCalculoSimples").parent().show();
		} else {
			$("#edBaseSimples").val("0,00");
			$("#edAliquotaSimples").val("0,0000");
			$("#edValorSimples").val("0,00");
			$("#edAliquotaSimples").parent().hide();
			$("#edValorSimples").parent().hide();
			$("#modalidadeBaseCalculoSimples").parent().hide();
		}

		if (arrayImposto[valor].retencao || arrayImposto[valor].possuiST) {
			$("#tabnav_icms").show();
		}
		$("#link_aba_icms_geral").click();
	}

	try {
		$("#edTributacao" + sImposto).val(arrayImposto[valor].tributacao);
	} catch(err) {
		$("#edTributacao" + sImposto).val("isento");
	}

	try {
		atualizarItemTempImposto(imposto, "base", nroUsa($("#edBase" + sImposto).val()));
	} catch(e) {
		//debug(e);
	}
	try {
		atualizarItemTempImposto(imposto, "tributacao", $("#edTributacao" + sImposto).val());
	} catch(e) {
		//debug(e);
	}

}

function getArrayImposto(imposto) {
	switch (imposto) {
	case "ICMS":
		return arrayStIcms;
		break;
	case "IPI":
		return arrayStIpi;
		break;
	case "ISSQN":
		return arrayStIssqn;
		break;
	case "PIS":
		return arrayStPis;
		break;
	case "COFINS":
		return arrayStCofins;
		break;
	case "II":
		return arrayStII;
		break;
	case "SIMPLES":
		return arrayStSimples;
		break;
	}
}

function cancelarEdicaoItem(){
	itemTempEdicao = {};
	closeMessage();
	$("#edDescricao").removeClass("ac_error");
	$("#edDescricao").addClass("tipsyOff");
	$("#edDescricao").removeAttr("title");
	$("#edOpFiscal").removeClass("ac_error");
	$("#edOpFiscal").addClass("tipsyOff");
	$("#edOpFiscal").removeAttr("title");
	$("#produtoEquivalente").removeClass("ac_error");
	$("#produtoEquivalente").addClass("tipsyOff");
	$("#produtoEquivalente").removeAttr("title");
	$('.icon-info-novo').popover('hide');
}

function limparFormEdicaoItem() {
	$('.date-pick').datepicker("hide");
	$("#form_edicao_item :text").val("");
	$("#form_edicao_item :text").removeClass("warning");
	$("#mensagem").removeClass("warn");
	$("#mensagem").html("");
	$("#nroSerieArmaCano tbody").html("");
	$("#form_edicao_item select").val("");
	$("#icones_gtin, #icones_gtin_embalagem").empty();
//	idItemEdicao = 0;
}

function closeMessage(){
	Boxy.get("#controls-popup").hide();
}

function inicializarItemTemp(){
	itemTemp = {};
	itemTemp["id"] = "";
	itemTemp["descricao"] = "";
	itemTemp["codigo"] = "";
	itemTemp["un"] = "";
	itemTemp["quantidade"] = "";
	itemTemp["valorUnitario"] = "";
	itemTemp["valorTotal"] = "";
}

function calcularImpostos(origem) {
	$("#mensagem").html("").removeClass("warn").addClass("nomessage");
	if ($("#calculaImpostos").val() != "N"){
		if (contItens > 0){
			displayWait("pleasewait", true);
			$('#botaoSalvar').attr('disabled', true);
			xajax_calcularImpostos(xajax.getFormValues('formNotaFiscal', true), JSON.stringify(arrayItens), -1, origem);
		}
	} else {
		$("#valorDesconto").val($("#desconto").val());
	}
}

function calcularTotalItemEdicao(){
	var qtd = parseFloat(nroUsa($("#edQuantidade").val()));
	var valor = parseFloat(nroUsa($("#edValorUnitario").val()));
	var total = (qtd * 10) * (valor * 10) / (100);
	var alqComissaoItem = parseFloat(nroUsa($("#edAlqComissao").val()));
	$("#edValorTotal").val(nroBra(total));
	$("#edBaseComissao").val(nroBra(total));
	$("#edValorComissao").val(nroBra(total * alqComissaoItem / 100));
	atualizarItemTemp('valorTotal',nroBra(total));
	atualizarItemTemp('base_comissao', nroBra(total));
	atualizarItemTemp('vlr_comissao', $("#edValorComissao").val());
	if ($('#calculaImpostos').val() == 'S') {
		zerarTotaisImpostosItemEdicao();
	}
}

function zerarTotaisImpostosItemEdicao(){
	$("#edValorBaseIcms").val('0,00');
	$("#edValorIcms").val('0,00');
	$("#edValorBaseIpi").val('0,00');
	$("#edValorIpi").val('0,00');
	$("#edValorBaseIssqn").val('0,00');
	$("#edValorIssqn").val('0,00');
	$("#edValorPis").val('0,00');
	$("#edValorCofins").val('0,00');
	$("#edValorIcmsST").val('0,00');
	$("#edValorPisST").val('0,00');
	$("#edValorCofinsST").val('0,00');
	$("#edValorII").val('0,00');
	$("#edValorDespesaAduaneira").val('0,00');
	$("#edValorPisFixo").val('');
	$("#edValorCofinsFixo").val('0,00');
}

function buscarDadosTransportador(param){
	xajax_buscarDadosTransportador(param.id);
	$('#transportador').focus();
}

function setIdTipoNota(param){
	idOperacaoAnterior = $("#idTipoNotaAnterior").val();
	$("#nroItens").val(0);
	$("#natureza").removeClass("warning");
	$("#natureza").removeClass("ac_error");
	$("#natureza").addClass("tipsyOff");
	$("#natureza").removeAttr("title");
	$("#faturada").val(param.faturada);
	$("#indFinal").prop("checked", param.consumidor == "S");
	$("#idTipoNota").val(param.id);
	$("#idTipoNotaAnterior").val(param.id);
	$("#natureza").val(param.value);
	$("#trocaManualDeOperacaoFiscal").val("S");

	$("#crt").val(param.crt);
	$("#indPres").val(param.indPres);
	//$("#crt").change();
	$(".linhaItemNota").remove();
	montarItensTela();

	$("#simples").val(param.simples);
	$("#alqSimples").val(param.alqSimples);

	var idNota = $("#id").val();
	if (idNota == "0"){
		$("#serie").val(param.serie);
		if ($("#notaTipo").val() != "T") {
			xajax_obterProximoNumeroDeNota(param.serie, $("#notaTipo").val(), $("#loja").val(), $("#idConfUnidadeNegocio").val());
		}
	}
	alterarOperacoesDosItens(param.id,param.faturada);

	if (param.faturada == 'N') {
		apagarParcelas();
	}

	$("#obsSistema").val(param.obs);

	if (param.devolucao == 'S') {
		$('#finalidade').val(4);
		exibeOcultaDocReferenciado();
	}

	if (tipo == "E") {
		if (param.compraDeProdutorRural == "S") {
			$("#doc_referenciado").show();
		}
	}

	$('#natureza').focus();
}

function alterarOperacoesDosItens(idNovaOperacao, faturada){
	$("#natureza").val();
	$.each(arrayItens, function(id,objItem){
		if(objItem!=undefined){
			if(objItem.idOperacaoFiscal == idOperacaoAnterior){
				objItem.idOperacaoFiscal = idNovaOperacao;
				objItem.natureza = $("#natureza").val();
				objItem.faturada = faturada;
				objItem.buscarImpostos = "S";
			}
		}
	});
	calcularImpostos("I");
}

function setarEntradaSaida(es){
	$('#tipo').val(es);
	if (es == "S") {
		$("#lblData").html("Data saída");
		$("#lblHora").html("Hora saída");
		$("#hContato").html("Destinatário");
		$("#lbl_tipo_imp").html("Tipo de Saída");
		$("#opc_imp").html("Exportação");
	} else {
		$("#lblData").html("Data entrada");
		$("#lblHora").html("Hora entrada");
		$("#hContato").html("Remetente");
		$("#lbl_tipo_imp").html("Tipo de Entrada");
		$("#opc_imp").html("Importação");
		$("#opc_nfce").remove();
	}
}

function configurarTipoNormalExterno(atualizarNumero, idConfUnidadeNegocio) {
	var versao = 2;

	if(idConfUnidadeNegocio === undefined || idConfUnidadeNegocio == null ){
		idConfUnidadeNegocio = 0;
	}

	if (idConfUnidadeNegocio == 0) {
		idConfUnidadeNegocio = $("#idConfUnidadeNegocio").val();
	}
	if ($("#notaTipo").val() == "C"){
		versao = $("#nfce_versao").val();
		$('#tipoAmbiente').val($('#tipoAmbienteNfce').val());
	} else {
		versao = $("#nfe_versao").val();
		$('#tipoAmbiente').val($('#tipoAmbienteNfe').val());
	}
	if (tipo == "E") {
		$("#div_exportacao").hide();
		if ($("#notaTipo").val() == "E") {
			$("#declaracao_importacao").show();
		} else {
			$("#declaracao_importacao").hide();
		}
		if ($("#id").val() == 0) {
			$("#numero").val("").attr('placeholder', '');
			if ($("#notaTipo").val() != "T") {
				xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo").val(), $("#loja").val(), idConfUnidadeNegocio);
			}
		}
		$("#opc_nfce").remove();
	} else {
		$("#declaracao_importacao").hide();
		if ($("#notaTipo").val() == "E") {
			$("#div_exportacao").show();
			if ($("#id").val() == 0) {
				xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo").val(), $("#loja").val(), idConfUnidadeNegocio);
			} else if (atualizarNumero == "S"){
				xajax_obterOperacaoPadrao("S", $("#loja").val());
			}
		} else if ($("#notaTipo").val() == "C"){
			$("#div_exportacao").hide();
			if ($("#id").val() == 0) {
				xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo").val(), $("#loja").val(), idConfUnidadeNegocio);
			} else if (atualizarNumero == "S") {
				xajax_obterOperacaoPadrao("C", $("#loja").val());
			}
		} else if ($("#notaTipo").val() == "N"){
			$("#div_exportacao").hide();
			if ($("#id").val() == 0) {
				xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo").val(), $("#loja").val(), idConfUnidadeNegocio);
			} else if (atualizarNumero == "S") {
				xajax_obterOperacaoPadrao("S", $("#loja").val());
			}
		} else {
			$("#div_exportacao").hide();
		}
	}
}

function setNaturezaOp(param){
	$('#natureza').removeClass("ac_error");
	$('#natureza').addClass("tipsyOff");
	$('#natureza').removeAttr("title");
	document.getElementById('natureza').value = param.id+"...";
}

function setNaturezaOpServ(param){
	document.getElementById('natureza_servicos').value = param.id+"...";
	$('#natureza_servicos').removeClass("ac_error");
	$('#natureza_servicos').addClass("tipsyOff");
	$('#natureza_servicos').removeAttr("title");
	$('#cfop_servicos').focus();
}

function setCfopServ(param){
	document.getElementById('cfop_servicos').value = param.id;
	$('#cfop_servicos').removeClass("ac_error");
	$('#cfop_servicos').addClass("tipsyOff");
	$('#cfop_servicos').removeAttr("title");
	$('#natureza_servicos').focus();
}

function addAutocompleterProduto(field, div) {
	new Ajax.Autocompleter(field,div,'services/produtos.lookup.php', {afterUpdateElement: setIdProduto});
}

function addCompletersItens(){
	for(var i=0; i<document.getElementsByName('ni').length; i++) {
		var itemId = document.getElementsByName('itens[produto][]')[i].id;
		var itNro = document.getElementsByName('ni')[i].value;
		new Ajax.Autocompleter(itemId,'autocomplete','services/produtos.lookup.php?it='+itNro+'', {afterUpdateElement: setIdProdutoItem});
	}
}

function setIdContato(idContato) {
   document.getElementById('idContato').value = idContato;
   $('#contato').removeClass("warning");
   $('#contato').removeClass("ac_error");
   $('#contato').addClass("tipsyOff");
   $('#contato').removeAttr("title");
   contatoNovo = false;
   verificarContato();
   xajax_buscarDadosContato($("#idContato").val(), $("#id").val(), $("#tipo").val(), null, $("#trocaManualDeOperacaoFiscal").val());
}

function setIdContatojQuery(param){
	document.getElementById('idContato').value = param.id;
	$('#contato').removeClass("warning");
	$('#contato').removeClass("ac_error");
	$('#contato').addClass("tipsyOff");
	$('#contato').removeAttr("title");
	$('#contato').focus();
	contatoNovo = false;
	verificarContato();
	xajax_buscarDadosContato($("#idContato").val(), $("#id").val(), $("#tipo").val(), null, $("#trocaManualDeOperacaoFiscal").val());
}

function setIdProduto(id,field) {
   document.getElementById('produtoId').value = id;
   qtd = document.getElementById('quantidade').value;
   idTipoNota = document.getElementById('idTipoNota').value;
   $('#produto').removeClass("ac_error");
   $('#produto').addClass("tipsyOff");
   $('#produto').removeAttr("title");
   xajax_buscarDadosDoProduto(id, qtd, "", "", "", "", idTipoNota, $("#loja option:selected").val());
   xajax_buscarDadosEstoqueProduto(id);
   //xajax_buscarImpostos(id, document.getElementById('idTipoNota').value,-1);
}

function setIdProdutojQuery(param) {
	$('#produtoId').val(param.id);
	qtd = $('#quantidade').val();
	idTipoNota = $('#idTipoNota').val();
	idMunicipio = $('#idMunicipio').val();
	uf = $("#uf").val();
	tipoNota = $("#tipo").val();
	$('#produto').removeClass("ac_error");
	$('#produto').addClass("tipsyOff");
	$('#produto').removeAttr("title");
	xajax_buscarDadosDoProduto(param.id, qtd, idTipoNota, idMunicipio, uf, "",tipoNota, $("#loja option:selected").val());
	xajax_buscarDadosEstoqueProduto(param.id);
	$('#produto').focus();
}

function setIdProdutoEquivalente(param) {
	$("#produtoId").val(param.id);
	$("#produtoEquivalente").removeClass("ac_error");
	$("#produtoEquivalente").addClass("tipsyOff");
	$("#produtoEquivalente").removeAttr("title");
	xajax_buscarDadosDoProdutoEquivalente(param.id, 'N');
	$("#produtoEquivalente").focus();
}

function setIdProdutoEdicao(param){
	$('#edIdProduto').val(param.id);
	qtd = $('#edQuantidade').val();
	idOpFiscal = $('#edIdOperacaoFiscal').val();
	idMunicipio = $('#idMunicipio').val();
	uf = $("#uf").val();
	tipoNota = $("#tipo").val();
	xajax_buscarDadosDoProduto(param.id, qtd, idOpFiscal, idMunicipio, uf,"S",tipoNota, $("#loja option:selected").val());
	xajax_buscarDadosEstoqueProduto(param.id);
	$('#edDescricao').removeClass("ac_error");
	$('#edDescricao').addClass("tipsyOff");
	$('#edDescricao').removeAttr("title");
	$('#edDescricao').focus();
}

function setIdServico(id,field) {
   document.getElementById('servicoId').value = id;
   qtd = document.getElementById('quantidadeServico').value;
   xajax_buscarDadosDoServico(id, '', qtd);
}

function setIdProdutoItem(id,field) {
	var itemNumber = field.substring(7);

	qtd = document.getElementById('quantidade'+itemNumber).value;
	idTipoNota = document.getElementById('idTipoNota').value;
	xajax_buscarDadosDoProduto(id, itemNumber, qtd, idTipoNota, "", "", "", $("#loja option:selected").val());
	//document.getElementById(field.id+'Id').value = item.id;
	$('#descricao'+itemNumber).removeClass("ac_error");
	$('#descricao'+itemNumber).addClass("tipsyOff");
	$('#descricao'+itemNumber).removeAttr("title");
	xajax_buscarImpostos(id, document.getElementById('idTipoNota').value,itemNumber);
}

function removeDetailItem(ni) {
    xajax_removeDetailItem(ni);
}
/*
function addDetailItem() {
	var itemNumber;
	itemNumber = document.getElementById('itemNumber').value;
	itemNumber++;
	document.getElementById('itemNumber').value = itemNumber;
	//alert( document.getElementById('valorIpiFixo').value);
	xajax_addDetailItem(document.getElementById('itemNumber').value,
	                    document.getElementById('produtoId').value,
	                    document.getElementById('codigo').value,
	                    document.getElementById('produto').value,
	                    document.getElementById('cf').value,
	                    document.getElementById('st').value,
	                    document.getElementById('un').value,
                        document.getElementById('quantidade').value,
	                    document.getElementById('precounitario').value,
	                    document.getElementById('precototal').value,
	                    document.getElementById('icms').value,
	                    document.getElementById('ipi').value,
	                    document.getElementById('valorIpiAdd').value,
	                    document.getElementById('consumidor_final').value,
	                    document.getElementById('base_icms').value,
	                    document.getElementById('base_ipi').value,
	                    document.getElementById('tributacao_icms').value,
	                    document.getElementById('tributacao_ipi').value,
	                    0,
	                    0,
	                    document.getElementById('valorIpiFixo').value);

}
*/
function totalItem(qtd , valor, itemNumber, form){
	var total;
	qtd = parseFloat(nroUsa(document.getElementById(qtd).value));
	valor = parseFloat(nroUsa(document.getElementById(valor).value));
	total = (qtd*valor);
	total = nroBra(total.toString());
	document.getElementById('precototal'+itemNumber).value = total;
	//$('precototal'+itemNumber).setAttribute('value',total);
	$('#precototal'+itemNumber).val(total);
	totalIpiItem(itemNumber);
	totalItens();
	mudarImpostos();
}

function totalIpiItem(itemNumber){
	var totalIPI, totalItem, tributacao_ipi, base_ipi, ipi;
	totalIPI = 0;
	var valorIpiFixo = document.getElementById('valorIpiFixo'+itemNumber).value;
	if(valorIpiFixo > 0){
		//alert(document.getElementById('valorIpiFixo'+itemNumber).value);
		var qtd = parseFloat(document.getElementById('quantidade'+itemNumber).value);
		totalIPI = qtd * valorIpiFixo;
	}else{
		if(document.getElementById('tributacao_ipi'+itemNumber).value =='tributado'){
			totalItem = parseFloat(nroUsa(document.getElementById('precototal'+itemNumber).value));
			base_ipi = document.getElementById('base_ipi'+itemNumber).value;
			ipi = parseFloat(nroUsa(document.getElementById('ipi'+itemNumber).value));
			totalIPI = (totalItem)*((base_ipi/100)*(ipi/100));
		}
	}
	if(itemNumber==""){
		document.getElementById('valorIpiAdd').value = nroBra(totalIPI.toString());
	}else{
		document.getElementById('valorIpi'+itemNumber).value = nroBra(totalIPI.toString());
	}
}

function totalItens(){
	var subTotal = 0;
	for(var i=0; i<document.getElementsByName('itens[precounitario][]').length; i++) {
		var preco = parseFloat(nroUsa(document.getElementsByName('itens[precounitario][]')[i].value));
		var qtd = parseFloat(nroUsa(document.getElementsByName('itens[quantidade][]')[i].value));
		subTotal += preco * qtd;
	}
	document.getElementById('valorRealProdutos').value = subTotal;
	posicaoPercent = document.getElementById('desconto').value.indexOf("%");
	desconto = nroUsa(document.getElementById('desconto').value);
	if(desconto=="")desconto = 0;
	if(posicaoPercent==-1){
		document.getElementById('tipoDesconto').value ='v';
		document.getElementById('valorDesconto').value = nroBra(parseFloat(desconto));
		subTotal = subTotal - parseFloat(desconto);
	}else{
		valorDesconto = desconto.substring(0,posicaoPercent);

		document.getElementById('tipoDesconto').value ='%';
		document.getElementById('valorDesconto').value = nroBra((valorDesconto*subTotal/100));

		subTotal = subTotal -(valorDesconto*subTotal/100);
	}
	subTotal = nroBra(subTotal);
	document.getElementById('valorProdutos').value = subTotal;
}

function obterNroItens(){
	nro = document.getElementsByName('itens[produto][]').length;
	document.getElementById('nroItens').value = nro;
}

function obterDesconto(){
	return document.getElementById('desconto').value;
}

function totalGeral(){
	var valorDesconto;
	var total = parseFloat(nroUsa(document.getElementById('valorProdutos').value));
	posicaoPercent = document.getElementById('desconto').value.indexOf("%");
	desconto = nroUsa(document.getElementById('desconto').value);
	if(posicaoPercent==-1){
		document.getElementById('tipoDesconto').value ='v';
		document.getElementById('valorDesconto').value = nroBra(parseFloat(desconto));
		total = total - parseFloat(desconto);
	}else{
		document.getElementById('tipoDesconto').value ='%';
		valorDesconto = desconto.substring(0,posicaoPercent);

		document.getElementById('valorDesconto').value = (valorDesconto*total/100);
		total = total -(valorDesconto*total/100);
	}

	totalServicos = parseFloat(nroUsa(document.getElementById('valorServicos').value));

	total = total + totalServicos;
	total = nroBra(total);
	document.getElementById('valorNota').value = total;
}

function mudarImpostos(){
	xajax_mudarImpostos(xajax.getFormValues('formNotaFiscal'),document.getElementById('idTipoNota').value);
}

function addServico(){
	var itemNumber;
	itemNumber = document.getElementById('servicoNumber').value;
	itemNumber++;
	document.getElementById('servicoNumber').value = itemNumber;

	xajax_addServico(document.getElementById('servicoNumber').value,
					document.getElementById('servicoId').value,
					document.getElementById('servicoDesc').value,
					document.getElementById('quantidadeServico').value,
					document.getElementById('precounitarioServico').value,
					document.getElementById('precototalServico').value,
					document.getElementById('percentualISS').value);
}

function removeServico(ni){
	 xajax_removeServico(ni);
}

function totalServico(qtd , valor, itemNumber, form){

	var total;
	qtd = document.getElementById(qtd).value;
	valor = document.getElementById(valor).value;

	if ((valor != '') && (qtd != '')){
		qtd = parseFloat(nroUsa(qtd));
		valor = parseFloat(nroUsa(valor));

		total = (qtd*valor);
		total = nroBra(total.toString());

		document.getElementById('precototalServico'+itemNumber).value = total;
		calcularServicos();
	}
}

function calcularServicos(){

	var subTotal = 0;
	for(var i=0; i<document.getElementsByName('servicos[precounitarioServico][]').length; i++) {
		var preco = parseFloat(nroUsa(document.getElementsByName('servicos[precounitarioServico][]')[i].value));
		var qtd = parseFloat(nroUsa(document.getElementsByName('servicos[quantidadeServico][]')[i].value));
		subTotal += preco * qtd;
	}

	subTotal = nroBra(subTotal);
	document.getElementById('valorServicos').value = subTotal;

	calcularISS();
	totalGeral();
}

function calcularISS(){

	var subTotal = 0;
	var baseCalculoISS = 0;
	var totalServicos = document.getElementById('valorServicos').value;

	var baseISS = document.getElementById('baseISS').value;
	if (baseISS ==''){
		baseISS = '0.00';
	}else{
		baseISS = parseFloat(nroUsa(baseISS));
	}

	//alert(baseISS);

	var percentualISS = document.getElementById('percentualISS').value;
	if (percentualISS ==''){
		percentualISS = '0.00';
	}else{
		percentualISS = parseFloat(nroUsa(percentualISS));
	}

	for(var i=0; i<document.getElementsByName('servicos[precounitarioServico][]').length; i++) {
		var subTotal = 0;
		var preco = parseFloat(nroUsa(document.getElementsByName('servicos[precounitarioServico][]')[i].value));
		var qtd = parseFloat(nroUsa(document.getElementsByName('servicos[quantidadeServico][]')[i].value));
		var valorTotalServico = preco * qtd;
		subTotal += valorTotalServico;

		document.getElementsByName('servicos[aliqISS][]')[i].value = nroBra(percentualISS);

		if (percentualISS > 0){
			valorBaseIss = ((valorTotalServico) * (baseISS/100)) ;
			baseCalculoISS += valorBaseIss;
			document.getElementsByName('servicos[valorISS][]')[i].value = nroBra((valorBaseIss) /100);
		}
	}

	totalServicos = parseFloat(nroUsa(totalServicos));
	var valorISS = 0;

	valorISS = (baseCalculoISS * percentualISS)/ 100;

	baseCalculoISS = nroBra(baseCalculoISS);
	document.getElementById('baseCalculoISS').value = baseCalculoISS;

	subTotal = nroBra(valorISS);
	document.getElementById('valorISS').value = subTotal;
}

function obterNotaTinyShop(numero){
	clearForm();
	displayForm();
	displayWait('pleasewait');
	xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
	xajax_obterNotaTinyShop(numero);
}

function obterNotaMagento(numero) {
	clearForm();
	displayForm();
	displayWait('pleasewait');
	xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
	xajax_obterNotaMagento(numero);
}

function obterNotaPrestashop(numero, endereco) {
	clearForm();
	displayForm();
	displayWait('pleasewait');
	xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
	xajax_obterNotaPrestashop(numero, endereco);
}

function obterNotaTray(numero, importarRazaoSocial) {
	clearForm();
	displayForm();
	displayWait('pleasewait');
	xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
	xajax_obterNotaTray(numero, importarRazaoSocial);
}

function obterNotaPedidoXML(arquivo) {
	$('#btnImportXML').attr("disabled", true);
	if (arquivo != "") {
		clearForm();
		displayForm();
		displayWait('pleasewait');
		xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
		xajax_obterNotaPedidoXML(arquivo);
	} else {
		$('#btnImportXML').removeAttr("disabled");
	}
}

function obterNotaPedidoXMLNFe(arquivo) {
	$('#btnImportXML').attr("disabled", true);
	if (arquivo != "") {
		clearForm();
		displayWait('pleasewait');
		//xajax_obterOperacaoPadrao($("#tipo").val());
		//xajax_inicializarArraySts();
		xajax_obterNotaFiscalXMLNFe(arquivo,tipo);
	} else {
		$('#btnImportXML').removeAttr("disabled");
	}
}

function verificarServicos(){
	if (document.getElementById('notaTemServico').value=='S'){
		document.getElementById('div-servicos').className = '';
		xajax_obterCefopServicos(document.getElementById('cfopServico').value);
		calcularServicos();
	}else{
		document.getElementById('div-servicos').className = 'invisivel';
		document.getElementById('cfop_servicos').value = '';
		document.getElementById('natureza_servicos').value = '';
	}
}

function salvar(){
	salvarParcelas();

	if ($("#a_vista").prop("checked")) {
		$("#tipoPagamento").val("av");
	} else {
		$("#tipoPagamento").val("ap");
	}
	xajax_salvarNotaFiscal($('#id').val(), xajax.getFormValues('formNotaFiscal'), arrayItens, volumesLogistica);
}

function configurarAba(opc, link, foco) {
	$("#tabnav > li > a").removeClass("active");

	$("#div_prod").hide();
	$("#div_icms").hide();
	$("#div_simples").hide();
	$("#div_ipi").hide();
	$("#div_issqn").hide();
	$("#div_pis").hide();
	$("#div_cofins").hide();
	$("#div_ii").hide();
	$("#div_iex").hide();
	$("#div_combustivel").hide();
	$("#div_armamento").hide();
	$("#div_veiculo").hide();
	$("#div_exportacao").hide();
	$("#div_outro").hide();
	$("#div_estoque").hide();
	$("#div_retencoes").hide();

	$("#div_" + opc).show();
	link.addClass("active");
	foco.focus();
}

function configurarAbaIcms(opc, link, foco) {
	$("#tabnav_icms > li > a").removeClass("active");

	$("#div_icms_geral").hide();
	$("#div_icms_st").hide();
	$("#div_icms_retencao").hide();
	$("#div_icms_partilha").hide();

	$("#div_icms_" + opc).show();
	link.addClass("active");
	foco.focus();
}

function setarTipoEquivalente(tipoEquivalente) {
	if (tipoEquivalente == "N") {
		$("#equivalenteTipoN").prop("checked", true);
	} else if (tipoEquivalente == "E") {
		$("#equivalenteTipoE").prop("checked", true);
	} else {
		$("#equivalenteTipoX").prop("checked", true);
	}
	configurarFormPorTipoEquivalente();
}

function configurarFormPorTipoEquivalente() {
	 if ($("#equivalenteTipoE").prop("checked")) {
		$("#div_equivalente_novo").hide();
		$("#div_equivalente_existente").show();
		$("#codigoNoFabricante").parent().show();
		atualizarItemTemp("equivalenteTipo", "E");
	} else if ($("#equivalenteTipoN").prop("checked")){
		$("#div_equivalente_existente").hide();
		$("#div_equivalente_novo").show();
		$("#codigoNoFabricante").parent().show();
		atualizarItemTemp("equivalenteTipo", "N");
	}else  {
		$("#div_equivalente_novo").hide();
		$("#div_equivalente_existente").hide();
		$("#codigoNoFabricante").parent().hide();
		atualizarItemTemp("equivalenteTipo", "X");
	}

}

function setarItemParaProdutoNovo() {
	$("#idProduto").val("0");
	$("#produtoEquivalente").val("");
	$("#edCodigo").val("");
	atualizarItemTemp("idProduto", "0");
	atualizarItemTemp("produtoEquivalente", "");
	atualizarItemTemp("codigo", "");
}

function desabilitarCampos(situacao) {
	$("input").attr("readonly", "readonly");
	$("select").attr("disabled", "disabled");
	$("input:radio").attr("disabled", "disabled");
	$("#linhaInclusaoItem").attr("disabled", "disabled");
	$("#trh").hide();
	$("#trh").attr("disabled", "disabled");
	$(".button-delete").attr("disabled", "disabled");
	$(".button-search").hide();
	$(".button-add").hide();
	$(".button-new").hide();
	$("#botaoCancelar").attr("value", "Fechar");
	$("#botaoCalcularParc, #pag_botaoCalcular, #pag_grparcelas td[id^=exclui]").hide();
	$("#aNovaLinhaItem").hide();
	$("#aNovaLinhaParcela").hide();
	$("#add_novo_volume").hide();
	$("#pag_aNovaLinhaParcela").hide();
	$("#etiqueta_mostrar").prop("disabled", true);
	if (situacao == 6 || situacao == 7) {
		$('#nomeVendedor').prop('readonly', false);
		$("[name='servicosLogistica[]'], #integracaoLogistica").prop("disabled", false);
		permissaoEdicaoTracking();
		atualizarCamposTransportadora = false;
		$('#dataEmissao').datepicker('disable');
		$('#dataSaidaEntrada').datepicker('disable');
	} else {
		$('#botaoSalvar').hide();
	}
}

function habilitarCampos() {
	$("input").removeAttr("readonly");

	if ($("#codigo").attr("disabled")) {
		$("#codigo").attr("readonly", "readonly");
		$("#edCodigo").attr("readonly", "readonly");
	}

	$("#baseICMS").attr("readonly", "readonly");
	$("#baseIcmsDiferimento").attr("readonly", "readonly");
	$("#valorICMS").attr("readonly", "readonly");
	$("#valorServicos").attr("readonly", "readonly");
	$("#valorProdutos").attr("readonly", "readonly");
	$("#nroItens").attr("readonly", "readonly");
	$("#valorIPI").attr("readonly", "readonly");
	$("#valorISSQN").attr("readonly", "readonly");
	$("#valorNota").attr("readonly", "readonly");
	$("#tValorAproxImpostos").attr("readonly", "readonly");
	$("#totalFaturado").attr("readonly", "readonly");
	$("#edValorTotal").attr("readonly", "readonly");
	$("#baseICMSSubst").attr("readonly", "readonly");
	$("#valorICMSSubst").attr("readonly", "readonly");
	$("#valorFunrural").attr("readonly", "readonly");
	$("#edValorComissao").attr("readonly", "readonly");
	$('#vUnTrib').prop('readonly', true);
	$("#edValorDescontoItem").attr("readonly", "readonly");

	$("#valorMinimoParaRetencao").attr("readonly", "readonly");
	$("#valorRetBaseIR").attr("readonly", "readonly");
	$("#valorRetIR").attr("readonly", "readonly");
	$("#valorRetCSLL").attr("readonly", "readonly");
	$("#valorRetPIS").attr("readonly", "readonly");
	$("#valorRetCOFINS").attr("readonly", "readonly");
	$("#vISSRetTot").attr("readonly", "readonly");

	$("#precototal").attr("readonly", "readonly");
	$("#alq_icms").attr("readonly", "readonly");
	$("#alq_ipi").attr("readonly", "readonly");

	if ($("#peso_calculado").val() == "S") {
		$("#pesoLiquido").attr("readonly", "readonly");
		$("#pesoBruto").attr("readonly", "readonly");
	}

	if ($("#volume_calculado").val() == "S") {
		$("#qtdVolumes").attr("readonly", "readonly");
	}

	if ($('#nfe\\:desconto_calculado').val() == 'S') {
		$('#desconto').prop('readonly', true);
	} else {
		$('#desconto').prop('readonly', false);
	}

	$("select").removeAttr("disabled");
	$("input:radio").removeAttr("disabled");
	$("#linhaInclusaoItem").removeAttr("disabled");
	$("#trh").show();
	$("#trh").removeAttr("disabled");
	$(".button-delete").removeAttr("disabled");
	$(".button-search").show();
	$(".button-add").show();
	$(".button-new").show();
	$("#botaoSalvar").show();
	$("#botaoCancelar").attr("value", "Cancelar");
	$("#botaoCalcularParc, #pag_botaoCalcular").show();
	$("#aNovaLinhaItem").show();
	$("#aNovaLinhaParcela").show();
	$("#add_novo_volume").show();
	$("#pag_aNovaLinhaParcela").show();
	$('#dataEmissao').datepicker('enable');
	$('#dataSaidaEntrada').datepicker('enable');
}

function ajustarFormContatoRapido(tipo) {
	atualizarCamposPorDestino();
	if (tipo == "F") {
		$("#td_pais").hide();
		$("#td_cnpj").show();
		$("#td_ie").show();
		$("#td_uf").show();

		$("#municipio").tipsy({gravity: $.fn.tipsy.autoWE});
		$("#municipio").keydown(function(event){
			clearHidenResult(event,$('#idMunicipio'));
		});

		$('#cep').cep();
		document.getElementById('lblCnpj').innerHTML = "CPF<span style='color:red'>&nbsp;*</span>";
		document.getElementById('lblIe').innerHTML = "Inscrição Estadual";
		if ($("#idContato").val() == 0){
			$("#indIEDest").val(9);
		}
		$("#btnConsultaCadContribuinte").hide();
	} else if (tipo == "E") {
		$("#td_cnpj").hide();
		$("#td_ie").hide();
		$("#td_uf").hide();
		$("#td_pais").show();

		$("#cep").uncep();
		$("#municipio").unbind();
		$("#idMunicipio").val("0");
		$("#uf").val("EX");

		if ($("#idContato").val() == 0){
			$("#indIEDest").val(9);
		}
		$("#btnConsultaCadContribuinte").hide();
	} else {
		$("#td_pais").hide();
		$("#td_cnpj").show();
		$("#td_ie").show();
		$("#td_uf").show();

		$("#municipio").tipsy({gravity: $.fn.tipsy.autoWE});
		$("#municipio").keydown(function(event){
			clearHidenResult(event,$('#idMunicipio'));
		});

		//$('#cep').cep();

		document.getElementById('lblCnpj').innerHTML = "CNPJ<span style='color:red'>&nbsp;*</span>";
		document.getElementById('lblIe').innerHTML = "Inscrição Estadual";
		$("#btnConsultaCadContribuinte").show();
	}
	atualizaIEDest();
}

function limparCodigoProduto(event) {
	if ((event.keyCode <= 8) ||
		((event.keyCode >= 46) && (event.keyCode <= 111)) ||
		(event.keyCode >= 186)
	) {
		itemTemp["idProduto"] = 0;
		$('#produtoId').val('0');
	}
}

function limparCodigoProdutoPopup(event) {
	if ((event.keyCode <= 8) ||
		((event.keyCode >= 46) && (event.keyCode <= 111)) ||
		(event.keyCode >= 186)
	) {
		itemTempEdicao["idProduto"] = 0;
		$("#edIdProduto").val("0");
	}
}

function inicializarArraySts(dadosStIcms, dadosStIpi, dadosStIssqn, dadosStPis, dadosStCofins, dadosStII, dadosStSimples, dadosSpedBaseCalculoCredito, dadosSpedTipoCredito, dadosSpedTipoItem) {
	arrayStIcms = dadosStIcms;
	arrayStSimples = dadosStSimples;
	arrayStIpi = dadosStIpi;
	arrayStIssqn = dadosStIssqn;
	arrayStPis = dadosStPis;
	arrayStCofins = dadosStCofins;
	arrayStII = dadosStII;

	arraySpedBaseCalculoCredito = dadosSpedBaseCalculoCredito;
	arraySpedTipoCredito = dadosSpedTipoCredito;
	arraySpedTipoItem = dadosSpedTipoItem;
}

function montarSelectSTICMS(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStIcms > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStIcms"));
	$.each(arrayStIcms, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStIcms"));
		}
	});
}

function montarSelectSTSimples(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStSimples > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStSimples"));
	$.each(arrayStSimples, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStSimples"));
		}
	});
}

function montarSelectSTIPI(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStIpi > option").remove();
	$("<option value=''>Sem IPI</option>").appendTo($("#selectStIpi"));
	$.each(arrayStIpi, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStIpi"));
		}
	});
}

function montarSelectSTISSQN(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStIssqn > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStIssqn"));
	$.each(arrayStIssqn, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStIssqn"));
		}
	});
}

function montarSelectSTPIS(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStPis > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStPis"));
	$.each(arrayStPis, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStPis"));
		}
	});
}

function montarSelectSPED(spedBaseCalculoCreditoSelecionado, spedTipoCreditoSelecionado, spedTipoItemSelecionado) {
	var marcado;
	$("#selectSpedTipoCredito > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectSpedTipoCredito"));
	$.each(arraySpedTipoCredito, function(indice, opcao) {
		if (indice == spedTipoCreditoSelecionado) {
			marcado = "selected";
		} else {
			marcado = "";
		}
		$("<option value='" + indice + "' " + marcado + ">" + opcao + "</option>").appendTo($("#selectSpedTipoCredito"));
	});

	$("#selectSpedBaseCalculoCredito > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectSpedBaseCalculoCredito"));
	$.each(arraySpedBaseCalculoCredito, function(indice, opcao) {
		if (indice == spedBaseCalculoCreditoSelecionado) {
			marcado = "selected";
		} else {
			marcado = "";
		}
		$("<option value='" + indice + "' " + marcado + ">" + opcao + "</option>").appendTo($("#selectSpedBaseCalculoCredito"));
	});

	$("#selectSpedTipoItem > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectSpedTipoItem"));
	$.each(arraySpedTipoItem, function(indice, opcao) {
		if (indice == spedTipoItemSelecionado) {
			marcado = "selected";
		} else {
			marcado = "";
		}
		$("<option value='" + indice + "' " + marcado + ">" + opcao + "</option>").appendTo($("#selectSpedTipoItem"));
	});
}

function montarSelectSTCOFINS(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStCofins > option").remove();

	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStCofins"));
	$.each(arrayStCofins, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStCofins"));
		}
	});
}

function montarSelectSTII(itemSelecionado) {
	var marcado, indice, opcao;
	$("#selectStII > option").remove();
	$("<option value='Selecione'>Selecione</option>").appendTo($("#selectStII"));
	$.each(arrayStII, function(indice, opcao) {
		if ((opcao["tipo"] == "A") || (opcao["tipo"] == $("#tipo").val())) {
			if (indice == itemSelecionado) {
				marcado = "selected";
			} else {
				marcado = "";
			}
			$("<option value='" + indice + "' " + marcado + ">" + opcao.descr + "</option>").appendTo($("#selectStII"));
		}
	});
}

function vincularEnventosCamposLinha() {
	$("#linhaInclusaoItem > td > input").each(function() {
		if ($(this).attr("type") == "text") {
			$(this).bind("blur", function(e){
				t = setTimeout("addDetailItem(true)", 1000);
			});

			$(this).bind("focus", function(e) {
				clearTimeout(t);
			});
		}
	})
}

function adicionarLinhaItem() {
	if ($("#linhaInclusaoItem").css("display") != "none") {
		if ($('#quantidade').val() == 0 && $('#produto').val() != ""){
			$('#quantidade').val(1);
			alterarItemTemp(1, 'quantidade');
		}
		calcularValorParcialItem();
		addDetailItem(true);
	}

	$("#produtoId").val("");
  	$("#produto").val("");
  	$("#valorIpiFixo").val("");
	$("#codigo").val("");
	$("#un").val("");
	$("#quantidade").val("");
	$("#precounitario").val("");
	$("#precototal").val("");
	$("#cf").val("");

	inicializarItemTemp();

	$("#linhaInclusaoItem").show();
	$("#produto").focus();
}

function limparEventoTimeOut() {
	clearTimeout(t);
}

function atribuirEventoHighlight() {
	$("#tItensNota > tbody > tr").each(function() {
		if (($(this).attr("id") != "") && ($(this).attr("id") != "linhaInclusaoItem") && ($(this).attr("id") != "itens_header")) {
			$(this).bind("mouseover", function(e){
				$(this).addClass("highlight");
			})

			$(this).bind("mouseout", function(e){
				$(this).removeClass("highlight");
			})
		}
	})
}

function limparCodigoContato(event) {
	if ((event.keyCode <= 8) ||
		((event.keyCode >= 46) && (event.keyCode <= 111)) ||
		(event.keyCode >= 186)
	) {
		$('#idContato').val('0');
	}
}

function verificarContato() {
	if ((($("#idContato").val() <= 0) || ($("#idContato").val() == "")) && ($("#contato").val() != "")) {
		if (! contatoNovo) {
			limparCamposContato();
			contatoNovo = true;
		}
	}
}

function limparCamposContato() {
	$("#endereco").val("");
	$("#enderecoNro").val("");
	$("#complemento").val("");
	$("#bairro").val("");
	$("#cep").val("");
	$("#idMunicipio").val("");
	$("#municipio").val("");
	$("#uf").val("");
	$("#fone").val("");
	$("#tipoPessoa").val("F");
	$("#cnpj").val("");
	$("#ie").val("");
	$("#inscricaoSuframa").val("");
}

function exibirListaServicos() {
	new Boxy.load('templates/form.lista.servicos.popup.php', {
		title: "Lista de serviços",
		modal: true
	});
}

function ocultarLinhaAdicionarItem() {
	$("#linhaInclusaoItem").hide();
}

function changeSerie() {
	$('#numero').val('');
	xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo").val(), $("#loja").val(), $('#idConfUnidadeNegocio').val());
}

function configurarMensagemImpostos(mensagemErroImpostos) {
	if (mensagemErroImpostos != "") {
		exibirMensagemValidacao(mensagemErroImpostos);
	}
}

function exibirMensagemValidacao(mensagem) {
	mensagemValidacao = mensagem;
	new Boxy.load('templates/form.erro.validacao.popup.php', {
		title: "Conflito de regras de tributação",
		modal: true,
		afterShow: ajustarFormValidacao
	});
}

function ajustarFormValidacao() {
	$("#mensagem_validacao").html(mensagemValidacao);
}

function closeValidacao() {
	Boxy.get("#mensagem_validacao").hide();
}

function verificaParcela() {
	var valor, pos;
	valor = $("#desconto").val();
	pos = valor.indexOf("%");
	return pos;
}

function calcularComissao() {
	var vlrBaseComiss = parseFloat(nroUsa($("#edBaseComissao").val()));
	var alqComiss = parseFloat(nroUsa($("#edAlqComissao").val()));

	var comisDesconto = parseFloat(nroUsa($("#edValorDescontoItem").val())) * alqComiss / 100;

	var valorComiss = nroBra((vlrBaseComiss * alqComiss / 100) - comisDesconto);
	itemTempEdicao["vlr_comissao"] = valorComiss;
	$("#edValorComissao").val(valorComiss);
}

function zeroQtd(valor){
	if (valor == "0"){
		$("#quantidade").val(1);
	}
}

function setarDadosEstoqueProduto(aEstoqueAtual, aEstoqueMinimo, aEstoqueMaximo, aTipoProduto) {
	estoqueAtual = aEstoqueAtual;
	estoqueMinimo = aEstoqueMinimo;
	estoqueMaximo = aEstoqueMaximo;
	tipoProduto = aTipoProduto;
}

function recalcularPorCrt() {
	calcularImpostos("N");
	$(".linhaItemNota").remove();
	montarItensTela();
}

function adicionarLinhaArma(serie,cano){
	var table = '<tr>' +
				'<td><input type="text" value="' + serie + '" class="input_text editgridh" name="edNSerieArma[]" id="edNSerieArma" maxlength="9" onchange="atualizarItemTempArma(\'arma_serie\',this.value);"></td>' +
				'<td><input type="text" value="' + cano + '" class="input_text editgridh" name="edNCanoArma[]" id="edNCanoArma" maxlength="9" onchange="atualizarItemTempArma(\'arma_cano\',this.value);"></td>' +
				'<td class="editgridh center"><input class="button-delete" type="button" onclick="removerSerieArmaCano(this)"></td>' +
				'</tr>';
	$("#nroSerieArmaCano tbody").append(table);
}

function removerSerieArmaCano(obj){
	obj.parentNode.parentNode.remove();
	itemTempEdicao["arma_cano"] = {};
	itemTempEdicao["arma_serie"] = {};
	var cont = 0;
	$('#nroSerieArmaCano > tbody  > tr').each(function() {
		itemTempEdicao["arma_serie"][cont] = $(this).find("td").find("input#edNSerieArma").val();
		itemTempEdicao["arma_cano"][cont] = $(this).find("td").find("input#edNCanoArma").val();
		cont++;
	});
}

function changetpViaTransp(){
	if ($("#DItpViaTransp").val() == 1){
		$("#container_DIvAFRMM").show();
	} else {
		$("#container_DIvAFRMM").hide();
	}
}

function changetpintermedio(){
	if ($("#DItpIntermedio").val() != 1){
		$(".DItpIntermedio").show();
	} else {
		$(".DItpIntermedio").hide();
	}
}

function atualizaIEDest(){
	var indIEDest = $("#indIEDest").val();
	if (indIEDest == 2){
		$("#ie").val("ISENTO");
		$("#ie").prop("readonly", true);
	} else {
		if($("#ie").val()=="ISENTO"){$("#ie").val("");}
		$("#ie").prop("readonly", false);
	}
}

function exibeOcultaDocReferenciado() {
	if ($("#finalidade").val() == "3" || $("#finalidade").val() == "4" || ($('#indPres').val() == '5')) {
		$("#doc_referenciado").show();
	} else {
		$("#doc_referenciado").hide();
	}
	exibeOcultaChaveNFeReferenciada();
}

function exibeOcultaChaveNFeReferenciada() {
	$("#td_chave_ref").hide();
	switch($("#tipoDocReferenciado").val()){
		case '4':
			$('#td_chave_ref').hide();
			$('#div_prod_rural_nf_ref').show();
			break;
		case '1':
		case '2':
			$('#td_chave_ref').hide();
			$('#div_prod_rural_nf_ref').show();
			break;
		case "55":
			$("#td_chave_ref_titulo").html("Chave de acesso da nota");
			$("#td_chave_ref").show();
			$('#div_prod_rural_nf_ref').hide();
			break;
		case "2D":
			$("#td_chave_ref_titulo").html("Número do Contador de Ordem de Operação - COO");
			$("#td_chave_ref").show();
			$('#div_prod_rural_nf_ref').hide();
			break;
	}
}

function adicionarLinhaAdicao(obj){
	if(obj == ""){
		var contLinhas = 1;
		$("table#tAdicoes tbody tr").each(function( index2 ) {
			contLinhas = contLinhas + 1;
		});
		var html = "";
		html += '<tr>' +
				'<td><input type="text" class="input_text editgridh" name="nAdicao[]" value="' + contLinhas + '"></td>' +
				'<td><input type="text" class="input_text editgridh" name="nSeqAdicC[]"></td>' +
				'<td><input type="text" class="input_text editgridh" name="cFabricante[]"></td>' +
				'<td><input type="text" class="input_text editgridh" name="vDescDI[]"></td>' +
				'<td><input type="text" class="input_text editgridh" name="nDraw[]"></td>' +
				'<td class="center" width="34">';
		if($("table#tAdicoes tbody").html() != ""){
			html += '<input class="editgridh button-delete" type="button" onclick="removeItemAdicao(this)">';
		}
		html += '</td>' +
				'</tr>';
		$("table#tAdicoes tbody").append(html);
	}else{
		try{
			obj = $.parseJSON(obj);
		}catch(e){
			alert("Não foi possível montar a tabela de Adições:\n" + e);
		}
		$("table#tAdicoes tbody").html("");
		var contAdicao = 0;
		$.each(obj,function(index,value){
			var html = "";
			html += '<tr>' +
					'<td><input type="text" class="input_text editgridh" name="nAdicao[]" value="' + value.nAdicao + '"></td>' +
					'<td><input type="text" class="input_text editgridh" name="nSeqAdicC[]" value="' + value.nSeqAdicC + '"></td>' +
					'<td><input type="text" class="input_text editgridh" name="cFabricante[]" value="' + value.cFabricante + '"></td>' +
					'<td><input type="text" class="input_text editgridh" name="vDescDI[]" value="' + nroBra(value.vDescDI) + '"></td>' +
					'<td><input type="text" class="input_text editgridh" name="nDraw[]" value="' + value.nDraw + '"></td>' +
					'<td class="center" width="34">';
			if(contAdicao != 0){
				html += '<input class="editgridh button-delete" type="button" onclick="removeItemAdicao(this)">';
			}
			html += '</td>' +
					'</tr>';
			$("table#tAdicoes tbody").append(html);
			contAdicao++;
		});
	}

}

function removeItemAdicao(obj){
	obj.parentNode.parentNode.remove();
}

function montarSelectAdicoes(nAdicao){
	if(nAdicao == undefined || nAdicao == ""){
		nAdicao = "";
	}
	var adicoes = "";
	$("table#tAdicoes tbody tr").each(function( index2 ) {
		var selected = "";
		if(nAdicao == $(this).find("input").eq(0).val() && nAdicao != ""){
			selected = 'selected="selected"';
		}
		adicoes += '<option value"' + $(this).find("input").eq(0).val() + '" ' + selected + '>' + $(this).find("input").eq(0).val() + '</option>';
	});
	$("#edNAdicao").html(adicoes);
}

function filtrarCodEnqIPI(st) {
	if (st == '') {
		$('#camposIpi').hide();
	} else {
		$('#camposIpi').show();
	}
	$("#edCEnq option").hide();
	$("#edCEnq option.outros").show();
	switch(st){
		case "02":
		case "52":
			$("#edCEnq option.isencao").show();
			break;
		case "04":
		case "54":
			$("#edCEnq option.imunidade").show();
			break;
		case "05":
		case "55":
			$("#edCEnq option.suspensao").show();
			break;
		default:
			$("#edCEnq option.reducao").show();
			break;

	}
}

function atualizarCamposPorDestino(){
	$('#linhaCargaMedia').hide();
	$('#linhaSuframa').hide();
	if ($('#tipoPessoa').val() == 'J'){
		switch ($('#uf').val()) {
			case 'MT':
				$("#linhaCargaMedia").show();
				break;
			case 'AC':
			case 'AP':
			case 'AM':
			case 'RO':
			case 'RR':
				$("#linhaSuframa").show();
				break;
		}
	}
}

function exibirAlertasNfe(erros){
	var html = '<h4>Algumas informações podem causar a rejeição dessa nota</h4><ul>';
	$.each(erros, function(key, erro){
		html += '<li>' + erro + '</li>';
	});
	html += '</ul>';
	$("#mensagem").html(html).addClass("warn").removeClass("nomessage");
}

function exibirTribPartilha() {
	if ($('#tipoPartilha').val() == 'I') {
		$('#container_trib_partilha').hide();
	} else {
		$('#container_trib_partilha').show();
	}
}

function exibirCnpjFab() {
	if ($('#indEscala').val() == 'N') {
		$('#CNPJFab').parent().show();
	} else {
		$('#CNPJFab').parent().hide();
	}
}

function exibirAbasICMSISS() {
	var tipo = $('#edTipo').val();
	$('#link_aba_icms').parent().hide();
	$('#link_aba_iss').parent().hide();
	if (tipo == 'P') {
		$('#link_aba_icms').parent().show();
		$('#link_aba_iss').parent().hide();
	} else if (tipo == 'S') {
		$('#link_aba_icms').parent().hide();
		$('#link_aba_iss').parent().show();
	}
}

function exibirCamposII() {
	if ($('#selectStII').val() == '01') {
		$('#campos_ii').show();
	} else {
		$('#campos_ii').hide();
	}
}