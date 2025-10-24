$("#cidade_contato_rapido").tipsy({gravity: $.fn.tipsy.autoWE});

function completerContact(event,field,afterUpdate){
	if(event.keyCode==13){
		var origem = "campo";
		autoCompletarContato(field,afterUpdate,origem, 'N');
		displayWait('pleasewait');
		return false;
	}
}

function completerContactComOpcaoIncluir(event,field,afterUpdate){
	if(event.keyCode==13){
		var origem = "campo";
		autoCompletarContato(field,afterUpdate,origem, 'S');
		displayWait('pleasewait');
		return false;
	}
}

function autoCompletarContato(field,afterUpdate,origem, opcaoIncluir){
	if(origem=="campo"){
		var busca = document.getElementById(field).value;
		xajax_autoCompletarContato(busca,field,afterUpdate, opcaoIncluir);
	}else{
		searchContatos(field,afterUpdate, opcaoIncluir);
		closeWait('pleasewait');
	}
}

function searchContatos(field,afterUpdate,opcaoIncluir) {
	var busca = removeAcentos(document.getElementById(field).value);
	var url = '';

	if (opcaoIncluir == 'S') {
		url = 'templates/form.searchContatos.popup.php?field=' + field + '&busca=' + busca + '&afterUpdate=' + afterUpdate;
	} else {
		url = 'templates/form.searchContatos.popup.php?field=' + field + '&busca=' + busca + '&afterUpdate=' + afterUpdate;
	}
	$.get(url, function(data) {
	})
	.done(function(data) {
		var dialog = {
			'content': data,
			'config': {
				'width': 964,
				'modal': true
			},
			'htmlTitle': 'Pesquisar Cadastro',
			'hideCancel': true,
			'hideOk': false,
			'idOk': 'setarContatoDialog',
			'fnCreate': function() {
				xajax_pesquisarContatos(xajax.getFormValues('formBuscaContatos'));
				displayWait('pleasewait', true, 'Aguarde carregando...');
				if (opcaoIncluir == 'S') {
					$('#busca').focus();
					$('#btnNovoContatoRapidoPopup').show();
				}
			}
		};
		createDialog(dialog);
	})
	.always(function(){
		closeWait('pleasewait');
	})

	if(navigator.userAgent.indexOf('MSIE')>=0){
		hideSelects('hidden')
	}
}

function setarContato(id, nome, field){
	document.getElementById(field).value = nome;
	$('#setarContatoDialog').click();
	document.getElementById(field).focus();
}

function incluirContatoRapido(field, afterUpdate, afterShow){
	var busca = document.getElementById(field).value;
	var arguments = {action: "renderTemplate", link: "templates/form.contatoRapido.popup.php", field: field, afterUpdate: afterUpdate, busca: busca};
	var deferredObj = $.Deferred();
	getRenderedTemplate(arguments, function(data) {
		var dialog = {
			'content': data[1],
			'config': {
				'width': 800,
				'modal': true
			},
			'htmlTitle': 'Cadastro rápido',
			'idOk': 'btnIncluirOk',
			'idCancelar': 'btnIncluirCancelar',
			'fnCreate': function() {
				$('#btnIncluirCancelar').hide();
				$('#btnIncluirOk').hide();
				ajustarTelaContatoRapido();
				if (afterShow != undefined) {
					eval(afterShow);
				}
				deferredObj.resolve();
			}
		};

		createDialog(dialog);
	});
	return deferredObj.promise();
}

function ajustarTelaContatoRapido(){
	$('#codigo').focus();
	iniciarContatoRapido();
}

function closeContatoRapido() {
	$('#window-content-insert').dialog("destroy");
}