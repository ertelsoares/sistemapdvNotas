var tipo;

function abreTelaTags(tipoTags) {
	$.get("templates/form.tags.gerenciador.popup.php", function(data) {
		var dialog = {
			content: data,
			config: {
				title: "Marque as Tags relacionadas com o produto",
				width: 650
			},
			fnCreate: mostrarDados,
			fnOk: function() {
				atualizarTagsSelecionadas();
				mostrarTags();
			},
			hideCancel: true
		};

		createDialog(dialog);
	});

	tipo = tipoTags;
}

function atualizarTagsSelecionadas() {
	$.each((editandoVariacao ? arrayTagsVar : arrayTags), function(nomeGrupo, tags) {
		$.each(tags, function() {
			this.marcado = false;
		});
	});

	var tagsSelecionadasElem = $("#tags-area input[name^='grupo_']:checked");
	$.each(tagsSelecionadasElem, function() {
		var tagSelecionadaId = $(this).val();
		if (editandoVariacao) {
			var grupoTags = arrayTagsVar[$(this).attr("data-grupo")];
		} else {
			var grupoTags = arrayTags[$(this).attr("data-grupo")];
		}

		if (grupoTags) {
			$.each(grupoTags, function(i, tag) {
				if (tag.id == tagSelecionadaId) {
					this.marcado = true;
				}
			});
		}
	});

	if (!editandoVariacao) {
		atualizarTagsClonesInfoPai();
	}
}

function atualizarTagsClonesInfoPai() {
	if (variacoes) {
		$.each(variacoes, function() {
			if (this.cloneInfoPai) {
				if (this.tagsMarcadas) {
					this.tagsMarcadas = this.tagsMarcadas.concat(montarArrayTagsMarcadas()).filter(function(value, index, self) {
						return self.indexOf(value) === index;
					});
				} else {
					this.tagsMarcadas = montarArrayTagsMarcadas();
				}
			}
		});
	}
}

function mostrarTags() {
	$(htmlId('#slot_tags')).html("");
	$.each((editandoVariacao ? arrayTagsVar : arrayTags), function(nomeGrupo, tags) {
		$.each(tags, function(idTag, objTag) {
			if (objTag.marcado) {
				$(htmlId('#slot_tags')).append('<div class="tag-left"></div><div class="tag">' + objTag.nome + '</div>');
			}
		});
	});
}

function mostrarDados() {
	$("#tags-area").append("<div class='tags_coluna' id='tags_coluna1'></div>");
	$("#tags-area").append("<div class='tags_coluna' id='tags_coluna2'></div>");
	$("#tags-area").append("<div class='tags_coluna' id='tags_coluna3'></div>");

	var arrayTagsTmp = (editandoVariacao ? arrayTagsVar : arrayTags);
	if (arrayGrupos) {
		$.each(arrayGrupos, function(idGrupo, dadosGrupo) {
			t++;
			if (t >= 4) {
				t = 1;
			}
			var idGrupo = dadosGrupo.id;
			mostrarGrupo(dadosGrupo.id, dadosGrupo.nome, t);
			if (arrayTagsTmp[dadosGrupo.id] == undefined) {
				mostrarAdicionar(idGrupo);
				return true;
			}
			var tags = arrayTagsTmp[dadosGrupo.id];
			$.each(tags, function(idTag, objTag) {
				$("<input type='radio' name='grupo_" + idGrupo + "' />").attr("id", objTag.id).attr("data-grupo", idGrupo).val(objTag.id).appendTo("#div_" + objTag.idGrupoTag);
				$("#" + objTag.id).prop("checked", objTag.marcado);
				$("<label />").attr("for", objTag.id).html(objTag.nome).appendTo("#div_" + objTag.idGrupoTag);
				$("#div_" + objTag.idGrupoTag).append("<br/>");
			});
			mostrarAdicionar(idGrupo);
			mostrarDescmarcarTodos(idGrupo, dadosGrupo.nome);
		});
	}
}

function mostrarGrupo(idGrupo, grupo, i) {
	$("#tags_coluna" + i).append(
			"<div class='tag-slot' id='slot_" + idGrupo + "'><h4>" + grupo
					+ "</h4></div>");
	$("#slot_" + idGrupo).append("<div id='div_" + idGrupo + "'></div>");
}

function mostrarAdicionar(idGrupo) {
	$("#slot_" + idGrupo)
			.append(
					'<div clas="tag-footer"><input type="button" class="link link-action" value="Adicionar Tag..." onclick="abrirPopupNovaTag('
							+ idGrupo + ')" /></div>');
}

function mostrarDescmarcarTodos(idGrupo, nome) {
	$("#slot_" + idGrupo)
			.append(
					'<div clas="tag-footer"><input type="button" class="link link-action" value="Desmarcar Todos" onclick="desmarcarTodos(\''
							+ idGrupo + '\', \'' + nome + '\'); " /></div>');
}

function desmarcarTodos(idGrupo, nome) {
	$("input[name='grupo_" + idGrupo + "']:checked").prop("checked", false);
	var tags1 = (editandoVariacao ? arrayTagsVar[idGrupo] : arrayTags[idGrupo]);
	$.each(tags1, function(idTagAux, objTagAux) {
		objTagAux.marcado = false;
	});
}

function incluiTag(nomeGrupoTag, aTag) {
	var tagsAux = (editandoVariacao ? arrayTagsVar[aTag.idGrupoTag] : arrayTags[aTag.idGrupoTag]);
	$.each(tagsAux, function(idTagAux, objTagAux) {
		objTagAux.marcado = false;
	});
	$("<input type='radio' name='grupo_" + aTag.idGrupoTag + "'/>").attr("id", aTag.id).attr("data-grupo", aTag.idGrupoTag).val(aTag.id).appendTo("#div_" + aTag.idGrupoTag);

	$("#" + aTag.id).prop("checked", true);
	$("<label />").attr("for", aTag.id).html(aTag.nome).appendTo("#div_" + aTag.idGrupoTag);
	$("#div_" + aTag.idGrupoTag).append("<br/>");

	try {
		arrayTagsVar[aTag.idGrupoTag][aTag.id] = aTag;
		arrayTags[aTag.idGrupoTag][aTag.id] = aTag;
	} catch (e) {
		console.log(e);
	}
}

function incluiGrupoTag(aTag) {
	arrayGrupos[arrayGrupos.length] = aTag;

	arrayTagsVar[aTag.id] = {};
	arrayTags[aTag.id] = {};

	mostrarGrupo(aTag.id, aTag.nome, '1');
	mostrarAdicionar(aTag.id);

}

function abrirPopupNovaTag(grupo) {
	$.get("templates/form.tag.new.popup.php?tipo=" + tipo + "&grupo=" + grupo, function(data) {
		var dialog = {
			content: data,
			config: {
				title: "Tag",
				width: 320
			},
			fnCreate: ajustarFormNovaTag,
			fnOk: function() {
				xajax_incluirNovaTag(xajax.getFormValues('formNewTag'));
				mostrarTags();
			},
			hideCancel: true
		};

		createDialog(dialog);
	});
}

function ajustarFormNovaTag() {
	var tipoPopup = $("#tipoPopup").val();
	xajax_obterSelectGruposTags(tipoPopup);
}

function abrirPopupNovoGrupoTag() {
	$.get("templates/form.tag.new.grupo.popup.php?tipo=" + tipo, function(data) {
		var dialog = {
			content: data,
			config: {
				title: "Grupo de tags",
				width: 320
			},
			fnCreate: ajustarFormNovaTag,
			fnOk: function() {
				xajax_incluirNovoGrupoTag(xajax.getFormValues('formNewGrupoTag'));
				mostrarTags();
			},
			hideCancel: true
		};

		createDialog(dialog);
	});
}