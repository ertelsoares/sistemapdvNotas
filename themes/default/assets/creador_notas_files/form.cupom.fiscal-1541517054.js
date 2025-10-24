var linkVoltar = " <a href='#' onclick='mostrarServicos(); return false;'>Voltar</a> ";
var linkClicado = false;
var appAcessoHardware = "appJava";

function initCupom() {
	mostrarWait("Aguarde, carregando componente fiscal...");
	xajax_obterConfiguracoesCupomFiscal($("#idOrigemCupom").val(), $("#tipoCupom").val());
}

function gerarCupom(idOrigem, tipo) {
	xajax_gerarArquivoCupom(idOrigem, tipo);
}

function configurarLinks(situacao) {
	switch (situacao) {
	case 0: $("#numeroCupom").html("");
			$("#liImprimirCupom").show();
			break;
	case 1: $("#liImprimirCupom").hide();
			break;
	case 2: $("#liImprimirCupom").hide();
			break;
	}
}

function mostrarServicos(situacao) {
	configurarLinks(situacao);
	
	linkClicado = false;
	
	$("#divResultadoCupom").hide();
	$("#cf-wait").hide();
	$("#servicesCupom").show();
}

function mostrarResultadoCF(titulo, mensagem) {
	$("#divResultadoCupom").html("<br/><h3>" + titulo + "</h3>" + mensagem + "<br/>" + linkVoltar);
	$("#cf-wait").hide();
	$("#servicesCupom").hide();
	$("#divResultadoCupom").show();
}

function mostrarWait(mensagem) {
	$("#mensagem-wait").html(mensagem);
	$("#servicesCupom").hide();
	$("#divResultadoCupom").hide();
	$("#cf-wait").show();
}

function trim(sString) {
	while (sString.substring(0,1) == ' ') {
		sString = sString.substring(1, sString.length);
	}
	while (sString.substring(sString.length-1, sString.length) == ' ') {
		sString = sString.substring(0, sString.length-1);
	}
	return sString;
}

function imprimirCupom() {
	if (! linkClicado) {
		linkClicado = true;
		mostrarWait("Aguarde, imprimindo cupom fiscal.");
		setTimeout("processarImpressaoCupom()", 100);
	}
}

function cancelarCupom() {
	if (! linkClicado) {
		linkClicado = true;
		mostrarWait("Aguarde, cancelando cupom fiscal.")
		setTimeout("processarCancelamentoCupom()", 100);
	}
}
	
function reducaoZ() {
	if (confirm("Atenção!\n\n" +
				"Após imprimir a redução Z do dia, não é possível emitir cupons fiscais no dia fechado.\n" +
				"Confirma a impressão da redução Z?")) {
		if (! linkClicado) {
			linkClicado = true;
			mostrarWait("Aguarde, realizando redução Z.")
			setTimeout("processarReducaoZ()", 100);
		}
	}
}

function setAppAcessoHardware(app){
	appAcessoHardware = app;
}
