<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box box-primary">
               <?= form_open_multipart("settings", 'class="validation" autocomplete="nope"'); ?>
                <div class="box-body">

                    <div class="col-12">
                        <h3>Configurações da Loja</h3>
                    </div>
                    <?php if($contacadastro!=""){?>
                    <div class="col-12">
                        <h4>Plano: <?=$contacadastro;?></h4>
                    </div>
                    <?php } ?>

                    <div class="col-lg-12">
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang("Nome da Loja", 'site_name'); ?>
                                    <?= form_input('site_name', $settings->site_name, 'class="form-control" id="site_name" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("Telefone da loja", 'tel'); ?>
                                    <?= form_input('tel', $settings->tel, 'class="form-control" id="tel" required="required"'); ?>
                                </div>

                                <div class="form-group">
                                    <?=lang('Estado da Loja', 'timezone'); ?>
                                    <?php
                                    $timezones = array('AC' => 'AC', 'AL' => 'AL', 'AM' => 'AM', 'AP' => 'AP', 'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES', 'GO' => 'GO', 'MA' => 'MA', 'MG' => 'MG', 'MS' => 'MS', 'MT' => 'MT', 'PA' => 'PA', 'PB' => 'PB', 'PE' => 'PE', 'PI' => 'PI', 'PR' => 'PR', 'RJ' => 'RJ', 'RN' => 'RN', 'RO' => 'RO', 'RR' => 'RR', 'RS' => 'RS', 'SC' => 'SC', 'SE' => 'SE', 'SP' => 'SP', 'TO' => 'TO');
                                    echo form_dropdown('timezone', $timezones, $settings->timezone, 'class="form-control select2" style="width:100%;" id="timezone" required="required"') ?>
                                </div>

                                <div class="form-group">
                                    <?=lang('Senha Master Desbloqueio PDV)', 'delete_code'); ?>
                                    <?= form_input('pin_code', $settings->pin_code, 'class="form-control" id="pin_code" required="required"'); ?>
                                </div>

                                <input type="hidden" name="language" value="<?php echo $settings->language; ?>">
                                <input type="hidden" name="rows_per_page" value="<?php echo $settings->rows_per_page; ?>">
                                <input type="hidden" name="display_product" value="<?php echo $settings->bsty; ?>">
                                <input type="hidden" name="pro_limit" value="<?php echo $settings->pro_limit; ?>">
                                <input type="hidden" name="display_kb" value="<?php echo $settings->display_kb; ?>">
                                <input type="hidden" name="item_addition" value="<?php echo $Settings->item_addition; ?>">
                                <input type="hidden" name="protocol" value="<?php echo $settings->protocol; ?>">
                                <input type="hidden" name="stripe" value="<?php echo $settings->stripe; ?>">
                                <input type="hidden" name="default_email" value="<?php echo $settings->default_email; ?>">

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <?= lang('default_category', 'default_category') ?>
                                    <?php
                                    foreach ($categories as $catrgory) {
                                        $ct[$catrgory->id] = $catrgory->name;
                                    }
                                    echo form_dropdown('default_category', $ct, $settings->default_category, 'class="form-control select2" style="width:100%;" id="default_category" required="required"') ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("default_customer", 'default_customer'); ?>
                                    <?php
                                    foreach ($customers as $customer) {
                                        $cu[$customer->id] = $customer->name;
                                    }
                                    echo form_dropdown('default_customer', $cu, $settings->default_customer, 'class="form-control select2" style="width:100%;" id="default_customer" required="required"'); ?>
                                </div>

                                 <div class="form-group">
                                    <?=lang('Usar comandas', 'modelonegocio'); ?>
                                    <?php
                                    $valorModelo = (empty(!$settings->modelonegocio))? $settings->modelonegocio: "todos";
                                    $tp["todos"] = "Não";
                                    $tp["restaurante"] = "Sim";
                                    echo form_dropdown('modelonegocio', $tp, $valorModelo, 'class="form-control select2" style="width:100%;" id="modelonegocio" required="required"') ?>
                                </div>

                                <div class="form-group">
                                    <?=lang('Quantidade de mesas', 'total_mesas'); ?>
                                    <?php
                                    echo form_input('total_mesas', $settings->total_mesas, 'class="form-control justnum" style="width:100%;" id="total_mesas"') ?>
                                </div>

                            </div>
                          </div> 
                          <hr> 
                          
                          <div class="row">
                          <div class="col-12">
                            <h3>Configuração do Emissor - Nota Fiscal</h3>
                          </div>
                          
                          <div class="col-sm-6">
                                <div class="form-group">
                                    <?=lang('Ativar emissão de notas', 'ativar_emissao_notas'); ?>
                                    <br>
                                    <?php
                                    $tpativar["0"] = "Não";
                                    $tpativar["1"] = "Sim";
                                    echo form_dropdown('ativar_emissao_notas', $tpativar, $settings->ativar_emissao_notas, 'class="form-control select2" style="width:100px;" id="ativar_emissao_notas" required="required"') ?>
                                    <br>
                                    (Ao ativar, os campos de impostos nos produtos serão obrigatórios)
                                </div>
                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <?=lang('Gerar NFC-e diretamente ao finalizar a venda', 'pdvdiretonfc'); ?>
                                    <?php
                                    $valorDirectoNFC = (empty(!$settings->pdvdiretonfc))? $settings->pdvdiretonfc: 0;
                                    $gerar[0] = "Não";
                                    $gerar[1] = "Sim";
                                    echo form_dropdown('pdvdiretonfc', $gerar, $valorDirectoNFC, 'class="form-control select2" style="width:100%;" id="pdvdiretonfc" required="required"') ?>
                                </div>
                           </div>  

                           <div class="col-sm-12">     
                            <hr>
                          </div>
                          
                           <div class="col-md-4">
                                <div class="form-group">
                                   <label for="vat_no">CNPJ</label>
                                    <?= form_input('vat_no', $Settings->vat_no, 'class="form-control tip" id="vat_no"'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Inscrição Estadual', 'ie'); ?>
                                    <?= form_input('ie', $Settings->ie, 'class="form-control tip" id="ie" '); ?>
                                </div>
                            </div>
                                                
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Inscrição Municipal', 'im'); ?>
                                    <?= form_input('im', $Settings->im, 'class="form-control tip" id="im" '); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Razão Social', 'razaosocial'); ?>
                                    <?= form_input('razaosocial', $Settings->razaosocial, 'class="form-control tip" id="razaosocial" '); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Nome Fantasia', 'fantasia'); ?>
                                    <?= form_input('fantasia', $Settings->fantasia, 'class="form-control tip" id="fantasia" '); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('CNAE', 'cnae'); ?>
                                    <?= form_input('cnae', $Settings->cnae, 'class="form-control tip" id="cnae"'); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Natureza da operação padrão', 'naturezaoperacao'); 
                                    echo form_dropdown('naturezaoperacao', listaCFOPNF, set_value('naturezaoperacao', $Settings->mailpath), 'class="form-control select2" id="naturezaoperacao" style="width:100%;"');
                                    ?>
                                    </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('CRT', 'crt'); ?>
                                    <?php
										$bs = array( '1' => '1 - Simples Nacional', '2' => '2 - Simples Nacional - Excesso de Sublimite de Receita Bruta', '3' => '3 - Regime Normal',  '4' => '4 - Simples Nacional - MEI',);
                                        echo form_dropdown('crt', $bs, set_value('crt', $Settings->crt), 'class="form-control" required="required" id="crt"');
										?>
                                </div>
                            </div>
                                

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Telefone', 'phone_number'); ?>
                                    <?= form_input('phone_number', $Settings->phone_number, 'class="form-control  phone_with_ddd" required="required" id="phone_number"  '); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Endereço', 'address'); ?>
                                    <?= form_input('address', $Settings->address, 'class="form-control tip" id="address" '); ?>
                                </div>
                            </div>
                            
                           <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Número', 'numero'); ?>
                                    <?= form_input('numero', $Settings->numero, 'class="form-control tip" id="numero" '); ?>
                                </div>
                            </div>
                          
								<div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Bairro', 'bairro'); ?>
                                    <?= form_input('bairro', $Settings->bairro, 'class="form-control tip" id="bairro"'); ?>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <?php
                                    $bs = array('' => 'Selecione...', 'AC' => 'AC', 'AL' => 'AL', 'AM' => 'AM', 'AP' => 'AP', 'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES', 'GO' => 'GO', 'MA' => 'MA', 'MG' => 'MG', 'MS' => 'MS', 'MT' => 'MT', 'PA' => 'PA', 'PB' => 'PB', 'PE' => 'PE', 'PI' => 'PI', 'PR' => 'PR', 'RJ' => 'RJ', 'RN' => 'RN', 'RO' => 'RO', 'RR' => 'RR', 'RS' => 'RS', 'SC' => 'SC', 'SE' => 'SE', 'SP' => 'SP', 'TO' => 'TO');
                                    echo form_dropdown('estado', $bs, set_value('estado', $Settings->estado), 'class="form-control" id="estado" onchange="GetMunicipio(this.value, \'#ccidade\')" style="width:100%;"');
                                    ?>
                                </div>
                            </div>

                            <input type="hidden" name="codigoUF" id="codigoUF" value="<?php echo $Settings->codigoUF; ?>">
                            <input type="hidden" name="city" id="nomeMunicipio" value="<?php echo $Settings->city; ?>">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Cidade', 'city'); ?>
                                    <?php echo form_dropdown('ccidade', null, null, 'class="form-control tip" onchange="selecionarMunicipio()" id="ccidade"'); ?>
                                </div>
                            </div>
                            

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('CEP', 'postal_code'); ?>
                                    <?= form_input('postal_code', $Settings->postal_code, 'class="form-control tip placeholder" id="postal_code"  '); ?>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Ambiente de emissão', 'tpAmb'); ?>
                                    <?php
                                    $bs = array( '2' => 'Homologação (Testes)', '1' => 'Produção');
                                    echo form_dropdown('tpAmb', $bs, set_value('tpAmb', $Settings->tpAmb), 'class="form-control select2" required="required" id="tpAmb" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Token IBPT (Cálculo automático de impostos) <b style="font-size:14px;"><a href="https://deolhonoimposto.ibpt.org.br/Site/PassoPasso" target="_blank">Como gerar o token?</a></b>', 'tokenIBPT'); ?>
                                    <?= form_input('tokenIBPT', $Settings->tokenIBPT, 'class="form-control tip" id="tokenIBPT" '); ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('CSC', 'CSC'); ?>
                                    <?= form_input('CSC', $Settings->CSC, 'class="form-control tip" id="CSC" '); ?>
                                </div>
                            </div>

							<div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('CSC ID', 'CSCid'); ?>
                                    <?= form_input('CSCid', $Settings->CSCid, 'class="form-control tip justnum" id="CSCid" '); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Tamanho do papel da NFC-e (Min: 58mm / Max: 80mm)', 'tamanhopapel'); ?>
                                    <?= form_input('tamanhopapel', $Settings->tamanhopapel, ' class="form-control tip justnum" type="number" min="58" max="80" id="tamanhopapel" '); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                <?= lang('Certificado Digital - A1 (.pfx /.p12)', 'certificado'); echo ($Settings->certificado=="")? " (<b style='color:red'>Certificado não enviado</b>)" : " (<b style='color:green'>Certificado enviado</b>)";  ?> 
                                    <input type="file" name="certificadofile" id="certificadofile" accept=".p12,.pfx">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang('Senha Certificado Digital', 'certificadosenha'); echo ($Settings->certificadosenha=="")? " (<b style='color:red'>Senha não enviada</b>)" : " (<b style='color:green'>Senha enviada</b>)"; ?>
                                    <?= form_input('certificadosenha', '', 'class="form-control tip" id="certificadosenha" autocomplete="new-password" type="password" '); ?> <a href="javascript:void(0)" onclick="$('#certificadosenha').attr('type', 'text')">Mostrar</a> / <a href="javascript:void(0)" onclick="$('#certificadosenha').attr('type', 'password')">Ocultar</a>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="form-group">
                                    <?= lang('Logo', 'Logo'); echo " (formato: .jpg ou .png)"; echo ($Settings->logo=="")? " (<b style='color:orange'>Logo não enviado</b>)" : " (<a href='". base_url().$Settings->logo."' target='_blank' style='color:green'>Ver logo</a>)"; ?> 
                                    <input type="file" name="logonota" id="logo" accept="image/png, image/jpeg" style="width:300px;">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                            	 <label class="control-label">Número da próxima NF</label>
								<div class="controls">
                                    <?= form_input('ultima_nf', $settings->ultima_nf, 'class="form-control tip justnum" required="required" id="ultima_nf" '); ?>
                                </div>
                             </div>
                              </div>
                             
                              <div class="col-md-4">
                              <div class="form-group">
                            	 <label class="control-label">Série da NF-e</label>
								<div class="controls">
                                    <?= form_input('serie_nf', $settings->serie_nf, 'class="form-control tip justnum" required="required" id="serie_nf" '); ?>
                                </div>
                             </div>
                            </div>
							
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo Transmissão NF-e</label>
                                      <div class="controls">
                                      <?php
                                        $bs = array( '1' => 'Síncrona', '0' => 'Assíncrona');
                                        echo form_dropdown('transmissaoNFe', $bs, set_value('transmissaoNFe', $Settings->transmissaoNFe), 'class="form-control select2" required="required" id="transmissaoNFe" style="width:100%;"');
                                    ?>
                                    </div>
                                </div>
                              </div>

							
							<div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>
							
							 <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Número da próxima NFC</label>
                                      <div class="controls">
                                        <?= form_input('ultima_nfc', $settings->ultima_nfc, 'class="form-control tip justnum" required="required" id="ultima_nfc" '); ?>
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Série da NFC-e</label>
                                      <div class="controls">
                                        <?= form_input('serie_nfc', $settings->serie_nfc, 'class="form-control tip justnum" required="required" id="serie_nfc" '); ?>
                                    </div>
                                </div>
                              </div>
							  
							  


                              <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
								</div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Observação padrão da NF (opcional)", 'bill_footer'); ?>
                                        <?= form_textarea('bill_footer', $settings->footer, 'class="form-control" id="bill_footer"'); ?>
                                    </div>
                                    <!--<span style="font-size:12px;">{{IMPOSTO_NA_NOTA}} - mostrará os valores aproximados dos impostos. Fonte: IBPT</span>-->
                                    <br><br>
                                </div>
                            
                                <hr> 
                                <br> 
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Cabeçado das notas não fiscais", 'bill_header'); ?>
                                        <?= form_textarea('bill_header', $settings->header, 'class="form-control redactor" id="bill_header"'); ?>
                                    </div>
                                </div>
                    
                            </div>

                            

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                    <hr>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4>Configurar código de barras da Balança</h4>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Tipo da informação contida no código", 'balanca_tipodado'); ?>
                                        <?php
                                        $bsba = array( '0' => 'Não usar código da balança', '1' => 'Peso', '2' => 'Valor');
                                        echo form_dropdown('balanca_tipodado', $bsba, set_value('balanca_tipodado', $Settings->balanca_tipodado), 'class="form-control select2" id="balanca_tipodado" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Digito inicial para identificar peso ou valor", 'balanca_digitosiniciais'); ?>
                                        <?= form_input('balanca_digitosiniciais', $settings->balanca_digitosiniciais, 'class="form-control" id="balanca_digitosiniciais"'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Posição onde inicia a informação de peso ou valor", 'balanca_posicaopesovalor'); ?>
                                        <?= form_input('balanca_posicaopesovalor', $settings->balanca_posicaopesovalor, 'class="form-control" id="balanca_posicaopesovalor"'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Tamanho da informação de peso ou valor", 'balanca_tamanhoinfopesovalor'); ?>
                                        <?= form_input('balanca_tamanhoinfopesovalor', $settings->balanca_tamanhoinfopesovalor, 'class="form-control" id="balanca_tamanhoinfopesovalor"'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Quantidade de casas decimais no peso ou valor", 'balanca_casadecimais'); ?>
                                        <?= form_input('balanca_casadecimais', $settings->balanca_casadecimais, 'class="form-control " id="balanca_casadecimais"'); ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                    <hr>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4>Configurar PIX</h4>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <?= lang("Tipo da chave", 'pagamento_pix_tipochave'); ?>
                                        <?php
                                        if($settings->pagamento_pix!=""){
                                            $pix = json_decode($settings->pagamento_pix, true);
                                        }
                                        $bsba = array( '' => 'Não usar', 'celular' => 'Celular', 'cpf' => 'CPF', 'cnpj' => 'CNPJ', 'aleatoria' => 'Outros');
                                        echo form_dropdown('pagamento_pix_tipochave', $bsba, set_value('pagamento_pix_tipochave', $pix["pagamento_pix_tipochave"]), 'class="form-control select2" id="pagamento_pix_tipochave" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4 pagamentopix" style="">
                                    <div class="form-group">
                                    <label for="pagamento_pix_chave">Chave PIX</label> <input type="text" name="pagamento_pix_chave" value="<?=$pix["pagamento_pix_chave"];?>" class="form-control tip" id="pagamento_pix_chave" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-3 pagamentopix" style="">
                                    <div class="form-group">
                                    <label for="pagamento_pix_beneficiario">Nome do beneficario</label> <input type="text" name="pagamento_pix_beneficiario" value="<?=$pix["pagamento_pix_beneficiario"];?>" class="form-control tip" placeholder="Informe o nome do beneficiario" size="30" onclick="this.select();" maxlength="25" id="pagamento_pix_beneficiario">
                                    </div>
                                </div>
                                <div class="col-md-3 pagamentopix" style="">
                                    <div class="form-group">
                                    <label for="pagamento_pix_cidade">Cidade do beneficiário</label> <input type="text" name="pagamento_pix_cidade" value="<?=$pix["pagamento_pix_cidade"];?>" class="form-control tip" placeholder="Informe a cidade" onclick="this.select();" maxlength="15" id="pagamento_pix_cidade">
                                    </div>
                                </div>
                            </div>

                            </div>
                            </div>

                            <?php if(LOJA==1){ ?>

                            <div class="col-md-12">
                                <div class="form-group">
                                <hr>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>Configurar Catálogo Online</h4>
                            </div>

                            <div class="col-md-12">
                                <p>Banners</p>
                                <?php
                                    echo '<ul class="list-group">';
                                    for ($i=1; $i <= 3; $i++) { 
                                        echo '<li class="list-group-item">';
                                        if(file_exists(__DIR__."/../../../../../loja/banners/banner$i.png")){
                                            echo '<p><a href="/loja/banners/banner'.$i.'.png" target="_blank">Banner '.$i.' <i class="fa fa-external-link"></i></a></p>';
                                        }else{
                                            echo '<p>Banner '.$i.'</p>';
                                        }
                                        echo '<input type="file" name="banner_'.$i.'" id="banner_'.$i.'" accept="image/png" style="width:300px;">';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                ?>
                            </div>

                            <div class="col-md-12">                              
                                <div id="div_entrega_por_bairro" class="form-group">
                                    <p>Locais atendidos</p>

                                        <div style="max-height: 340px; overflow: scroll;">
                                            <table id="bairros" class="input_fields_wrap" style="width:100%;">
                                                <thead>
                                                <tr>
                                                    <th>Local</th>
                                                    <th style="width:130px;" class="campos_entrega_por_bairro">Valor da entrega</th>
                                                    <th style="width:100px;text-align:center">Ação</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $cc = 1;
                                                    if(!empty($bairros)){
                                                        foreach($bairros as $k){
                                                            echo 
                                                            '<tr class="campos_bairros">
                                                            <td style="width:40%"><input type="hidden" name="bairro_ids['.$cc.']" value="'.$k["id"].'"><input type="search" placeholder="Nome" value="'.$k["nome"].'" class="form-control" name="bairro_nome['.$cc.']"> </td>
                                                            <td style="width:40%"><input type="text" required="true" placeholder="Valor" value="'.number_format($k["valor"], 2, ',', '.').'" class="form-control money" name="bairro_valor['.$cc.']"> </td> 
                                                            <td  style="width:10%;text-align:center"><a href="javascript:void(0)" bid="'.$k["id"].'" onclick="removerLocal(this)" class="remove_field btn btn-xs btn-danger">Remover</a></td>
                                                            </tr>';
                                                            $cc++;
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <br>
                                        <button type="button" style="float:right;border:0px" class="add_field_button btn-xs btn-success" value="+">Adicionar novo local</button>
                                    </div>
                                </div>
                            </div>

                
                            <?php } ?>
    
                            <div class="col-md-12">
                                    <div class="form-group">
                                    <hr>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12" style="margin:40px 10px;text-align:center">
                                    <?= form_submit('update', lang('update_settings'), 'class="btn btn-primary btn-lg"'); ?>
                                    <?= form_close(); ?>
                                    </div>
                                </div>

                            </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?= $assets ?>jquery.mask.min.js" type="text/javascript"></script>
<script>

$(document).ready(function(){

    <?php  if(DEMO) { ?>
        $('input, select').attr('readonly', 'readonly');
    <?php } ?>

   <?php if( $Settings->estado!="" && $Settings->ccidade!="") { ?>
    GetMunicipio('<?php echo $Settings->estado;?>', '#ccidade', null, '<?php echo $Settings->ccidade;?>');
   <?php } ?>

	$('#isentocheck').on('ifChecked', function(event){
		$('#cf2').val('ISENTO');
	});

	$("#tipoPessoa").on('change', function(){

		if($(this).val()=="1") {
			$("#cf1").addClass("cpf").removeClass("cnpj");
			$("#cf1").attr("pattern", ".{14,}");
			$("#labeldoc").text("CPF");
			$("#cf2").removeAttr("required");
		}else  if($(this).val()=="2") {
			$("#cf1").removeClass("cpf").addClass("cnpj");
			$("#cf1").attr("pattern", ".{18,}");
			$("#labeldoc").text("CNPJ");
			$("#cf2").attr("required", true);
		}else{
			$("#cf1").removeClass("cpf").removeClass("cnpj");
			$("#labeldoc").text("Documento");
			$("#cf2").removeAttr("required");
			$("#cf1").removeAttr("pattern");
		}

	});
    var behavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    options = {
        onKeyPress: function (val, e, field, options) {
            field.mask(behavior.apply({}, arguments), options);
        }
    };
    $('.justnum').mask('0000000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', { translation: { 'Z': { pattern: /[0-9]/, optional: true } } });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00000-000", {placeholder: "_____-___"});
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.mobphone_with_ddd').mask('(00) 00000-0000', {placeholder: '(00) 00000-0000'});
    $(".phone_or_movil_with_ddd").mask(behavior, options);

    $("#pagamento_pix_tipochave").on('change', function(){
    
        $('#pagamento_pix_chave').removeClass('mobphone_with_ddd cpf cnpj').attr("placeholder", "");
        $('#pagamento_pix_chave').unmask();
        if($("#pagamento_pix_tipochave").val()=="celular"){
            $('#pagamento_pix_chave').addClass('mobphone_with_ddd');
        }
        if($("#pagamento_pix_tipochave").val()=="cpf"){
            $('#pagamento_pix_chave').addClass('cpf');
        }
        if($("#pagamento_pix_tipochave").val()=="cnpj"){
            $('#pagamento_pix_chave').addClass('cnpj');
        }
    }); 

    setTimeout(() => {
        $("#pagamento_pix_tipochave").trigger("change");
    }, 100);

});



function GetMunicipio($select, $cidades, $pais = null, $selecionar = null){

	if($select == 'EX'){
		if($pais!=null) $($pais).show();
		$($cidades).html("");
		$($cidades).append($('<option>').text("EXTERIOR").attr('value', "EX"));
	}else if($select == ''){

		$($cidades).html("");
		
	}else{
		if($pais!=null) $($pais).hide();
		$($cidades).html("");

		// Buscar Ajax das cidades
		$.ajax({ 
			url: '<?=base_url();?>/lib-local/getMunicipios.php?uf=' + $select, 
			dataType: 'json', 
			data: "", 
			async: false, 
			success: function(result){ 
				$.each(result, function(i, value) {
					if($selecionar!=null && value['codigo']==$selecionar){
						$($cidades).append($('<option>').text(value['nome']).attr('value', value['codigo']).attr('selected', 'selected'));	
					}else{
						$($cidades).append($('<option>').text(value['nome']).attr('value', value['codigo']));
					}
					
				});
			}
		});
	}

    function getKeyByValue(object, value) {
        return Object.keys(object).find(key => object[key] === value);
    }

    var objEs = { 11: 'RO', 12: 'AC', 13: 'AM', 14: 'RR', 15: 'PA', 16: 'AP', 17: 'TO', 21: 'MA', 22: 'PI', 23: 'CE', 24: 'RN', 25: 'PB', 26: 'PE', 27: 'AL', 28: 'SE', 29: 'BA', 31: 'MG', 32: 'ES', 33: 'RJ', 35: 'SP', 41: 'PR', 42: 'SC', 43: 'RS', 50: 'MS', 51: 'MT', 52: 'GO', 53: 'DF' };

    $("#codigoUF").val(getKeyByValue(objEs, $select));

}

function selecionarMunicipio(){
	$("#nomeMunicipio").val($( "#ccidade option:selected" ).text());
}

$(document).ready(function() {

    $('#certificadosenha').attr('type', 'password');

    if ($('#protocol').val() == 'smtp') {
        $('#smtp_config').slideDown();
    } else if ($('#protocol').val() == 'sendmail') {
        $('#sendmail_config').slideDown();
    }
    $('#protocol').change(function () {
        if ($(this).val() == 'smtp') {
            $('#sendmail_config').slideUp();
            $('#smtp_config').slideDown();
        } else if ($(this).val() == 'sendmail') {
            $('#smtp_config').slideUp();
            $('#sendmail_config').slideDown();
        } else {
            $('#smtp_config').slideUp();
            $('#sendmail_config').slideUp();
        }
    });

    $('#enable_java_applet').change(function () {
        var ja = $(this).val();
        if (ja == 1) {
            $('#jac').slideDown();
        } else {
            $('#jac').slideUp();
        }
    });
    var ja = '<?=$Settings->java_applet?>';
    if (ja == 1) {
        $('#jac').slideDown();
    } else {
        $('#jac').slideUp();
    }
});
$(document).ready(function () {
    var max_fields      = 100; //limite de campos
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var x = parseInt('<?=$cc?>'); //initlal text box count
    
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){
            x++;
            create_bairro(x);
        }
    });
});


function create_bairro(id = ""){
    var st = "display:block;"
    $(".input_fields_wrap").append(
        '<tr class="campos_bairros">'+
        '<td style="width:40%"><input type="hidden" name="bairro_ids['+id+']" value=""><input type="search" placeholder="Nome" value="" class="form-control" name="bairro_nome['+id+']"> </td> '+
        '<td style="width:40%"><input type="text" required="true" placeholder="Valor" value="" class="form-control money" name="bairro_valor['+id+']"> </td>  '+
        '<td style="width:10%;text-align:center;"><a href="javascript:void(0)" onclick="removerLocal(this)" class="remove_field btn btn-xs btn-danger">Remover</a></td>'+
        '</tr>'
    );
}

function removerLocal(e){
    var msg = "Deseja remover?";
    bootbox.addLocale('bl', { OK: 'OK', CANCEL: 'Não', CONFIRM: 'Sim' });
    bootbox.setDefaults({ closeButton: false, locale: "bl" });
    bootbox.confirm(msg, function(result) {
        if (result) {
            try {
            bid = $(e).attr("bid");
            } catch (error) {
                alert(error);
            }
            $(e).closest('tr').remove();
        }
    });
}
</script>