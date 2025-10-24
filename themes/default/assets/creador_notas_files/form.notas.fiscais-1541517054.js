var situacao = "";
var stringTipo;
var strSit = "";
var criterio;
var tipo = "S";
var dataServidor, timerImpressaoEtiqueta;
var host = "";
var objEtiqueta = {};
var nroEtiquetas = 0;
var etiquetaImpressa = 0;
var possuiAcessoNFe = false;
var appletCarregado = false;
var appletCupomCarregado = false;
var appletEtiquetasCarregado = false;
var appletLoteCarregado = false;
var nomeArquivo ;
var idEmpresa = "";
var dadosItens = "";
var systemDir = "";
var idOrigemParaEstoque = 0;
var idItemTempProdutoCliente;
var aIdNota;
var qtdeAoEntrar = 0;
var qtdeAoSair = 0;
var arrayTags = {};
var arrayGrupos = {};
var idTag = 0;
var indGravado = 0;
var nfce = false;
var dialog = $("<div></div>");
var arrayMarkupLoja = new Array();
var okLoja = "<i class='icon-ok-sign' style='color: green'></i>";
var erroLoja = "<i class='icon-warning-sign' title='Erro ao sincronizar preço com as lojas virtuais' style='color: #ff9702'></i>";
var filtroEnviarDadosLojaVirtual	= ["Magento","VTex", "Tray", "OOK", "Mkx", "Shopify", "MercadoShops", "SkyHub", "Fbits", "CNova", "Bling", "RakutenOne", "Ciashop", "B2W", "Prestashop", "MercadoLivre", "EzCommerce", "Xtech", "LojaIntegrada", "PerasLoja", "Starter", "Z3", "JetUol", "MeusPedidos", "Netshoes", "Zattini", "KanuiWS", "TricaeWS", "DafitiWS", "VendasExternas", "Walmart", "RakutenGenesis", "WooCommerceWH", "Nuvemshop", 'Buscape', "MagentoV2", "iShoppingWS", "Toro", "Amazon", "Olist", "PlataformaNeo", "FbitsWS", "ViaVarejo", "Carrefour", "IntegraCommerce", "Bis2Bis", "OtimoNegocio", "WideCommerce", "Hibrido"];
var filtroNumeroNfeLojaVirtual		= ["Shopify"];
var filtroRastreamentoLojaVirtual	= ["Magento", "OOK", "Shopify", "MercadoShops","SkyHub","Fbits", "Ciashop", "Tray", "B2W", "Prestashop", "Xtech", "LojaIntegrada", "PerasLoja", "Starter", "Z3", "Netshoes", "Zattini", "KanuiWS", "TricaeWS", "DafitiWS", "VendasExternas", "WooCommerceWH", 'Buscape', "LojaModular", "MagentoV2", "iShoppingWS", "Toro", "Amazon",  "Nuvemshop", "Carrefour", "IntegraCommerce", "Bis2Bis", "OtimoNegocio"];
var filtroEnviarNFe 				= ["Magento", "VTex", "OOK", "Mkx", "MercadoShops", "Tray", "SkyHub", "Fbits", "RakutenOne", "Ciashop", "MercadoLivre", "EzCommerce", "B2W", "CNova", "LojaIntegrada", "PerasLoja", "Starter", "Z3", "JetUol", "MeusPedidos", "Netshoes", "Zattini", "Prestashop", "VendasExternas", "KanuiWS", "TricaeWS", "DafitiWS", "RakutenGenesis", "WooCommerceWH", 'Buscape', "LojaModular", "MagentoV2", "iShoppingWS", "Toro", "Olist", "FbitsWS", "ViaVarejo", "Carrefour", "IntegraCommerce", "Bis2Bis", "OtimoNegocio", "WideCommerce", "Hibrido"];
var filtroNumeroVendaLojaVirtual	= ["Magento", "Shopify", "MagentoV2", "Bis2Bis", "OtimoNegocio", "WideCommerce", "Hibrido"];
var filtroStatusLoja				= ["OOK", "VTex", "Tray", "Fbits", "RakutenOne", "Ciashop", "B2W", "SkyHub", "EzCommerce", "Xtech", "LojaIntegrada", "PerasLoja", "Starter", "Z3", "JetUol", "Netshoes", "Zattini", "KanuiWS", "TricaeWS", "DafitiWS", "VendasExternas", "Walmart", "RakutenGenesis", "WooCommerceWH", "LojaModular", "MagentoV2", "iShoppingWS", "Toro", "Amazon", "PlataformaNeo", "FbitsWS", "Prestashop", "Carrefour", "CNova", "Bis2Bis", "OtimoNegocio", "WideCommerce", "Hibrido"];
var posicaoScroll = 0;

function initForm(stringTipoNota, dataAtual, aHost,aIdEmpresa){
	idEmpresa = aIdEmpresa;
	host = aHost;
	dataServidor = dataAtual;
	framePagamento = new FramePayment({
		showPaymentMethods: true,
		type: tipo == 'E' ? 1 : 2
	});

	$(document).history(function(e, currentHash, previousHash) {
		if(currentHash=='list'){
			displaySearch();
		} else if (currentHash=='add') {
			incluirNotaFiscal();
		} else if (currentHash.split('/')[0]=='edit') {
			notaId = $.history.getCurrent().split('/')[1];
			editarNotaFiscal(notaId);
		} else if (currentHash.split('/')[0]=='tinyshop') {
			pedidoId = $.history.getCurrent().split('/')[1];
			obterNotaTinyShop(pedidoId);
		} else if (currentHash.split('/')[0]=='magento') {
			pedidoId = $.history.getCurrent().split('/')[1];
			obterNotaMagento(pedidoId);
		} else if (currentHash.split('/')[0]=='tray') {
			pedidoId = $.history.getCurrent().split('/')[1];
			obterNotaTray(pedidoId);
		}else if (currentHash.split('/')[0]=='os') {
			ordemServicoId = $.history.getCurrent().split('/')[1];
			obterNotaOrdemServico(ordemServicoId);
		} else if (currentHash.split('/')[0]=='prestashop') {
			pedidoId = $.history.getCurrent().split('/')[1];
			endereco = $.history.getCurrent().split('/')[2];
			obterNotaPrestashop(pedidoId, endereco);
		} else if (currentHash.split('/')[0]=='mercadoshops') {
			var pedidoMercadoShopsId = $.history.getCurrent().split('/')[1];
			obterPedidoMercadoShops(pedidoMercadoShopsId);
		}

	});

	if($.history.getCurrent().split('/')[0]=='edit') {
		notaId = $.history.getCurrent().split('/')[1];
		editarNotaFiscal(notaId);
		$('#desconto').focus();
	} else if ($.history.getCurrent()=='add') {
		incluirNotaFiscal();
	} else if (getUrlParameter('idOrigem') != '') {
		clearForm();
		$('#idOrigem').val(getUrlParameter('idOrigem'));
		var pedidoId = getUrlParameter('idOrigem');
		var destino = getUrlParameter('destino');
		if (tipo == "E") {
			xajax_obterNotaFiscalPorPedidoCompra(pedidoId);
		} else {
			xajax_obterNotaFiscalPorVenda(pedidoId, destino);
		}
		displayForm();
		$('#itens').hide();
		$("#trh").hide();
		$('#linhaInclusaoItem').hide();
		$('#desconto').focus();
	} else if ($.history.getCurrent().split('/')[0]=='tinyshop') {
		pedidoId = $.history.getCurrent().split('/')[1];
		obterNotaTinyShop(pedidoId);
	} else if ($.history.getCurrent().split('/')[0]=='magento') {
		pedidoId = $.history.getCurrent().split('/')[1];
		obterNotaMagento(pedidoId);
	} else if ($.history.getCurrent().split('/')[0]=='tray') {
		pedidoId = $.history.getCurrent().split('/')[1];
		obterNotaTray(pedidoId);
	} else if (getUrlParameter('idNotaOrigem') != '') {
		var pedidoId = getUrlParameter('idNotaOrigem');
		copiarNota(pedidoId , "S");
	} else if (getUrlParameter('idVendas') != '') {
		var pedidosIds = getUrlParameter('idVendas');
		montarNotaDeVariasPedidos(pedidosIds);
	} else if (getUrlParameter('idOrdemServico') != '') {
		ordemServicoId = getUrlParameter('idOrdemServico');
		obterNotaOrdemServico(ordemServicoId);
	} else if ($.history.getCurrent().split('/')[0]=='prestashop') {
		pedidoId = $.history.getCurrent().split('/')[1];
		endereco = $.history.getCurrent().split('/')[2];
		obterNotaPrestashop(pedidoId, endereco);
	} else if ($.history.getCurrent().split('/')[0]=='mercadoshops') {
        var pedidoMercadoShopsId = $.history.getCurrent().split('/')[1];
        obterPedidoMercadoShops(pedidoMercadoShopsId);
	} else if ($.history.getCurrent().split('/')[0]=='nfce') {
		notaId = $.history.getCurrent().split('/')[1];
		nfce = true;
		editarNotaFiscal(notaId);
		$('#desconto').focus();
	}

	vincularEnventosCamposLinha();
	stringTipo = stringTipoNota;
	xajax_inicializarArraySts();
	$('.act-relatorio').click(function() {window.open('relatorios/relatorio.notas.fiscais.php?tipoNota=' + tipo + (situacao == 'C' ? '&filtro=' + situacao : '')); return false;} );

	$('.act-excluir').on('click', function() {
		if (validarSelectedItems()) {
			excluirNotasFiscais();
			selectedItems = [];
		}
	});

	$('.act-imprimir-etiquetas').on('click', function() {
		if (validarSelectedItems()) {
			imprimirEtiquetas();
		}
	});

	$('.act-enviar-selecionadas').on('click', function() {
		if (validarSelectedItems()) {
			enviarNFesSelecionadas();
		}
	});

	$('.act-print').on('click', function() {
		if (validarSelectedItems()) {
			imprimirNFesSelecionadas();
		}
	});

	$('.act-mover-ambiente').on('click', function() {
		if (validarSelectedItems()) {
			moverNFesSelecionadas();
		}
	});

	$('.act-exportar-xml').on('click', function() {
		if (validarSelectedItems()) {
			exportarXmlsInit();
		}
	});

	$('.act-etiquetas').on('click', function() {
		if (validarSelectedItems()) {
			imprimirEtiquetasCorreiosSelecionadas();
		}
	});

	$('.act-gerar-gnre').on('click', function() {
		if (validarSelectedItems()) {
			gerarGnresSelecionadas();
		}
	});

	$('.act-sigepweb').click( function() {
		if (validarSelectedItems()) {
			exportarContatosSIGEPWeb();
		}
	});

	$(".titulo").click(function() {
		togglePesquisa();
	});
	$("#link-close-pesquisa").click(function() {
		togglePesquisa();
	});
	$("#link-pesquisa").click(function() {
		togglePesquisa();
	});
	$(".item").click(function() {
		$(".item").removeClass("selected");
		$(this).addClass("selected");
		$("#div-mes").hide();
		$("#div-data").hide();
		$("#div-periodo").hide();
		criterio = $(this).attr("id").substring(4);
		return false;
	});
	$(".item-sit").click(function() {
		$(".item-sit").removeClass("selected");
		$(this).addClass("selected");
		situacao = $(this).attr("id").substring(4);
		strSit = $(this).html();
		listar();
		return false;
	});
	if (tipo == "S"){
		$("#ordenacaoPeriodo").val("emissao");
	} else {
		$("#ordenacaoPeriodo").val("entradasaida");
	}
	$("#ordenacaoPeriodo").change(function(){
		listar();
	});
	$("#data-fim").change(function() {
		atualizarTitulo(stringTipo +" "+ $('#data-ini').val() + " - " + $('#data-fim').val());
		listar();
	});
	$("#data-ini").change(function() {
		atualizarTitulo(stringTipo +" "+ $('#data-ini').val() + " - " + $('#data-fim').val());
		listar();
	});
	$("#opc-ultimos").click(function() {
		atualizarTitulo("Últimas " + stringTipo);
		listar();
	});
	$("#opc-ult30").click(function() {
		incUltimos(30);
		atualizarTitulo(stringTipo +" "+ $('#data-ini').val() + " - " + $('#data-fim').val());
		listar();
	});
	$("#opc-mes").click(function() {
		$("#div-mes").show();
		$("#mes").focus();
		incrementaMeses(0);
	});
	$("#opc-data").click(function() {
		$("#div-data").show();
		$("#p-data").focus();
		incrementaDias(0);
	});
	$("#opc-periodo").click(function() {
		$("#div-periodo").show();
		$("#data-ini").focus();
		atualizarTitulo(stringTipo +" "+ $('#data-ini').val() + " - " + $('#data-fim').val());
		listar();
	});
	$("#pesquisa-mini").focus();

	$('#p-data').val(getDataAtual(dataServidor));
	$('#mes').val(getMesAtual(dataServidor)+"/"+getAnoAtual(dataServidor));
	$('#data-ini').val(getDataInicialMes(dataServidor));
	$('#data-fim').val(getDataFinalMes(dataServidor));
	$('#pesquisa-mini').val("");

	situacao = "";
	$(".item-sit").removeClass("selected");
	$('#sit-'+situacao).addClass("selected");

	$('.act-exportar').click( function() {
		window.location = 'exportacao.xml.nfe.php?tipo=' + tipo + '&dataIni=' + $('#data-ini').val() + '&dataFim=' + $('#data-fim').val();
		return false;
	});

	$("#nomeVendedor").autocomplete({
		source : "services/vendedores.lookup.php",
		select: function(event, ui) {
			setIdVendedorjQuery(ui["item"]);
		},
		change: function(event, ui) {
			testCompleter($(this), $("#idVendedor"), "Vendedor não encontrado no sistema");
		},
		delay:250,
		minLength:2,
		selectOnly:true
	});

	$("#psqVendedor").autocomplete({
		source : "services/vendedores.lookup.php",
		select: function(event, ui) {
			setIdVendedorPsq(ui["item"]);
		},
		change: function(event, ui) {
			testCompleter($(this), $("#idPsqVendedor"), "Vendedor não encontrado no sistema", false);
		},
		delay:250,
		minLength:2,
		selectOnly:true
	});

	criterio = "ult30";
	pagina = 1;
	adv = 'false';
	if(adv=='true'){
		togglePesquisa();
	}
	$('#opc-' + criterio).click();
	initFormatters($("#dec_qtde").val(), $("#dec_valor").val());
	$("#nomeVendedor").tipsy({gravity: $.fn.tipsy.autoWE});
	$("#psqVendedor").tipsy({gravity: $.fn.tipsy.autoWE});

	$("#edQuantidade").bind("focus", function () {
		qtdeAoEntrar = parseFloat(nroUsa($(this).val()));
	});

	$("#edQuantidade").bind("blur", function () {
		qtdeAoSair = parseFloat(nroUsa($(this).val()));
		if (qtdeAoEntrar != qtdeAoSair) {
			$("#qtdeEstoque").val($("#edQuantidade").val());
			$("#qtdeProdutoEquivalente").val($("#edQuantidade").val());
			atualizarItemTemp("qtdeEstoque", $("#qtdeEstoque").val());
			atualizarItemTemp("qtdeProdutoEquivalente", $("#qtdeProdutoEquivalente").val());
		}
	});

	$("#nomePsqProduto").autocomplete({
		source: "services/produtos.lookup.php",
		select: function(event, ui) {
			setIdPsqProdutojQuery(ui["item"]);
		},
		change: function(event, ui) {
			testCompleter($(this), $("#idPsqProduto"), "Produto não encontrado no sistema", false);
		},
		delay:500,
		minLength:2,
		selectOnly:true
	});

	$(document).on("click", "img[data-action='abrirPopupRastroObjeto']", function(){abrirPopupRastroObjeto($(this).parent().parent().attr("data-origem-logistica"), $(this).parent().parent().attr("idContato"));});
	$("#edCest").autocompleteCest({ncmField: $("#edCf"), callback: function() {atualizarItemTemp('cest',$("#edCest").val())}});

	try {
		xajax_validarPreRequisitosNfe();
	} catch (e){
		alert("Não foi possível comunicar com o servidor. Motivo: " + e);
	}

	bindDataVendaAlterada($("#dataEmissao"));
	$('#numero').focus(function(){
		if ($('#numero').val() == '' && !($('#notaTipo').val() == 'X' || $('#notaTipo').val() == 'T')) {
			$('#numero').val($('#proximoNumero').val());
		}
	});
	$('#numero').blur(function() {
		if (!($('#notaTipo').val() == 'X' || $('#notaTipo').val() == 'T') && $('#numero').val() == $('#proximoNumero').val()) {
			$('#numero').val('');
		}
	});
	listenerPermissaoForm();

	$(".buscaCep").click(function() {
		idClick = this.id;
		if(idClick === 'buscaEndereco') {
			cepVal = $('#cep').val();
			itens = '{"cep":"cep","municipio":"cidade","uf":"uf","bairro":"bairro","endereco":"endereco","idMunicipio":"codCidade"}';
			itensValidar = ["cep","municipio"];
		} else if(idClick === 'buscaEnderecoDiferente') {
			cepVal = $('#etiqueta_cep').val();
			itens = '{"etiqueta_cep":"cep","etiqueta_municipio":"cidade","etiqueta_uf":"uf","etiqueta_bairro":"bairro","etiqueta_endereco":"endereco","etiqueta_id_municipio":"codCidade"}';
			itensValidar = ["etiqueta_cep","etiqueta_municipio"];
		}
		$.processaControle(cepVal, itens, itensValidar);
		$(this).hide();

		return false;
	});
	setCamposConsCad("ie", "contato", "endereco", "enderecoNro", "bairro", "idMunicipio", "cidade", "cep", "uf", 'cnpj');
	initPopovers({'elements': $('.icon-info-novo')});

	$("#gtin, #gtinEmbalagem").on("change", function() {
		var icones = ($(this).attr("id") == "gtin" ? "#icones_gtin" : "#icones_gtin_embalagem");
		$(icones).empty();

		if ($(this).val().length) {
			$.post('utils/requestMethods.php', {action: 'produtoGtinDuplicado', arguments: {'id': $("#edIdProduto").val(), 'gtin': $(this).val()}}, function(data) {
				if (data.duplicado) {
					$(icones).html("<a class='formIcon' title='Já existe um produto cadastrado com esse GTIN.'><i id='iconWarning' class='icon-warning-sign'></i></a>");
				}
			});
		}
	});

	$('#refNFe').change(function() {
		$('#refNFe').val($('#refNFe').val().replace(/ /g, ''));
	});

	//$('#exportarXmlsNotas').initInputSelector();
	//$('#exportarXmlsCartasCorrecao').initInputSelector();

	$('#finalidade, #indPres').on('change', exibeOcultaDocReferenciado);

	$('#listaNotasFiscais').initSelectedCheckboxes(function() {
		countChecked2();
	});

	$(document).on('click', '#listaNotasFiscais #checkAllNotas', function() {
		$.each($('#listaNotasFiscais input[type="checkbox"]'), function() {
			addSelectedCheckboxesToArray($(this), selectedItems);
		});

		countChecked2();
	});

	$(document).on('click', '#listaNotasFiscais input[type="checkbox"]', function() {
		if (readCookie('tourMultipleCheckboxesNotas') == null) {
			var step = {
				'title': 'Alteração!',
				'content': 'Agora você pode executar ações com notas fiscais selecionadas <b>entre páginas</b>, sem perder a seleção previamente feita.',
				'target': this,
				'placement': 'right'
			}

			hopscotchInfo('tour_multiplecheckboxes_notas', step, 'tourMultipleCheckboxesNotas', true);
		}
	});

	$(document).on('click', '#primeira, #anterior, #proxima, #ultima, #listaNotasFiscais input[type="checkbox"]', function() {
		var nSelecionados = getIdsSelectedItems().length;

		if (($.inArray($(this).attr('id'), ['primeira', 'anterior', 'proxima', 'ultima']) != -1 && nSelecionados) || $('.warn-search').is(':visible')) {
			if (nSelecionados) {
				atualizarMsgSearch('Foram selecionadas <b>' + nSelecionados + ' nota(s)</b>.<br>Clique <a id="uncheck_all">aqui</a> para desmarcar a seleção.');
			} else {
				if (situacao == '' && criterio == 'ultimos') {
					$('.warn-search').hide();
				} else {
					montarMsgSearch();
				}
			}
		}
	});
}

function setIdPsqProduto(idPsqProduto) {
	$('#idPsqProduto').val(idPsqProduto);
}

function setIdPsqProdutojQuery(param) {
	$('#idPsqProduto').val(param.id);
	$('#nomePsqProduto').removeClass("ac_error");
	$('#nomePsqProduto').addClass("tipsyOff");
	$('#nomePsqProduto').removeAttr("title");
	$('#nomePsqProduto').focus();
	listar();
}

function testarIdPsqProduto(){
	if(($("#idPsqProduto").val() > 0)){
		listar();
	} else {
		if (($("#nomePsqProduto").val() == "") && ($("#idPsqProduto").val() == "0")) {
			listar();
		}
	}
}

function excluirNotasFiscais() {
	var confirmouExclusao = false;

	if ($('#possui_restricao_nfe').val() == 'S') {
		$.each(getIdsSelectedItems(), function(i, idNota) {
			var situacao = $('input[type="checkbox"][value="' + idNota + '"]').parents('tr').attr('situacao');

			if ((situacao == '6') || (situacao == '7') || (situacao == '10')) {
				confirmouExclusao = confirm('A nota fiscal será excluída do sistema. Antes de realizar a exclusão, você deve armazenar o arquivo XML.\nConfirma a exclusão da nota fiscal selecionada?');
			} else if ((situacao == '4') || (situacao == '9')) {
				confirmouExclusao = confirm('O envio da nota fiscal selecionada não foi concluído.\nAntes de excluí-la, recomendamos que você verifique se a mesma foi recebida pela secretaria da fazenda, armazenando o arquivo XML antes de realizar a exclusão.\nConfirma a exclusão da nota fiscal selecionada?');
			} else {
				confirmouExclusao = confirm('Notas fiscais enviadas não podem ser excluídas, sem o armazenamento do respectivo XML.\nConfirma a exclusão da nota fiscal selecionada?');
			}
			//1-Pendente
			//2-Emitida
			//3-Cancelada
			//4-Enviada - Aguardando recibo
			//5-Rejeitada
			//6-Autorizada
			//7-Emitida DANFE
			//8-Registrada
			//9-Enviada - Aguardando protocolo
			//10-Denegada
		});
	} else {
		confirmouExclusao = confirm('Notas fiscais enviadas não podem ser excluídas, sem o armazenamento do respectivo XML.\nApós a exclusão é recomendado inutilizar ou reaproveitar o número dessa nota.\nConfirma a exclusão das ' + stringTipo + ' selecionadas?');
	}

	if (confirmouExclusao) {
		displayWait('waitNotas');
		xajax_excluirNotasFiscais(getIdsSelectedItems(), tipo);
	}
}

function incrementaDias(n) {
	$('#p-data').val(incDias($('#p-data').val(), n));
	atualizarTitulo(stringTipo +" do dia " + $("#p-data").val());
	$('#data-ini').val($('#p-data').val());
	$('#data-fim').val($('#p-data').val());
	listar();
}

function incrementaMeses(n) {
	var data = "01/"+$('#mes').val();
	$('#mes').val(incMeses(data, n).substring(3));
	atualizarTitulo(stringTipo + " do mês " + $("#mes").val());
	var data2 = "01/"+$('#mes').val();
	$('#data-ini').val(getDataInicialMes(data2));
	$('#data-fim').val(getDataFinalMes(data2));
	listar();
}

function incUltimos(n) {
	$('#data-ini').val(incDias($('#p-data').val(), -n));
	$('#data-fim').val(getDataAtual());
}

function paginarNotasFiscais(registrosPorPagina, totalRegistros) {
	paginacao(registrosPorPagina, totalRegistros, false);
	$('#registrosPorPagina').val(registrosPorPagina);
}

function ativarTotalizadores() {
	countChecked2();
}

function listar() {
	removerPaginacao();
	if(situacao == "" && criterio == "ultimos"){
		$(".warn-search").hide();
	}else{
		montarMsgSearch();
	}
	displayWait('waitNotas');
	var dataInicial = $('#data-ini').val();
	var dataFinal = $('#data-fim').val();
	var pesquisa = $('#pesquisa-mini').val();
	var adv = $("#search").hasClass('selected');
	var idVendedorPsq = $("#idPsqVendedor").val();
	var codRastreio = $("#codRastreio").val();
	if (typeof codRastreio == "undefined") {
		codRastreio = "";
	}
	var situacaoRastro = $("#situacaoObjetoCorreios").val();
	if (typeof situacaoRastro == "undefined") {
		situacaoRastro = "";
	}

	var uf = $("#psqUF option:selected").val();
	if (typeof uf == "undefined") {
		uf = " ";
	}
	xajax_listarNotasFiscais(tipo, situacao, criterio, pagina, pesquisa, dataInicial, dataFinal, idVendedorPsq, adv, codRastreio, $("#idPsqProduto").val(), $("#psqNumeroPedidoDaLojaVirtual").val(), situacaoRastro, uf, $("#ordenacaoPeriodo").val(), xajax.getFormValues("notaFiscalFiltros").replace(/\§/g, '.'), function() {
		var nSelecionados = getIdsSelectedItems().length;

		if (nSelecionados) {
			atualizarMsgSearch('Foram selecionadas <b>' + nSelecionados + ' nota(s)</b>.<br>Clique <a id="uncheck_all">aqui</a> para desmarcar a seleção.');
		}

		renderCheckedItems(selectedItems, 'listaNotasFiscais');
	});
	atencaoConta();
}

function montarMsgSearch(){
	var msg = "";
	var m1 = "";
	var m2 = "";
	if(situacao != ""){
		m1 = 'da situação "'+strSit+'"';
	}
	switch(criterio){
		case 'ult30':
			m2 = ' dos <strong>últimos 30 dias</strong>';
			break;
		case "mes":
			m2 = " do mês "+$("#mes").val();
			break;
		case "data":
			m2 = " do dia "+$("#p-data").val();
			break;
		case "periodo":
			m2 = " do período de "+$("#data-ini").val()+" a "+$("#data-fim").val();
			break;
	}
	var msg = 'Exibindo notas fiscais '+m1+m2;
	msg += "<br/>Clique <a href='javascript:removerTodosFiltros()'>aqui</a> para remover este filtro.";

	atualizarMsgSearch(msg);
}

function removerTodosFiltros(){
	situacao = "";
	criterio = "ult30";
	pagina = "1";
	$(".item-sit").removeClass("selected");
	$(".item-sit[id='sit-']").addClass("selected");
	$(".item[id='opc-ultimos']").click();
	$(".warn-search").hide();
}

function listarUltimos() {
	removerPaginacao();
	$(".item").removeClass("selected");
	$("#opc-ultimos").click();
}

function fitrarTipo(tipo){
	document.getElementById('tipo').value = tipo;
	listarNotas();
}

function fitrarSituacao(situacao){
	document.getElementById('situacao').value = situacao;
	listarNotas();
}

function fitrarPeriodo(periodo){
	document.getElementById('periodo').value = periodo;
	listarNotas();
}

function listarDadosPeriodo(){
	document.getElementById('periodo').value = 'periodo';
	listarNotas();
}

function estornarContasNota(idNota){
	xajax_estornarContasNota(idNota);
}

function lancarContasNota(idNota){
	xajax_lancarContasNota(idNota, function(data) {
		if (data['error']) {
			DialogMessage.error({ alertTitle: 'Não foi possível lançar contas e comissões.', description: 'Motivo: ' + data['msg']});
		}
		listar();
	});
}

function estornarEstoqueNota(idNota){
	xajax_estornarEstoqueNota(idNota);
}

function lancarEstoqueNota(idNota){
	xajax_lancarEstoqueNota(idNota);
}

function gerarBoletos(id){
	xajax_verificaConfiguracaoDaContaPadrao(id);
    newTab = window.open('', '_blank');
}

function contaConfigurada(id) {
	xajax_lancarContasNota(id, function() {
        newTab.location = 'gerar.boleto.php?idOrigem='+id;
    });
}

function contaNaoConfigurada() {
    newTab.close();
	new Boxy.load('templates/form.config.conta.contabil.padrao.popup.php', {
		title: "Emissão de boleto",
		modal: true,
		afterShow: configurarContaPadrao
	});
}

function configurarContaPadrao() {
	$("#mensagem_popup").html("<h4>Não foi possível emitir o boleto</h4>Não existe uma conta padrão para emissão de boletos ou sua configuração está incompleta.");
        xajax_obterContasBanco();
}

function vincularEventosLine() {
	$(".tline").unbind();
	$(".nfe_msg").unbind("click");
	$(".tline").parent().children().bind("mouseenter", function() {$(this).parent().addClass("highlight"); $(this).parent().css("cursor", "pointer")});
	$(".tline").parent().children().bind("mouseleave", function() {$(this).parent().removeClass("highlight")});
	$(".tline").bind("click", function() {editarNotaFiscal($(this).parent().attr("idNota"))});

	$(".nfe_msg").bind("click", function() {mostrarMensagem($(this).parent().parent().attr("idNota"))});

	$(".button-navigate").contextMenu("popUpMenu", {
		onContextMenu: function(e) {
			if ($(e.target).attr("id") == "dontShow")
				return false;
			else
				return true;
		},
		bindings : {
			'im_1' : function(t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupNFe($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("nome"), $(t).parent().parent().attr("protocolo"), $(t).parent().parent().attr("situacao"), "outras");
			},
			'im_2' : function(t) {
				incluirNotaFiscalComplementar($(t).parent().parent().attr("idNota"));
			},
			'im_4' : function(t) {
				copiarNota($(t).parent().parent().attr("idNota"));
			},
			'im_5' : function(t) {
				gerarBoletos($(t).parent().parent().attr("idNota"));
			},
			'im_6' : function(t) {
				$(".button-navigate").attr("disabled", true);
                displayWait('waitNotas', true, 'Estornando conta, aguarde...');
				estornarContasNota($(t).parent().parent().attr("idNota"));
			},
			'im_7' : function(t) {
				$(".button-navigate").attr("disabled", true);
                displayWait('waitNotas', true, 'Lançando conta, aguarde...');
				lancarContasNota($(t).parent().parent().attr("idNota"));
			},
			'im_8' : function(t) {
				$(".button-navigate").attr("disabled", true);
				estornarEstoqueNota($(t).parent().parent().attr("idNota"));
			},
			'im_9' : function(t) {
				$('.button-navigate').attr('disabled', true);
				xajax_verificaLancarEstoque($(t).parent().parent().attr('idNota'), function() {
					$('.button-navigate').attr('disabled', false);
				});
			},
			'im_10' : function(t) {
				if ($("#possui_restricao_nfe").val() == "S") {
					xajax_enviarEmailNotaGratis($(t).parent().parent().attr("idNota"));
				} else {
					abrirPopupEnvioDocumento($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("idContato"), $(t).parent().parent().attr("chaveAcesso"));
				}
			},
			'im_12' : function(t) {
				imprimirEtiquetas($(t).closest('tr').find('.tcheck'));
			},
			'im_14' : function(t) {
				abrirTelaCupom($(t).parent().parent().attr("idNota"));
			},
			'im_15' : function (t) {
				imprimirDANFEaPartirDaLista($(t).parent().parent().attr("idNota"));
			},
			'im_16' : function (t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupNFe($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("nome"), $(t).parent().parent().attr("protocolo"), $(t).parent().parent().attr("situacao"), "cancelamento", $(t).parent().parent().attr("idContato"), $(t).parent().parent());
			},
			'im_17' : function (t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupNFe($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("nome"), $(t).parent().parent().attr("protocolo"), $(t).parent().parent().attr("situacao"), "envio", $(t).parent().parent().attr("idContato"));
			},
			'im_19' : function (t) {
				$(".button-navigate").attr("disabled", true);
				copiarNotaInversa($(t).parent().parent().attr("idNota"));
			},
			'im_20' : function (t) {
				$(".button-navigate").attr("disabled", true);
				exibirFormacaoDePrecos($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("estoque"));
			},
			'im_22' : function (t) {
				imprimirEspelhoDaNota($(t).parent().parent().attr("idNota"));
			},
			'im_23' : function (t) {
				enviarEspelhoDaNotaPorEmail($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("idContato"));
			},
			'im_24' : function (t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupNFe($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("nome"), $(t).parent().parent().attr("protocolo"), $(t).parent().parent().attr("situacao"), "cartaCorrecao");
			},
			'im_25' : function(t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupNFe($(t).parent().parent().attr("idNota"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("nome"), $(t).parent().parent().attr("protocolo"), $(t).parent().parent().attr("situacao"), "consultaRecibo");
			},
			'im_26' : function(t) {
				$(".button-navigate").attr("disabled", true);
				abrirPopupOcorrencia($(t).parent().parent().attr("idNota"));
			},
			'im_27' : function(t) {
				var idObjetoPlp = $(t).parent().parent().attr("data-objetoplp");
				var tipoIntegracaoLogistica = $(t).parent().parent().attr("data-tipo-integracao-logistica");
				var integracaoLogistica = LogisticFactory.create(tipoIntegracaoLogistica);
				var impressaoIndividual = (integracaoLogistica.individualLabelPrint() ? 1 : 0);

				if (parseInt(idObjetoPlp) == 0) {
					displayWait("impressaoWait", true, "Imprimindo as etiquetas selecionadas, aguarde...");
					idOrigem = $(t).parent().parent().attr("idnota");
					xajax_imprimirRotulo({ "etiquetas": [{ "idObjetoPlp": idObjetoPlp, "idOrigem": idOrigem, "possuiEtiqueta": 0 }] }, "etiqueta", "N", impressaoIndividual);
				} else {
					var dadosObj = { "etiquetas" : [{ "idObjetoPlp": idObjetoPlp, "possuiEtiqueta" : 1 }] };

					if (integracaoLogistica.externalLabel()) {
						if(tipoIntegracaoLogistica == "MercadoEnvios") {
							choosePrintFormat(dadosObj);
						} else {
							displayWait("impressaoWait", true, "Imprimindo as etiquetas selecionadas, aguarde...");
							printWin = window.open("./impressao/etiquetasEnvio.php", "_blank");
							xajax_imprimirRotuloExterno(dadosObj, impressaoIndividual);
						}
					} else {
						displayWait("impressaoWait", true, "Imprimindo as etiquetas selecionadas, aguarde...");
						xajax_imprimirRotulo(dadosObj, "etiqueta", "N", impressaoIndividual);
					}
				}
			},
			'im_55' : function(t) {
				displayWait("impressaoWait", true, "Imprimindo os AR's selecionados, aguarde...");
				xajax_imprimirRotulo({"etiquetas" : [{"idObjetoPlp" : $(t).parent().parent().attr("data-objetoplp"), "possuiEtiqueta" : 1}]}, "ar", "N");
			},
			'im_66' : function(t) {
				enviarCodigoRastreamentoPorEmail($(t).parent().parent().attr("data-origem-logistica"), $(t).parent().parent().attr("numero"), $(t).parent().parent().attr("etiquetacorreios"), $(t).parent().parent().attr("idContato"), $(t).parent().parent().attr("idMagento"), $(t).parent().parent().attr("data-objetoplp"), $(t).parent().parent().attr("tipointegracao"));
			},
			'im_consultarSituacao': function(t){
				abrirPopupConsultarSituacao(t);
			},
			'im_gerar_pdf': function(t){
				gerarPdfNfe($(t).parent().parent().attr("idNota"));
			}
		},

		onShowMenu: function(e, menu) {
			var situacao = $(e.target).parent().parent().attr("situacao");
			var notaTipo = $(e.target).parent().parent().attr("notaTipo");
			var etiquetaCorreios = $(e.target).parent().parent().attr("etiquetaCorreios");
			var tipoIntegracao = $(e.target).parent().parent().attr("tipoIntegracao");

			if (situacao == 11){
				$('#im_2', menu).remove();
				$('#im_16', menu).remove();
				$('#im_17', menu).remove();
			} else {
				$('#im_consultarSituacao', menu).remove();
			}

			if ((situacao != "4") && (situacao != "9")){
				$('#im_25', menu).remove();
			}

			if (!((situacao == "6") || (situacao == "7"))) {
				$('#im_24', menu).remove();
				$('#im_gerar_pdf', menu).remove();
			}

			if (notaTipo == "T") {
				if (tipo == "E"){
					$('#im_15', menu).remove();
				}
				$('#im_17', menu).remove();
			}

			if (! possuiAcessoNFe) {
				$('#im_1', menu).remove();
				$('#im_2', menu).remove();
				$('#im_15', menu).remove();
				$('#im_16', menu).remove();
				$('#im_17', menu).remove();
				$('#im_23', menu).remove();
				$('#im_24', menu).remove();
				$('#im_consultarSituacao', menu).remove();
			} else {
				if ((situacao == "3") || (situacao == "10")) {
					$('#im_2', menu).remove();
				}
			}

			if ((tipo == "E") || ($(e.target).parent().parent().attr("podeBoleto") == "N")) {
				$('#im_5', menu).remove();
			}

			if ($(e.target).parent().parent().attr("contas") == "V" || $(e.target).parent().parent().attr("contas") == "F") {
				$('#im_5', menu).remove();
				$('#im_6', menu).remove();
				$('#im_7', menu).remove();
			} else {
				if ($(e.target).parent().parent().attr("contas") != "S") {
					$('#im_6', menu).remove();
					if ((situacao == "3") || (situacao == "10")) {
						$('#im_7', menu).remove();
					}
				} else {
					$('#im_7', menu).remove();
				}
			}

			if ($(e.target).parent().parent().attr("estoque") == "R") {
				$('#im_8', menu).remove();
				$('#im_9', menu).remove();
			} else {
				if ($(e.target).parent().parent().attr("estoque") != "S") {
					$('#im_8', menu).remove();
					if ((situacao == "3") || (situacao == "10")) {
						$('#im_9', menu).remove();
					}
				} else {
					$('#im_9', menu).remove();
				}
			}

			if ((situacao == "3") || (situacao == "10")) {
				$('#im_10', menu).remove();
			}

			if ((situacao == "3") || (situacao == "10")) {
				$('#im_12', menu).remove();
				$('#im_16', menu).remove();
				$('#im_17', menu).remove();
			}
			//if (situacao == "9") {
			//	$('#im_1', menu).remove();
			//	$('#im_2', menu).remove();
			//}

			if (possuiAcessoNFe) {
				if ((situacao == "2") || (situacao == "6") || (situacao == "7") || (situacao == "10")) {
					$('#im_17', menu).remove();
				}
				if ((situacao == "1") || (situacao == "5") || (situacao == "8") || (situacao == "10")) {
					$('#im_16', menu).remove();
				}
				if ((situacao == "9") || (situacao == "4")) {
					$('#im_16', menu).remove();
					$('#im_17', menu).remove();
				}
				if (situacao == "1"){
					$('#im_10', menu).remove();
				}
			}
			if (etiquetaCorreios == "") {
				$('#im_55',menu).remove();
				$('#im_66',menu).remove();
			}

			if (tipoIntegracao == '' || tipoIntegracao != 'Api') {
				$('#im_26', menu).remove();
			}

			var tipoIntegracaoLogistica = $(e.target).parent().parent().attr("data-tipo-integracao-logistica");
			if(tipoIntegracaoLogistica.length > 0) {
				LogisticFactory.create(tipoIntegracaoLogistica).initFormOrderFiscal();
			} else {
				LogisticFactory.create("Correios").initFormOrderFiscal();
			}

			return menu;
		}
	});
}

function buscarPedido() {
	new Boxy.load("templates/form.pedido.tinyshop.popup.php", {
		title: "Integração TinyShop",
		modal: true,
		unloadOnHide: true,
		afterShow: ajustarFormTinyShop
	});
}

function ajustarFormTinyShop() {
	$('#numeroPedido').focus();
}

function buscarDadosPedido(){
	$('#message-pedido').html("");
	$('#message-pedido').removeAttr("class");
	$('#numeroPedido').removeAttr("class");
	if ($('#numeroPedido').val() == "") {
		$('#message-pedido').html('Informe o número do pedido!');
		$('#message-pedido').addClass('error');
		$('#numeroPedido').addClass('warning');
	} else {
		//window.location="notas.fiscais.php?tinyShop=S&numeroPedido="+$('#numeroPedido').val();
		$.history.add('tinyshop/'+$('#numeroPedido').val());
		obterNotaTinyShop($('#numeroPedido').val());
	}
}

function buscarPedidoTray() {
	new Boxy.load("templates/form.pedido.tray.popup.php", {
		title: "Integração Tray",
		modal: true,
		unloadOnHide: true,
		afterShow: ajustarFormTray
	});
}

function ajustarFormTray() {
	$('#numeroPedidoTray').focus();
}

function buscarDadosPedidoTray() {
	$('#message-pedido').html("");
	$('#message-pedido').removeAttr("class");
	$('#numeroPedidoTray').removeAttr("class");
	if ($('#numeroPedidoTray').val() == "") {
		$('#message-pedido').html('Informe o número do pedido!');
		$('#message-pedido').addClass('error');
		$('#numeroPedidoTray').addClass('warning');
	} else {
		$.history.add('tray/' + $('#numeroPedidoTray').val());

		var importarRazaoSocial = 0;
		if($("#carregarRazaoSocial").prop("checked")) {
			importarRazaoSocial = 1;
		}

		obterNotaTray($('#numeroPedidoTray').val(), importarRazaoSocial);
	}
}

function buscarPedidoMagento() {
	new Boxy.load("templates/form.pedido.magento.popup.php", {
		title: "Integração Magento",
		modal: true,
		unloadOnHide: true,
		afterShow: ajustarFormMagento
	});
}

function buscarPedidoPrestashop() {
	new Boxy.load("templates/form.pedido.prestashop.popup.php", {
		title: "Integração Prestashop",
		modal: true,
		unloadOnHide: true,
		afterShow: ajustarFormPrestashop
	});
}

function buscarListaDePedidoPrestashop() {
	new Boxy.load("templates/form.lista.pedido.prestashop.popup.php", {
		title: "Integração Magento",
		modal: true,
		unloadOnHide: true,
		afterShow: ajustarFormListaDePedidosPrestashop
	});
}


function closePopupPrestashop(){
	Boxy.get("#formListaPedidoPrestashop").hide();
}

function editatPedidoApartirDeListaNoPrestashop(idNota){
	closePopupPrestashop();
	editarNotaFiscal(idNota);
}

function ajustarFormListaDePedidosPrestashop() {
	displayWait('waitPrestashop');
	xajax_obterListaDePedidosDoPrestashop();
}

function ajustarFormMagento() {
	$("tipoEndereco").prop("checked", false);
	$('#numeroPedido').focus();
}

function ajustarFormPrestashop() {
	$("tipoEndereco").prop("checked", false);
	$('#numeroPedido').focus();
}

function buscarDadosPedidoMagento() {
	$('#message-pedido').html("");
	$('#message-pedido').removeAttr("class");
	$('#numeroPedido').removeAttr("class");
	if ($('#numeroPedido').val() == "") {
		$('#message-pedido').html('Informe o número do pedido!');
		$('#message-pedido').addClass('error');
		$('#numeroPedido').addClass('warning');
	} else {
		$.history.add('magento/' + $('#numeroPedido').val());
		obterNotaMagento($('#numeroPedido').val());
	}
}

function buscarDadosPedidoPrestashop() {
	$('#message-pedido').html("");
	$('#message-pedido').removeAttr("class");
	$('#numeroPedido').removeAttr("class");
	if ($('#numeroPedido').val() == "") {
		$('#message-pedido').html('Informe o número do pedido!');
		$('#message-pedido').addClass('error');
		$('#numeroPedido').addClass('warning');
	} else {
		var tipoEnd = "1";
		if ($('#tipoEndereco').prop("checked")) {
			tipoEnd = "2";
		}
		$.history.add('magento/' + $('#numeroPedido').val() + '/' + tipoEnd);
		obterNotaPrestashop($('#numeroPedido').val(), tipoEnd);
	}
}

function abrirPopupEnvioDocumento(idNota, idContato, chaveAcesso) {
	if (possuiAcessoNFe) {
		if (chaveAcesso != "") {
			var link = host + "relatorios/nfe.xml.php?chaveAcesso=" + chaveAcesso;
			var mensagem = encodeURIComponent('<br/><br/>Segue o link para obter o arquivo XML da nota fiscal');
			dialog.dialog({
				autoOpen: false,
				title: 'Envio de documento por email',
				modal: true,
				resizable: false,
				close:function(){
					$(this).dialog("destroy");
					listar();
				},
				width: 425
			});

			dialog.load('templates/form.envio.documento.popup.php?idDoc=' + idNota + '&idContato=' + idContato + '&tipo=danfe&mensagemComplementar=' + mensagem + '&linkComplementar=' + link, '', function(){
				dialog.dialog("open");
				ajustarFormEnvioDoc();
			});
		} else {
			dialog.dialog({
				autoOpen: false,
				title: 'Envio de documento por email',
				modal: true,
				resizable: false,
				close:function(){
					$(this).dialog("destroy");
				},
				width: 425
			});
			dialog.load('templates/form.envio.documento.popup.php?idDoc=' + idNota + '&idContato=' + idContato + '&tipo=danfe', "", function(){
				dialog.dialog("open");
				ajustarFormEnvioDoc();
			});
		}
	} else {
		dialog.dialog({
			autoOpen: false,
			title: 'Envio de documento por email',
			modal: true,
			resizable: false,
			close:function(){
				$(this).dialog("destroy");
			},
			width: 425
		});
		dialog.load('templates/form.envio.documento.popup.php?idDoc=' + idNota + '&idContato=' + idContato + '&tipo=copiaNF', "", function(){
			dialog.dialog("open");
			ajustarFormEnvioDoc();
		});
	}
}

function ajustarFormEnvioDoc(){
	var idDoc = $("#idDocEnvioDoc").val();
	var idContato = $("#idContatoEnvioDoc").val();
	var tipoEnvio = $("#tipoEnvioDoc").val();
	displayWait('pleasewait');
	xajax_obterDadosDoDestinatario(idDoc, idContato, tipoEnvio);
	xajax_getDanfe(idDoc);
	$("#nomeDestinatario").focus();
}

function closeMessageDocComp(){
	dialog.dialog('close');
}

function popupAvisoNfe400() {
	if ($('#nfe_versao').val() == 3.10) {
		var ufLiberada = /*$('#nfe\\:liberar310').val() == 'S'*/false;
		var dialog = {
			'content': (ufLiberada == false ? $('#avisoNfe400bloqueio') : $('#avisoNfe400')),
			'textCancelar': (ufLiberada == false ? 'Voltar' : 'Continuar sem migrar'),
			'textOk': 'Migrar modelo 4.00',
			'htmlTitle': 'Atenção',
			'width': 440,
			fnOk: function() {
				window.open('nfe400.resumo.php');
				xajax_atualizarLayoutNfe(function() {
					window.location.reload(false);
				});
			},
			fnCancelar: function() {
				if (ufLiberada == false) {
					window.location.reload(false);
				} else {
					if ($('#nfe400check').prop('checked') == false) {
						$('#nfe400msgcheck').html('Para continuar sem migrar é necessário marcar a opção abaixo.').addClass('warning');
						return false;
					}
				}
			},
			fnBeforeClose: function() {
				window.location.reload(false);
			},
			fnCreate: function() {
				$('#nfe400check').prop('checked', false);
				$('#nfe400msgcheck').html('').removeClass('warning');
			}
		};
		createDialog(dialog);
	}
}

function abrirPopupNFe(idNota, numeroNota, nomeContato, protocoloNFe, situacao, opcao, idContato, elemento) {
	popupAvisoNfe400();
	if ($("#certificadoArmazenado").val() == "S") {

	} else {
		if (!appletCarregado && appAcessoHardware == "applet") {
			$("#applet").html('<applet code="NFe.class" name="NFe" width="0" height="0" archive="applets/nfe-13.0.jar" MAYSCRIPT><param name="codebase_lookup" value="false"></applet>');
			appletCarregado = true;
		}
	}

	var arguments = {action: "renderTemplate", link: "templates/form.nfe.php", getArguments: "?idNota=" + idNota + "&numero=" + numeroNota + "&nome=" + nomeContato + "&protocolo=" + protocoloNFe + "&situacao=" + situacao + "&opcao=" + opcao + "&tipoNota=" + tipo + "&dataEmissao=" + $(elemento).attr("dataEmissao")};

	getRenderedTemplate(arguments, function(data){
		new Boxy(data, {
			modal: true,
			unloadOnHide: true,
			afterShow: ajustarFormNfe,
			afterHide: listar
	})});


	if (opcao == "envio") {
		$("#idEnvioDoc").val("0");
		$("#html_mail").val("");
		$("#nomeDestinatario").val("");
		$("#emailDestinatario").val("");
		$("#nomeDoRemetente").val("");
		setTimeout("xajax_obterDadosDoDestinatario(" + idNota + ", " + idContato + ", 'danfe');", 800);
		displayWait("waitNotas", true, "Buscando informações, aguarde...");
	} else if (opcao == "cartaCorrecao"){
		setTimeout("xajax_obterUltimaCorrecao(" + idNota + ")", 800);
	}
}

function closePopupNfe(){
	Boxy.get("#form-nfe").hide();
}

function ajustarFormNfe(){
	$(document).ready(function() {
		setTimeout("init()", 700);
	});
}

function possuiNFe(possui) {
	if (possui) {
		possuiAcessoNFe = true;
	} else {
		possuiAcessoNFe = false;
	}
}

function setarTipo(aTipo) {
	tipo = aTipo;
	if (aTipo == "E") {
		$("#opc_terc").show();
	} else {
		$("#opc_terc").hide();
	}
}

function editarNotaFiscal(id){
	posicaoScroll = $(document).scrollTop();
	alqComissao = 0;
	$.history.add('edit/'+id);
	clearForm();
	displayForm();
	displayWait("pleasewait", true);
	xajax_obterNotaFiscal(id, "");
	inicializarItemTemp();

	$("#trh").hide();
	$("#natureza").focus();
}

function copiarNota(id, copiaInversa){
	if (copiaInversa != "S") {
		copiaInversa = "N";
	}
	if (!copiaInversa) {
		$.history.add('add');
	}
	clearForm();
	displayForm();
	xajax_obterNotaFiscal(id, "S", copiaInversa);
	$('#natureza').focus();
	inicializarItemTemp();

	$('#linhaInclusaoItem').hide();
	$("#trh").hide();

	$("#natureza").focus();
}

function cancelarEdicao() {
	$.history.add('list');
	displaySearch(true);
	clearForm();
}

function msgAoPularValorSequencialDaNotaFiscal() {
	var html = "<div style='text-align:left;'><div class='warn'>" +
				"<p>Por Favor, verifique o número da nota informado.</p>" +
				"<p>Você está pulando a numeração " + $("#proximoNumero").val() + " a " + ($("#numero").val() - 1) + ". </p>" +
				"<p>Se prosseguir será possível editar manualmente os números das notas ou inutilizá-los.</p>" +
				"<input type='checkbox' id='autorizacao' name='autorizacao'><label for='autorizacao'>Estou ciente das consequências e desejo prosseguir.</label>" +
				"</div><div  style='text-align:right;' class='controls'><br /><input type='button' value='Prosseguir' onclick='confirmarGravacaoNotaPuloValorSequencial();' class='button-default' /></div></div>";
	b = new Boxy($(html), {
		title: "Atenção",
		modal: true,
		unloadOnHide: true,
		width: 300
	});
	b.resize(450);
}

function confirmarGravacaoNotaPuloValorSequencial(){
	if ($("#autorizacao").prop('checked')){
	b.hideAndUnload();

	salvarParcelas();
	if ($("#a_vista").prop("checked")) {
		$("#tipoPagamento").val("av");
	} else {
		$("#tipoPagamento").val("ap");
	}
		displayWait("pleasewait", true, "Salvando informações da nota, aguarde...");
		xajax_salvarNotaFiscal($('#id').val(), xajax.getFormValues('formNotaFiscal', true), arrayItens, volumesLogistica);
	} else {
		alert("Marque a opção de deseja prosseguir");
	}
}

function salvarNotaFiscal(){
	if (($("#notaTipo").val() == "N") || ($("#notaTipo").val() == "C")) { // NOTA DE EMISSÃO LIVRE
		if (parseFloat($("#numero").val()) > parseFloat($("#proximoNumero").val())) {
			msgAoPularValorSequencialDaNotaFiscal();
		} else {
			displayWait("pleasewait", true, "Salvando informações da nota, aguarde...");
			xajax_salvarNotaFiscal($('#id').val(), xajax.getFormValues('formNotaFiscal', true), arrayItens, volumesLogistica);
		}
	} else { // TIPO DE NOTA DIFERENTE DE EMISSÃO LIVRE
		displayWait("pleasewait", true, "Salvando informações da nota, aguarde...");
		xajax_salvarNotaFiscal($('#id').val(), xajax.getFormValues('formNotaFiscal', true), arrayItens, volumesLogistica);
	}

	// Comum a todos
	$('#botaoSalvar').attr("disabled",'disabled');
		salvarParcelas();
		if ($("#a_vista").prop("checked")) {
			$("#tipoPagamento").val("av");
		} else {
			$("#tipoPagamento").val("ap");
	}
} // Fim do Salvar

function incluirNotaFiscal() {
	alqComissao = 0;
	$("#calculaImpostos").val("S");
	setarLinkLigaDesliga();
	$.history.add('add');
	clearForm();
	displayForm();
	setarEntradaSaida(tipo);

	if (tipo == "E") {
		$("#notaTipo").val("T");
	} else {
		$("#notaTipo").val("N");
	}
	//configurarTipoNormalExterno();

	xajax_incluirNotaFiscal($("#tipo").val());
	//xajax_obterOperacaoPadrao($("#tipo").val());

	$('#linhaInclusaoItem').show();
	//$("#parcelas_header").hide();
	//$("#trh").hide();

	limparParcelas();

	if (tipo == 'E') {
		$('#notaTipo').focus();
	} else {
		$('#natureza').focus();
	}
	inicializarItemTemp();
}

function incluirNotaFiscalComplementar(idNotaFiscalReferenciada) {
	$.history.add('add');
	clearForm();
	displayForm();

	xajax_criarNotaFiscalComplementar(idNotaFiscalReferenciada);
}

function completarCamposNfComplementar() {
	xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());

	$("#finalidade").val("2");
	$("#idNotaFiscalReferenciada").val(idNotaFiscalReferenciada);

	$('#linhaInclusaoItem').show();
	$("#parcelas_header").hide();
	$("#trh").hide();

	$('#natureza').focus();
	inicializarItemTemp();
}

function clearForm() {
	$('#observacoes').val('');
	$('#obs').val('');
	$('#formNotaFiscal input:text').val('');
	$('#formNotaFiscal textarea').val('');
	$('#idVendedor').val('');
	$('#situacao').val('1');
	$('#origem').val('0');
	$('#precoLista').val('');
	$('#idOrigem').val('0');
	$('#id').val('0');
	$('#idListaVendas').val('0');
	$('#idMagento').val('');
	$('#prestashop').val('nao');
	$('#formNotaFiscal input:text').removeClass('warning');
	$('#botaoSalvar').removeAttr('disabled');
	$('#mensagem').removeClass('warn');
	$('#mensagem').html('');
	$('#custoAtualizado').val('');
	$('#trocaManualDeOperacaoFiscal').val('');
	$('#idTransportador').val('0');

	$('#mensagem_erro_nfe').removeClass('warn-nfe');
	$('#mensagem_erro_nfe').addClass('nomessage');
	$('#mensagem_erro_nfe').html('');

	$('#dataEmissao').val(getDataAtual());
	$('#dataSaidaEntrada').val(getDataAtual());
	$('#horaSaidaEntrada').val(getHoraAtual());
	$('#nroItens').val('0');

	$('#finalidade').val('1');
	$('#idNotaFiscalReferenciada').val('');

	$('#idContato').val('0');
	$('#idMunicipio').val('0');
	$('#parcelaNumber').val('0');
	$('#valorDesconto').val('0');
	$('.linhaItemNota').remove();
	$('.linhaParcelaNota').remove();

	$('#sisdeclaraTipoNota').val('0');
	$('#sisdeclaraOperacao').val('1');
	$('#produtoEquivalente').val('');

	$('#div_prod_rural_nf_ref').hide();

	filtrarPorOperacao();

	habilitarCampos();

	arrayItens = {};
	itemTemp = {};
	itemTempEdicao = {};
	contItens = 0;
	destinoCefop = '';
	idOperacaoAnterior = 0;
	contatoNovo = true;

	$('#contato').removeClass('ac_error');
	$('#contato').addClass('tipsyOff');
	$('#contato').removeAttr('title');
	$('#municipio').removeClass('ac_error');
	$('#municipio').addClass('tipsyOff');
	$('#municipio').removeAttr('title');
	$('#natureza').removeClass('ac_error');
	$('#natureza').addClass('tipsyOff');
	$('#natureza').removeAttr('title');
	$('#nomeVendedor').removeClass('ac_error');
	$('#nomeVendedor').addClass('tipsyOff');
	$('#nomeVendedor').removeAttr('title');
	$('#transportador').removeClass('ac_error');
	$('#transportador').addClass('tipsyOff');
	$('#transportador').removeAttr('title');

	$('#tr_obs_cliente').hide();
	exibeOcultaChaveNFeReferenciada();

	$('#informacoes_correios').html('');
	$('#div_aviso_termino_etiquetas').html('');
	$('#idEnderecoEtiqueta').val(0);
	$('#etiqueta_nome').val('');
	$('#etiqueta_endereco').val('');
	$('#etiqueta_numero').val('');
	$('#etiqueta_complemento').val('');
	$('#etiqueta_id_municipio').val(0);
	$('#etiqueta_municipio').val('');
	$('#etiqueta_uf').val(' ');
	$('#etiqueta_cep').val('');
	$('#etiqueta_bairro').val('');
	$('#dados_endereco_etiqueta').hide();
	$('#etiqueta_mostrar').prop('checked', false);
	$('#etiqueta_mostrar').prop('disabled', false);
	$('#indIEDest').val(1);
	$('#indFinal').prop('checked', false);
	$('#valorUnitarioComII').prop('checked', false);

	$('#DItpViaTransp').val(1);
	changetpViaTransp();
	$('#DItpIntermedio').val(1);
	changetpintermedio();
	$('#DIvAFRMM').val('0,00');
	$('#DICNPJ').val('');
	$('#DIUFTerceiro').val(' ');

	$('#doc_referenciado').hide();
	$('#td_chave_ref').hide();
	$('#tipoDocReferenciado').val('');
	$('table#tAdicoes tbody').html('');

	$('#loja').val(0);

	adicionarLinhaAdicao('');
	setExisteEstoqueInfo(0);
	clearFormLogistica();
}

function carregarLojasAtivas() {
	xajax_carregarLojasIntegradas();
}

function displayForm() {
	window.scrollTo(0,0);
	$('#edicao').removeClass("invisivel");
	$('#lista').addClass("invisivel");
	$('#infoNumero').hide();
}

function displaySearch(manterPosicao) {
	habilitarCampos();
	if (manterPosicao == true) {
		window.scrollTo(0, posicaoScroll);
	} else {
		window.scrollTo(0,0);
	}
	$('#edicao').addClass("invisivel");
	$('#lista').removeClass("invisivel");
}

function filtrarPorOperacao() {
	$(".eng").hide();
	$(".gra").hide();
	$(".uva").hide();
	switch ($("#sisdeclaraTipoNota").val()) {
	case "0":
		$(".eng").show();
		break;
	case "1":
		$(".gra").show();
		break;
	case "2":
		$(".uva").show();
		break;
	}
}

function buscarPedidoXML() {
	nomeArquivo = "";
	new Boxy.load("templates/form.pedido.xml.popup.php", {
		title: "Integração por Arquivo XML",
		modal: true,
		afterShow: ajustarFormPedidoXML
	});
}

function buscarXMLNFe() {
	nomeArquivo = "";
	displayWait('waitNotas', true);
	$.get('templates/form.pedido.xml.nfe.popup.php', function(content) {
		var dialog = {
			config: {
				title: 'Importar XML NF-e',
				width: 500
			},
			content: content,
			textOk: 'Importar',
			hideCancel: true,
			idOk: 'btnImportXML',
			fnOk: function() {
				obterNotaPedidoXMLNFe(nomeArquivo);
			},
			fnCreate: function() {
				ajustarFormXMLNFe();
			}
		};
		createDialog(dialog, 1);
		closeWait('waitNotas');
	});
}

function ajustarFormXMLNFe() {
	habilitarBotaoLeitura();
	$("#arquivo").val("");
	$('#arquivo').focus();

	$("#btnImportXML").hide();
	paramsFp = {
		"elemento": document.getElementById("file-uploader"),
		"acao": "uploadPedidoXMLNFe.php?idEmpresa=" + idEmpresa,
		"extensoes": ["xml"],
		"callback": liberarProcessarXMLNFe
	};
	initFileUploader(paramsFp);
}

function liberarProcessarXMLNFe(response) {
	nomeArquivo = response.tmp;
	$("#logLerPedidoXML").html("");
	$("#logLerPedidoXML").removeClass("warn");
	$("#logLerPedidoXML").addClass("nomessage");
	$("#btnImportXML").removeAttr("disabled");
	$("#btnImportXML").show();
}

function ajustarFormPedidoXML() {
	habilitarBotaoLeitura();
	$("#arquivo").val("");
	$('#arquivo').focus();

	$(".qq-upload-button").show();
	$("#btnImportXML").hide();
	paramsFp = {
		"elemento": document.getElementById("file-uploader"),
		"acao": "uploadPedidoXML.php?idEmpresa=" + idEmpresa,
		"extensoes": ["xml"],
		"callback": liberarProcessarXML
	};
	initFileUploader(paramsFp);
}

function liberarProcessarXML(response) {
	nomeArquivo = response.tmp;
	$("#logLerPedidoXML").html("");
	$("#logLerPedidoXML").removeClass("warn");
	$("#logLerPedidoXML").addClass("nomessage");
	$("#btnImportXML").removeAttr("disabled");
	$("#btnImportXML").show();
	$(".qq-upload-button").hide();
}

function uploadPedidoXML() {
	if ($("#arquivo").val() != "") {
		micoxUpload(document.getElementById('formPedidoXML'), 'uploadPedidoXML.php', 'dadosImportacao', 'images/carregando.gif', 'Erro ao carregar')
	}
}

function closePopup(){
	Boxy.get("#formPedidoXML").hide();
	habilitarBotaoLeitura();
}

function habilitarBotaoLeitura() {
	$("#btnImportXML").removeAttr("disabled");
	$("#uploadEfetuado").val("N");
}

//Comissão
function setIdVendedorjQuery(param) {
	$("#idVendedor").val(param.id);
	$("#nomeVendedor").removeClass("warning");
	$("#nomeVendedor").removeClass("ac_error");
	$("#nomeVendedor").addClass("tipsyOff");
	$("#nomeVendedor").removeAttr("title");
	$("#nomeVendedor").focus();
	comissaoSetIdVendedor($("#idVendedor").val(), true);
}
function comissaoCalcularComissoes() {
	if (arrayItens.length > 0) {
		$.each(arrayItens, function(id, item) {
			var alqComissao = comissaoGetAliquotaComissao(parseFloat(nroUsa(arrayItens[id]["precoLista"])), parseFloat(nroUsa(arrayItens[id]["valorUnitario"])));
			arrayItens[id]["alq_comissao"] = nroBra(alqComissao.toString());
			arrayItens[id]["vlr_comissao"] = nroBra((parseFloat(nroUsa(arrayItens[id]["alq_comissao"])) * ((parseFloat(nroUsa(arrayItens[id]["base_comissao"])) - parseFloat(nroUsa(arrayItens[id]["valorDescontoItem"]))) / 100)).toString());
		});
	}
}

function comissaoCalcularComissao(obterAlq) {
	if (obterAlq) {
		var alqComissao = comissaoGetComissao(itemTempEdicao["precoLista"], itemTempEdicao["valorUnitario"]);
		itemTempEdicao["alq_comissao"] = nroBra(alqComissao.toString());
	} else {
		var alqComissao = parseFloat(nroUsa(itemTempEdicao["alq_comissao"]));
	}
	itemTempEdicao["vlr_comissao"] = nroBra((parseFloat(nroUsa(itemTempEdicao["alq_comissao"])) * parseFloat(nroUsa(itemTempEdicao["base_comissao"])) / 100).toString());
	$("#edAlqComissao").val(itemTempEdicao["alq_comissao"]);
	$("#edValorComissao").val(itemTempEdicao["vlr_comissao"]);
}

//function comissaoCalcularComissao(aItem, obterAlq) {
//	if (obterAlq) {
//		var alqComissao = comissaoGetAliquotaComissao(parseFloat(nroUsa(arrayItens[aItem]["valorLista"])), parseFloat(nroUsa(arrayItens[aItem]["valorUnitario"])))
//		arrayItens[aItem]["alq_comissao"] = nroBra(alqComissao.toString());
//	} else {
//		alqComissao = parseFloat(nroUsa(arrayItens[aItem]["alq_comissao"]));
//	}
//	arrayItens[aItem]["vlr_comissao"] = nroBra((parseFloat(nroUsa(arrayItens[aItem]["alq_comissao"])) * parseFloat(nroUsa(arrayItens[aItem]["base_comissao"])) / 100).toString());
//}

function comissaoGetComissao(sValorLista, sValor) {
	return comissaoGetAliquotaComissao(parseFloat(nroUsa(sValorLista)), parseFloat(nroUsa(sValor)));
}

function setarCategoria($id){
	$("#pag_categoria").val($id);
}

function abrirTelaCupom(idNota) {

	if (!appletCupomCarregado && appAcessoHardware == "applet") {
		$("#applet_cupom").html('<applet code="CupomFiscal.class" name="CupomFiscal" width="0" height="0" archive="applets/nfe-13.0.jar" MAYSCRIPT></applet>');
		appletCupomCarregado = true;
	}

	new Boxy.load("templates/form.cupom.fiscal.php?idOrigem=" + idNota + "&tipo=N", {
		title: "Emissão de Cupom Fiscal",
		modal: true,
		afterShow: ajustarFormCupomFiscal
	});
}

function ajustarFormCupomFiscal(){
	$(document).ready(function() {
		initCupom();
	});
}

function habilitarBotaoSalvar(){
	$("#botaoSalvar").removeAttr("disabled");
}

function xmlNaoImportado(msg){
	$('<div>' + msg + '</div>').dialog({
		resizable:false,
		modal:true
	});
}

function cancelarInlusao() {
	if ($("#notaTipo").val() == "C"){
		window.location="nfces.php";
		return false;
	}
	$.history.add('list');
	displaySearch(true);
	clearForm();
}

function mostrarMensagem(id) {
	aIdNota = id;

	new Boxy($("#div_nfe_msg"),{
		title: "Nota fiscal eletrônica - Erros",
		afterShow: ajustarFormNfeMensagens,
		modal: true,
		unloadOnHide: false
	});
}

function ajustarFormNfeMensagens() {
	$(document).ready(function() {
		xajax_getMensagemNFe(aIdNota);
	});
}

function exibirMensagem(mensagem) {
	$("#lista-erros-msg").html(mensagem.mensagem);
}

function closePopupMsg() {
	Boxy.get("#div_nfe_msg").hide();
	editarNotaFiscal(aIdNota);
}

function imprimirDANFEaPartirDaLista(idNota, fechaPopup) {
	if (fechaPopup == "S") {
	} else {
		fechaPopup = "N";
	}
	xajax_alterarSituacaoNota(idNota, 7);
	window.open("relatorios/danfe.php?idNota1=" + idNota + "&fechaPopup=" + fechaPopup);
}

function imprimirEspelhoDaNota(idNota) {
	window.open("relatorios/espelho.nota.php?idNota1=" + idNota);
}

function ligarDesligarCalculoDeImpostos() {
	if ($("#calculaImpostos").val().toUpperCase() == "S") {
		$("#calculaImpostos").val("N");
		$("#link_calculo_impostos").html("Cálculo automático desligado");
		habilitarEdicaoDeTotais("N");
	} else {
		$("#calculaImpostos").val("S");
		$("#link_calculo_impostos").html("Cálculo automático ligado");
		//marcarTodosItensParaSeremRecalculados();
		calcularImpostos("N");
		habilitarEdicaoDeTotais("S");
	}
}

function habilitarEdicaoDeTotais(opcHabilitar) {
	if (opcHabilitar == "N") {
		$("#baseICMS").removeAttr("readonly");
		$("#valorICMS").removeAttr("readonly");
		$("#baseICMSSubst").removeAttr("readonly");
		$("#valorICMSSubst").removeAttr("readonly");
		$("#valorServicos").removeAttr("readonly");
		$("#valorProdutos").removeAttr("readonly");
		$("#valorIPI").removeAttr("readonly");
		$("#valorISSQN").removeAttr("readonly");
		$("#valorNota").removeAttr("readonly");
		$("#valorFunrural").removeAttr("readonly");
		$("#totalFaturado").removeAttr("readonly");
		$("#tValorAproxImpostos").removeAttr("readonly");
		$("#valorMinimoParaRetencao").removeAttr("readonly");
		$("#valorRetBaseIR").removeAttr("readonly");
		$("#valorRetIR").removeAttr("readonly");
		$("#valorRetCSLL").removeAttr("readonly");
		$("#valorRetPIS").removeAttr("readonly");
		$("#valorRetCOFINS").removeAttr("readonly");
	} else {
		$("#baseICMS").attr("readonly", "readonly");
		$("#valorICMS").attr("readonly", "readonly");
		$("#baseICMSSubst").attr("readonly", "readonly");
		$("#valorICMSSubst").attr("readonly", "readonly");
		$("#valorServicos").attr("readonly", "readonly");
		$("#valorProdutos").attr("readonly", "readonly");
		$("#valorIPI").attr("readonly", "readonly");
		$("#valorISSQN").attr("readonly", "readonly");
		$("#valorNota").attr("readonly", "readonly");
		$("#valorFunrural").attr("readonly", "readonly");
		$("#totalFaturado").attr("readonly", "readonly");
		$("#tValorAproxImpostos").attr("readonly", "readonly");
		$("#valorMinimoParaRetencao").attr("readonly", "readonly");
		$("#valorRetBaseIR").attr("readonly", "readonly");
		$("#valorRetIR").attr("readonly", "readonly");
		$("#valorRetCSLL").attr("readonly", "readonly");
		$("#valorRetPIS").attr("readonly", "readonly");
		$("#valorRetCOFINS").attr("readonly", "readonly");
	}
}

function setarLinkLigaDesliga() {
	if ($("#calculaImpostos").val().toUpperCase() == "S") {
		$("#link_calculo_impostos").html("Cálculo automático ligado");
		habilitarEdicaoDeTotais("S");
	} else {
		$("#link_calculo_impostos").html("Cálculo automático desligado");
		habilitarEdicaoDeTotais("N");
	}
}

function setIdVendedorPsq(param) {
	$("#idPsqVendedor").val(param.id);
	$('#psqVendedor').removeClass("ac_error");
	$('#psqVendedor').addClass("tipsyOff");
	$('#psqVendedor').removeAttr("title");
	$("#psqVendedor").focus();
	listar();
}

function limparCodigoVendedor(event) {
	if ((event.keyCode <= 8) ||
		((event.keyCode >= 46) && (event.keyCode <= 111)) ||
		(event.keyCode >= 186)
	) {
		$("#idPsqVendedor").val("0");
	}
}

function limparCodigoProdutoPsq(event) {
	if ((event.keyCode <= 8) ||
		((event.keyCode >= 46) && (event.keyCode <= 111)) ||
		(event.keyCode >= 186)
	) {
		$("#idPsqProduto").val('0');
	}
}

function copiarNotaInversa(idNota) {
	xajax_copiarNotaInversa(idNota, tipo);
}

function abrirPopupDepositoEstoques(idOrigem, idDeposito) {
	idOrigemParaEstoque = idOrigem;
	idDepositoSelect = idDeposito;
	new Boxy.load('templates/form.estoque.deposito.popup.php', {
		title: "Depósitos",
		modal: true,
		afterShow: limparTelaDeposito
	});
}

function limparTelaDeposito() {
	xajax_obterOpcoesDepositos("idDeposito", "", "N", idDepositoSelect);
	$("#idOrigemDeposito").val(idOrigemParaEstoque);
}

function closePopupDeposito(){
	Boxy.get("#formLancamentosDepositos").hide();
}

/* Formação de preços */

var valorAoEntrar = 0;
var valorAoSair = 0;

function exibirFormacaoDePrecos(idNota, estoqueLancado) {
	if (estoqueLancado == "N") {
		alert("A formação de preços pode ser acessada apenas após a integração com o estoque.");
		listar();
	} else {
		new Boxy.load("templates/form.formacao.precos.php?idNota=" + idNota, {
			title: "Formação de preços",
			modal: true,
			unloadOnHide: true,
			afterShow: ajustarFormFormacaoDePrecos,
			afterHide: listar
		});
	}
}

function ajustarFormFormacaoDePrecos() {
	$(document).ready(function() {
		initFormatters($("#dec_qtde").val(), $("#dec_valor").val());
		xajax_obterDadosParaFormacaoDoPrecoDeVenda($("#idNotaFormacaoPrecos").val());
	});
}

function exibirDadosFormacaoPrecoVenda(pvValorFrete, pvMarkup, pvItens) {
	$("#pvMarkup").val(pvMarkup);
	$("#pvFreteNota").val(pvValorFrete);
	$("#pvMarkupLojas").val("0,00");

	$("#pvFreteNota").bind("focus", function () {
		valorAoEntrar = parseFloat(nroUsa($(this).val()));
	})

	$("#pvFreteNota").bind("blur", function () {
		valorAoSair = parseFloat(nroUsa($(this).val()));
		if (valorAoEntrar != valorAoSair) {
			formacaoPrecosRateioDeFrete();
		}
	})

	$("#pvMarkup").bind("focus", function () {
		valorAoEntrar = parseFloat(nroUsa($(this).val()));
	})

	$("#pvMarkup").bind("blur", function () {
		valorAoSair = parseFloat(nroUsa($(this).val()));
		if (valorAoEntrar != valorAoSair) {
			formacaoPrecosSetarMarkup();
		}
	})

	$("#pvMarkupLojas").bind("focus", function () {
		valorLojaAoEntrar = parseFloat(nroUsa($(this).val()));
	})

	$("#pvMarkupLojas").bind("blur", function () {
		valorLojaAoSair = parseFloat(nroUsa($(this).val()));
		if (valorLojaAoEntrar!= valorLojaAoSair) {
			formacaoPrecosSetarMarkupLojas();
		}
	})

	var tamanho = 0;
	if(getWindowWidth() < 1024){
		tamanho = getMobileWidthForDialogs(924) - 50;
		$('.boxy-wrapper').css({"left":"0px"});
		$('#innerFormacao').css({"width":tamanho});
		$('#subtituloLoja').css({"display":"none"});
		$("#div-formacao-preco").css({'width':'99%'});
	}else {
		tamanho = (getWindowWidth() - 1024) / 2;
		$("#div-formacao-precoLoja").css({'border':'1px solid gray','height':'200px'});
		$('.boxy-wrapper').css({"left": tamanho + "px"});
	}

	$("#pvFreteNota").focus();



	var tabelaFormacaoPrecos = "<tr><th><input type='checkbox' onclick='toggleCheckFormacaoPrecos();'></th><th>Item</th><th>Preço atual</th><th><input type='checkbox' checked='checked' onclick='toggleCheckFormacaoPrecosCustoUnitario();'>* Custo unitário</th><th>Markup</th><th><input type='checkbox' checked='checked' onclick='toggleCheckFormacaoPrecosPrecoVenda();'>* Preço de venda</th></tr>";
	$.each(pvItens, function(id, item) {
		tabelaFormacaoPrecos += "<tr style='cursor: pointer;' class='mostraTabela'>" +
								//"<td><input type='checkbox' value='" + item.produto + "' id='produto" + id + "' class='editgrid' /></td>" +
								"<td class='tline2' style='width:3%' ><input type='checkbox' id='marcado" + item.idProduto + "' editou='' name='formacaoPrecos[marcadoParaTags][]" + item.idProduto + "' class='editgrid marcado' value='" + item.idProduto + "'></td>" +
								"<td class='tline2' style='width:20%'><input type='text' value='" + item.produto + "' name='formacaoPrecos[produto][]' id='produto" + id + "' class='editgrid ac_input highlightTabela' background-color:white;' readonly='readonly' ></td>" +
								"<td class='tline2' style='width:10%'><input type='text' value=" + item.precoLista + " name='formacaoPrecos[precoLista][]' id='precoLista" + id + "' class='editgrid ac_input highlightTabela' background-color:white;' readonly='readonly' style='width:100%'>" +
								"<input type='hidden' value=" + item.pvICMSST + " name='formacaoPrecos[pvICMSST][]' id='pvICMSST" + id + "' /></td>" +
								"<input type='hidden' value=" + item.descontoItem + " name='formacaoPrecos[descontoItem][]' id='descontoItem" + id + "' /></td>" +
								"<td class='tline2' style='width:10%' >" +
								"	<input type='checkbox' value='S' id='chbx_custo_" + id + "' name='chbx_custo_" + id + "' class='checkbox calculaValor' checked='checked' disabled >" +
								"	<input type='text' value='" + item.pvCustoUnitario + "' name='formacaoPrecos[pvCustoUnitario][]' id='pvCustoUnitario" + id + "' class='editgrid ac_input edt-number calculaValor' disabled onBlur='formacaoPrecosAtualizarItem(" + id + ", \"N\") ;' style='width:76%'>" +
								"</td>" +
								"<td class='tline2' style='width:10%'><input type='text' value=" + item.pvMarkup + " name='formacaoPrecos[pvMarkup][]' id='pvMarkup" + id + "' class='editgrid ac_input edt-number '  onBlur='formacaoPrecosAtualizarItem(" + id + ", \"N\");'style='width:76%'></td>" +
								"<td class='tline2' style='width:10%'>" +
								"	<input type='checkbox' value='S' id='chbx_venda_" + id + "' name='chbx_venda_" + id + "' class='checkbox calculaValor' checked='checked' disabled >" +
								"	<input type='text' value=" + item.pvPrecoVenda + " name='formacaoPrecos[pvPrecoVenda][]' id='pvPrecoVenda" + id + "' class='editgrid ac_input edt-number calculaValor' disabled  onBlur='formacaoPrecosAtualizarItem(" + id + ", \"S\") ;'style='width:76%'>" +
								"</td>" +
								"<td  class='tline2' style='display: none;'>" +
								"	<input type='text' value=" + item.idItem + " name='formacaoPrecos[idItem][]' id='idItem" + id + "'>" +
								"	<input type='text' value=" + item.qtde + " name='formacaoPrecos[qtde][]' id='qtde" + id + "'>" +
								"	<input type='text' value=" + item.valorUnitario + " name='formacaoPrecos[valorUnitario][]' id='valorUnitario" + id + "'>" +
								"	<input type='text' value=" + item.pvFrete + " name='formacaoPrecos[pvFrete][]' id='pvFrete" + id + "'>" +
								"	<input type='text' value=" + item.ipi + " name='formacaoPrecos[ipi][]' id='ipi" + id + "'>" +
								"	<input type='text' value=" + item.idProduto + " name='formacaoPrecos[idProduto][]' id='idProduto" + id + "'>" +
								"	<input type='text' value=" + id + " name='formacaoPrecos[idLista][]' id='idLista'>" +
								"	<input type='text' value=" + item.pvCustoUnitario + " name='formacaoPrecos[valorUnitario][]' id='custoInicial" + id + "'>" +
								"	<input  id='mobile" + id + "' value = '0' ></input>" +
								"</td>" +
								"</tr>";
	})
	$("#tabela_formacao_precos").html(tabelaFormacaoPrecos);

	highlightTabela();

	$('.mostraTabela').bind("click", function(){
		if(!$(this).hasClass('activeStore')){
			var existe = false;
			var aItem = new Array();
			$("#tabela_formacao_precos .activeStore ").removeClass('activeStore');
			idProduto = $(this).find('.marcado').val();
			idLista = $(this).find("#idLista").val();
			$(this).addClass('activeStore');
			//Marca o produto que foi clicado e exibido na tela
			$("#marcado"+idProduto).attr("editou","S");
			guardarValoresMarkupLoja();
			highlightItem($(this));
			$(arrayMarkupLoja).each(function (key, value) {
				if(value.indice == idLista){
					existe = true;
					value.preco = nroUsa(value.preco);
					value.preco_markup = nroUsa(value.preco_markup);
					aItem.push(value);
				}
			})

			if(existe == true){
				mostrarTabelaFormacaoPrecosLojas(aItem,idLista);
			}else{
				obterMarkupLoja(idProduto,idLista);
			}
		}
	});

	$('.marcado').bind("click",function(){
		if(this.checked==true){
			$(this).parent().parent().find(".calculaValor").removeAttr("disabled");
		}else{
			var indice = $(this).parent().siblings().find("#idLista").val();
			$(this).parent().parent().find(".calculaValor").attr("disabled", "disabled");
			removerValoresMarkupLoja(indice);
		}
	});

	initFormatters($("#dec_qtde").val(), $("#dec_valor").val());
}

function highlightTabela(){
	$(".tline2").unbind();
	$(".tline2").parent().children().bind("mouseenter", function() {
		$(this).parent().addClass("highlight");
		$(this).parent().find('input').addClass("highlight");
		$(this).parent().find('.highlightTabela').css("background-color","#dee4ea");
		$(this).parent().css("cursor", "pointer");
	});
	$(".tline2").parent().children().bind("mouseleave", function() {
			if(!$(this).parent().hasClass("clicked")) {
				$(this).parent().removeClass("highlight");
				$(this).parent().find('input').removeClass("highlight");
				$(this).siblings().children().removeClass("highlight");
				$(this).parent().find('.highlightTabela').css("background-color","white");
			}
	});
}

function toggleCheckFormacaoPrecos(){
	$("#formacaoPrecosForm table#tabela_formacao_precos tr").each(function(item,i) {
		var linha = $(this).find('td input').eq(0);
		if(linha.prop("checked")){
			linha.prop("checked", false);
			$(this).find(".calculaValor").attr("disabled","disabled");
		}else{
			linha.prop("checked", true);
			$(this).find(".calculaValor").removeAttr("disabled");
		}
	});
}

function toggleCheckFormacaoPrecosCustoUnitario(){
	$("#formacaoPrecosForm table#tabela_formacao_precos tr").each(function(item,i) {
		var linha = $(this).find('td input').eq(4);
		if(linha.prop("checked")){
			linha.prop("checked", false);
		}else{
			linha.prop("checked", true);
		}
	});
}

function toggleCheckFormacaoPrecosPrecoVenda(){
	$("#formacaoPrecosForm table#tabela_formacao_precos tr").each(function(item,i) {
		var linha = $(this).find('td input').eq(7);
		if(linha.prop("checked")){
			linha.prop("checked", false);
		}else{
			linha.prop("checked", true);
		}
	});
}

function salvarDadosFormacaoPrecos() {
//	displayWait("waitNotas");
	guardarValoresMarkupLoja();
	markupLojas = verificaCheckProduto(arrayMarkupLoja);
	var formacaoProdutos = verificaCheckProdutoFormacao();
	xajax_salvarDadosFormacaoPrecos($("#idNotaFormacaoPrecos").val(), $("#pvFreteNota").val(), formacaoProdutos, markupLojas);

	arrayMarkupLoja = new Array();
}

function formacaoPrecosRateioDeFrete() {
	var pvTotalProdutos = 0;
	var pvCusto = 0;
	var qtde = 0;
	var valorUnitario = 0;

	$("input[name='formacaoPrecos[produto][]']").each(function (key, value) {
		pvTotalProdutos += parseFloat($("#qtde" + key).val()) * parseFloat($("#valorUnitario" + key).val());
	})

	$("input[name='formacaoPrecos[produto][]']").each(function (key, value) {
		qtde = parseFloat($("#qtde" + key).val());
		valorUnitario = parseFloat($("#valorUnitario" + key).val());
		pvCusto = (qtde * valorUnitario) / pvTotalProdutos;
		pvCusto = (pvCusto * parseFloat(nroUsa($("#pvFreteNota").val())));
		pvCusto = (pvCusto / qtde);
		pvCusto += valorUnitario + parseFloat($("#ipi" + key).val()) + parseFloat($("#pvICMSST" + key).val()) - parseFloat($("#descontoItem" + key).val());
		$("#pvCustoUnitario" + key).val(nroBra(pvCusto));
	})

	formacaoPrecosAtualizar();
}

function formacaoPrecosSetarMarkup() {
	$("input[name='formacaoPrecos[pvMarkup][]']").each(function (key, value) {
		$(this).val($("#pvMarkup").val());
	})

	formacaoPrecosAtualizar();
}

function formacaoPrecosAtualizar() {
	var pvCusto = 0;
	var pvVenda = 0;
	$("input[name='formacaoPrecos[produto][]']").each(function (key, value) {
		pvCusto = parseFloat(nroUsa($("#pvCustoUnitario" + key).val()));
		pvVenda = pvCusto * parseFloat(nroUsa($("#pvMarkup" + key).val()));
		$("#pvPrecoVenda" + key).val(nroBra(pvVenda));
	})
}

function formacaoPrecosAtualizarItem(key, aPartirDoPrecoDeVenda) {
	var pvCusto = 0;
	var pvVenda = 0;
	var pvMarkup = 0;
	var idProdutoLoja = 0;
	var idProdutoLista = 0;


	pvCusto = parseFloat(nroUsa($("#pvCustoUnitario" + key).val()));
	pvMarkup = parseFloat(nroUsa($("#pvMarkup" + key).val()));
	pvVenda = parseFloat(nroUsa($("#pvPrecoVenda" + key).val()));

	if (aPartirDoPrecoDeVenda == "S") {
		pvMarkup = pvVenda / pvCusto;
		$("#pvMarkup" + key).val(nroBra(pvMarkup));
	} else {
		pvVenda = pvCusto * pvMarkup;
		$("#pvPrecoVenda" + key).val(nroBra(pvVenda));
	}

	//verifica se a tabela markupLoja refere-se ao produto editado
	idProdutoLoja = $('#tabela_formacao_precos_lojas').find('#produto0').val();
	idProdutoLista = $('#tabela_formacao_precos').find('#idProduto' + key).val();

	if(idProdutoLoja == idProdutoLista){
		atualizaTabelaMarkupLoja(pvVenda);
	}

}

function closePopupFormacaoPrecos() {
	Boxy.get("#formacaoPrecosForm").hide();
	closeWait("waitNotas")
}

function enviarEspelhoDaNotaPorEmail(idNota, idContato){
//	new Boxy.load('templates/form.envio.documento.popup.php?idDoc=' + idNota + '&idContato=' + idContato + '&tipo=espelho.nota', {
//		title: "Envio de documento por email",
//		modal: true,
//		afterShow: ajustarFormEnvioDocEspelho
//	});
	dialog.dialog({
		autoOpen: false,
		title: 'Envio de documento por email',
		modal: true,
		resizable: false,
		close:function(){
			$(this).dialog("destroy");
		},
		width: 425
	});
	dialog.load('templates/form.envio.documento.popup.php?idDoc=' + idNota + '&idContato=' + idContato + '&tipo=espelho.nota', "", function(){
		dialog.dialog("open");
		ajustarFormEnvioDocEspelho();
	});

}

function ajustarFormEnvioDocEspelho(){
	var idDoc = $("#idDocEnvioDoc").val();
	var idContato = $("#idContatoEnvioDoc").val();
	var tipoEnvio = $("#tipoEnvioDoc").val();
	displayWait('pleasewait');
	xajax_obterDadosDoDestinatario(idDoc, idContato, tipoEnvio);
	xajax_getEspelhoDanfe(idDoc);
	$("#nomeDestinatario").focus();
}

function montarNotaDeVariasPedidos(listaPedidos) {
	//$.history.add('add');
	clearForm();
	displayForm();
	$('#idListaVendas').val(listaPedidos);
	xajax_montarNotaDeVariasPedidos(listaPedidos);
	$('#natureza').focus();
	inicializarItemTemp();

	$('#linhaInclusaoItem').hide();
	$("#trh").hide();

	$("#natureza").focus();
}

function iniciarTagsProdutosSelecionados() {
	new Boxy.load("templates/form.tags.gerenciador.popup.php", {
		title: "Marque as Tags relacionadas aos produtos selecionados",
		modal: true,
		afterShow: xajax_obterTags,
		afterHide: aplicarTagsProdutosSelecionados
	});
}

function aplicarTagsProdutosSelecionados() {
	displayWait("waitNotas");
	var arrayTagsMarcadas = montarArrayTagsMarcadas();
	xajax_aplicarTagsAosProdutosSelecionadosApartirDeNotas(xajax.getFormValues("formacaoPrecosForm"), arrayTagsMarcadas);
}

function montarArrayTagsMarcadas(){
	var arrayTagsMarcadas = new Array();
	var x = 0;
	$.each(arrayTags, function (nomeGrupo, tags) {
	 	$.each(tags, function (idTag, objTag) {
		 	if(objTag.marcado){
		 		arrayTagsMarcadas[x] = objTag.id;
		 		x++;
		 	}
		 });
	 });
	 return arrayTagsMarcadas;
}

function setarArrayTagsSelecionados(arrayServidor) {
	var grupo = '';
	arrayGrupos = arrayServidor.grupos;
	$.each(arrayGrupos, function(idGrupo, Grupo) {
		arrayTags[Grupo.nome] = {};
	});
    $.each(arrayServidor, function (idTag, objTag) {
    	try {
    		if (idTag != "grupos") {
    			if (objTag.nomeGrupo != grupo) {
    				arrayTags[objTag.nomeGrupo] = {};
    				grupo = objTag.nomeGrupo;
    			}
    			arrayTags[objTag.nomeGrupo][idTag] = objTag;
    		}
    	} catch(e) {
		 	alert(e);
		}
    });
	mostrarDadosTags();
}

function mostrarDadosTags() {
		t = 0;
		$("#tags-area").append("<div class='tags_coluna' id='tags_coluna1'></div>");
		$("#tags-area").append("<div class='tags_coluna' id='tags_coluna2'></div>");
		$("#tags-area").append("<div class='tags_coluna' id='tags_coluna3'></div>");
		$.each(arrayGrupos, function (idGrupo, dadosGrupo) {
		t++;
		if( t>=4 ){ t=1; }
    	var idGrupo = dadosGrupo.id;
    	mostrarGrupo(dadosGrupo.id, dadosGrupo.nome, t);
    	if (arrayTags[dadosGrupo.nome] == undefined) {
    		mostrarAdicionar(idGrupo);
    		return true;
    	}
    	var tags = arrayTags[dadosGrupo.nome];
	    $.each(tags, function (idTag, objTag) {
	    	$("<input type='radio' name='grupo_" + idGrupo + "' />").attr("id", objTag.id).bind("click", function() {

	    		$.each(tags, function (idTagAux, objTagAux) {
	    			objTagAux.marcado = false;
	    		});

	    		objTag.marcado = true;
	    	}).appendTo("#div_" + objTag.idGrupoTag);
			$("#" + objTag.id).prop("checked", objTag.marcado);
			$("<label />").attr("for", objTag.id).html(objTag.nome).appendTo("#div_" + objTag.idGrupoTag);
			$("#div_" + objTag.idGrupoTag).append("<br/>");
		});
		mostrarAdicionar(idGrupo);
		mostrarDescmarcarTodos(idGrupo,dadosGrupo.nome);
	});
}

function obterNotaOrdemServico(id) {
	clearForm();
	xajax_obterNotaFiscalPorOrdemServico(id);
	displayForm();
	$('#itens').hide();
	$("#trh").hide();
	$('#linhaInclusaoItem').hide();
	$('#desconto').focus();
	$("#idOrigem").val(id);
}

//MercadoShops
function obterPedidoMercadoShops(numero) {
        clearForm();
        displayForm();
        displayWait('pleasewait');
        xajax_obterOperacaoPadrao($("#tipo").val(), $("#loja").val());
        xajax_obterPedidoMercadoShops(numero);
}

function imprimirNFesSelecionadas() {
	var ids = getIdsSelectedItems({'asString': true});

	if (ids != '') {
		switch ($('#imprimeFrenteVerso').val()) {
			case 'S':
				window.open("relatorios/danfe.php?idNota1=" + ids + "&fechaPopup=N&frenteverso=S");
				break;
			case 'N':
				window.open("relatorios/danfe.php?idNota1=" + ids + "&fechaPopup=N");
				break;
			default:
				var urlNota = 'relatorios/danfe.php?idNota1=' + ids + '&fechaPopup=N';
				var dialog = {
					'content': '#popupImpressaoFrenteVerso',
					'htmlTitle': 'Imprimir selecionadas',
					'width': 440,
					'textOk': 'Sim',
					'textCancelar': 'Não',
					'fnOk': function() {
						if ($('#checkImprimeFrenteVerso').prop('checked') == true) {
							urlNota += '&gravaFrenteVerso=S';
							$('#imprimeFrenteVerso').val('S');
						}
						window.open(urlNota + '&frenteverso=S');
					},
					'fnCancelar': function(){
						if ($('#checkImprimeFrenteVerso').prop('checked') == true) {
							urlNota += '&gravaFrenteVerso=S';
							$('#imprimeFrenteVerso').val('N');
						}
						window.open(urlNota + '&frenteverso=N');
					}
				};
				createDialog(dialog);
				break;
		}
	} else {
		alert('Nenhuma nota selecionada.');
	}
	indGravado = 0;
}

function moverNFesSelecionadas() {
	var origem = $('#tipoAmbienteNfe').val();
	xajax_moverNfesParaAmbiente(getIdsSelectedItems(), origem);
}

function proximo() {
	var nume = $('#listaNotas').val();
	var notass = nume.split(",");
	var achou = "N";
	displayWait('waitImpressaoLote');
	$.each(notass, function(key, item) {
		if (achou == "S") {
		} else if (key == indGravado) {
			imprime(item, key);
			indGravado++;
			achou = "S";
		}
	});
	if (achou == "N") {
		$("#espacoImportando").html("<h2>Impressão concluída!</h2>");
		closeWait('waitImpressaoLote');
	} else {
		$("#espacoImportando").html("<h2>Estamos imprimindo as notas fiscais</h2><p class='caption'>Total de notas fiscais: " + notass.length + "</p><p class='caption'>Imprimindo " + indGravado + " de " + notass.length + "</p>");
	}
	closeWait("waitNotas");
}
function imprime(id, ind) {
	document.getElementById("iframeNotas").contentWindow.location.reload(true);
	$("#iframeNotas").attr("src", "relatorios/danfe.php?idNota1=" + id + "&fechaPopup=S");
	xajax_alterarSituacaoNota(id, 7);
	closeWait("waitNotas");
}

function proximaImpressao() {
	proximo();
}

function imprimirEtiquetasCorreiosSelecionadas() {
	var objetosPlps = [];

	$.each(getIdsSelectedItems(), function(i, idNota) {
		var element = $('input[type="checkbox"][value="' + idNota + '"]').parents('tr');
		var idObjetoPlp = element.attr('data-objetoplp');

		if (parseInt(idObjetoPlp) == 0) {
			objetosPlps.push({'idObjetoPlp': idObjetoPlp, 'idOrigem': idNota, 'possuiEtiqueta': 0});
		} else {
			objetosPlps.push({'idObjetoPlp': idObjetoPlp, 'possuiEtiqueta': 1});
		}
	});

	if (objetosPlps.length != 0) {
		displayWait('impressaoWait', true, 'Imprimindo as etiquetas selecionadas, aguarde...');
		xajax_imprimirRotulo({'etiquetas': objetosPlps}, 'etiqueta', 'N', 0);
	} else {
		alert('As notas fiscais selecionadas não estão vinculadas à nenhum serviço dos correios.');
	}
}

function imprimirEtiquetas(elem) {
	var idsNota = obterSelecionadosGerarEtiqueta(elem);
	if(!$.isEmptyObject(idsNota)) {
		displayWait('impressaoWait', true, "Aguarde...");
		xajax_montarEtiquetasNota(Object.keys(idsNota), function(idsProdutos) {
			closeWait('impressaoWait');
			var tiposParams = [{ ids: idsProdutos, params: { multiplicarPorQuantidade: 'qty'}}, { ids: idsNota}];
			var params = { produto: tiposParams[0], notaFiscal: tiposParams[1]};
			if (tipo == 'S') {
				params = { notaFiscal: tiposParams[1], produto: tiposParams[0]};
			}
			abrirPopupGerarEtiqueta(params, {}, (tipo == 'S' ? 'defaultLabelModelNotaSaida' : 'defaultLabelModelNotaEntrada'));
		});
	}
}

function exportarContatosSIGEPWeb() {
	if (confirm('Confirma exportação do(s) contato(s) da(s) venda(s) selecionada(s)?')) {
		xajax_exportarContatosSIGEPWeb(getIdsSelectedItems());
	}
}

function exibirEtiquetas() {
	new Boxy.load('templates/form.exibir.etiquetas.popup.php', {
		title: "Etiquetas vinculadas à nota "+$("#numero").val(),
		modal: true,
		afterShow: chamarEtiquetasNota
	});
}

function chamarEtiquetasNota() {
	xajax_obterEtiquetasVinculadasNota($("#id").val());
}

function ajustarFormEtiqueta(){
	if ($("#etiqueta_mostrar:checked").length > 0){
		$("#dados_endereco_etiqueta").show();
	} else {
		$("#dados_endereco_etiqueta").hide();
		}
}

function abrirPopupConsultarRecibo(obj){
	abrirPopupNFe($(obj).parent().parent().attr("idNota"), $(obj).parent().parent().attr("numero"), $(obj).parent().parent().attr("nome"), $(obj).parent().parent().attr("protocolo"), $(obj).parent().parent().attr("situacao"), "consultaRecibo");
}

function abrirPopupConsultarSituacao(obj){
	abrirPopupNFe($(obj).parent().parent().attr("idNota"), $(obj).parent().parent().attr("numero"), $(obj).parent().parent().attr("nome"), $(obj).parent().parent().attr("protocolo"), $(obj).parent().parent().attr("situacao"), "consultaSituacao");
}

//OCORRENCIAS
function abrirPopupOcorrencia(idOrigem) {
	var dialog = {
		'content': '<div>Não foi possível carregar as informações.</div>',
		'classOk': 'hidden',
		'textCancelar': 'Fechar',
		'htmlTitle': 'Ocorrências',
		'width': 964,
		fnAfterDestroy: function() {
			listar();
		}
	};

	$.get('templates/form.ocorrencias.popup.php?origem=notas&idOrigem=' + idOrigem, function(data) {
		dialog.content = '<div>' + data + '</div>';
		dialog.fnCreate = function() {
			getOccurrances();
		}
	}).always(function() {
		createDialog(dialog);
	});
}

function getOccurrances() {
	var idOcorrencia = $("#idOrigemPopup").val();

	xajax_listOccurrences(idOcorrencia);
}

function createOccurrancesGrid(aOcorrencias) {
	$("#tabOcorrencias > tbody > tr").each(function() {
		if (($(this).attr("id") != "tr_oco_label")) {
			$(this).remove();
		}
	})

	$.each(aOcorrencias, function(id, item) {
		$("<tr style='height:24px'><td style='padding-left: 2px;'>" + item.data + "</td><td style='padding-left: 4px;'>" + item.ocorrencia + "</td></tr>").appendTo("#tabOcorrencias");
	})
}

function inutilizarNumeracao(){
	abrirPopupNFe(0, 0, "", 0, 0, "inutilizacao");
}

function verificarNumeroLoja(idConfUnidadeNegocio){
	if(idConfUnidadeNegocio === undefined || idConfUnidadeNegocio == null ){
		idConfUnidadeNegocio = 0;
	}

	if (idConfUnidadeNegocio == 0) {
		idConfUnidadeNegocio = $('#idConfUnidadeNegocio').val();
	}
	$('#numero').val('');
	xajax_obterProximoNumeroDeNota($("#serie").val(), $("#notaTipo option:selected").val(), $("#loja").val(), idConfUnidadeNegocio);
}

function sendDataShopStore() {
	if (validarSelectedItems()) {
		var selecionado = 0;
		var pedidos = [];

		$.each(getIdsSelectedItems(), function(i, idNota) {
			var element = $('input[type="checkbox"][value="' + idNota + '"]').parents('tr');

			pedidos.push({
				'cliente': parseFloat(element.attr('idContato')),
				'idVenda': parseFloat(element.attr('idNota')),
				'numeroVenda': parseFloat(element.attr('numero')),
				'numeroPedido': parseFloat(element.attr('numero')),
				'codigoRastreamento': element.attr('etiquetaCorreios'),
				'codigoRastreio': element.attr('etiquetaCorreios'),
				'numeroLojaVirtual': element.attr('idMagento'),
				'idMagento': element.attr('idMagento'),
				'tipoIntegracao': element.attr('tipoIntegracao'),
				'idLoja': element.attr('idLoja'),
				'chaveAcesso': element.attr('chaveAcesso'),
				'situacao': element.attr('situacao'),
				'serie': element.attr('serie'),
				'urlRastreamento': element.attr('data-url-rastreamento') || '',
				'origemLogistica': element.attr('data-origem-logistica') || 0
			});
		});

		if (!$.isEmptyObject(pedidos)) {
			$('#dialogSendData').dialog({
				'modal': true,
				'resizable': false,
				'width': 450,
				'modal': true,
				'title': 'Envio de dados - Loja virtual',
				'buttons': {
					'Sim': function() {
						aParamOrders = {
							'loja': $('select#listaLojasAtivasGeneral option:selected').attr('idLoja'),
							'idIntegracao': $('#listaLojasAtivasGeneral').val(),
							'integrationType': $('select#listaLojasAtivasGeneral option:selected').attr('tipointegracao')
						};

						aParamData = {
							'tipoCadastro': 'N',
							'enviarRastreamento': $('select#enviar_rastreamento_loja_virtual option:selected').val(),
							'enviarNFe': $('select#enviar_nfe_loja_virtual option:selected').val(),
							'enviarStatusLoja': $('select#enviar_status_loja_virtual option:selected').val()
						};

						if (aParamOrders.loja == undefined) {
							alert('Por favor, selecione a loja virtual que deseja utiliza para esta operação.');
						} else {
							xajax_enviarDadosLojaVirtual(pedidos, aParamOrders, aParamData);
							$(this).dialog('close');
						}
					},
					'Não': function() {
						$(this).dialog('close');
					}
				},
				'create': function() {
					limparSetTipoIntegracaoModal();
					xajax_listarLojasVirtuaisAtivas(null, 'listaLojasAtivasGeneral', 'Selecione');
					$(this).closest('.ui-dialog').find('.ui-button').eq(1).addClass('button-default');
					$(this).closest('.ui-dialog').find('.ui-button').eq(1).css('min-width', '45px').css('height', '26px');
					$(this).closest('.ui-dialog').find('.ui-button').eq(2).addClass('button-default');
					$(this).closest('.ui-dialog').find('.ui-button').eq(2).css('min-width', '45px').css('height', '26px');
				},
				'close': function() {
					limparSetTipoIntegracaoModal();
					$(this).dialog('destroy');
				}
			});
		} else {
			$('<div style="text-align:left;"><p>Atenção: É necessário selecionar a(s) notas fiscais, para envio das informações para a loja virtual.</p></div>').dialog({
				'modal': true,
				'resizable': false,
				'width': 325,
				'title': 'Envio de dados - Loja virtual',
				'buttons': {
					'Ok': function() {
						$(this).dialog('destroy');
					}
				},
				'create': function() {
					$(this).closest('.ui-dialog').find('.ui-button').eq(1).addClass('button-default');
					$(this).closest('.ui-dialog').find('.ui-button').eq(1).css('min-width', '45px').css('height', '26px');
				},
				'close': function(){
					$(this).dialog('destroy');
				}
			});
		}
	}
}

function limparSetTipoIntegracaoModal(){
	$("#sp_enviar_rastreamento_loja_virtual").css("display","none");
	$("#sp_enviar_nfe_loja_virtual").css("display","none");
	$("#sp_enviar_status_loja_virtual").css("display","none");
	$("#infoWarn").css("height", "auto").css("margin-top", 92).css("padding-top", 10);
	$("#enviar_rastreamento_loja_virtual").val('false');
	$("#enviar_nfe_loja_virtual").val('false');
	$("#enviar_status_loja_virtual").val('false');
}

function setarTipoIntegracaoModal(){
	var type = $("select#listaLojasAtivasGeneral option:selected").attr("tipointegracao");
	$("#tipoIntegraçãoHidden").val(type);
	if(jQuery.inArray(type , filtroEnviarDadosLojaVirtual) != '-1'){
		$("#infoWarn").css("height", "auto").css("margin-top", 160).css("padding-top", 15);

		if(jQuery.inArray(type , filtroRastreamentoLojaVirtual) != '-1'){
			$("#sp_enviar_rastreamento_loja_virtual").css("display","block");
		}else{
			$("#sp_enviar_rastreamento_loja_virtual").css("display","none");
		}


		if(jQuery.inArray(type , filtroEnviarNFe) != '-1'){
			$("#sp_enviar_nfe_loja_virtual").css("display","block");
		}else{
			$("#sp_enviar_nfe_loja_virtual").css("display","none");
		}
		if(jQuery.inArray(type , filtroStatusLoja) != '-1'){
			xajax_buscaStatusLoja(type);
			$("#sp_enviar_status_loja_virtual").css("display","block");
		}else{
			$("#sp_enviar_status_loja_virtual").css("display","none");
		}

		if(jQuery.inArray(type , filtroNumeroVendaLojaVirtual) != '-1'){
			$("#sp_enviar_numero_venda_loja_virtual").css("display","block");
		}else{
			$("#sp_enviar_numero_venda_loja_virtual").css("display","none");
		}

	}else{

		limparSetTipoIntegracaoModal

		$('<div style="text-align:left;"><p>Atenção: Este recurso esta indisponível para a plataforma virtual '+type+'.</p>' +
			'</div>').dialog({modal: true, resizable: false, width: 325, title: 'Envio de dados - Loja virtual',
							buttons: {
								"Ok": function() {
									$(this).dialog("destroy");
								}
							}, create:function() {
								$(this).closest('.ui-dialog').find('.ui-button').eq(1).addClass('button-default');
								$(this).closest('.ui-dialog').find('.ui-button').eq(1).css("min-width", "45px").css("height", "26px");
							 }, close:function(){
								$(this).dialog("destroy");
							}
			});
	}
}

function montarPopupPreRequisitos(configurarCertificado, completarEmpresa, sCompletarEmpresa, exibirCompraCertificado){
	$('<div>', {id: 'nf-requirements', style: 'text-align: left;'}).append(
		$('<div>', {style: 'border: solid 1px #CCCCCC;'}).append(
			$('<div>',{class:"container-fluid"}).append(
				$('<div>', {style: 'margin-top: 0px !important', class: 'col-xs-12 alert-box alert-box-transparent ' + (configurarCertificado ? 'alert-box-warning' : 'alert-box-ok') }).append(
					$('<h3>', {class: 'alert-box-title', text: configurarCertificado ? 'Atenção' : 'Você configurou o certificado '}),
					$('<p>', {text: 'Configurar um certificado digital. ', style: (configurarCertificado ? '' : 'text-decoration: line-through;')}).append(
						$('<a>', {title: 'Clique para corrigir', text: 'Corrigir', href: 'preferencias.php#certificado-digital/certificado-digital-configuracoes', target: '_blank', style:'text-decoration: none;'})
					)
				)
			),
			$('<div>',{class:"container-fluid"}).append(
				$('<div>', {style: 'margin-top: 0px !important', class: 'col-xs-12 alert-box alert-box-transparent ' + (completarEmpresa ? 'alert-box-warning' : 'alert-box-ok') }).append(
					$('<h3>', {class: 'alert-box-title', text: completarEmpresa ? 'Atenção' : 'As informações da empresa estão completas'}),
					$('<p>', {text: 'Completar as informações da sua empresa. ', style: (completarEmpresa ? '' : 'text-decoration: line-through;')}).append(
						$('<a>', {title: 'Clique para corrigir', text: 'Corrigir', href: 'empresa.php', target: '_blank', style:'text-decoration: none;'}),
						$('<li>').append(
							$('<span>', {text: sCompletarEmpresa, style: 'color:#999; margin-left: 0px !important;'})
						)
					)
				)
			)
		),
		exibirCompraCertificado ? $('<p>', {style: 'font-size:11pt; font-style: italic; text-align: center;', text: 'Adquira seu certificado digital A1 por R$210,00. '}).append(
			$('<a>', {class: 'link', style:'font-size:11pt; font-style: italic; text-decoration: none;', text: 'Saiba mais', target: '_blank', href: 'compras.certificado.php'})
		) : $('<div>')
	).appendTo('body');
	var dialog = {
		config: {
			title: 'Antes de continuar, você deveria...',
			width: 500
		},
		content: $("#nf-requirements"),
		textOk: 'Prosseguir',
		hideCancel: true,
		fnOk: function() {
			$("#nf-requirements").html('');
		},
		fnBeforeClose: function() {
			$("#nf-requirements").html('');
		}
	};
	createDialog(dialog);
}

function gerarGnresSelecionadas() {
	displayWait('waitNotas', true, 'Gerando GNREs, aguarde...');
	xajax_gerarGnresNotasFiscais(getIdsSelectedItems({'asString': true}));
}

function montaStatusLoja(obj){
	//Limpa o campo
	$('#enviar_status_loja_virtual').html('');
	//Adiciona o campo padrão
	$('#enviar_status_loja_virtual').append($('<option>', {
		    value: false,
	        text : 'Não'
	    }));
	//Adiciona os valores da loja virtual
	$.each(obj, function( id, atributo ) {
		  $('#enviar_status_loja_virtual').append($('<option>', {
			  value: id,
			  text : atributo
		  }));
	});
}

function gerarPdfNfes(){
	$("#gerarPdfNfePopup").dialog({
		title: "Gerar PDF DANFE",
		resizable:false,
		modal:true,
		open:function(){
			$("#gerarPdfDanfeDateIni").val($("#data-ini").val());
			$("#gerarPdfDanfeDateFim").val($("#data-fim").val());
			mostrarOcultarGerarPdfDanfeDateContainer();
			$("#gerarPdfDanfeMsg").html("").addClass("nomessage").removeClass("warn");
		},
		width:300
	});
}

function mostrarOcultarGerarPdfDanfeDateContainer(){
	if ($("#gerarPdfDanfeRadioSelecionadas").prop("checked") == true){
		$(".gerarPdfDanfeDateContainer").hide();
	} else {
		$(".gerarPdfDanfeDateContainer").show();
	}
}

function gerarPdfNfe(idNota) {
	var idsNotasSelecionadas = '';

	if (idNota != undefined){
		displayWait('waitNotas', true, 'Gerando arquivo, aguarde...');
		xajax_gerarPdfNfe(idNota, {'gerarPdfDanfeRadio': 'selecionadas'});

		return true;
	}

	$('#gerarPdfDanfeSubmit').prop('disabled', true);
	$('#gerarPdfDanfeMsg').html('Gerando arquivo, aguarde...').addClass('warn').removeClass('nomessage');

	if ($('#gerarPdfDanfeRadioSelecionadas').prop('checked') == true) {
		var ids = getIdsSelectedItems({'asString': true});
		var limiteSelecionados = parseFloat($('#registrosPorPagina').val()) * 2;

		if (ids == '') {
			$('#gerarPdfDanfeMsg').html('Nenhuma nota fiscal selecionada!');
			$('#gerarPdfDanfeSubmit').prop('disabled', false);

			return false;
		} else if (getIdsSelectedItems().length > limiteSelecionados) {
			$('#gerarPdfDanfeMsg').html('Por favor, selecione no máximo ' + limiteSelecionados + ' notas.');
			$('#gerarPdfDanfeSubmit').prop('disabled', false);

			return false;
		}
	}
	xajax_gerarPdfNfe(ids, xajax.getFormValues('gerarPdfDanfeForm'));
}

function callbackGerarPdfNfe(link, erros){
	$("#gerarPdfDanfeSubmit").prop("disabled", false);
	closeWait("waitNotas");
	if (erros != ""){
		$("#gerarPdfDanfeMsg").html(erros);
	} else {
		$("#gerarPdfDanfeMsg").html("").addClass("nomessage").removeClass("warn");
		window.open(link, "_blank");
		listar();
	}
}

function obterMarkupLoja(idProduto, idLista){
	xajax_obterDadosMarkupLoja(idProduto, idLista,true);
}

function mostrarTabelaFormacaoPrecosLojas(aVinculoLojas,idLista){

	var tabelaFormacaoPrecosLoja = "<tr><th></th><th></th><th>PV atual</th><th>Markup</th><th>PV</th></tr>";
	if(aVinculoLojas){
		$.each(aVinculoLojas, function(id, item) {

			var numCampoDisplay = $("#pvMarkupLojas").val();
			numCampoValor = Number(numCampoDisplay.replace(/[^0-9\.]+/g,"."));
			if(numCampoValor > 0){
				markupLoja = numCampoValor;
			}else{
				markupLoja = item.preco_markup;
				numCampoDisplay = nroBra(item.preco_markup);
			}
			precoVenda = ($("#pvPrecoVenda" + idLista).val());
			if(markupLoja === 0 ){
				markupLoja = 1;
			}

			var checked = "";
			if(item.marcado !== false){
				checked = "checked='checked'";
			}

			precoVendaLoja = nroBra(nroUsa(precoVenda) * markupLoja);
			tabelaFormacaoPrecosLoja += "<tr class = 'linhaMarkupLoja'>" +
									"<td><input type='checkbox' value='S' id='atualizarMarkupProdutoLoja" + id + "' name='atualizarMarkupProdutoLoja" + id + "' class='checkbox tline3' " + checked + " ></td>" +

									"<td class = 'tline3'>" +
										"<img src='" + getIconeIntegracao(item.integracao) + "' alt='" + item.nomeLoja + "' title='" + item.nomeLoja + "'  style='width:18px;height:18px'>" +
									"</td>" +
									"<td class = 'tline3'><input type='text' value='" + precoVenda + "' name='formacaoPrecos[precoAtualLoja][]' id='precoVendaAtualLoja" + id + "' ' readonly='readonly' style='width: 65px;'></td>" +
									"<td class = 'tline3'><input type='text' value=" + numCampoDisplay + " name='formacaoPrecos[markupLoja][]' id='markupLoja" + id + "' class=' editgrid ac_input edt-number ' onBlur='formacaoPrecosLojaAtualizarItem(" + id + ", \"N\");' style='width: 65px;' ></td>" +
									"<td >" +
									"	<input type='text' value='" + precoVendaLoja  + "' name='formacaoPrecos[precoVendaLoja][]' id='precoVendaLoja" + id + "' class='editgrid ac_input edt-number' style='width: 65px;' onBlur='formacaoPrecosLojaAtualizarItem(" + id + ", \"S\");'>" +
									"</td>" +
									"<td style='display:none'>" +
										"<input  id='loja" + id + "' value = '" + item.idLoja + "' ></input>" +
										"<input  id='produto" + id + "' value = '" + item.idProduto + "' ></input>" +
										"<input  id='produtoIndice" + id + "' value = '" + idLista + "' ></input>" +
										"<input  id='idIntegracao" + id + "' value = '" + item.idIntegracao + "' ></input>" +
										"<input  id='integracao" + id + "' value = '" + item.integracao + "' ></input>" +
									"</td>"+
									"</tr>";
		})



		if(getWindowWidth() < 1024){
			if ( $("#mobile" +idLista).val() == 0 ) {
				$("#mobile" +idLista).val(1);
				$("#idProduto" + idLista).parent().parent().after("<tr style='margin-top: 10px;'>" + tabelaFormacaoPrecosLoja + "</tr><tr style = 'height : 15px;'></tr>");
			}
			$("#div-formacao-precoLoja").css({'float':'left','width':''});
		}else{
			$("#tabela_formacao_precos_lojas").html(tabelaFormacaoPrecosLoja);
		}

	}else{
		$("#tabela_formacao_precos_lojas").html("");
	}


}


function formacaoPrecosLojaAtualizarItem(key, aPartirDoPrecoDeVenda) {
	var pvVendaAtual = 0;
	var pvVenda = 0;
	var pvMarkup = 0;

	pvMarkup = parseFloat(nroUsa($("#markupLoja" + key).val()));
	pvVendaNovo = parseFloat(nroUsa($("#precoVendaLoja" + key).val()));
	pvVendaAtual = parseFloat(nroUsa($("#precoVendaAtualLoja" + key).val()));



	if (aPartirDoPrecoDeVenda == "S") {
		pvMarkup = pvVendaNovo / pvVendaAtual;
		$("#markupLoja" + key).val(nroBra(pvMarkup));
	} else {
		pvVendaNovo = pvVendaAtual * pvMarkup;
		$("#precoVendaLoja" + key).val(nroBra(pvVendaNovo));
	}
}

function formacaoPrecosSetarMarkupLojas() {
	$("input[name='formacaoPrecos[markupLoja][]']").each(function (key, value) {
		$(this).val($("#pvMarkupLojas").val());
	})



	formacaoPrecosLojaAtualizar();
}

function formacaoPrecosLojaAtualizar() {
	var pvVenda = 0;
	$("input[name='formacaoPrecos[precoAtualLoja][]']").each(function (key, value) {
		pvVendaLoja = parseFloat(nroUsa($("#precoVendaAtualLoja" + key).val())) * parseFloat(nroUsa($("#markupLoja" + key).val()));
		$("#precoVendaLoja" + key).val(nroBra(pvVendaLoja));
	})
}

function limpaTabelaMarkupLoja(){
	$("#tabela_formacao_precos_lojas").html("");
}

function guardarValoresMarkupLoja(){
	var precoVenda = 0;
	var idLoja = 0;
	var indiceLista= 0;
	var vinculoLoja = 0;
	var idProduto = 0;
	var idIntegracao = 0;
	var integracao = "";

	$('.linhaMarkupLoja').each(function(id){
		var indiceArray = null;
		var markupProdutoLoja = new Array();

		precoVenda = $(this).find("#precoVendaLoja" + id ).val();
		idLoja = $(this).find("#loja" + id).val();
		indiceLista = $(this).find("#produtoIndice" + id ).val();
		idProduto = $(this).find("#produto" + id).val();
		idIntegracao = $(this).find("#idIntegracao" + id).val();
		markupLoja = $(this).find("#markupLoja" + id ).val();
		integracao = $(this).find("#integracao" + id ).val();
		marcado = $(this).find("#atualizarMarkupProdutoLoja" + id ).is(':checked') ;
		markupProdutoLoja.indice = indiceLista;
		markupProdutoLoja.preco = precoVenda;
		markupProdutoLoja.preco_markup = markupLoja;
		markupProdutoLoja.idProduto = idProduto;
		markupProdutoLoja.idLoja = idLoja;
		markupProdutoLoja.idIntegracao = idIntegracao;
		markupProdutoLoja.integracao = integracao;
		markupProdutoLoja.marcado = marcado;

		//Verifica se ja existe no array
		$(arrayMarkupLoja).each(function(id2){
			if(arrayMarkupLoja[id2] !== 'undefined'){
				if(arrayMarkupLoja[id2].idLoja == markupProdutoLoja.idLoja && arrayMarkupLoja[id2].idProduto == markupProdutoLoja.idProduto){
					indiceArray = id2;
				}
			}
		});

		if(indiceArray != null && indiceArray >= 0){
	 		arrayMarkupLoja[indiceArray] = markupProdutoLoja;
		}else{
			arrayMarkupLoja.push(markupProdutoLoja);
		}


	});
}


function removerValoresMarkupLoja(indice){

	$(arrayMarkupLoja).each(function(id){
		if( typeof arrayMarkupLoja[id] !== 'undefined'){
			if(indice == arrayMarkupLoja[id].indice){
				delete arrayMarkupLoja[id];
			}
		}
	});
}


function verificaCheckProduto(arrayMarkupLoja){

	var markupLojas = new Array();
	$(arrayMarkupLoja).each(function(id){
		var markupLoja = new Array();
		var indice = arrayMarkupLoja[id].indice;
		markupLoja.preco = arrayMarkupLoja[id].preco;
		markupLoja.preco_markup = arrayMarkupLoja[id].preco_markup;
		markupLoja.idProduto = arrayMarkupLoja[id].idProduto;
		markupLoja.idLoja = arrayMarkupLoja[id].idLoja;
		markupLoja.idIntegracao = arrayMarkupLoja[id].idIntegracao;
		markupLoja.integracao = arrayMarkupLoja[id].integracao;
		markupLoja.editado = true;
		markupLoja.marcado = arrayMarkupLoja[id].marcado;
		markupLojas.push(markupLoja);

	});

	$('.mostraTabela').each(function(indice){
		var listado = false;
		if($(this).find('.marcado').is(":checked")){
			$(arrayMarkupLoja).each(function(index){
				if(indice == arrayMarkupLoja[index].indice){
					listado = true;
				}
			});

			var markupValor = $("#pvMarkupLojas").val();
			var markupLojasValor = Number(markupValor.replace(/[^0-9\.]+/g,"."));

			if(listado != true && markupLojasValor > 0){
				var markupLoja = new Array();
				var idProduto = $(this).find("#idProduto" + indice).val();
				var indice = $(this).find("#idLista").val();
				var precoVenda = $("#pvPrecoVenda" + indice).val();
				var precoVendaValor = Number(precoVenda.replace(/[^0-9\.]+/g,"."));


				markupLoja.preco = precoVendaValor * markupLojasValor;
				markupLoja.preco_markup = markupLojasValor;
				markupLoja.idProduto = idProduto;
				markupLoja.idLoja = null;
				markupLoja.idIntegracao = null;
				markupLoja.integracao = null;
				markupLoja.editado = false;
				markupLoja.marcado = true;

				markupLojas.push(markupLoja);
			}

		}
	});
	return markupLojas;
}


function highlightItem(elemento){
	$(elemento).parents().find('.clicked').removeClass("clicked");
	$(elemento).parents().find('.highlight').removeClass("highlight");
	$(elemento).parents().find('.highlightTabela').css("background-color","white");
	$(elemento).addClass('clicked');
	$(elemento).addClass("highlight");
	$(elemento).find('input').addClass("highlight");
	$(elemento).find('.highlightTabela').css("background-color","#dee4ea");
	$(elemento).css("cursor", "pointer");
}

function atualizaTabelaMarkupLoja(pvVenda){
	$('.linhaMarkupLoja').each(function(id){
		$(this).find('#precoVendaAtualLoja' + id).val(nroBra(pvVenda));
	});

	$('.linhaMarkupLoja').each(function(id){
		formacaoPrecosLojaAtualizarItem(id,'N');
	});
}

function verificaCheckProdutoFormacao(){
	var formacaoPrecosProdutos = new Array();
	$('.mostraTabela').each(function(id){
		if($(this).find('.marcado').is(":checked")){
			var indice = $(this).find("#idLista").val();
			var itemProduto = new Array();
			itemProduto["idItem"] = $("#idItem" + indice).val();
			itemProduto["idProduto"]= $("#idProduto" + indice).val();
			itemProduto["pvMarkup"] = $("#pvMarkup" + indice).val();
			itemProduto["pvCustoUnitario"] = $("#pvCustoUnitario" + indice).val();
			itemProduto["pvPrecoVenda"] = $("#pvPrecoVenda" + indice).val();
			itemProduto["pvFrete"] = $("#pvFrete" + indice).val();
			itemProduto["precoLista"] = $("#precoLista" + indice).val();
			itemProduto["custoInicial"] = $("#custoInicial" + indice).val();
			itemProduto["chbx_custo"] = $("#chbx_custo_" + indice).is(":checked");
			itemProduto["chbx_venda"] = $("#chbx_venda_" + indice).is(":checked");

			formacaoPrecosProdutos.push(itemProduto);
		}
	});
	return formacaoPrecosProdutos;
}

function erroSincronizarPreco(idLoja,idProduto){
	$('.linhaMarkupLoja').each(function(id){
		if(idLoja == $('#loja' + id).val() &&  idProduto == $('.linhaMarkupLoja #produto'+id).val()){
			if($('#respostaLoja' + id).is(":visible")){
				$('#respostaLoja' + id).html("<td id=respostaLoja"+id+">" + erroLoja + "</td>");
			}else{
				$(this).find('#precoVendaLoja' + id).parent().after("<td id=respostaLoja"+id+">" + erroLoja + "</td>");
			}
		}
	});


	$('.mostraTabela').each(function(id){
		if(idProduto == $("#idProduto" + id).val()){
			if($('#respostaLista' + id).is(":visible")){
				$('#respostaLista' + id).html("<span id=respostaLista"+id+">" + erroLoja + "</span>")
			}else{
				$(this).find('#produto' + id).after("<span id=respostaLista"+id+">" + erroLoja + "</span>");
			}
		}
	});
}

function okSincronizarPreco(idLoja,idProduto){
	$('.linhaMarkupLoja').each(function(id){
		if(idLoja == $('#loja' + id).val() && idProduto == $('.linhaMarkupLoja #produto'+id).val()){
			if($('#respostaLoja' + id).is(":visible")){
				$('#respostaLoja' + id).html("<span id=respostaLoja"+id+">" + okLoja + "</span>")
			}else{
				$(this).find('#precoVendaLoja' + id).parent().after("<td id=respostaLoja"+id+">" + okLoja + "</td>");
			}
		}
	});


	$('.mostraTabela').each(function(id){
		if(idProduto == $("#idProduto" + id).val() && !$('#respostaLista' + id).is(":visible")){
			$(this).find('#produto' + id).after("<span id=respostaLista"+id+">" + okLoja + "</span>");
		}
	});
}

function listarUnidadesDeNegocio(idLoja, idConf) {
	$('#idConfUnidadeNegocio option').css('display', 'none');
	var unidadeAnterior = $('#idConfUnidadeNegocio').val();
	if (idLoja == 0) {
		$('#idConfUnidadeNegocio option[value="0"]').css('display', 'inline');
		$('#idConfUnidadeNegocio').val(0);
		if (idConf == -1 && unidadeAnterior != $('#idConfUnidadeNegocio').val()) {
			$('#idConfUnidadeNegocio').change();
		}
		return true;
	}

	xajax_listarUnidadesDeNegocio(idLoja, function(data){
		data.forEach(function(unidade) {
			$('#idConfUnidadeNegocio option[value="' + unidade.value + '"]').css('display', 'inline');
			if (idConf == unidade.value || (unidade.padrao == 1 && (idConf == -1 || idConf == 0))) {
				$('#idConfUnidadeNegocio').val(unidade.value);
			}
		});
		if (idConf == -1 && unidadeAnterior != $('#idConfUnidadeNegocio').val()) {
			$('#idConfUnidadeNegocio').change();
		}
	});
}

function exportarXmls() {
	var dialog = {
		config: {
			title: 'Exportar XMLs',
			width: 500
		},
		content: $("#exportarXmls"),
		textOk: 'Exportar',
		hideCancel: true,
		fnOk: function() {
			exportarXmlsInit();
		}
	};
	createDialog(dialog, 1);
}

var idsExportacao = [];
var tipoExportacaoXml = '';
var paginaExportacaoXml = 1;
var limiteExportacaoXml = 5;
var dirExportacaoXml = '';
var filenameExportacaoXml = '';
var alertasExportacaoXml = '';

function exportarXmlsInit() {
	dirExportacaoXml = '';
	filenameExportacaoXml = '';
	alertasExportacaoXml = '';

	idsExportacao = getIdsSelectedItems();

	tipoExportacaoXml = (idsExportacao.length == 0 ? 'filtradas' : 'selecionadas');

	if (tipoExportacaoXml == 'filtradas') {
		alert('Nenhuma nota selecionada');
		return false;
	}

	var dialog = {
		config: {
			title: 'Exportando XMLs',
			width: 500
		},
		content: '<div style="text-align:center;"><i id="exportarXmlsLoadingIcon" class="fa fa-refresh spin-sync" style="color:#CBC9CC;font-size:60pt;"></i><p id="exportarXmlProgresso">Exportando XMLs, aguarde...</p><div id="exportarXmlResult"></div></div>',
		hideOk: true,
		hideCancel: true
	};
	createDialog(dialog, 1);

	paginaExportacaoXml = 1;
	/*if (tipoExportacaoXml == 'filtradas') {
		xajax_contarParaGerarArquivoXMLNFes(tipo, xajax.getFormValues('exportarXmlsForm'), xajax.getFormValues('notaFiscalFiltros'), function (data) {
			if (data.total == 0) {
				alert('Nenhuma nota com os critérios selecionados');
				return false;
			}
			totalExportacaoXml = data.total;
			$('#exportarXmlProgresso').html('<span id="exportarXmlNroAtual">0</span> de ' + data.total + ' notas exportadas');
			buscaProximaPaginaIdsExportarXml();
		});
	} else {*/
		totalExportacaoXml = idsExportacao.length;
		$('#exportarXmlProgresso').html('<span id="exportarXmlNroAtual">0</span> de ' + idsExportacao.length + ' notas exportadas');
		buscaProximaPaginaIdsExportarXml();
	//}
}

function buscaProximaPaginaIdsExportarXml() {
	if (tipoExportacaoXml == 'selecionadas') {
		var ids = [];
		var continuar = true;
		var inicio = ((paginaExportacaoXml - 1) * limiteExportacaoXml);
		var fim = inicio + limiteExportacaoXml;
		if (fim > idsExportacao.length) {
			fim = idsExportacao.length;
		}
		for (i = inicio; i < fim; i++) {
			if (idsExportacao[i] != undefined) {
				ids.push(idsExportacao[i]);
			}
		}
		paginaExportacaoXml++;
		if (ids.length < limiteExportacaoXml) {
			continuar = false;
		}
		exportarXmlsPagina(ids, continuar);
	} else {
		// TODO busca ids no banco
	}
}

function exportarXmlsPagina(ids, continuar) {
	if (ids.length == 0) {
		exportarXmlsExibirResultado('');
		return false;
	}
	xajax_gerarArquivoXMLNFes(ids, filenameExportacaoXml, function(data) {
		if (data.erros != '') {
			exportarXmlsExibirResultado(data.erros);
			return false;
		}
		if (data.filename != '') {
			dirExportacaoXml = data.dir;
			filenameExportacaoXml = data.filename;
		}
		if (data.alertas != '') {
			$.each(data.alertas, function(key, alerta) {
				alertasExportacaoXml += '<li>' + alerta + '</li>';
			});
		}
		$('#exportarXmlNroAtual').html(parseInt($('#exportarXmlNroAtual').html()) + data.sucessos);
		if (continuar == true) {
			buscaProximaPaginaIdsExportarXml()
		} else {
			exportarXmlsExibirResultado(data.erros);
		}
	});
}

function exportarXmlsExibirResultado(erros) {
	if (erros != '') {
		$('#exportarXmlsLoadingIcon').addClass('fa-ban').removeClass('fa-refresh spin-sync').css('color', '#FF6D7C');
		$('#exportarXmlResult').html('<h3 class="alert-box-title">Exportação concluída</h3><p>' + erros + '</p>').addClass('col-xs-12 alert-box alert-box-error').css('text-align', 'left');
		return false;
	}
	var html = '';
	if (parseInt($('#exportarXmlNroAtual').html()) > 0) {
		$('#exportarXmlsLoadingIcon').addClass('fa-check-circle-o').removeClass('fa-refresh spin-sync').css('color', '#3FAF6C');
		html = '<div class="col-xs-12 alert-box alert-box-ok" style="text-align:left;"><h3 class="alert-box-title">Exportação concluída</h3><p><a href="' + dirExportacaoXml +  filenameExportacaoXml + '">Clique aqui para fazer o download do arquivo gerado</a>.</p></div>'
	} else {
		$('#exportarXmlsLoadingIcon').addClass('fa-exclamation-triangle').removeClass('fa-refresh spin-sync').css('color', '#df913d');
	}
	if (alertasExportacaoXml != '') {
		html += '<div class="col-xs-12 alert-box alert-box-warning" style="text-align:left;"><h3 class="alert-box-title">Avisos</h3><ul>' + alertasExportacaoXml + '</ul></div>';
	}
	$('#exportarXmlResult').html(html);
}

function validarSelectedItems() {
	var limiteSelecionados = parseFloat($('#registrosPorPagina').val()) * 2;
	var ids = getIdsSelectedItems();
	var res = false;

	if (ids.length > limiteSelecionados) {
	    DialogMessage.warning({'description': 'Por favor, selecione no máximo ' + limiteSelecionados + ' notas.'});
	} else if (ids.length > 0) {
		res = true;
	} else {
		DialogMessage.warning({'description': 'Nenhuma nota selecionada.'});
	}

	return res;
}