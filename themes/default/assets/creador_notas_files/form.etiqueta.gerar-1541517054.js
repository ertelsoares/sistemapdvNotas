var newTab;

function abrirPopupGerarEtiqueta(params, options, cookieId) {
	options = options || {};
	displayWait('impressaoWait', true, "Aguarde...");
	$.get("templates/form.etiqueta.gerar.popup.php", { tipos: Object.keys(params)}, function(data) {
		closeWait('impressaoWait');
		createDialog({
			content: data,
			width: 375,
			fnCreate: function() {
				$('#modeloEtiqueta').val(readCookie(cookieId));
				$('#opcMultiplicarPorQuantidade').hide()
				$('#opcDepositosEtiqueta').hide();
				if (options['multiplicarPorQuantidade']) {
					$('#opcMultiplicarPorQuantidade')
						.show()
						.find('label span:eq(0)').text(options['multiplicarPorQuantidade']['label']);
					$('#multiplicarPorQuantidade').on('click', function() {
						$('#opcDepositosEtiqueta').hide();
						if($(this).prop('checked')) {
							$('#opcDepositosEtiqueta').show();
						}
					});
				}
				if (options['depositos']) {
					populateSelect('#depositosEtiqueta', options['depositos']['values'], null, options['depositos']['default']);
				}
				$('#mais_opc_print_etiq').on("click", function() {
					$("#container_mais_opc").slideToggle(200);
				});
			},
			fnOk: function() {
				var tipo = $('#modeloEtiqueta option:selected').attr('data-tipo');
				if ($.isEmptyObject(params[tipo]['ids'])) {
					return gerarEtiquetasDisplayError(['Nenhum item a ser impresso.']);
				}
				var paramsGerar = $.extend(params[tipo]['params'], {
					copias: $("#copiasEtiqueta").val(),
					linha: $("#iniciaEmLinha").val(),
					coluna: $("#iniciaEmColuna").val()
				});
				if ($('#opcMultiplicarPorQuantidade').is(':visible')) {
					if ($('#multiplicarPorQuantidade').prop('checked')) {
						paramsGerar['multiplicarPorQuantidade'] = options['multiplicarPorQuantidade']['value'];

					}
					paramsGerar['idDeposito'] = $('#depositosEtiqueta').val();
				}

				xajax_gerarEtiquetas(xajax.xp(params[tipo]['ids'], $("#modeloEtiqueta").val(), paramsGerar), function(response) {
					if (response['error']) {
						return gerarEtiquetasDisplayError(response['error'])
					}
					mostrarArquivoEtiqueta(response['fileName']);
					createCookie(cookieId, $("#modeloEtiqueta").val());
				});
				openTab();
				return false;
			}
		});
	});
}

function obterSelecionadosGerarEtiqueta(elem) {
	var ids = {};

	if (!elem && window['selectedItems']) {
		$.each(getIdsSelectedItems(), function() {
			ids[this] = {};
		});
	} else {
		$(elem ? elem : ".tcheck:input:checkbox:checked").each(function() {
			ids[$(this).val()] = {};
		});
	}

	if(Object.keys(ids).length === 0) {
		alert('Nenhum item selecionado!');
		return;
	}
	return ids;
}

function gerarEtiquetasDisplayError(errors) {
	if (newTab) {
		newTab.close();
	}

	htmlErros = "<ul>";
	$.each(errors, function(i, value){
		htmlErros += "<li>" + value + "</li>";
	});
	htmlErros += "</ul>";

	showDialogMessage({
		'description': htmlErros,
		'hideCancel': true,
		'textOk': 'OK'
	});
}

function showWarningMessage(msg, timeOut, type) {
	var message = "";
	if ($.isArray(msg)) {
		$.each(msg, function(i, val) {
			message += val + "<br>";
		});
	} else {
		message = msg;
	}

	Toast.create({
		'type': (type || Toast.W),
		'msg': message,
		'config': {
			'timeOut': (typeof timeOut == "undefined" ? 10000 : timeOut)
		}
	});
}

function mostrarArquivoEtiqueta(fileName) {
	if (newTab == null) {
		openTab();
	}

	newTab.document.getElementsByTagName("p")[0].innerHTML = "";
	newTab.location.href = 'download.arquivo.tmp.php?file=' + fileName + '&mode=s'
	newTab = null;
}

function openTab(msgWait) {
	if (!msgWait) {
		msgWait = "Carregando documento...";
	}

	newTab = window.open("", "_blank");
	newTab.document.write("<p>" + msgWait + "</p>");
}