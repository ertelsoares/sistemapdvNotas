$(document).ready(function() {
	$(document).on("click", "#botaoVisualizarUltimoRastro", function() {
		$("#mensagemRastro").hide();
		$("#dadosRastro").show();
	});
});

function verificarSyncErro(syncErro) {
	var btnUltimoRastro = "";

	if ($("#dataAlteracao").val().length > 0) {
		btnUltimoRastro = "<input id='botaoVisualizarUltimoRastro' class='btn-secundary-novo btn-wh-150' type='button' value='Último Rastro' style='margin-left: 5px' />";
	}

	if (syncErro === true) {
		$("#mensagemRastro").html("<div class='col-lg-12 alert-box alert-box-info alert-box-transparent margin-top0'><p>Serviço de consulta de rastro indisponível. Sincronize novamente mais tarde.</p></div><div class='wh100 text_right'><input id='botaoSincronizarRastro' class='btn-secundary-novo btn-wh-100' type='button' value='Sincronizar' />" + btnUltimoRastro + "</div>");
		$("#mensagemRastro").show();
		$("#dadosRastro").hide();
	}
}