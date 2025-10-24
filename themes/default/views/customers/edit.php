<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title"><?= lang('update_info'); ?></h3>
        </div>
        <div class="box-body">
          <?php echo form_open("customers/edit/".$customer->id);?>
          
       
			<div class="modal-body">
				<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
        			<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label" for="tipo">
								Tipo Cadastro
							</label>
							<?php
								$bs = array( '1' => 'Pessoa Física','2' => 'Pessoa Jurídica',  '3' => 'Extrangeiro');
								echo form_dropdown('tipo_cad', $bs, $customer->tipo_cad, 'class="form-control" id="tipo" style="width:100%;"');
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
							<?= form_input('name', $customer->name, 'class="form-control input-sm kb-text" id="cname"'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cemail">
								<?= lang("email_address"); ?>
							</label>
							<?= form_input('email', $customer->email, 'class="form-control input-sm kb-text" id="cemail"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="phone">
								<?= lang("phone"); ?>
							</label>
							<?= form_input('phone', $customer->phone, 'class="form-control input-sm kb-pad" id="cphone"');?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf1">
								<?= lang("cf1"); ?>
							</label>
							<?= form_input('cf1', $customer->cf1, 'class="form-control input-sm kb-text" id="cf1"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf2">
								<?= lang("cf2"); ?>
							</label>
							<?= form_input('cf2', $customer->cf2, 'class="form-control input-sm kb-text" id="cf2"');?>
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
							<?= form_input('endereco', $customer->endereco, 'class="form-control input-sm kb-text" id="endereco"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="numero">
								Número
							</label>
							<?= form_input('numero', $customer->numero, 'class="form-control input-sm kb-text" id="numero"');?>
						</div>
					</div>
				</div>
        
        		<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="complemento">
								Complemento
							</label>
							<?= form_input('complemento', $customer->complemento, 'class="form-control input-sm kb-text" id="complemento"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="bairro">
								Bairro
							</label>
							<?= form_input('bairro', $customer->bairro, 'class="form-control input-sm kb-text" id="bairro"');?>
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
    				</div>
				</div>
				
        	
        		<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cep">
							 CEP
							</label>
							<?= form_input('cep', $customer->cep, 'class="form-control input-sm kb-text" id="cep"'); ?>
						</div>
					</div>
					
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="pais">
								País
							</label>
							<?= form_input('pais', $customer->pais, 'class="form-control input-sm kb-text" id="pais"');?>
						</div>
					</div>
				</div>
        		<!-- Fim campos personalizados -->

    
				<div class="form-group">
				<input type="hidden" name="isframe" id="isframe" value="<?php echo $_GET["isframe"];?>">
				<input type="hidden" name="reload_topframe" id="reload_topframe" value="<?php echo $_GET["reload_topframe"];?>">

              <?php echo form_submit('edit_customer', $this->lang->line("edit_customer"), 'class="btn btn-primary"');?>
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

$("#uf").val("<?=$customer->estado;?>");
GetMunicipio('<?=$customer->estado;?>','#municipio',null,'<?=$customer->codigocidade;?>');

function GetMunicipio($estado, $cidades, $pais = null, $selecionar = null){

	if($estado == 'EX'){
		if($pais!=null) $($pais).show();
		$($cidades).html("");
		$($cidades).append($('<option>').text("EXTERIOR").attr('value', "EX"));
		selecionarMunicipio();
	}else if($estado == ''){
		$($cidades).html("");
		selecionarMunicipio();
	}else{
		if($pais!=null) $($pais).hide();
		$($cidades).html("");

		// Buscar Ajax das cidades
		$.getJSON('<?=base_url();?>/lib-local/getMunicipios.php?uf=' + $estado, function(result){
				$.each(result, function(i, value) {
					if($selecionar!=null && value['codigo']== $selecionar){
						$($cidades).append($('<option>').text(capitalize(value['nome'])).attr('value', value['codigo']).attr('selected', 'selected'));	
						selecionarMunicipio();
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


