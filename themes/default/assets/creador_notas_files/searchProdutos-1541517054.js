function completerProduct(event, field, afterUpdate, apenasVenda){
	apenasVenda = apenasVenda || 'N';

	if (event.keyCode == 13) {
		autoCompletarProduto(field, afterUpdate, apenasVenda);

		if ($('#pleasewait').length > 0) {
			displayWait('pleasewait');
		}
	}
}

function autoCompletarProduto(field, afterUpdate, apenasVenda) {
	apenasVenda = apenasVenda || 'N';
	var busca = document.getElementById(field).value;

	try {
		xajax_autoCompletarProduto(busca, field, afterUpdate, apenasVenda);
	} catch (e) {
		srv_estoque_autoCompletarProduto(busca, field, afterUpdate);
	}
}

function searchProdutos(field,afterUpdate){
	var busca = document.getElementById(field).value;
	if(navigator.userAgent.indexOf('MSIE')>=0){
		hideSelects('hidden')
	}

	new Boxy.load('templates/form.searchProdutos.popup.php?field='+field+'&busca='+busca+'&afterUpdate='+afterUpdate, {
		title: "Busca avançada",
		modal: true,
		afterShow: ajustarFormSearchProd
	});
}

function ajustarFormSearchProd(){
	var buscaPopup = $("#buscaPopup").val();
	if(buscaPopup !=""){
		displayWait('pleasewait');
		xajax_pesquisarProdutos(xajax.getFormValues('formBuscaProdutos'));
	}
	$("#buscaProdutos").focus();
}

function closeProductMessage() {
	if(navigator.userAgent.indexOf('MSIE')>=0){
		hideSelects("visible");
	}

	Boxy.get("#controls-popup").hide();
}

function setarProduto(id, nome, field){
	closeProductMessage();
	document.getElementById(field).value = nome;
	document.getElementById(field).focus();
}

function incluirProdutoRapido(field,afterUpdate){
	var busca = document.getElementById(field).value;
	if(navigator.userAgent.indexOf('MSIE')>=0){
		hideSelects('hidden')
	}

	new Boxy.load('templates/form.produtoRapido.popup.php?field='+field+'&afterUpdate='+afterUpdate+'&busca='+busca, {
		title: "Inclusão de produto",
		modal: true,
		afterShow: ajustarformInclusaoProd
	});
}

function ajustarformInclusaoProd(){
	$("#codigo").focus();
}

function actionFieldProducts(event){
	if(event.keyCode==27){
		closeProductMessage();
	}
}

var existeEstoqueInfo = false;
function setExisteEstoqueInfo(ee) {
	existeEstoqueInfo = ee;
}

function getEstoqueInfo(dadosEstoque) {
	var qtd = nroUsaFloat(dadosEstoque.quantidade);
	var eAtual = nroUsaFloat(dadosEstoque.estoqueAtual);
	var eMin = nroUsaFloat(dadosEstoque.estoqueMinimo);
	var eMinStr = (dadosEstoque.estoqueMinimo != "" ? nroBraDecimais(eMin, $("#dec_qtde").val()) : "");
	var eMax = nroUsaFloat(dadosEstoque.estoqueMaximo);
	var eMaxStr = (dadosEstoque.estoqueMaximo != "" ? nroBraDecimais(eMax, $("#dec_qtde").val()) : "");

	if (existeEstoqueInfo) {
		qtd = 0;
	}
	var tipoProduto = '';
	if(dadosEstoque.tipoProduto == undefined){
		tipoProduto = "P";
	}else{
		tipoProduto = dadosEstoque.tipoProduto;
	}
	var estoqueAposOperacao = (dadosEstoque.tipo == "C" ? eAtual + qtd : eAtual - qtd);
	var msg = "Estoque atual: " + nroBraDecimais(eAtual, $("#dec_qtde").val()) + "<br>" +
	  		  "Estoque mínimo: " + eMinStr + "<br>" +
	  		  "Estoque máximo: " + eMaxStr  + "<br>" +
	  		  "Estoque depois da " + (dadosEstoque.tipo == "C" ? "compra" : "venda") +": " + nroBraDecimais((existeEstoqueInfo ? eAtual : estoqueAposOperacao), $("#dec_qtde").val()) + "<br>";

	var estoqueInfoHidden = "<input type='hidden' id='h_estoque_atual_" + dadosEstoque.itemNro + "' value='" + dadosEstoque.estoqueAtual + "' />" +
							"<input type='hidden' id='h_estoque_minimo_" + dadosEstoque.itemNro + "' value='" + eMinStr + "' />" +
							"<input type='hidden' id='h_estoque_maximo_" + dadosEstoque.itemNro + "' value='" + eMaxStr + "' />" +
							"<input type='hidden' id='h_tipo_produto_" + dadosEstoque.itemNro + "' value='" + tipoProduto + "' />" ;

	if (dadosEstoque.ordemCompra) {
		msg += "Ordem de compra: " + dadosEstoque.ordemCompra;
		estoqueInfoHidden += "<input type='hidden' id='h_ordem_compra_" + dadosEstoque.itemNro + "' value='" + dadosEstoque.ordemCompra + "' />";
	}

	var infoCor = "green";
	if (estoqueAposOperacao < 0) {
		infoCor = "red";
	} else if (estoqueAposOperacao < eMin) {
		infoCor = "yellow";
	}

	if(tipoProduto == "P"){
		return "<i class='icon-circle icon-circle-" + infoCor + "' style='cursor: pointer; margin-left: 2px;' onmouseover='mostrarEstoque(event, \"" + msg + "\")' onmouseout='cancelarEstoque()'></i>" + estoqueInfoHidden;
	}else{
		return estoqueInfoHidden;
	}

}

function mostrarEstoque(event, msg) {
	flagMouseOver = true;
	x = event.clientX;
	y = event.clientY;
	setTimeout('ativarEstoque(' + x + ', ' + y + ', "' + msg + '")', 500);
}

function ativarEstoque(x, y, msg) {
	if (flagMouseOver) {
		viewPopUp("slotEstoqueProduto", x, y);
		$("#tplEstoqueProduto").html(msg);
	}
}

function cancelarEstoque() {
	flagMouseOver = false;
	$("#slotEstoqueProduto").hide();
}