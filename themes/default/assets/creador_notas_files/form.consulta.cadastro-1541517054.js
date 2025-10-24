var dConsCad = null;
var ie = null;
var nome = null;
var endereco = null;
var enderecoNro = null;
var bairro = null;
var idMunicipio = null;
var cidade = null;
var cep = null;
var idCnpj = null;

function abrirConsultarCadastroContribuinte(cnpj, uf){
	$("#resultadoConsCad").hide();
	dConsCad = $('#formConsCad').dialog({
		title: "Importar endereço da SEFAZ",
		resizable: false,
		modal: true,
		width: getMobileWidthForDialogs(500),
		height:450,
		close:function(){
			$(this).dialog("destroy");
		},
		open:function(){
			$("#cnpjConsCad").val(cnpj);
			$("#ufConsCad").val(uf);
		}
	});
}

function consultarCadastroContribuinte(){
	$("#msgConsCad").removeClass('success');
	$("#msgConsCad").removeClass('warn');
	$("#msgConsCad").html('Buscando informações, aguarde...');
	$("#resultadoConsCad").hide();
	try {
		xajax_consultarCadastroContribuinteContato($("#cnpjConsCad").val(), $("#ufConsCad").val());
	} catch (e){
		$("#msgConsCad").addClass('warn');
		$("#msgConsCad").html('Não foi possível efetuar essa requisição. Motivo: ' + e);
	}
}

function montarDadosContribuinte(dados2){
	if (dados2.erro != undefined){
		$("#msgConsCad").removeClass('success');
		$("#msgConsCad").addClass('warn');
		$("#msgConsCad").html("Não foi possível obter a informação do contato.<br />Motivo: " + dados2.erro);
		return false;
	}
	$("#msgConsCad").removeClass('warn');
	$("#msgConsCad").removeClass('success');
	$("#msgConsCad").html("");

	if(dados2.situacao == 0){
		$("#msgConsCad").removeClass('success');
		$("#msgConsCad").addClass('warn');
		$("#msgConsCad").html("<h4>Inscrição Baixada</h4> <p>Dados possivelmente desatualizados</p>");
	}
	$("#ieConsCad").val(dados2.ie);
	$("#nomeConsCad").val(dados2.nome);
	$("#enderecoConsCad").val(dados2.endereco);
	$("#enderecoNroConsCad").val(dados2.numero);
	$("#bairroConsCad").val(dados2.bairro);
	$("#idMunicipioConsCad").val(dados2.idMunicipio);
	$("#cidadeConsCad").val(dados2.municipio);
	$("#cepConsCad").val(dados2.cep);

	$("#resultadoConsCad").show();
}

function preencherDadosContribuinte(){
	$("#" + ie).val($("#ieConsCad").val());
	$("#" + nome).val($("#nomeConsCad").val());
	$("#" + endereco).val($("#enderecoConsCad").val());
	$("#" + enderecoNro).val($("#enderecoNroConsCad").val());
	$("#" + bairro).val($("#bairroConsCad").val());
	$("#" + idMunicipio).val($("#idMunicipioConsCad").val());
	$("#" + cidade).val($("#cidadeConsCad").val());
	$("#" + cep).val($("#cepConsCad").val());
	$("#" + uf).val($("#ufConsCad").val());
	$("#" + idCnpj).val($("#cnpjConsCad").val());
	closePopupConsCad();
}

function setCamposConsCad(xie, xnome, xendereco, xenderecoNro, xbairro, xidMunicipio, xcidade, xcep, xuf, xcnpj){
	ie = xie;
	nome = xnome;
	endereco = xendereco;
	enderecoNro = xenderecoNro;
	bairro = xbairro;
	idMunicipio = xidMunicipio;
	cidade = xcidade;
	cep = xcep;
	uf = xuf;
	idCnpj = xcnpj;
}

function closePopupConsCad(){
	dConsCad.dialog("close");
}
