
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/jquery.ui.autocomplete.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/chosen.min.css" media="screen">
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/jquery-ui-1.9.0.custom.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/toastr.css" media="screen">
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery.serialize-object.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/utils-1541517054.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/bootstrap.min-1541517054.css">
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/bootstrap.min-1541517054.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/hopscotch.min-1541517054.css">
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/style-1.7-1542374540.css" media="screen">
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.tags.gerenciador-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery.mask.min-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/hopscotch.min-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/xajax-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/colorbox/colorbox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/colorbox/colorbox.css">
<script type="text/javascript">

    function hideMsg(parentMsg, tipoAviso) {
    }
</script>
<style>
h3.subtitulo{
margin-top: 20px!important;
font-size: 16px!important;
width: 100%!important;
padding: 5px!important;
color: #000;
background: #d6d6d6!important;
box-shadow: 0px 0px 0px;
}
.invalidoborder{border:1px solid red}
.content-wrapper{float:left!important;}
a:hover, a:focus {text-decoration: none!important;}
input:-moz-read-only { /* For Firefox */background: #dfdfdf!important;}
/*input:read-only, input:disabled {background: #dfdfdf!important;} */
}
</style>
</head>
<body style="cursor: default;">
<div id="main-container">
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.notas.fiscais-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.nfe-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.lote.nfe-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.cupom.fiscal-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/calculo.comissao-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery.print-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.integracoes.logisticas.uteis-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.logisticas.integracoes.gerais-1541517054.js"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.integracoes.logisticas.objs-1541517054.js"></script>
<script src="<?= $assets ?>creador_notas_files/json2.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?= $assets ?>creador_notas_files/fileuploader.css">
<script src="<?= $assets ?>creador_notas_files/fileuploader.js"></script>
<script src="<?= $assets ?>creador_notas_files/defiant.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/form.nota.fiscal.itens-1541517054.js"></script>
<div id="container">
	<div id="edicao" class="">

	<div class="content box-content">
		<div id="base">
		<input type="hidden" id="situacao" name="_situacao" value="1">
		<input type="hidden" id="tipoAmbiente" name="_tipoAmbiente" value="q">
		<input type="hidden" id="custoAtualizado" name="_custoAtualizado" value="">
		<input type="hidden" id="pvFrete" name="pvFrete">
		<input type="hidden" id="idListaVendas" name="idListaVendas" value="0">
		<input type="hidden" id="idNotaFiscalReferenciada" name="idNotaFiscalReferenciada" value="">
		<input type="hidden" id="id" name="_id" value="0">
		<input type="hidden" id="faturada" name="_faturada" value="S">
		<input type="hidden" id="simples" name="_simples" value="S">
		<input type="hidden" id="alqSimples" name="_alqSimples" value="0,00">
		<input type="hidden" id="valorSimples" name="_valorSimples">
		<input type="hidden" id="peso_calculado" name="_peso_calculado" value="N">
		<input type="hidden" id="volume_calculado" name="_volume_calculado" value="N">
		<input type="hidden" id="nfe:desconto_calculado" name="_nfe:desconto_calculado" value="N">
		<input type="hidden" id="calculaImpostos" name="_calculaImpostos" value="S">
		<input type="hidden" id="tipoOrigem" name="tipoOrigem" value="">
		<input type="hidden" id="idControl" name="idControl" value="4utr2sab5mp7i3g4kg4r5llldutcf697">
		<input type="hidden" id="nfe_versao" name="nfe_versao" value="4.00">
		<input type="hidden" id="versao" name="_versao" value="">
		<input type="hidden" id="nfce_versao" name="_nfce_versao" value="4.00">
		<input type="hidden" id="calcularDifal" value="M">

	    <?= form_open("pos/nfe/0/2/emitir/nf", array('id' => 'formNotaFiscal', 'name' => 'formNotaFiscal', "target"=> "popupEnvio", "onsubmit" => "return EnvioNFForm()", "autocomplete" => "nope")); ?>
                <input type="hidden" id="teste" name="teste" value="">
				
				<h3 class="subtitulo">Dados da nota</h3>
				<div class="linha_form wh10 margin_right">
					<p class="titulo_input" id="lbl_tipo_imp">Tipo da Nota Fiscal</p>
					<select class="input_text" id="notaTipo" name="tipooperacao" onchange="configurarTipoNormalExterno('S')">
						<option value="1" selected="selected">Saída</option>
						<option value="0">Entrada</option>
					</select>
				</div>

				<div class="linha_form wh8 margin_right">
					<p class="titulo_input">Série<span style="color:red">&nbsp;*</span>
					</p> <input class="input_text number" type="text" value="<?=$Settings->serie_nf;?>" name="serie" id="serie" >
				</div>
				
				<div class="linha_form wh10 margin_right">
					<p class="titulo_input" title="O número pode ser alterado ao gravar caso outro usuário já tenha inserido uma nota com o mesmo número">Número<span style="color:red">&nbsp;*</span></p>
					<input class="input_text number" type="text" name="numero" id="numero" maxlength="9" value="<?php echo $proxima_nf; ?>" placeholder="<?php echo $proxima_nf; ?>" required="true"><input type="hidden" name="proximoNumero" id="proximoNumero" value="000">
				</div>
				<div class="linha_form wh10 margin_right">
					<p id="lblData" class="titulo_input">Data Emissão</p> <input class="input_text number" type="date" name="data_emissao" id="dataEmissao" value="<?php echo date("Y-m-d");?>"></div>
				
				<div class="linha_form wh10 margin_right">
					<p id="lblData" class="titulo_input">Auto Saida/Entrada</p><br>
					<input class="input_text" type="checkbox"style="float:right" name="data_entrada_saida_auto" id="dataSaidaEntradaauto" value="on" checked></div>
					
				<div class="linha_form wh10 margin_right">
					<p id="lblData" class="titulo_input">Data Saida/Entrada</p> <input class="input_text number" type="date" name="data_entrada_saida" id="dataSaidaEntrada" value="<?php echo date("Y-m-d");?>"></div>
				<div class="linha_form wh10">
					<p id="lblHora" class="titulo_input">Hora Saida/Entrada</p> <input class="input_text number" type="time" name="hora_entrada_saida" id="horaSaidaEntrada" value="<?php echo date("H:i");?>">
				</div>

				<div class="linha_form wh42 margin_right">
					<p class="titulo_input">Natureza da operação<span style="color:red">&nbsp;*</span></p> <select class="input_text tipsyOff ui-autocomplete-input" required="true" name="natureza_operacao" id="natureza">
					<?php   
					
						foreach(listaCFOPNF as $k => $v){
							$s = ($k == $Settings->mailpath)? " selected='selected'" : "";
							echo '<option value="'.$k.'" '.$s.'>'.$v.'</option>';
						}	   
					?>
					</select>
				</div> 
	
				<div class="linha_form wh32 margin_right">
					<p class="titulo_input">Finalidade<span style="color:red">&nbsp;*</span></p>
					<select class="input_text" id="finalidade" required="true" name="finalidade">
					<option value="1" selected="selected">NF-e normal</option>
					<option value="4">Devolução de mercadoria</option>
					</select>
				</div>

				<div class="linha_form wh33  margin_right">
					<p class="titulo_input">Indicador de Presença<span style="color:red">&nbsp;*</span></p>
					<select id="indPres" name="presenca" required="true" class="input_text">
					<option value="0">0 - Não se aplica</option>
					<option value="1" selected>1 - Operação presencial</option>
					<option value="2">2 - Operação não presencial, pela Internet</option>
					<option value="3">3 - Operação não presencial, Teleatendimento</option>
					<!--<option value="4">4 - NFC-e em operação com entrega a domicílio;</option>-->
					<option value="5">5 - Operação presencial, fora do estabelecimento</option>
					<option value="9">9 - Operação não presencial, outros</option>
					</select>
				</div>

				<div class="linha_form wh25 margin_right">
					<p class="titulo_input">Destino da operação<span style="color:red">&nbsp;*</span></p>
					<select class="input_text" id="destinooperacao" required="true" name="destinooperacao">
					<option value="1">1- Dentro do Estado (Operação Interna)</option>
					<option value="2">2- Fora do Estado (Operação Interestadual)</option>
					<option value="3">3- Operação com Exterior</option>
					</select>
				</div>

				<div class="linha_form wh30 margin_right">
					<p class="titulo_input">Referenciar NF-e (Chave)</p> <input class="input_text number" type="text" value="" name="nfe_referenciada" id="nfe_referenciada" >
				</div>
				
				<div class="linha_form wh25 margin_right">
					<p class="titulo_input">Intermediador - CNPJ</p> <input class="input_text number" type="text" value="" name="intermediador_cnpj" id="intermediador_cnpj" >
				</div>
				<div class="linha_form wh25 margin_right">
					<p class="titulo_input">Intermediador - Identificador</p> <input class="input_text number" type="text" value="" name="intermediador_ident" id="intermediador_ident" >
				</div>

				<h3 class="subtitulo" id="hContato">Destinatário</h3>

				<div class="linha_form wh35  margin_right">
					<p class="titulo_input">Nome<span style="color:red">&nbsp;*</span>
					</p>
					<input type="text" id="contato" name="contato" required="true" placeholder="Digite parte do nome ou cnpj/cpf" class="input_text tipsyOff ui-autocomplete-input" maxlength="60" autocomplete="nope"><span class="input_icones">
						<a title="Pesquisar" href="javascript:void(0)" class="formIcon" id="btnBuscarContato"><i class="icon-search"></i></a>
					</span>
					<div id="resultados_clientes" style="display:none;position: absolute; width: 500px; float: left; padding: 10px; border: 1px solid #ccc; margin-top: 47px; z-index: 999; box-shadow: 1px 1px 9px #ccc; background: #eaeaea;">
					</div>
					<input type="hidden" id="idContato" name="idContato" value=""></div>
					
				<div class="linha_form wh10 margin_right">
					<p class="titulo_input">Tipo da Pessoa<span style="color:red">&nbsp;*</span></p>
					<select id="tipoPessoa" required="true" class="input_text" name="tipoPessoa" onchange="ajustarFormContatoRapido($(this).val()); ajustarFormContatoRapido2($(this).val()); if(this.value == 'J'){ $('#indFinal').iCheck('uncheck'); }else{ $('#indFinal').iCheck('check'); } "><option value="J">Jurídica</option><option value="F">Física</option><option value="E">Estrangeiro</option></select></div>
				
				<div class="linha_form wh12 margin_right" id="td_idext" style="display: none;">
					<p class="titulo_input" id="idext">Doc. Identif. do Extrangeiro<span style="color:red">&nbsp;*</span></p>
					<input type="text" required="true" class="input_text" name="idext" id="idext" maxlength="14">
				</div>


				<div class="linha_form wh12 margin_right" id="td_cnpj" style="display: block;">
					<p class="titulo_input" id="lblCnpj">CNPJ<span style="color:red">&nbsp;*</span></p>
					<input type="text" required="true" class="input_text" name="cnpj" id="cnpj" onkeypress="
						if(document.getElementById('tipoPessoa').value == 'J'){
						return txtBoxFormat(this.form, this.name, '99.999.999/9999-99', event);
						}else if(document.getElementById('tipoPessoa').value == 'F'){
						return txtBoxFormat(this.form, this.name, '999.999.999-99', event);}" onkeyup="if(document.getElementById('tipoPessoa').value == 'J'){
						return autoTab(this, 18, event);
						}else if(document.getElementById('tipoPessoa').value == 'F'){
					return autoTab(this, 14, event);}"></div>
				<div class="linha_form wh12 margin_right" id="td_ie" style="display: block;">
					<p class="titulo_input" id="lblIe">Inscrição Estadual<span style="color:red">&nbsp;*</span></p>
					<input type="text" class="input_text" name="ie" id="ie" maxlength="14"></div>
				<div class="linha_form wh12 margin_right">
					<p class="titulo_input">Contribuinte<span style="color:red">&nbsp;*</span></p>
					<select id="indIEDest" name="indIEDest" required="true" class="input_text" onchange="atualizaIEDest();calcularImpostos('S'); if(this.value=='9') $('#ie').val(''); "><option value="1">1 - Contribuinte ICMS</option><option value="2">2 - Contribuinte isento de Inscrição no Cadastro de Contribuintes</option><option value="9">9 - Não contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes</option></select></div>
				<span class="linha_form" style="height:42px;vertical-align:bottom;line-height:53px;display:inline-block;padding-top:5px;">
					<input type="checkbox" id="indFinal" name="indFinal" value="1" onchange="calcularImpostos('S');"><label for="indFinal">Consumidor final</label>
				</span>
				<div class="linha_form wh50 margin_right">
					<p class="titulo_input">Endereço<span style="color:red">&nbsp;*</span></p>
					<input type="text" class="input_text" required="true" name="endereco" id="endereco" maxlength="60"></div>
				<div class="linha_form wh13">
					<p class="titulo_input">Número<span style="color:red">&nbsp;*</span></p>
					<input type="text" class="input_text" required="true" name="enderecoNro" id="enderecoNro" maxlength="60"></div>
				<div class="linha_form wh35 margin_left">
					<p class="titulo_input">Complemento</p>
					<input type="text" class="input_text" name="complemento" id="complemento" maxlength="60"></div>
				<div class="linha_form wh10 margin_right" id="td_uf" style="display: block;">
					<p class="titulo_input">UF<span style="color:red">&nbsp;*</span></p> <select name="uf" id="uf" class="input_text" required="true" onchange="GetMunicipio(this.value, '#municipio');calcularImpostos('N');"><option value=""> Selecione o Estado </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option><option value="EX">EX</option></select></div>
				<div class="linha_form wh40 margin_right">
					<p class="titulo_input">Município<span style="color:red">&nbsp;*</span>
					</p>
					<input type="hidden" name="cidade" id="nomeMunicipio" value="">
					<input type="hidden" name="cidade_cod" id="idMunicipio" value="">
					<select class="input_text tipsyOff ui-autocomplete-input" required="true" onchange="selecionarMunicipio(this.value)" name="municipio" id="municipio"></select></div>
					
				<div class="linha_form wh11 margin_right input-cep">
					<p class="titulo_input">CEP</p>
					<input type="text" class="input_text" id="cep" name="cep" maxlength="9"><div class="input_icones">
						<!--<a href="#" class="formIcon buscaCep" id="buscaEndereco" title="Buscar CEP"><i class="icon-search"></i></a>-->
					</div>
				</div>
				
				<div class="linha_form wh32">
					<p class="titulo_input">Bairro<span style="color:red">&nbsp;*</span></p>
					<input type="text" class="input_text" required="true"  name="bairro" id="bairro" maxlength="60"></div>

					<div class="linha_form wh15 margin_right" id="td_pais" style="display: none;">
					<p class="titulo_input">País
					</p>
					<input type="hidden" id="nome_pais" name="nome_pais" value="Brasil">
					<select class="input_text tipsyOff" name="cod_pais" id="Pais" onchange="if(this.value!='1058'){$('#uf').val('EX');$('#nomeMunicipio').val('EXTERIOR');$('#idMunicipio').val('9999999');$('#municipio').append($('<option>').text('EXTERIOR').attr('value', 'EXTERIOR').attr('selected', 'selected'));} $('#nome_pais').val($('#Pais option:selected').text());" chown -R www-data:root>
					<option value="">- Selecione o país -</option><option value="1058" selected>Brasil</option><option value="0132">Afeganistao</option><option value="7560">Africa do Sul</option><option value="0175">Albania, Republica da</option><option value="0230">Alemanha</option><option value="0370">Andorra</option><option value="0400">Angola</option><option value="0418">Anguilla</option><option value="0434">Antigua e Barbuda</option><option value="0477">Antilhas Holandesas</option><option value="0531">Arabia Saudita</option><option value="0590">Argelia</option><option value="0639">Argentina</option><option value="0647">Armenia, Republica da</option><option value="0655">Aruba</option><option value="0698">Australia</option><option value="0728">Austria</option><option value="0736">Azerbaijao, Republica do</option><option value="0779">Bahamas, Ilhas</option><option value="0809">Bahrein, Ilhas</option><option value="9950">Bancos Centrais</option><option value="0817">Bangladesh</option><option value="0833">Barbados</option><option value="0850">Belarus, Republica da</option><option value="0876">Belgica</option><option value="0884">Belize</option><option value="2291">Benin</option><option value="0906">Bermudas</option><option value="0973">Bolivia, Estado Plurinacional da</option><option value="0981">Bosnia-herzegovina, Republica da</option><option value="1015">Botsuana</option><option value="1082">Brunei</option><option value="1112">Bulgaria, Republica da</option><option value="0310">Burkina Faso</option><option value="1155">Burundi</option><option value="1198">Butao</option><option value="1279">Cabo Verde, Republica de</option><option value="1457">Camaroes</option><option value="1414">Camboja</option><option value="1490">Canada</option><option value="1511">Canarias, Ilhas</option><option value="1546">Catar</option><option value="1376">Cayman, Ilhas</option><option value="1538">Cazaquistao, Republica do</option><option value="7889">Chade</option><option value="1589">Chile</option><option value="1600">China, Republica Popular</option><option value="1635">Chipre</option><option value="5118">Christmas, Ilha, Navidad</option><option value="7412">Cingapura</option><option value="1651">Cocos, Keeling, Ilhas</option><option value="1694">Colombia</option><option value="1732">Comores, Ilhas</option><option value="1775">Congo</option><option value="8885">Congo, Republica Democratica do</option><option value="1830">Cook, Ilhas</option><option value="1872">Coreia do Norte, Rep. Pop. Democr.</option><option value="1902">Coreia do Sul, Republica da</option><option value="1961">Costa Rica</option><option value="1937">Costa do Marfim</option><option value="1988">Coveite, Kuwait</option><option value="1953">Croacia, republica da</option><option value="1996">Cuba</option><option value="2321">Dinamarca</option><option value="7838">Djibuti</option><option value="2356">Dominica, Ilha</option><option value="2402">Egito</option><option value="6874">El Salvador</option><option value="2445">Emirados Arabes Unidos</option><option value="2399">Equador</option><option value="2437">Eritreia</option><option value="2470">Eslovaca, Republica</option><option value="2461">Eslovenia, Republica da</option><option value="2453">Espanha</option><option value="2496">Estados Unidos</option><option value="2518">Estonia, Republica da</option><option value="2534">Etiopia</option><option value="2550">Falkland, Ilhas Malvinas</option><option value="2593">Feroe, Ilhas</option><option value="8702">Fiji</option><option value="2674">Filipinas</option><option value="2712">Finlandia</option><option value="1619">Formosa, Taiwan</option><option value="2755">Franca</option><option value="2810">Gabao</option><option value="2852">Gambia</option><option value="2895">Gana</option><option value="2917">Georgia, Republica da</option><option value="2933">Gibraltar</option><option value="2976">Granada</option><option value="3018">Grecia</option><option value="3050">Groenlandia</option><option value="3093">Guadalupe</option><option value="3131">Guam</option><option value="3174">Guatemala</option><option value="1504">Guernsey, Ilha do Canal, inclui Alderney e Sark</option><option value="3379">Guiana</option><option value="3255">Guiana Francesa</option><option value="3298">Guine</option><option value="3344">Guine-Bissau</option><option value="3310">Guine-Equatorial</option><option value="3417">Haiti</option><option value="3450">Honduras</option><option value="3514">Hong Kong</option><option value="3557">Hungria, Republica da</option><option value="3573">Iemen</option><option value="3611">India</option><option value="3654">Indonesia</option><option value="3727">Ira, Republica Islamica do</option><option value="3697">Iraque</option><option value="3751">Irlanda</option><option value="3794">Islandia</option><option value="3832">Israel</option><option value="3867">Italia</option><option value="3913">Jamaica</option><option value="3999">Japao</option><option value="1508">Jersey, Ilha do Canal</option><option value="3964">Johnston, Ilhas</option><option value="4030">Jordania</option><option value="4111">Kiribati</option><option value="4200">Laos, Rep. Pop. Democr. do</option><option value="4235">Lebuan,ilhas</option><option value="4260">Lesoto</option><option value="4278">Letonia, Republica da</option><option value="4316">Libano</option><option value="4340">Liberia</option><option value="4383">Libia</option><option value="4405">Liechtenstein</option><option value="4421">Lituania, Republica da</option><option value="4456">Luxemburgo</option><option value="4472">Macau</option><option value="4499">Macedonia, Ant. Rep. Iugoslava</option><option value="4502">Madagascar</option><option value="4525">Madeira, Ilha da</option><option value="4553">Malasia</option><option value="4588">Malavi</option><option value="4618">Maldivas</option><option value="4642">Mali</option><option value="4677">Malta</option><option value="3595">Man, Ilha De</option><option value="4723">Marianas do Norte</option><option value="4740">Marrocos</option><option value="4766">Marshall, Ilhas</option><option value="4774">Martinica</option><option value="4855">Mauricio</option><option value="4880">Mauritania</option><option value="4885">Mayotte, Ilhas Francesas</option><option value="4936">Mexico</option><option value="0930">Mianmar, Birmania</option><option value="4995">Micronesia</option><option value="4901">Midway, Ilhas</option><option value="5053">Mocambique</option><option value="4944">Moldavia, Republica da</option><option value="4952">Monaco</option><option value="4979">Mongolia</option><option value="4985">Montenegro</option><option value="5010">Montserrat, Ilhas</option><option value="5070">Namibia</option><option value="5088">Nauru</option><option value="5177">Nepal</option><option value="5215">Nicaragua</option><option value="5258">Niger</option><option value="5282">Nigeria</option><option value="5312">Niue, Ilha</option><option value="5355">Norfolk, Ilha</option><option value="5380">Noruega</option><option value="5428">Nova Caledonia</option><option value="5487">Nova Zelandia</option><option value="5568">Oma</option><option value="9970">Organizacoes Internacionais</option><option value="5665">Pacifico, Ilhas do, possessao dos EUA</option><option value="5738">Paises Baixos, Holanda</option><option value="5754">Palau</option><option value="5800">Panama</option><option value="5452">Papua Nova Guine</option><option value="5762">Paquistao</option><option value="5860">Paraguai</option><option value="5894">Peru</option><option value="5932">Pitcairn, Ilha</option><option value="5991">Polinesia Francesa</option><option value="6033">Polonia, Republica da</option><option value="6114">Porto Rico</option><option value="6076">Portugal</option><option value="9903">Provisao de Navios e Aeronaves</option><option value="6238">Quenia</option><option value="6254">Quirguiz, Republica</option><option value="6289">Reino Unido</option><option value="6408">Republica Centro-africana</option><option value="6475">Republica Dominicana</option><option value="6602">Reuniao, Ilha</option><option value="6700">Romenia</option><option value="6750">Ruanda</option><option value="6769">Russia, Federacao da</option><option value="6858">Saara Ocidental</option><option value="6777">Salomao, Ilhas</option><option value="6904">Samoa</option><option value="6912">Samoa Americana</option><option value="6971">San Marino</option><option value="7102">Santa Helena</option><option value="7153">Santa Lucia</option><option value="6955">Sao Cristovao e Neves, Ilhas</option><option value="7005">Sao Pedro e Miquelon</option><option value="7200">Sao Tome e Principe, Ilhas</option><option value="7056">Sao Vicente e Granadinas</option><option value="7285">Senegal</option><option value="7358">Serra Leoa</option><option value="7370">Servia</option><option value="7315">Seychelles</option><option value="7447">Siria, Republica Arabe da</option><option value="7480">Somalia</option><option value="7501">Sri Lanka</option><option value="7544">Suazilandia</option><option value="7595">Sudao</option><option value="7641">Suecia</option><option value="7676">Suica</option><option value="7706">Suriname</option><option value="7722">Tadjiquistao, Republica do</option><option value="7765">Tailandia</option><option value="7803">Tanzania, Rep. Unida da</option><option value="7919">Tcheca, Republica</option><option value="7820">Territorio Brit. Oc. Indico</option><option value="7951">Timor Leste</option><option value="8001">Togo</option><option value="8109">Tonga</option><option value="8052">Toquelau, Ilhas</option><option value="8150">Trinidad e Tobago</option><option value="8206">Tunisia</option><option value="8230">Turcas e Caicos, Ilhas</option><option value="8249">Turcomenistao, Republica do</option><option value="8273">Turquia</option><option value="8281">Tuvalu</option><option value="8311">Ucrania</option><option value="8338">Uganda</option><option value="8451">Uruguai</option><option value="8478">Uzbequistao, Republica do</option><option value="5517">Vanuatu</option><option value="8486">Vaticano, Est. da Cidade do</option><option value="8508">Venezuela</option><option value="8583">Vietna</option><option value="8630">Virgens, Ilhas, Britanicas</option><option value="8664">Virgens, Ilhas, E.U.A.</option><option value="8737">Wake, Ilha</option><option value="8907">Zambia</option><option value="6653">Zimbabue</option><option value="8958">Zona do Canal do Panama</option></select>	
			</div>
			
				<div class="linha_form wh15 margin_right">
					<p class="titulo_input">Fone/Fax</p>
					<input type="text" class="input_text format_fone" name="fone" id="fone" maxlength="14"></div>
				<div class="linha_form wh30 margin_right">
					<p class="titulo_input">E-Mail</p>
					<input type="text" class="input_text" name="email" id="email" maxlength="60"></div>
				<div class="linha_form wh32 margin_right"><p class="titulo_input">Vendedor</p>
					<input type="hidden" name="idVendedor" id="idVendedor" value=""><input type="text" class="input_text tipsyOff ui-autocomplete-input" name="nomeVendedor" id="nomeVendedor" onkeydown="clearHidenResult(event,$('#idVendedor'));" placeholder="não obrigatório" autocomplete="off"></div>
				
				<div class="linha_form wh30 margin_right">
					<a class="btn btn-warning" id="inf-cliente-produto" title="Salvar dados do cliente" onclick="saveasNewCliente()"><i class="icon-plus"></i> Salvar como novo cliente</a> <span id="cliente-anadido" style="color:green;display:none">Cliente salvo</span>
				</div>
				<div class="linha_form wh30 margin_right">
					<a class="btn btn-warning" id="inf-cliente-produto" title="Buscar na Receita Federal" onclick="BuscarReceita()" ><i class="icon-search"></i> Buscar na Receita Federal</a>
				</div>

				
				<div class="linha_form wh20" id="linhaSuframa" style="display:none;">
					<p class="titulo_input">Inscrição suframa</p>
					<input type="text" class="input_text" name="inscricaoSuframa" id="inscricaoSuframa" maxlength="9"></div>
					<br>
				
				<div id="div_exportacao" style="display: none;">
					<h3 class="subtitulo">Dados de Exportação</h3>

					<div class="linha_form wh79 margin_right">
						<p class="titulo_input">Local de Embarque</p>
						<input type="text" class="input_text" id="localEmbarque" name="localEmbarque"></div>
					<div class="linha_form wh20">
						<p class="titulo_input">UF Embarque</p> <select name="ufEmbarque" id="ufEmbarque" class="input_text"><option value=" "> UF ... </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="EX">EX</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></div>
				</div>

				<div id="declaracao_importacao" style="display: none;">

					<h3 class="subtitulo">Declaração de Importação</h3>

					<div class="linha_form wh25 margin_right">
						<p class="titulo_input">Número da DI</p>
						<input type="text" class="input_text" id="diNumero" name="diNumero"></div>
					<div class="linha_form wh17 margin_right">
						<p class="titulo_input">Data de Registro da DI</p>
						<input class="input_text number date-pick" type="text" name="diData" id="diData" onchange="formatDateField(this);" maxlength="10"><img class="ui-datepicker-trigger" src="<?= $assets ?>creador_notas_files/calendar.png" alt="..." title="..."></div>
					<div class="linha_form wh30 margin_right">
						<p class="titulo_input">Código do Exportador</p>
						<input type="text" class="input_text" id="diCodigoExportador" name="diCodigoExportador"></div>
					<div class="linha_form wh25">
						<p class="titulo_input">Despesas Aduaneiras</p>
						<input type="text" class="input_text" name="valorDespesaAduaneira" id="valorDespesaAduaneira" value="0,00" onchange="calcularImpostos('N');"></div>
					<div class="linha_form wh68 margin_right">
						<p class="titulo_input">Local Desembaraço</p>
						<input type="text" class="input_text" id="diLocalDesembaraco" name="diLocalDesembaraco"></div>
					<div class="linha_form wh13 margin_right">
						<p class="titulo_input">UF Desembaraço</p>
						<select name="diUFDesembaraco" id="diUFDesembaraco" class="input_text"><option value=" "> UF ... </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="EX">EX</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></div>
					<div class="linha_form wh17">
						<p class="titulo_input">Data Desembaraço</p>
						<input class="input_text number date-pick" type="text" name="diDataDesembaraco" id="diDataDesembaraco" onchange="formatDateField(this);" maxlength="10"><img class="ui-datepicker-trigger" src="<?= $assets ?>creador_notas_files/calendar.png" alt="..." title="..."></div>

					<div class="linha_form margin_right wh19">
						<label class="titulo_input">Via de transporte</label>
						<select class="input_text" id="DItpViaTransp" name="DItpViaTransp" onchange="changetpViaTransp();"><option value="1">1 - Marítima</option><option value="2">2 - Fluvial</option><option value="3">3 - Lacustre</option><option value="4">4 - Aérea</option><option value="5">5 - Postal</option><option value="6">6 - Ferroviária</option><option value="7">7 - Rodoviária</option><option value="8">8 - Conduto / Rede Transmissão</option><option value="9">9 - Meios Próprios</option><option value="10">10 - Entrada / Saída ficta</option><option value="11">11 - Courier</option><option value="12">12 - Handcarry</option></select></div>
					<div class="linha_form wh24 margin_right">
						<label class="titulo_input">Forma de importação</label>
						<select class="input_text" id="DItpIntermedio" name="DItpIntermedio" onchange="changetpintermedio();"><option value="1">1 - Por conta própria</option><option value="2">2 - Por conta e ordem</option><option value="3">3 - Por encomenda</option></select></div>
					<div class="linha_form wh29 margin_right" id="container_DIvAFRMM">
						<label class="titulo_input">Valor da AFRMM</label>
						<input type="text" class="input_text edt-valor" id="DIvAFRMM" name="DIvAFRMM" onchange="calcularImpostos('N');"></div>
					<div class="linha_form wh33 margin_right DItpIntermedio" style="display: none;">
						<label class="titulo_input">CNPJ do adquirente</label>
						<input type="text" class="input_text" id="DICNPJ" name="DICNPJ" maxlength="18" onkeypress="txtBoxFormat(this.form, this.name, '99.999.999/9999-99', event);"></div>
					<div class="linha_form wh33 DItpIntermedio" style="display: none;">
						<label class="titulo_input">UF do adquirente</label>
						<select name="DIUFTerceiro" id="DIUFTerceiro" class="input_text"><option value=" "> UF ... </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="EX">EX</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></div>

					<div class="linha_form wh25" style="padding-top: 20px;">
						<input type="checkbox" id="valorUnitarioComII" name="valorUnitarioComII" value="S"><label for="valorUnitarioComII">Valor unitário do item com Imposto de Importação</label>
					</div>

					<div class="linha_form wh100">
						<h3 class="subtitulo">Adições</h3>
						<div class="linha_form wh100" style="height: auto; max-height: 200px; overflow: auto; padding: 4px;">
							<table id="tAdicoes" class="grid-header" style="width: 100%;"><thead><tr><th><p class="titulo_input">Número adição</p></th>
										<th><p class="titulo_input">Número sequencial adição</p></th>
										<th><p class="titulo_input">Código fabricante</p></th>
										<th><p class="titulo_input">Valor desconto DI</p><span class="inf" title="Opcional">i</span></th>
										<th><p class="titulo_input">Número drawback</p><span class="inf" title="Opcional">i</span></th>
										<th></th>
									</tr></thead><tbody><tr><td><input type="text" class="input_text editgridh" name="nAdicao[]" value="1"></td><td><input type="text" class="input_text editgridh" name="nSeqAdicC[]"></td><td><input type="text" class="input_text editgridh" name="cFabricante[]"></td><td><input type="text" class="input_text editgridh" name="vDescDI[]"></td><td><input type="text" class="input_text editgridh" name="nDraw[]"></td><td class="center" width="34"></td></tr></tbody></table><a id="aNovaLinhaAdicao" class="link-action plus-sign float_right" onclick="adicionarLinhaAdicao(''); return false;" style="padding-right: 5px;" href="#">Adicionar item</a>
						</div>
					</div>

				</div>

				<h3 class="subtitulo">Itens da nota fiscal</h3>
				<div class="linha_form wh50 margin_right">
					<p class="titulo_input">Buscar Produtos<span style="color:red">&nbsp;*</span></p>
						<input type="text" id="buscarProdutos" autocomplete="nope" class="input_text tipsyOff ui-autocomplete-input" placeholder="Digite parte do nome ou código do item" maxlength="60"> <a href='./iframeredi?link=../products/add?isframe=1' style="float:right;margin: 1px 0px;" data-toggle='ajax' title='Adicionar produto' class='tip btn btn-warning btn-xs'><i class='fa fa-plus'></i> Adicionar Produto</a>
					<div id="resultados_buscarProdutos" style="display:none;position: absolute; width: 500px; float: left; padding: 10px; border: 1px solid #ccc; margin-top: 47px; z-index: 999; box-shadow: 1px 1px 9px #ccc; background: #eaeaea;">
					</div>
				</div>
				<div class="linha_form wh100" style="background: none;">
					<table style="width:100%;">
						<tbody>
							<tr><td>
								<table id="tItensNota" class="grid-header tabela-numerada">
									<tbody>
										<tr id="itens_header">
											<th class="wh35">Produto ou serviço</th>
											<th class="wh10">Código</th>
											<th class="wh4">UN</th>
											<th class="wh7">Qtde</th>
											<th class="wh10">Preço un</th>
											<th class="wh12">Preço total</th>
											<th class="wh12">NCM</th>
											<th width="1"></th>
										</tr>
										
										</tbody>
									</table>
								</td>
							</tr>
			
						</tbody>
					</table>
					<br>
					<!--<a class='inline' href="#inline-add-produto"><button class="btn btn-warning btn-block btn-flat">Adicionar Produtos</button></a>-->
				</div>

				<h3 class="subtitulo">Totais da Nota</h3>

				<!--<div class="linha_form wh30 margin_right">
					<p class="titulo_input">Total dos Serviços</p>
					<input type="text" class="input_text" name="valorServicos" id="valorServicos" value="0,00" readonly="readonly"></div>-->

				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Valor do Frete</p>
					<input type="text" required="true" class="input_text edt-number money" name="frete" id="frete" value="0,00" onchange="TotaisValoresNota();calcularImpostos('N');"></div>
				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Valor do Seguro</p>
					<input type="text" class="input_text edt-number money" name="seguro" id="seguro" value="0,00" onchange="TotaisValoresNota();calcularImpostos('N');"></div>
				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Outras Despesas</p>
					<input type="text" class="input_text edt-number money" name="outrasDespesas" id="outrasDespesas" value="0,00" onchange="TotaisValoresNota();calcularImpostos('N');"></div>
				
				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Desconto</p>
					<input type="text" class="input_text money" name="desconto" id="desconto" value="0,00" onchange="TotaisValoresNota();calcularImpostos('N');"><input type="hidden" name="tipoDesconto" id="tipoDesconto"><input type="hidden" name="valorDesconto" id="valorDesconto" value="0"></div>
				
				<div class="linha_form wh100 margin_right">
						</div>

				<div class="linha_form wh30 margin_right">
					<p class="titulo_input">Total dos Produtos/Serviços</p>
					<input type="text" class="input_text" name="valorProdutos" id="valorProdutos" value="0,00" readonly="readonly"></div>
				<div class="linha_form wh30 margin_right">
				<p class="titulo_input">Total da Nota</p>
				<input type="text" class="input_text" name="valorNota" id="valorNota" value="0,00" onblur="terribleHack(this);" readonly="readonly"></div>
		

				<h3 class="subtitulo">Transportador/Volumes</h3>
				
				<div class="linha_form wh16 margin_right">
				<label class="titulo_input">Frete por conta<span style="color:red">&nbsp;*</span></label>
										<select class="input_text" name="modalidade_frete" required="true" id="fretePorConta"><option value="0">0 - Contratação do Frete por conta do Remetente (CIF)</option><option value="1">1 - Contratação do Frete por conta do Destinatário (FOB)</option><option value="2">2 - Contratação do Frete por conta de Terceiros</option><option value="3">3 - Transporte Próprio por conta do Remetente</option><option value="4">4 - Transporte Próprio por conta do Destinatário</option><option value="9" selected>9 - Sem Ocorrência de Transporte</option></select></div>

				<div class="linha_form wh34 margin_right">
					<input type="hidden" value="0" id="idTransportador" name="idTransportador"><p class="titulo_input">Nome</p>
					<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" class="input_text tipsyOff ui-autocomplete-input" name="transportador" id="transportador" autocomplete="off"></div>
				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Placa veículo</p>
					<input type="text" class="input_text" name="placa" id="placa"></div>
				<div class="linha_form wh10 margin_right">
					<p class="titulo_input">UF veículo</p> <select name="ufVeiculo" id="ufVeiculo" class="input_text"><option value=" "> UF ... </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="EX">EX</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></div>
				<div class="linha_form wh20">
					<p class="titulo_input">RNTC</p><span class="inf" title="Registro Nacional de Transportador de Carga (ANTT)">i</span>
					<input type="text" class="input_text" name="rntc" id="rntc">
				</div>
				<div class="linha_form wh17 margin_right">
					<p class="titulo_input">CNPJ/CPF</p>
					<input type="text" class="input_text" maxlength="18" name="cnpjTransportador" id="cnpjTransportador"></div>
				<div class="linha_form wh16 margin_right">
					<p class="titulo_input">Inscrição Estadual</p>
					<input class="input_text" type="text" name="ieTransportador" id="ieTransportador"></div>
				<div class="linha_form wh33 margin_right">
					<p class="titulo_input">Endereço</p>
					<input class="input_text" type="text" name="enderecoTransportador" id="enderecoTransportador"></div>
				<div class="linha_form wh20 margin_right">
					<p class="titulo_input">Município</p>
					<input class="input_text" type="text" name="municipioTransportador" id="municipioTransportador"></div>
				<div class="linha_form wh10">
					<p class="titulo_input">UF</p> <select name="ufTransportador" id="ufTransportador" class="input_text"><option value=""> UF ... </option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="EX">EX</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></div>
				<div class="linha_form wh12 margin_right">
					<p class="titulo_input">Quantidade</p>
					<input class="input_text" type="text" name="qtdVolumes" id="qtdVolumes" onkeyup="this.value=this.value.replace(/[^\d]/,'');"></div>
				<div class="linha_form wh21 margin_right">
					<p class="titulo_input">Espécie</p>
					<input class="input_text" type="text" name="especie" id="especie"></div>
				<div class="linha_form wh24 margin_right">
					<p class="titulo_input">Marca</p>
					<input class="input_text" type="text" name="marca" id="marca"></div>
				<div class="linha_form wh8 margin_right">
					<p class="titulo_input">Número de volumes</p>
					<input class="input_text" type="text" name="nroDosVolumes" id="nroDosVolumes"></div>
				<div class="linha_form wh15 margin_right">
					<p class="titulo_input">Peso Bruto</p>
					<span class="inf" title="Em Kg">i</span>
					<input class="input_text" type="text" name="pesoBruto" id="pesoBruto"></div>
				<div class="linha_form wh15">
					<p class="titulo_input">Peso Líquido</p>
					<span class="inf" title="Em Kg">i</span>
					<input class="input_text" type="text" name="pesoLiquido" id="pesoLiquido"></div>
	
				<h3 id="frame_pagamento_h3" class="subtitulo">Pagamento</h3>
				
				<div id="pagamentoDivs" class="linha_form wh100"></div>
				<input id="tpIntegra" name="tpIntegra" type="hidden" value="2">

			

				<div class="linha_form wh100">

					<div class="linha_form wh23 margin_right">
						<p class="titulo_input">Total de Pagamentos</p>
						<input type="text" name="totalpagamentos" id="totalpagamentos"  class="input_text" readonly="readonly">
					</div>
					<div class="linha_form wh23 margin_right">
						<p class="titulo_input">Troco</p>
						<input type="text" id="totaltroco" name="troco" class="input_text" readonly="readonly">
					</div>
					
				</div>
				<button class="btn btn-warning btn-flat" type="button" id="btnPagamento">Adicionar Pagamento</button>

				<h3 id="frame_vencimento_h3" class="subtitulo">Vencimentos</h3>
				
				<div id="vencimentoDivs" class="linha_form wh100"></div>
				<div class="linha_form wh100">

					<div class="linha_form wh23 margin_right">
						<p class="titulo_input">Total dos Vencimentos</p>
						<input type="text" name="totalvencimentos" id="totalvencimentos"  class="input_text" readonly="readonly">
					</div>
					
				</div>
				
				<button class="btn btn-warning btn-flat" type="button" id="btnVencimento">Adicionar Vencimento</button>

	
				<h3 class="subtitulo">Dados adicionais</h3>

				<div class="linha_form wh100">
					<p class="titulo_input">Observações</p>
					<input class="input_textarea" value="<?= strip_tags($Settings->footer);?>" name="observacoes" id="observacoes" style="height: auto;"> 
				</div>

				<div class="linha_form wh100">
					<div id="controls">
						<input type="button" id="botaoSalvarNotaFiscal" value="Enviar / Criar Nota" class="btn btn-success btn-flat btn-large" onclick='$("#teste").val("");$("#formNotaFiscal").submit();'> <input type="button" id="botaoSalvarNotaFiscal" value="Pré-visualizar" class="btn btn-info btn-flat btn-large" onclick='$("#teste").val("ok");$("#formNotaFiscal").submit();'> <input type="button" style="display:none" id="botaoContinuarNotaFiscal" value="Continuar editando.." class="btn btn-warning" onclick=""> 
					</div>
				</div>
			</form>

		</div>
		
</div>

</div>

</div>
</div>

<script type="text/javascript">

(function () {
    var previous;
    $("#natureza").on('focus', function () {
		previous = this.value;
    }).change(function() {
		var e = $(this).val().split("/");
		if(parseInt(e[0]) > 0){
			if (!confirm("Aplicar novo CFOP a todos os produtos?")) {
				$(this).val(previous); 
				return false;
			}
		}
        previous = this.value;
    });
})();

	function ajustarFormContatoRapido2(tipo){
		if(tipo=="E"){ 
			$("#td_idext").show(); 
			$("#td_pais").show();
			$("#cep").hide();
		}else{
			$("#td_idext").hide(); 
		}
	}

	function reloadframe(tipo = null){
		if(tipo==null) location.reload();

		return;

		var r = confirm("Nota emitida com sucesso!\nPressione 'OK' para emitir uma nova nota\nPressione 'Cancelar' para atualizar");
		if (r == true) {
			if(tipo!=null) $("#numero").val(tipo);
			$("#botaoSalvarNotaFiscal").removeAttr("disabled");
			$("#botaoContinuarNotaFiscal").hide();
		} else {
			location.reload();
		}
	}

	
	var infoFormas = {
	"codigoFiscal": {
		"1": "01 - Dinheiro",
		"2": "02 - Cheque",
		"3": "03 - Cartão de Crédito",
		"4": "04 - Cartão de Débito",
		"5": "05 - Crédito Loja",
		"10": "10 - Vale Alimentação",
		"11": "11 - Vale Refeição",
		"12": "12 - Vale Presente",
		"13": "13 - Vale Combustível",
		"15": "15 - Boleto Bancário",
		"16": "16 - Depósito Bancário",
		"17": "17 - Pagamento Instantâneo (PIX)",
		"18": "18 - Transferência bancária, Carteira Digital",
		"19": "19 - Programa de fidelidade, Cashback, Crédito Virtual",
		"90": "90 - Sem pagamento",
		"99": "99 - Outros" // precisa especificar 
	},
	"tpIntegra": {
		"1": "TEF",
		"2": "POS"
	},
	"tband": {
		"1": "Visa",
		"2": "Mastercard",
		"3": "American Express",
		"4": "Sorocred",
		"5": "Diners Club",
		"6": "Elo",
		"7": "Hipercard",
		"8": "Aura",
		"9": "Cabal",
		"99": "Outros"
	}
	};

	function AddFormasPagamento(id){

		$.each(infoFormas["codigoFiscal"], function(i, field){
			if(i>1 && i<10) i = "0"+ i; 
			$("#tpag_" + id).append("<option value='"+ i +"' >" + field + "</option>");
		});

	}

	
	function AddBandeirasPagamento(id){

		$.each(infoFormas["tband"], function(i, field){
			$("#tband_" + id).append("<option value='"+ i +"' >" + field + "</option>");
		});

	}

	 function CambioPagamento(id){

		var v = $("#tpag_" + id).val();
		if(v == "03" || v == "04"){	
			$(".cartao_" + id).remove();
			//$("#dadosPagamento_" + id).append('<div class="linha_form wh15 margin_right cartao_'+id+'"><label class="titulo_input">CNPJ credenciadora Cartão</label><input type="text" class="input_text" name="cnpjCredenciadora['+id+']"></div><div class="linha_form wh15 margin_right cartao_'+id+'"><label class="titulo_input">Bandeira do Cartão</label><select class="input_text" id="tband_'+id+'" name="tband['+id+']"></select></div><div class="linha_form wh15 cartao_'+id+'"><label class="titulo_input">Número Autorização</label><input type="text" class="input_text" name="cAut['+id+']"></div>');
			//AddBandeirasPagamento(id);
		}else{
			$(".cartao_" + id).remove();
		}

		TotaisValoresNota();

	 }

	 function TotaisValoresNota(){
		 var total = 0;
		 var totalpro = 0;

		$(".vpagamento").each(function(i, field){
			total += parseFloat(RealToDolar($(field).val()));
		});

		$(".precototal").each(function(i, field){
			totalpro += parseFloat(RealToDolar($(field).val()));
		});
		
		var vseguro =  parseFloat(RealToDolar($("#seguro").val()));
		var vfrete =  parseFloat(RealToDolar($("#frete").val()));
		var voutrasDespesas =  parseFloat(RealToDolar($("#outrasDespesas").val()));
		var vdesconto =  parseFloat(RealToDolar($("#desconto").val()));
			 
		$("#totalpagamentos").val(DolarToReal(total));
		$("#valorProdutos").val(DolarToReal(totalpro));
		$("#totaltroco").val(DolarToReal( parseFloat( total - ( (totalpro + vseguro + vfrete + voutrasDespesas) - vdesconto ))));
		$("#valorNota").val( DolarToReal( parseFloat((totalpro + vseguro + vfrete + voutrasDespesas) - vdesconto )));

	}
	 

	$(document).ready(function() {
	
		var idpag = 1;

		$("#btnPagamento").on("click", function(){
		
			var $counts = parseInt($(".pagdivs").length);
			$counts = $counts + 1;
			$("#pagamentoDivs").append('<div id="dadosPagamento_'+idpag+'" class="linha_form wh100 pagdivs"><div class="linha_form wh1 margin_right" style="font-size: 10px;color: #888;">'+$counts+'</div><div class="linha_form wh19 margin_right"><input type="hidden" name="idpag['+idpag+']" value="'+idpag+'"><label class="titulo_input">Forma de Pagamento*</label><select onchange="CambioPagamento('+idpag+')" class="tipopagamento input_text" required="true" id="tpag_'+idpag+'" name="tpag['+idpag+']"></select></div><div class="linha_form wh19"><label class="titulo_input">Valor*</label><input onchange="TotaisValoresNota()" class="input_text money vpagamento" required="true" style="width:95%" type="text" id="vpag_'+idpag+'" name="vpag['+idpag+']"></div><div class="linha_form margin_right" style="width:20px;padding-top: 22px;"><a title="Remover este pagamento" class="tableIcon" onclick="deletarPagamento('+idpag+');"><i class="icon-trash"></i></a></div></div>');
		
			AddFormasPagamento(idpag);
			idpag++;
		});



		var idvenci = 1;

		$("#btnVencimento").on("click", function(){

			var $counts = parseInt($(".vencdivs").length);
			$counts = $counts + 1;
			$("#vencimentoDivs").append('<div id="dadosVencimento_'+idvenci+'" class="linha_form wh100 vencdivs"><div class="linha_form wh1 margin_right" style="font-size: 10px;color: #888;">'+$counts+'</div><div class="linha_form wh19 margin_right"><input type="hidden" name="idvenci['+idvenci+']" value="'+idvenci+'"><label class="titulo_input">Data</label><input class="input_text" required="true" style="width:95%" type="date" id="datavenci_'+idvenci+'" name="datavenci['+idvenci+']"></div><div class="linha_form wh19"><label class="titulo_input">Valor*</label><input class="input_text money vvencimentos" onchange="TotaisVencimentos()" required="true" style="width:95%" type="text" id="vvenci_'+idvenci+'" name="vvenci['+idvenci+']"></div><div class="linha_form margin_right" style="width:20px;padding-top: 22px;"><a title="Remover este vencimento" class="tableIcon" onclick="deletarVencimento('+idvenci+');"><i class="icon-trash"></i></a></div></div>');
			idvenci++;
		});
	
	});

	function TotaisVencimentos(){
		var total = 0;

		$(".vvencimentos").each(function(i, field){
			total += parseFloat(RealToDolar($(field).val()));
		});

		$("#totalvencimentos").val(DolarToReal(total));

	}

	function deletarPagamento(id){
		$("#dadosPagamento_" + id).remove();
		TotaisValoresNota();
	}

	function deletarVencimento(id){
		$("#dadosVencimento_" + id).remove();
	}

	function selecionarProduto(id){

		$("#resultados_buscarProdutos").hide();
		$.getJSON("../products/getProductbyID?id=" + id, function(dados){
			$("#tItensNota").append('<tr id="linhaItem_'+ dados.id +'"><td><input type="hidden" id="produtoId_'+ dados.id +'" name="produtoId[]" value="'+ dados.id +'"><input class="input_text editgridh pprodutonome" type="text" id="produto_'+ dados.id +'" name="produto[]" maxlength="120" value="'+ dados.name +'" autocomplete="off"></td><td><input class="input_text editgridh" type="text" id="codigo_'+ dados.id +'" value="'+ dados.code +'" name="codigo[]"></td><td><input class="input_text editgridh" required="true" type="text" name="un[]" id="un" value="'+ dados.unit +'" maxlength="2"></td><td><input class="input_text editgridh quantidadeinput" style="background: #fff8ba;" required="true" value="" id="quantidade_'+ dados.id +'" name="quantidade[]" type="text" onchange="alterarItemProdutos('+dados.id+');"></td><td> <input required="true" class="input_text editgridh money_" id="precounitario_'+ dados.id +'" name="precounitario[]" value="'+ DolarToReal(dados.price) +'" type="text" onchange="alterarItemProdutos('+dados.id+');"></td><td><input class="input_text editgridh precototal" id="precototal_'+ dados.id +'" name="precototal[]" readonly type="text" required="true" onchange="alterarItemProdutos('+dados.id+');"></td><td><input class="input_text editgridh" id="ncm_'+ dados.id +'"  value="'+ dados.ncm+'" name="ncm[]" type="text" maxlength="10"></td><td class="center editgridh"><!--<a style="float: right;" title="Editar componentes do produto" class="tableIcon tip" onclick="editarProduto('+ dados.id +');"><i class="icon-pencil"></i></a>--><a style="float: none;" title="Complemento de informação do produto" class="tableIcon tip" id="ADDcompleinfoProdutoBTN_'+ dados.id +'" onclick="ADDcompleinfoProduto('+ dados.id +');"><i class="icon-comment"></i></a>  <a style="float: none;" title="Remover item do pedido" class="tableIcon tip" onclick="deletarProduto('+ dados.id +');"><i class="icon-trash"></i></a></td></tr>');
		});

		alterarItemProdutos(id);

	}

	function deletarProduto(id){
		$("#linhaItem_" + id).remove();
		$("#compleItem_" + id).remove();
		TotaisValoresNota();
	}

	function ADDcompleinfoProduto(id){
		$("#linhaItem_" + id).after('<tr id="compleItem_'+ id +'"><td><table><input class="input_text editgridh" type="text" placeholder="Complemento da descrição" name="informacoes_adicionais[]" maxlength="490" value="" autocomplete="off"></table></td></table>');
		$("#ADDcompleinfoProdutoBTN_" + id).hide();
	}

	function editarProduto(id){
		$.colorbox({url: "", width:"80%", height:"80%" });
	}

	function alterarItemProdutos(id){
		var q = $('#quantidade_'+ id).val();
		if(q!=""){
			const regex = /[^0-9|,|.]+/g;
			const subst = ``;
			q = q.replace(regex, subst);
			$('#quantidade_'+ id).val(q);
		}

		q = RealToDolar(q);
		if(q!="" || q!=0){
			$("#precototal_" + id).val(DolarToReal(RealToDolar($("#precounitario_" + id).val()) * q));
		} 

		TotaisValoresNota();
	}

	function BuscarReceita(){
		$.colorbox({iframe:true, href: "<?=base_url();?>/lib-local/buscarCnpjform.php?cnpj="+$("#cnpj").val(), width:"300px", height:"350px" });
	}

	function ResultadosReceita(dados, tipo = 1){
		
				dados2 = JSON.parse(dados);
				console.log(dados2);
				// situacao_cadastral : "ATIVA"

				$("#resultados_clientes").hide();
				if(tipo == 1){
					$("#contato").val(dados2.razao_social);
				}else{
					$("#contato").val(dados2.nome);
				}
				
				$("#endereco").val(dados2.logradouro);
				$("#enderecoNro").val(dados2.numero);
				$("#bairro").val(dados2.bairro);
				if(tipo == 1){
					$("#nomeMunicipio").val(dados2.cidade);
				}else{
					$("#nomeMunicipio").val(dados2.municipio);
				}
				$('#uf').val(dados2.uf);
				GetMunicipio(dados2.uf, '#municipio');
				//if(dados2.codigocidade!=null && dados2.codigocidade!="") $('#municipio').val(dados2.codigocidade); 
				$("#cep").val(dados2.cep);
				$("#idCnpj").val(dados2.cnpj);
				$("#email").val(dados2.email);	
				$("#cnpj").val(dados2.cnpj);	
				$("#fone").val(dados2.telefone);	
				if(dados2.complemento != "********"){ $("#complemento").val(dados2.complemento); }

				if(tipo == 1){
					$("#municipio option:contains(" + dados2.cidade_cap + ")").attr('selected', 'selected');
					if($("#municipio").val()==""){
						$("#municipio option[semacento^='"+dados2.cidade_cap+"']").prop('selected', true);
					}
				}else{
					$("#municipio option:contains(" + dados2.municipio + ")").attr('selected', 'selected');
					if($("#municipio").val()==""){
						$("#municipio option[semacento^='"+dados2.municipio+"']").prop('selected', true);
					}
				}

				//$( "#municipio option[name^='" + dados2.cidade_cap + "']" ).prop('selected', true);
				$('#tipoPessoa option[value="J"]').prop('selected', true);
				$('#indFinal').iCheck('uncheck');
				ajustarFormContatoRapido("J");
				if($("#municipio").val()!=""){
					selecionarMunicipio();
				}
		
	}

	function DolarToReal(numero = "", decimal = 2) {
		if(numero=="" || numero == null) return 0;
		return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR', minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
	}

	function RealToDolar(atual, tipo = 0){
		if(atual != undefined && atual != ""  && atual != 0){
			try {
				atual = atual.replace(".", "");
				atual = atual.replace(",", ".");
			} catch (err) {}
			return atual;
		}else{
			return 0;
		}
	}

	function AddCostumer(acc){
		if(acc==1){
			$("#add-costumers").colorbox({inline:true, width:"80%", height:"80%" });
		}else{
			$.colorbox.close();
		}
	}

	function saveasNewCliente(){

		if($('#tipoPessoa').val() == "F") var tipo_cad = 1;
		if($('#tipoPessoa').val() == "J") var tipo_cad = 2;
		if($('#tipoPessoa').val() == "E") var tipo_cad = 3;

        $.ajax({
            type: "get",
            url: '../customers/add_ajax',
            data: { 
				name : $("#contato").val(),
				email: $("#email").val(),	
				phone: $("#fone").val(),
				cf1: $("#cnpj").val(),	
				cf2: $("#ie").val(),
				endereco: $("#endereco").val(),
				cep: $("#cep").val(),
				numero: $("#enderecoNro").val(),
				complemento: $("#complemento").val(),
				cidade: $("#nomeMunicipio").val(),
				codigocidade: $("#municipio").val(),
				estado: $('#uf').val(),
				codigoestado: "",
				pais: $('#pais').val(),
				bairro: $("#bairro").val(),
				tipo_cad: tipo_cad
			},
            dataType: "json",
			async: false,
            success: function(res) {
                if(res.status == 'success') {
                   $("#cliente-anadido").show();
                } else {
                    $('#c-alert').html(res.msg);
                    $('#c-alert').show();
                }
            },
            error: function(){
                bootbox.alert(lang.customer_request_failed);
                return false;
            }
        });
        return false;
	}
	
	function selecionarCliente(id){
			$("#resultados_clientes").hide();
			$.getJSON("../customers/getCostumersbyID?id=" + id, function(dados2){
				$("#idContato").val(id);
				$("#ie").val(dados2.cf2);
				$("#contato").val(dados2.name);
				$("#endereco").val(dados2.endereco);
				$("#enderecoNro").val(dados2.numero);
				$("#bairro").val(dados2.bairro);
				$("#nomeMunicipio").val(dados2.cidade);
				$('#uf').val(dados2.estado);
				GetMunicipio(dados2.estado, '#municipio', null, dados2.codigocidade);
				if(dados2.codigocidade!=null && dados2.codigocidade!="" && typeof dados2.codigocidade !== "undefined") $('#idMunicipio').val(dados2.codigocidade); 

				$("#cep").val(dados2.cep);
				$("#idCnpj").val(dados2.cf1);
				$("#email").val(dados2.email);	
				$("#cnpj").val(dados2.cf1);	
				$("#fone").val(dados2.phone);	
				$("#complemento").val(dados2.complemento);	
				if(dados2.tipo_cad == 1 || dados2.tipo_cad == 3){
					$('#indIEDest option[value="9"]').prop('selected', true);
				}else if((dados2.cf2 == "0" || dados2.cf2 == 0) && (dados2.tipo_cad == 2)){
					$('#indIEDest option[value="9"]').prop('selected', true);
					
				}else if((dados2.cf2 == "ISENTO" || dados2.cf2 == "isento") && (dados2.tipo_cad == 2)){
					$('#indIEDest option[value="2"]').prop('selected', true);
					
				}else{
					$('#indIEDest option[value="1"]').prop('selected', true);
				}

				if(dados2.tipo_cad == 1){
					$('#tipoPessoa option[value="F"]').prop('selected', true);
					$('#indFinal').iCheck('check');
				}else if(dados2.tipo_cad == 2){
					$('#tipoPessoa option[value="J"]').prop('selected', true);
					$('#indFinal').iCheck('uncheck');
				}else if(dados2.tipo_cad == 3){
					$('#tipoPessoa option[value="E"]').prop('selected', true);
					$('#indFinal').iCheck('uncheck');
					$('#td_idext').show();
				}
				ajustarFormContatoRapido($('#tipoPessoa').val());

			});
	}


	function selecionarMunicipio(){
		$("#idMunicipio").val($( "#municipio" ).val());
		$("#nomeMunicipio").val($( "#municipio option:selected" ).text());
	}

	function GetMunicipio($select, $cidades_div, $pais = null, $selecionar = null){

		if($select == 'EX'){
			if($pais!=null) $($pais).show();
			$($cidades_div).html("");
			$($cidades_div).append($('<option>').text("EXTERIOR").attr('value', "EX"));
		}else if($select == ''){

			$($cidades_div).html("");
			
		}else{
			if($pais!=null) $($pais).hide();
			$($cidades_div).html("");

			// Buscar Ajax das cidades
			$.ajax({ 
				url: '<?=site_url()?>/lib-local/getMunicipios.php?uf=' + $select, 
			dataType: 'json', 
			data: "", 
			async: false, 
			success: function(result){ 
				$.each(result, function(i, value) {
					if($selecionar!=null && value['codigo']==$selecionar){
						$($cidades_div).append($('<option>').text(value['nome']).attr('value', value['codigo']).attr('semacento', retira_acentos(value['nome'])).attr('selected', 'selected'));	
					}else{
						$($cidades_div).append($('<option>').text(value['nome']).attr('value', value['codigo']).attr('semacento', retira_acentos(value['nome'])));
					}
					
				});

				if($selecionar!=null){
					setTimeout(() => {
						selecionarMunicipio();
					}, 2000);
				}
			}
			});

			
		}

		

	}


	function retira_acentos(str) 
	{

		var com_acento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝŔÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿŕ";
		var sem_acento = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYRsBaaaaaaaceeeeiiiionoooooouuuuybyr";

		var novastr="";
		for(i=0; i<str.length; i++) {
			var troca=false;
			for (a=0; a<com_acento.length; a++) {
				if (str.substr(i,1)==com_acento.substr(a,1)) {
					novastr+=sem_acento.substr(a,1);
					troca=true;
					break;
				}
			}
			if (troca==false) {
				novastr+=str.substr(i,1);
			}
		}
		return novastr;
	}     

	function EnvioNFForm(){

		var $va = true;
		var $faltapro = false;
		var $faltapag = false;
		var $faltaref = false;
		var $errtiponota = false;
		$("[required=true]").removeClass("invalidoborder");
		$("[required=required]").removeClass("invalidoborder");
		$("#nfe_referenciada").removeClass("invalidoborder");

		$("[required=true]").each(function(){
			if($(this).val()=="" && $(this).is(":visible")==true){ 
				$(this).addClass("invalidoborder");
				$va = false; 
			}
		});

		$("[required=required]").each(function(){
			if($(this).val()=="" && $(this).is(":visible")==true){ 
				$(this).addClass("invalidoborder");
				$va = false; 
			}
		});

		if(parseInt($(".pprodutonome").length) < 1){
			$va = false;
			$faltapro = true;
		}

		if(parseInt($(".tipopagamento").length) < 1){
			$va = false;
			$faltapag = true;
		}

		if(parseInt($("#finalidade").val()) == 4 && $("#nfe_referenciada").val()==""){
			$va = false;
			$faltaref = true;
			$("#nfe_referenciada").addClass("invalidoborder");
		}

		if(parseInt($("#finalidade").val()) == 4 && $("#notaTipo").val()=="1"){
			//$va = false;
			//$errtiponota = true;
			//$("#notaTipo").addClass("invalidoborder");
		}

		
		if($va == true){

			$("#botaoSalvarNotaFiscal").attr("disabled", "disabled");
			$("#botaoContinuarNotaFiscal").show();

			var $addenvio = "";
			var w = window.open('about:blank', 'popupEnvio', 'toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1100,height=700');
			w.document.write('<h2>Enviando nota fiscal, por favor, aguarde...</h2>');

            return true;
			
		} else{

			alert("Verifique todos os campos obrigatórios antes de emitir a nota");
			
			if($faltapag){
				alert("Nenhum pagamento foi adicionado");
			}

			if($faltapro){
				alert("Nenhum produto foi adicionado");
			}
			
			if($faltaref){
				alert("Para notas de devolução, deve referenciar a NF-e (usando a chave)");
			}

			if($faltaref){
				alert("Devolução, o tipo de nota deve ser Entrada");
			}
				
		}
		return false;
		
		}

		function ReloadPage(){

		}

	$(document).ready(function() {

		$("form").on("submit", function(e){
			//e.preventDefault();ç
		});

		$("body").click(function(){
			$("#resultados_clientes").hide();
		});

		$("[required=true]").change(function(){
			if($(this).val()==""){ 
				$(this).addClass("invalidoborder");
			}else{
				$(this).removeClass("invalidoborder");
			}
		});

		$('.date').mask('00/00/0000');
		$('.time').mask('00:00:00');
		$('#cep').mask('00000-000');
		$('.phone').mask('0000-0000');
		$('.phone_with_ddd').mask('(00) 0000-0000');
		$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.cnpj').mask('00.000.000/0000-00', {reverse: true});
		$('.money').mask('000.000.000.000.000,00', {reverse: true});
		$('.money2').mask("#.##0,00", {reverse: true});
		$('.money3').mask("#.###0,000", {reverse: true});


		$("#btnBuscarContato, #contato").on("keyup click", function(){
			if($("#contato").val()!=""){
				$.getJSON("../customers/suggestions?term=" + $("#contato").val(), function(result){
					$("#resultados_clientes").html("");
					var $inval = false;

						$.each(result, function(i, field){
							if(field["id"]!=0){
								$("#resultados_clientes").append("<span style='width:100%;cursor:pointer;border-bottom:1px solid #999;float:left;padding:5px 0px' onclick='selecionarCliente("+ field["id"] + ")'>"+field["name"]  + " - "+field["cf1"] + "</span><br>");
								$inval = false;
							}else{
								$inval = true ;
							}
						});
						if(!$inval){
							$("#resultados_clientes").show();
						}else{
							$("#resultados_clientes").hide();
						}
						
							
				});
			}else{
				$("#resultados_clientes").hide();
			}
		});

		$("#buscarProdutos").on("keyup click", function(){
			if($("#buscarProdutos").val()!=""){
				$.getJSON("../products/suggestions_nfe?term=" + $("#buscarProdutos").val(), function(result){
					$("#resultados_buscarProdutos").html("");
					$.each(result, function(i, field){
						$("#resultados_buscarProdutos").append("<span style='width:100%;cursor:pointer;border-bottom:1px solid #999;float:left;padding:5px 0px' onclick='selecionarProduto("+ field["id"] + ")'>"+field["name"]  + " - "+field["code"] + "</span><br>");
					});
					$("#resultados_buscarProdutos").show();
				});
			}else{
				$("#resultados_buscarProdutos").hide();
			}
		});

		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
		$(".inline").colorbox({inline:true, width:"80%", height:"80%" });

		if ($("#possui_restricao_nfe").val() != "S") {
			$("#natureza").change(function(){
				testCompleter($(this), $("#idTipoNota"), "Natureza de operação não encontrada no sistema");
			});
		}

		$('.date-pick').datepicker({
			startDate: '01/01/2020'
		});

		$("#botaoContinuarNotaFiscal").on("click", function(){
			$("#botaoSalvarNotaFiscal").removeAttr("disabled");
			$("#botaoContinuarNotaFiscal").hide();
		});

		$("#tipoPessoa").on("change", function(){
			var v = $(this).val();
			$("#cep").show();
		});


		

	});

</script>
</body>
</html>