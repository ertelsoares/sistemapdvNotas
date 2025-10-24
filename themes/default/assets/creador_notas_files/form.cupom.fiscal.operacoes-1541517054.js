function configurarParametrosDeComando() {
	return parameters = [{"conteudo": $("#comandos").val(),"pathEnt": $("#path_arquivo_ent").val(), "pathSai": $("#path_arquivo_sai").val()}];
}

function processarImpressaoCupom() {
	var jc = new JavaConnection(appAcessoHardware, "CupomFiscal").getInstance();
	parameters = configurarParametrosDeComando();

	jc.sendToPrinter(parameters, function (err, data){
		if(!err && data.substr(0, 2) == "OK"){
			var idCupom = trim(data.substr(3));
			$("#numeroCupom").html("Número do cupom: " + idCupom);
			$("#situacaoCupom").html("Situação: Emitido");
			configurarLinks(1);

			$("#comandos").val("ECF.DataHora");
			parameters = configurarParametrosDeComando();
			
			jc.sendToPrinter(parameters, function (err, data){
				if(!err){
					resultadoDH = trim(data.substr(3));
					resultadoDH = data.split(" ");
					var date = resultadoDH[0].substr(0, 2) + "/" + resultadoDH[0].substr(3, 2) + "/" + resultadoDH[0].substr(6, 2);
					var hora = resultadoDH[1];
					xajax_gravarCupom(0, idCupom, $("#idOrigemCupom").val(), date, hora, 1);
					mostrarResultadoCF("Cupom emitido: " + idCupom, "");
				}else{
					mostrarResultadoCF("Erro emitindo cupom", data);
				}
			});
		}else{
			mostrarResultadoCF("Erro emitindo cupom", data);
		}
	});
	
}
	
function processarCancelamentoCupom() {
	$("#comandos").val("ECF.Ativar\nECF.TestaPodeAbrirCupom");
	var jc = new JavaConnection(appAcessoHardware, "CupomFiscal").getInstance();;
	parameters = configurarParametrosDeComando();
	
	jc.sendToPrinter(parameters, function(err, data){
		if(!err){
			var resultado = data;
			var cancelandoCupomAberto = (trim(data) == "ERRO: Cupom Fiscal aberto");
			
			$("#comandos").val("ECF.Ativar\nECF.CancelaCupom");	
			parameters = configurarParametrosDeComando();
			
			jc.sendToPrinter(parameters, function(err, data){
				if(!err && data.substr(0, 2) == "OK"){
					if (! cancelandoCupomAberto) {
						$("#situacaoCupom").html("Situação: Cancelado");
						xajax_cancelarUltimoCupomEmitido();
						mostrarResultadoCF("O último cupom fiscal emitido foi cancelado", "");
					}else{
						mostrarResultadoCF("Erro cancelando cupom", data);
					}	
				}else{
					mostrarResultadoCF("Erro cancelando cupom", data);
				}
			});
		}else{
			mostrarResultadoCF("Erro cancelando cupom", data);
		} 
	});	
}
	
function processarReducaoZ() {
	$("#comandos").val("ECF.Ativar\nECF.ReducaoZ");
	var jc = new JavaConnection(appAcessoHardware, "CupomFiscal").getInstance();;
	parameters = configurarParametrosDeComando();
	
	jc.sendToPrinter(parameters, function(err, data){
		if(!err && data.substr(0, 2) == "OK"){
			mostrarResultadoCF("Redução Z realizada", "");
		}else{
			mostrarResultadoCF("Erro realizando a redução Z", data);
		}
	});
}