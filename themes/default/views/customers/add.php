<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">Para NF preencha todos os campos</h4>
				</div>
				<div class="box-body">
			<?php echo form_open("customers/add");?>

			<div class="modal-body">
				<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
        		<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<a class="btn btn-warning" id="inf-cliente-produto" title="Buscar na Receita Federal" onclick="BuscarReceita()" ><i class="icon-search"></i> Buscar na Receita Federal</a>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label" for="tipoPessoa">
								Tipo Cadastro
							</label>
							<?php
								$bs = array( '1' => 'Pessoa Física','2' => 'Pessoa Jurídica',  '3' => 'Extrangeiro');
								echo form_dropdown('tipo_cad', $bs, set_value('tipo_cad'), 'class="form-control" id="tipoPessoa" style="width:100%;"');
								?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label" for="cname">
								<?= lang("name"); ?>
							</label>
							<?= form_input('name', set_value('name'), 'class="form-control input-sm kb-text" id="cname"'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cemail">
								<?= lang("email_address"); ?>
							</label>
							<?= form_input('email', set_value('email'), 'class="form-control input-sm kb-text" id="cemail"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="fone">
								<?= lang("phone"); ?>
							</label>
							<?= form_input('phone', set_value('phone'), 'class="form-control input-sm kb-pad" id="fone"');?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf1">
								<?= lang("cf1"); ?>
							</label>
							<?= form_input('cf1', set_value('cf1'), 'class="form-control input-sm kb-text" id="cf1"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf2">
								<?= lang("cf2"); ?>
							</label>
							<?= form_input('cf2', set_value('cf2'), 'class="form-control input-sm kb-text" id="cf2"');?>
						</div>
					</div>
				</div>

				<!-- Campos personalizados-->
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="endereco">
								Endereço
							</label>
							<?= form_input('endereco', set_value('endereco'), 'class="form-control input-sm kb-text" id="endereco"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="enderecoNro">
								Número
							</label>
							<?= form_input('numero', set_value('numero'), 'class="form-control input-sm kb-text" id="enderecoNro"');?>
						</div>
					</div>
				</div>
        
       			 <div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="complemento">
								Complemento
							</label>
							<?= form_input('complemento', set_value('complemento'), 'class="form-control input-sm kb-text" id="complemento"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="bairro">
								Bairro
							</label>
							<?= form_input('bairro', set_value('bairro'), 'class="form-control input-sm kb-text" id="bairro"');?>
						</div>
					</div>
				</div>
        
				<div class="row">
					<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label" for="uf">
								Estado (Ex.: SP)
								</label>
								<select name="estado" id="uf" class="form-control" onchange="GetMunicipio(this.value, '#municipio');"><option value=""> Selecione o Estado </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option><option value="EX">EX</option></select>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label" for="municipio">
								Cidade
								</label>
								<select onchange="selecionarMunicipio(this.value)"  name='codigocidade' class="form-control input-sm kb-text" id="municipio"></select>
							</div>
						
							<input type="hidden" name="cidade" id="nomeMunicipio" value="">
							<input type="hidden" name="isframe" id="isframe" value="<?php echo $_GET["isframe"];?>">
							<input type="hidden" name="reload_topframe" id="reload_topframe" value="<?php echo $_GET["reload_topframe"];?>">
					</div>
				</div>
			
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cep">
							 CEP
							</label>
							<?= form_input('cep', set_value('cep'), 'class="form-control input-sm kb-text" id="cep"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="pais">
								País
							</label>
							<?= form_input('pais', 'BRASIL', 'class="form-control input-sm kb-text" id="pais"');?>
						</div>
					</div>
				</div>
        		<!-- Fim campos personalizados -->

						<div class="form-group">
							<?php echo form_submit('add_customer', $this->lang->line("add_customer"), 'class="btn btn-primary"');?>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/colorbox/colorbox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/colorbox/colorbox.css">
<script>
function BuscarReceita(){
$.colorbox({iframe:true, href: "<?=base_url();?>/lib-local/buscarCnpjform.php?cnpj="+$("#cf1").val(), width:"300px", height:"300px" });
}

function ResultadosReceita(dados){
	dados2 = JSON.parse(dados);
	$("#cname").val((dados2.razao_social!=null&&dados2.razao_social!=undefined)?dados2.razao_social:dados2.nome);
	$("#endereco").val(dados2.logradouro);
	$("#enderecoNro").val(dados2.numero);
	$("#bairro").val(dados2.bairro);
	$("#nomeMunicipio").val(dados2.cidade);
	GetMunicipio(dados2.uf, '#municipio');
	$('#uf').val(dados2.uf);
	$("#cep").val(dados2.cep);
	$("#cf1").val(dados2.cnpj);
	$("#cf1").val(dados2.cnpj);
	$("#cemail").val(dados2.email);	
	$("#fone").val(dados2.telefone.split("/")[0].trim());	
	$("#complemento").val(dados2.complemento);	
	$('#tipoPessoa option[value="2"]').prop('selected', true);
	setTimeout(() => {
		$("#municipio option:contains(" + dados2.cidade_cap + ")").attr('selected', 'selected');
		selecionarMunicipio();
	}, 1000);
}

function GetMunicipio($select, $cidades, $pais = null, $selecionar = null){
    
	if($select == 'EX'){
		if($pais!=null) $($pais).show();
		$($cidades).html("");
		$($cidades).append($('<option>').text("EXTERIOR").attr('value', "EX").attr('selected', "selected")).trigger("change");
	}else if($select == ''){
		$($cidades).html("").trigger("change");
	}else{
		if($pais!=null) $($pais).hide();
		$($cidades).html("");
		$($cidades).append($('<option>').text("-- Selecione --").attr('value', ""));

		// Buscar Ajax das cidades
		$.getJSON('<?=base_url();?>/lib-local/getMunicipios.php?uf=' + $select, function(result){
			$.each(result, function(i, value) {
				if($selecionar!=null && value['codigo']== $selecionar){
					$($cidades).append($('<option>').text(capitalize(value['nome'])).attr('value', value['codigo']).attr('selected', 'selected')).trigger("change");	
				}else{
					$($cidades).append($('<option>').text(capitalize(value['nome'])).attr('value', value['codigo']));
				}
				
			});
		});
	}

}

const capitalize = (str, lower = false) =>
  (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, match => match.toUpperCase());
;

function selecionarMunicipio(){
	$("#nomeMunicipio").val($( "#municipio option:selected" ).text());
}
</script>
