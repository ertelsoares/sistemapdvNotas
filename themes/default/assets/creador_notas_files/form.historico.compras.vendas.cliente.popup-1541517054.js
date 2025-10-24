$("#descricaoProdutoBuscaHistorico").autocomplete({
		source: "services/produtos.lookup.php",
		select: function(event, ui) {
			setIdProdutojQueryComplete(ui["item"]);
		},
		change: function(event, ui) {
			buscarNoHistorico();
			testCompleter($(this), $("#idProduto"), "Produto não encontrado no sistema");
		},
		delay:500,
		minLength:2,
		selectOnly:true
	});

	function setIdProdutojQueryComplete(param) {
		$("#idProduto").val(param.id);
		$("#descricaoProdutoBuscaHistorico").removeClass("ac_error").addClass("tipsyOff").removeAttr("title").focus();
	}

	function buscarNoHistorico() {
		xajax_consultarHistoricoVendasDoCliente($('#idContatoBuscaHistorico').val(), $('#tipoBuscaHistorico').val(), $('#descricaoProdutoBuscaHistorico').val(), function(data){
			montarInformacoesProdutosCliente(data);
		});
	}

	function mostrarAbaHistProdutos() {
		$("#link_hist_financeiro").removeClass("active");
		$("#link_hist_produtos").addClass("active");
		$("#dadosComprasCliente").removeClass("invisivel");
		$("#listagem_historico_financeiro_cliente").addClass("invisivel");
	}

	function mostrarAbaHistFinanceiro() {
		$("#link_hist_produtos").removeClass("active");
		$("#link_hist_financeiro").addClass("active");
		$("#dadosComprasCliente").attr('class', 'invisivel');
		$("#listagem_historico_financeiro_cliente").attr('class', '');
	}

	function clearFormHistorico(tipo) {
		$("#descricaoProdutoBuscaHistorico").removeClass("ac_error").val("");
		$("#idContatoBuscaHistorico").val($("#idContato").val());
		$("#tipoBuscaHistorico").val(tipo);
	}

	function montarInformacoesProdutosCliente(json, tipo){
		json.sort(function(a,b){
			 return new Date(b.data).getTime() - new Date(a.data).getTime();
		});
		if(json.length > 0) {
			$('#listagem_vendas_cliente').html(
				$('<table>', {id: 'tabela-produtos', class: 'grid', style: 'width:100%;'}).append(
					$('<tr>', {id: 'header-tabela-produtos'}).append(
						$('<th>', {style: 'padding:2pt;width:25%;'}).html('Descrição'),
						$('<th>', {style: 'padding:2pt;width:15%;text-align:center;'}).html('Valor unitário'),
						$('<th>', {style: 'padding:2pt;width:12%;text-align:center;'}).html('Quantidade'),
						$('<th>', {style: 'padding:2pt;width:13%;text-align:center;'}).html('Data'),
						$('<th>', {style: 'padding:2pt;width:16%;text-align:center;'}).html('Tipo'),
						$('<th>', {style: 'padding:2pt;width:11%;text-align:center;'}).html('Número')
					)
				)
			);
			$.each(json, function(i, item) {
				$('<tr>', {id: 'linha-tabela-produtos' + i}).appendTo('#tabela-produtos').append(
					$('<td>', {style: 'padding:2pt;text-align:left;'}).html(item.descricao),
					$('<td>', {style: 'padding:2pt;text-align:center;'}).html(item.valorUnitario),
					$('<td>', {style: 'padding:2pt;text-align:center;'}).html(item.qtde),
					$('<td>', {style: 'padding:2pt;text-align:center;'}).html(dataHoraBr(item.data, '-', 'N')),
					$('<td>', {style: 'padding:2pt;text-align:center;'}).html(item.origem),
					$('<td>', {style: 'padding:2pt;text-align:center;'}).html(item.numero)
				);
			});
		}else {
			modTitle = $('title').html().split('-')[1].trim().toLowerCase();
			var title = '';
			switch(modTitle) {
				case 'ordens de serviço':
					title = modTitle;
					break;
				default:
					title = 'vendas';
					break;
			}
			$('#listagem_vendas_cliente').html(
				$('<i>', {class: 'icon-info-sign small-icon', style:'color: #7cb5d8;'}).append(
					$('<span>', {style:'font-size:10pt;margin-left:5px;color:gray;'}).html("Não existem " + title +  " para este cliente.")
				)
			);
		}
	}

	function montarInformacoesHistoricoFinanceiro(json){
		if(json.length > 0) {
			$('#link_hist_financeiro').parent().fadeIn('fast');
			$('#listagem_historico_financeiro_cliente').html(
				$('<table>', {id: 'tabela-financeiro', class:'grid', style: 'width: 100%'}).append(
					$('<tr>', {id: 'header-tabela'}).append(
						$('<th>', {style: 'width:50%;padding:2pt;'}).html('Histórico'),
						$('<th>', {style: 'width:10%;padding:2pt;text-align:center;'}).html('Vencimento'),
						$('<th>', {style: 'width:10%;padding:2pt;text-align:center;'}).html('Saldo'),
						$('<th>', {style: 'width:3%;padding:2pt;'}).html('&nbsp;')
					)
				)
			);
			$.each(json, function(i, item) {
				$('<tr>', {id: 'linha-tabela-' + i}).appendTo('#tabela-financeiro').append(
					$('<td>', {style: 'padding: 2pt;'}).html(item.historico),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.dataVencimento),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.saldo),
					$('<td>', {style: 'padding-right: 5pt; text-align:center;'}).html(item.iconeSituacao)
				);
			});
		}else if(json.denied) {
			$('#link_hist_financeiro').parent().fadeOut('fast');
		}else {
			$('#link_hist_financeiro').parent().fadeIn('fast');
			$('#listagem_historico_financeiro_cliente').html(
				$('<i>', {class: 'icon-info-sign small-icon', style: 'color: #7cb5d8;'}).append(
					$('<span>', {style:'font-size:10pt;margin-left:5px;color:gray;', html: 'Não existem contas a receber em aberto para este cliente.'})
				)
			);
		}
	}

	function visualizarAsInformacoesHistoricoCliente(tipo) {
		tipo = tipo || 'S';
		$('#link_hist_produtos').html(tipo == 'O' ? 'Produtos/Serviços' : 'Produtos');
		if ($('#idContato').val() > 0) {
			displayWait('pleasewait', true, 'Carregando informações, aguarde...');
			clearFormHistorico(tipo);
			xajax_consultarHistoricoVendasDoCliente($('#idContato').val(), tipo, $('#descricaoProdutoBuscaHistorico').val(), function(data){
				montarInformacoesProdutosCliente(data, tipo);
				if(tipo === 'S' || tipo === 'O'){
					xajax_consultarHistoricoFinanceiroDoCliente($('#idContato').val(), tipo, function(data){
						montarInformacoesHistoricoFinanceiro(data);
					});
				}else{
					$('#link_hist_financeiro').addClass('invisivel');
				}
				var dialog = {
					'content': $('#form_historico_vendas'),
					'htmlTitle': 'Últimos registros de ' + $('title').html().split('-')[1].trim().toLowerCase(),
					'width': 640,
					'idCancelar': 'buttonCancelar',
					'idOk': 'buttonOk',
					'fnCreate': function() {
						mostrarAbaHistProdutos();
						$('#buttonCancelar').hide();
						$('#buttonOk').hide();
					}
				};
				createDialog(dialog);
				closeWait('pleasewait');
			});
		}
	}