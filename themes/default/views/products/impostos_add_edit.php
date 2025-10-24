<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?= $assets ?>creador_notas_files/jquery.mask.min-1541517054.js"></script>

<script type='text/javascript'>
$(document).ready(function() {
	
$('#NCST').val("102").trigger("change");
$('#QCST').val("08").trigger("change");
$('#SCST').val("08").trigger("change");

$('.readonlyField').parent("tr").css("display", "none!important");

$('body').find('#meuU2UF').val('<?=$Settings->estado?>').trigger('change');
$('body').find('#UcMun').val('<?=$Settings->ccidade?>').trigger('change');

$('.percent, [especialtype="percent"]').mask('##0,00%', {reverse: true});
$('.money').mask('000.000.000.000.000,00', {reverse: true});
$('.money2').mask("#.##0,00", {reverse: true});

$('#meuCtrltSTCheck').on('ifChecked', function(event){
$("#quadroCtrltST").show();
});

$('#meuCtrltSTCheck').on('ifUnchecked', function(event){
$("#quadroCtrltST").hide();
});

$('#meuNVECheck').on('ifChecked', function(event){
$("#quadroNVE").show();
});

$('#meuNVECheck').on('ifUnchecked', function(event){
$("#quadroNVE").hide();
});

$('#meuUsarImp').on('ifChecked', function(event){
$("#quadroProdII").show();
});

$('#meuUsarImp').on('ifUnchecked', function(event){
$("#quadroProdII").hide();
});

$('#meuProdExport').on('ifChecked', function(event){
$("#quadroProdExport").show();
});

$('#meuProdExport').on('ifUnchecked', function(event){
$("#quadroProdExport").hide();
});


$('#UindISS').on('change', function(){
var v = $(this).val();
if(v == 6 || v == 7){
$("#UnProcesso_rcpt").show();
}else{
$("#UnProcesso_rcpt").hide();
}
});

// IPI
$('#OCST').on('change', function(){
var v = $(this).val();
if(v == -1){

$("#OclEnq_rcpt").hide();
$("#OCNPJProd_rcpt").hide();
$("#OcSelo_rcpt").hide();
$("#OqSelo_rcpt").hide();
$("#OcEnq_rcpt").hide();
$("#OTipoCalc_rcpt").hide();
$("#OvBC_rcpt").hide();
$("#OpIPI_rcpt").hide();
$("#OvUnid_rcpt").hide();
$("#OvIPI_rcpt").hide();

}else if(v == '00' || v == '49'  || v == '50'  || v == '99'){

$("#OclEnq_rcpt").show();
$("#OCNPJProd_rcpt").show();
$("#OcSelo_rcpt").show();
$("#OqSelo_rcpt").show();
$("#OcEnq_rcpt").show();
$("#OTipoCalc_rcpt").show();
$("#OvBC_rcpt").show();
$("#OpIPI_rcpt").show();
$("#OvUnid_rcpt").hide();
$("#OvIPI_rcpt").show();

}else if(v == '01' || v == '02' || v == '03' || v == '04' || v == '05' || v == '51' || v == '52' || v == '53' || v == '54' || v == '55'){

$("#OclEnq_rcpt").show();
$("#OCNPJProd_rcpt").show();
$("#OcSelo_rcpt").show();
$("#OqSelo_rcpt").show();
$("#OcEnq_rcpt").show();
$("#OTipoCalc_rcpt").hide();
$("#OvBC_rcpt").hide();
$("#OpIPI_rcpt").hide();
$("#OvUnid_rcpt").hide();
$("#OvIPI_rcpt").hide();

}

});

// ICMS
$('#NCST').on('change', function(){
	var v = $(this).val();

	$("#tableicms").find("tr").find("td").each(function (){
		$(this).hide();
	});
	
	$(this).parent("td").show();

	if(v == '00'){ 

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();
	$("#undefined").show();

	}else if(v == '10'){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NmotDesICMSST_rcpt").show();
	$("#NvICMSSTDeson_rcpt").show();
	$("#NvBCFCP_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	}else if(v == 20){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NvBCFCP_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();

	}else if(v == 30){

	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	}else if(v == '40'){

	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#undefined").show();

	}else if(v == '41'){

	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NpFCP_rcpt").show();

	}else if(v == '50'){

	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();

	}else if(v == '51'){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NvICMSOp_rcpt").show();
	$("#NpDif_rcpt").show();
	$("#NvICMSDif_rcpt").show();
	$("#descrICMSDif").show();
	$("#NvBCFCP_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();
	$("#NvFCPEfet_rcpt").show();
	$("#NpFCPDif_rcpt").show();
	$("#NvFCPDif_rcpt").show();

	}else if(v == '60'){

	$("#Norig_rcpt").show();
	$("#NvBCSTRet_rcpt").show();
	$("#NpST_rcpt").show();
	$("#NvICMSSubstituto_rcpt").show();
	$("#NvICMSSTRet_rcpt").show();
	$("#NvBCFCPSTRet_rcpt").show();
	$("#NpFCPSTRet_rcpt").show();
	$("#NvFCPSTRet_rcpt").show();
	$("#meuICMSEfetCheck_rcpt").parent("td").show();

	}else if(v == '70'){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NmotDesICMSST_rcpt").show();
	$("#NvICMSSTDeson_rcpt").show();
	$("#NvBCFCP_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	}else if(v == 90){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NmotDesICMS_rcpt").show();
	$("#NvICMSDeson_rcpt").show();
	$("#NmotDesICMSST_rcpt").show();
	$("#NvICMSSTDeson_rcpt").show();
	$("#NvBCFCP_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	}else if(v == '10Part'){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NpBCOp_rcpt").show();
	$("#NUFST_rcpt").show();

	}else if(v == '90Part'){

	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NpBCOp_rcpt").show();
	$("#NUFST_rcpt").show();

	}else if(v == '41ST'){

	$("#Norig_rcpt").show();
	$("#NvBCSTRet_rcpt").show();
	$("#NpST_rcpt").show();
	$("#NvICMSSubstituto_rcpt").show();
	$("#NvICMSSTRet_rcpt").show();
	$("#NvBCSTDest_rcpt").show();
	$("#NvICMSSTDest_rcpt").show();
	$("#NvBCFCPSTRet_rcpt").show();
	$("#NpFCPSTRet_rcpt").show();
	$("#NvFCPSTRet_rcpt").show();

	}else if(v == '60ST'){

	$("#Norig_rcpt").show();
	$("#NvBCSTRet_rcpt").show();
	$("#NpST_rcpt").show();
	$("#NvICMSSubstituto_rcpt").show();
	$("#NvICMSSTRet_rcpt").show();
	$("#NvBCSTDest_rcpt").show();
	$("#NvICMSSTDest_rcpt").show();
	$("#NvBCFCPSTRet_rcpt").show();
	$("#NpFCPSTRet_rcpt").show();
	$("#NvFCPSTRet_rcpt").show();

	}else if(v == '101'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();
	$("#NpCredSN_rcpt").show();
	$("#NvCredICMSSN_rcpt").show();

	} else if(v == '102' || v == '103' || v == '300' || v == '400'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();

	} else if(v == '201'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NpCredSN_rcpt").show();
	$("#NvCredICMSSN_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	} else if(v == '202' || v == '203'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NpFCP_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();
	$("#NvFCPST_rcpt").show();

	} else if(v == '500'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();
	$("#NvBCSTRet_rcpt").show();
	$("#NpST_rcpt").show();
	$("#NvICMSSubstituto_rcpt").show();
	$("#NvICMSSTRet_rcpt").show();
	$("#NvBCFCPSTRet_rcpt").show();
	$("#NpFCPSTRet_rcpt").show();
	$("#NvFCPSTRet_rcpt").show();


	} else if(v == '900'){

	$("#NCST_rcpt").show();
	$("#Norig_rcpt").show();
	$("#NmodBC_rcpt").show();
	$("#NpRedBC_rcpt").show();
	$("#NvBC_rcpt").show();
	$("#NpICMS_rcpt").show();
	$("#NvICMS_rcpt").show();
	$("#NpCredSN_rcpt").show();
	$("#NvCredICMSSN_rcpt").show();
	$("#NmodBCST_rcpt").show();
	$("#NpMVAST_rcpt").show();
	$("#meuPautaST_rcpt").show();
	$("#NpRedBCST_rcpt").show();
	$("#NvBCST_rcpt").show();
	$("#NpICMSST_rcpt").show();
	$("#NvICMSST_rcpt").show();
	$("#NvBCFCPST_rcpt").show();
	$("#NpFCPST_rcpt").show();

	}

	$('#NmotDesICMS').val("0").trigger("change");
	$('#NmotDesICMSST').val("0").trigger("change");

});


$('#meuICMSEfetCheck_rcpt').on('ifChecked', function(event){
	$("#NpRedBCEfet_rcpt").show();
	$("#NvBCEfet_rcpt").show();
	$("#NpICMSEfet_rcpt").show();
	$("NvICMSEfet").show();
});
$('#meuICMSEfetCheck_rcpt').on('ifUnchecked', function(event){
	$("#NpRedBCEfet_rcpt").hide();
	$("#NvBCEfet_rcpt").hide();
	$("#NpICMSEfet_rcpt").hide();
	$("NvICMSEfet").hide();
});


//PIS
$('#QCST').on('change', function(){

	$("#tablepis").find("tr").find("td").each(function (){
		$(this).hide();
	});

	$(this).parent("td").show();

	var v = $(this).val();

	if(v == '01' || v == '02'){

	$("#QvBC_rcpt").show();
	$("#QpPIS_rcpt").show();
	$("#QvPIS_rcpt").show();
	$("#RTipoCalc_rcpt").show();
	$("#RvBC_rcpt").show();
	$("#RpPIS_rcpt").show();
	$("#RvPIS_rcpt").show();

	}else if(v == '02'){

	$("#QvAliqProd_rcpt").show();
	$("#QvPIS_rcpt").show();
	$("#RTipoCalc_rcpt").show();
	$("#RvBC_rcpt").show();
	$("#RpPIS_rcpt").show();
	$("#RvPIS_rcpt").show();

	}else if(v == '04'){

	$("#RTipoCalc_rcpt").show();
	$("#RvBC_rcpt").show();
	$("#RpPIS_rcpt").show();
	$("#RvPIS_rcpt").show();

	}else if(v == '05'){

	$("#QTipoCalc_rcpt").show();
	$("#QvBC_rcpt").show();
	$("#QpPIS_rcpt").show();
	$("#QvPIS_rcpt").show();
	$("#RTipoCalc_rcpt").show();
	$("#RvBC_rcpt").show();
	$("#RpPIS_rcpt").show();
	$("#RvPIS_rcpt").show();

	}else if(v == 06 || v == '07' || v == '08' || v == '09'){

	$("#RTipoCalc_rcpt").show();
	$("#RvBC_rcpt").show();
	$("#RpPIS_rcpt").show();
	$("#RvPIS_rcpt").show();

	}else if(v == '49' || v == '50' || v == '51' || v == '52' || v == '53' || v == '54' || v == '55' || v == '56' || v == '60'  || v == '61' || v == '62' || v == '63' || v == '64' || v == '65' || v == '66' || v =='67'  || v =='70' || v == '71' || v == '72' || v == '73' || v == '74' || v == '75' || v == '98' || v == '99' ){

	$("#QTipoCalc_rcpt").show();
	$("#QvBC_rcpt").show();
	$("#QpPIS_rcpt").show();
	$("#QvPIS_rcpt").show();
	$("#RTipoCalc_rcpt").show();
	}

	$('#RTipoCalc').val("0").trigger("change");
});

// PIS CALCULO PERSONALIZAO Tipo de cálculo Subst. Trib.
$('#RTipoCalc').on('change', function(){
var v = $(this).val();
if(v == '0'){

$('#RvBC_rcpt ').hide();
$('#RpPIS_rcpt').hide();
$('#RvAliqProd_rcpt').hide();
$('#RvPIS_rcpt').hide();

}else{

$('#RvBC_rcpt ').show();
$('#RpPIS_rcpt').show();
$('#RvAliqProd_rcpt').hide();
$('#RvPIS_rcpt').show();

}

});

// COFINS
$('#SCST').on('change', function(){

	$("#tablecofins").find("tr").find("td").each(function (){
		$(this).hide();
	});
	
	$(this).parent("td").show();

	var v = $(this).val();

	if(v == '01' || v == '02'){

	$("#SvBC_rcpt").show();
	$("#SpCOFINS_rcpt").show();
	$("#SvCOFINS_rcpt").show();
	$("#TTipoCalc_rcpt").show();
	$("#TvBC_rcpt").show();
	$("#TpCOFINS_rcpt").show();
	$("#TvCOFINS_rcpt").show(); 

	}else if(v == '03'){

	$("#SpCOFINS_rcpt").show();
	$("#SvCOFINS_rcpt").show();
	$("#TTipoCalc_rcpt").show();
	$("#TvBC_rcpt").show();
	$("#TpCOFINS_rcpt").show();

	}else if(v == '04'){

	$("#TTipoCalc_rcpt").show();
	$("#TvBC_rcpt").show();
	$("#TpCOFINS_rcpt").show();
	$("#TvCOFINS_rcpt").show();

	}else if(v == '05'){

	$("#STipoCalc_rcpt").show();
	$("#SvBC_rcpt").show();
	$("#SpCOFINS_rcpt").show();
	$("#SvCOFINS_rcpt").show();
	$("#TTipoCalc_rcpt").show();
	$("#TvBC_rcpt").show();
	$("#TpCOFINS_rcpt").show();
	$("#TvCOFINS_rcpt").show();

	}else if(v == 06 || v == '07' || v == '08' || v == '09'){

	$("#TTipoCalc_rcpt").show();
	$("#TvBC_rcpt").show();
	$("#TpCOFINS_rcpt").show();
	$("#TvCOFINS_rcpt").show();

	}else if(v == '49' || v == '50' || v == '51' || v == '52' || v == '53' || v == '54' || v == '55' || v == '56' || v == '60' || v == '61' || v == '62' || v == '63' || v == '64' || v == '65' || v == '66' || v == '67' || v == '70' || v == '71'  || v =='72'  || v =='73' || v =='74' || v =='75' || v =='98' || v =='99'){

	$("#STipoCalc_rcpt").show();
	$("#SvBC_rcpt").show();
	$("#SpCOFINS_rcpt").show();
	$("#SvCOFINS_rcpt").show();
	$("#TTipoCalc_rcpt").show();

	}

	$('#TTipoCalc').val("0").trigger("change");
});

// PERSONALIZADO
$('#TTipoCalc').on('change', function(){
var v = $(this).val();
if(v == '0'){

$('#TvBC_rcpt').hide();
$('#TpCOFINS_rcpt').hide();
$('#TvAliqProd_rcpt').hide();
$('#TvCOFINS_rcpt').hide();

}else{

$('#TvBC_rcpt').show();
$('#TpCOFINS_rcpt').show();
$('#TvAliqProd_rcpt').hide();
$('#TvCOFINS_rcpt').show();

}

});


$('#meuUsarProdEspec').on('change', function(){
var v = $(this).val();
if(v == '0'){
$('#quadroVeiculos').hide();
$('#quadroCombustiveis').hide();
$('#quadroMedicamentos').hide();
$('#quadroArmamentos').hide();
$('#quadroPapelImune').hide();
} else if(v == '1'){

$('#quadroVeiculos').show();
$('#quadroCombustiveis').hide();
$('#quadroMedicamentos').hide();
$('#quadroArmamentos').hide();
$('#quadroPapelImune').hide();

} else if(v == '2'){

$('#quadroVeiculos').hide();
$('#quadroCombustiveis').show();
$('#quadroMedicamentos').hide();
$('#quadroArmamentos').hide();
$('#quadroPapelImune').hide();

} else if(v == '3'){

$('#quadroVeiculos').hide();
$('#quadroCombustiveis').hide();
$('#quadroMedicamentos').show();
$('#quadroArmamentos').hide();
$('#quadroPapelImune').hide();

} else if(v == '4'){

$('#quadroVeiculos').hide();
$('#quadroCombustiveis').hide();
$('#quadroMedicamentos').hide();
$('#quadroArmamentos').show();
$('#quadroPapelImune').hide();

} else if(v == '5'){

$('#quadroVeiculos').hide();
$('#quadroCombustiveis').hide();
$('#quadroMedicamentos').hide();
$('#quadroArmamentos').hide();
$('#quadroPapelImune').show();

}

});

$('#meuUUF').on('change', function(){
});

var $dadosexport = "{";
$('#savebtnimpostos').on('click', function(event){
event.preventDefault();
	var env = true;
	if($("#nomepp").val()==""){
		alert("Nenhum nome foi escolhido");
		env = false;
	}
	if(env === true){

		var $va = true; 
		$("[required=true]").removeClass("invalidoborder");
		$("[required=required]").removeClass("invalidoborder");

		$("[required=true]").each(function(){
			if($(this).val()==""){ 
				$(this).addClass("invalidoborder");
				$va = false; 
			}
		});

		$("[required=required]").each(function(){
			if($(this).val()==""){ 
				$(this).addClass("invalidoborder");
				$va = false; 
			}
		});
		
		if($va){


			var impostoArr = '"impostos":{';
			if($(".boxServico").css('display') != 'none'){

				// issqn
				impostoArr += '"issqn":{';
				var c = 1;
				$("#tableissqn tr").each(function(){
					$(this).find("td").each(function(){
						if($(this).css('display') != 'none'){
							impostoArr += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							c++;
						}
					});
				});
				if(c>1) impostoArr = impostoArr.slice(0, -1);
				impostoArr += '},';
				// issqn

			}else if($(".boxProduto").css('display') != 'none'){

				// IPI
				impostoArr += '"ipi":{';
				var c = 1;
				$("#tableipi tr").each(function(){
					$(this).find("td").each(function(){
						if($(this).css('display') != 'none'){
							impostoArr += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							c++;
						}
					});
				});
				if(c>1) impostoArr = impostoArr.slice(0, -1);
				impostoArr += '},';
				// IPI

				// ICMS
				impostoArr += '"icms":{';
				var c = 1;
				$("#tableicms tr").each(function(){
					$(this).find("td").each(function(){
						if($(this).css('display') != 'none'){
							impostoArr += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							c++;
						}
					});
				});
				if(c>1) impostoArr = impostoArr.slice(0, -1);
				impostoArr += '},';
				// ICMS

			}

			// PIS
			impostoArr += '"pis":{';
				var c = 1;
				$("#tablepis tr").each(function(){
					$(this).find("td").each(function(){
						if($(this).css('display') != 'none'){
							impostoArr += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							c++;
						}
					});
				});
				if(c>1) impostoArr = impostoArr.slice(0, -1);
				impostoArr += '},';
				// PIS

				// COFINS
				impostoArr += '"cofins":{';
				var c = 1;
				$("#tablecofins tr").each(function(){
					$(this).find("td").each(function(){
						if($(this).css('display') != 'none'){
							impostoArr += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							c++;
						}
					});
				});
				if(c>1) impostoArr = impostoArr.slice(0, -1);
				impostoArr += '}';
				// COFINS

				impostoArr += '}';
				$dadosexport += impostoArr;

				
				if($("#meuCtrltSTCheck").is(':checked') && $(".boxProduto").css('display') != 'none'){
					// ST
					$("#tableCtrltST tr").each(function(){
						$(this).find("td").each(function(){
							$dadosexport += ',"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '"';
						});
					});
					// ST
				}

				if($("#meuNVECheck").is(':checked') && $(".boxProduto").css('display') != 'none'){
					$dadosexport += ',"nve":"' + $("#INVE").val() + '"';
				}
				
				$dadosexport += ',"exportacao":{';
				if($("#meuProdExport").is(':checked')){
					var exp = "";
					var c = 1;
					// meuProdExport
					$("#tablemeuProdExport tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								//exp += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
								c++;
							}
						});
					});
					// meuProdExport		
					if(c>1) exp += exp.slice(0, -1);
					$dadosexport += exp;
				}
		
				$dadosexport += '}';
				$dadosexport += ',"importacao":{';

				if($("#meuUsarImp").is(':checked')){
					// #meuUsarImp
					var imp = "";
					var c = 1;
					$("#tablemeuUsarImp tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								imp += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
							}
						});
					});
					// #meuUsarImp	
					if(c>1) imp += imp.slice(0, -1);
					$dadosexport += imp;
				}
				$dadosexport += '}';

				var v = $('#meuUsarProdEspec').val();
				if(v == '0'){

				} else if(v == '1'){

					// veiculos
					var vei = ',"veiculos_novos":{';
					var c = 1;
					$("#tableveiculo tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								vei += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
								c++;
							}
						});
					});
					if(c>1) vei = vei.slice(0, -1);
					vei+= '}';
					// veiculos
					$dadosexport += vei;

				} else if(v == '2'){

					// combustiveis
					var com = ',"combustiveis":{';
					var c = 1;
					$("#tablecombustiveis tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								com += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
								c++;
							}
						});
					});
					if(c>1) com = com.slice(0, -1);
					com+= '}';
					// combustiveis
					$dadosexport += com;

				} else if(v == '3'){

				//$('#quadroMedicamentos').show();

				} else if(v == '4'){

				// armamentos
					var arm = ',"armamentos":{';
					var c = 1;
					$("#tablearmamentos tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								arm += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
								c++;
							}
						});
					});
					if(c>1) arm = arm.slice(0, -1);
					arm+= '}';
				// armamentos
					$dadosexport += arm;

				} else if(v == '5'){

				// papelimune
				var pap = ',"papelimune":{';
					var c = 1;
					$("#tablepapelimune tr").each(function(){
						$(this).find("td").each(function(){
							if($(this).css('display') != 'none'){
								pap += '"'+ $(this).find("input, select").attr("name") +'":"' + $(this).find("input, select").val() + '",';
								c++;
							}
						});
					});
					if(c>1) pap = pap.slice(0, -1);
					pap+= '}';
					// papelimune
					$dadosexport += pap;

				}
				$dadosexport += "}";

				<?php if($impostos->id!=""){ ?>
							
					$.get("<?php echo site_url('products/save_impostos'); ?>", 
					{ id: <?=$impostos->id;?>, nome: $("#nomepp").val(), regras: $dadosexport, tipo: $("#meuProdutoOuServico").val() })
					.done(function( data ) {
						data = JSON.parse(data);
						if(data["status"]==true){
							alert( "Salvo com sucesso!");
							window.location = "<?php echo site_url('products/impostos_add_edit/'.$impostos->id); ?>";
						}else{
							alert( "Erro ao salvar!" );
						}
					});

				<?php }else{ ?>

					$.get("<?php echo site_url('products/insert_impostos'); ?>", 
					{ nome: $("#nomepp").val(), regras: $dadosexport, tipo: $("#meuProdutoOuServico").val() })
					.done(function( data ) {
						data = JSON.parse(data);
						if(data["status"]==true){
							alert( "Gravado com sucesso!");
							window.location = "<?php echo site_url('products/impostos_add_edit'); ?>/" + data["id"];
						}else if(data["status"]=="ya_existe"){
							alert( "Já existe outro grupo com mesmo nome. Por favor, escolha outro nome." );
						}else{
							alert( "Erro ao salvar!" );
						}
						
					});

			<?php } ?>

		}else{

			alert("Verifique todos os campos obrigatórios antes de emitir a nota");

		}
	}

});

// Carregados os dados
<? if($impostos->regras!=""): 
//	var_dump($impostos->regras);
?>

var obj = jQuery.parseJSON( '<?=$impostos->regras;?>' );
$('#meuProdutoOuServico option[value="<?=$impostos->tipo;?>"]').prop('selected', true).trigger("change");

var ttpps = <?=$impostos->tipo;?>;

if(ttpps==2){
	setServico();
}else{
	setProduto();
}

$.each(obj, function(y, val){

	if(y == "ind_escala") $('#meuCtrltSTCheck').iCheck('check');
	if(y == "nve") $('#meuNVECheck').iCheck('check');

	if($('select[name="' + y +'"]').length == 1){
		$('select[name="' + y +'"]').val(val).trigger('change');
	}

	if($('input[name="' + y +'"]').length == 1){	
		$('input[name="' + y +'"]').val(val);
	}

	$.each(this, function(x){

		$.each(this, function(i, v){
			console.log(x + " / "+i + " v: " + v, $('#table'+x).find('select[name="' + i +'"]').length);
			if($('#table'+x).find('select[name="' + i +'"]').length == 1){
			
				//$('#table'+x).find('select[name="' + i +'"] option[value="'+  +'"]').prop('selected', true);
				$('#table'+x).find('select[name="' + i +'"]').val(v).trigger('change');
			}

			if($('#table'+x).find('input[name="' + i +'"]').length == 1){	
				$('#table'+x).find('input[name="' + i +'"]').val(v);
			}

		});
	});
});

<? endif; ?>

});


function GetMunicipio($select, $cidades, $pais = null){

		if($select == 'EX'){
			if($pais!=null) $($pais).show();
			$($cidades).html("");
			$($cidades).append($('<option>').text("EXTERIOR").attr('value', "EX"));
		}else{
			if($pais!=null) $($pais).hide();
			$($cidades).html("");

			// Buscar Ajax das cidades
			$.ajax({ 
			url: '<?php echo site_url('products/municipios/'); ?>?term=' + $select, 
			dataType: 'json', 
			data: "", 
			async: false, 
			success: function(result){ 
				$.each(result, function(i, value) {
					$($cidades).append($('<option>').text(value['nome']).attr('value', value['codigo']));
				});
			}
			});
		}

}


function DolarToReal(atual, tipo){
	
	if(tipo==1){
		//com R$
		var f = atual.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
	}else{
		//sem R$
		var f = atual.toLocaleString('pt-br', {minimumFractionDigits: 2});
	}
	return f;

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

function setServico(){
	$("#itensListaServico_box").attr("required", true);
	$("#meuUUF").attr("required", true);
	$("#cMunFG").attr("required", true);
	$("#meuU2UF").attr("required", true);
	$("#UcMun").attr("required", true);
}

function setProduto(){
	$("#itensListaServico_box").removeAttr("required");
	$("#meuUUF").removeAttr("required");
	$("#cMunFG").removeAttr("required");
	$("#meuU2UF").removeAttr("required");
	$("#UcMun").removeAttr("required");
}

</script>
</head>
<style>
.boxField {
    padding: 5px !important;
    overflow: hidden;
	max-width: 300px;
}
.boxField label{    width: 100%;
    float: left;
    font-weight: bold;
	margin-bottom: 2px;
}
.boxField input, .boxField select{
	padding: 5px;
    width: 100%;
	float: left;
	height:30px;
}
table tr td {
    vertical-align: top;
}

h2{margin-top:20px;
font-size: 16px;
width: 100%;
padding: 5px;
background: #d6d6d6;}

.readonlyField{background:#ccc;}

.invalidoborder{border:1px solid red}
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Crie grupo de impostos para facilitar a gestão de impostos nos produtos</h3>
                </div>
                <div class="box-body">
				<form id="form_prodNFe" class="dialog inputForm" style="width: auto; min-height: 37px; max-height: none; height: auto;">
					<p>
						<h2>Dados gerais do <select id="meuProdutoOuServico"  onchange="if(this.value==1){$('.boxServico').hide();$('.boxProduto').show(); setServico(); }else{$('.boxServico').show();$('.boxProduto').hide(); setProduto();}" name="meuProdutoOuServico" autocomplete="off"><option value="1">Produto</option><option value="2">Serviço</option></select></h2>
					</p>
					<p>
						<label for="nomepp"> <span>Nome</span><br><input type="text" id="nomepp" value="<?=$impostos->nome;?>"  name="nomepp" style="padding: 5px; width: 500px; float: left; height: 30px;" autocomplete="off"></label></p>
					</p>

					<div class="boxServico" style="display:none">
						<h2>ISSQN</h2>
						<table id="tableissqn"  class="bottomSpace">
							<tr>
								<td class="boxField" id="UindISS_rcpt"><label for="UindISS"> <span>Exigibilidade do ISS</span></label><select id="UindISS"  name="indISS" autocomplete="off"><option value="1">Exigível</option><option value="2">Não incidência</option><option value="3">Isenção</option><option value="4">Exportação</option><option value="5">Imunidade</option><option value="6">Exigibilidade Suspensa por Decisão Judicial</option><option value="7">Exigib. Susp. por Processo Administrativo</option></select></td>
								<td class="boxField" id="UnProcesso_rcpt" style="display: none;"><label for="UnProcesso"> <span>Num. processo da suspensão</span></label><input type="text" id="UnProcesso"  name="nProcesso" autocomplete="off" especialtype="string" maxsize="30"></td>
								<td class="boxField" id="UcListServ_rcpt"><label for="UcListServ"> <span>Ítem lista serviço<b style="color:red">*</b></span></label><select id="itensListaServico_box" name="cListServ"><option value="">selecione</option><option value="01.01">01.01 - Análise e desenvolvimento de sistemas.</option><option value="01.02">01.02 - Programação.</option><option value="01.03">01.03 - Processamento de dados e congêneres.</option><option value="01.04">01.04 - Elaboração de programas de computadores, inclusive de jogos eletrônicos.</option><option value="01.05">01.05 - Licenciamento ou cessão de direito de uso de programas de computação.</option><option value="01.06">01.06 - Assessoria e consultoria em informática.</option><option value="01.07">01.07 - Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.</option><option value="01.08">01.08 - Planejamento, confecção, manutenção e atualização de páginas eletrônicas.</option><option value="02.01">02.01 - Serviços de pesquisas e desenvolvimento de qualquer natureza.</option><option value="03.02">03.02 - Cessão de direito de uso de marcas e de sinais de propaganda.</option><option value="03.03">03.03 - Exploração de salões de festas, centro de convenções, escritórios virtuais, stands, quadras esportivas, estádios, ginásios, auditórios, casas de espetáculos, parques de diversões, canchas e congêneres, para realização de eventos ou negócios de qualquer natureza.</option><option value="03.04">03.04 - Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não, de ferrovia, rodovia, postes, cabos, dutos e condutos de qualquer natureza.</option><option value="03.05">03.05 - Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário.</option><option value="04.01">04.01 - Medicina e biomedicina.</option><option value="04.02">04.02 - Análises clínicas, patologia, eletricidade médica, radioterapia, quimioterapia, ultra-sonografia, ressonância magnética, radiologia, tomografia e congêneres.</option><option value="04.03">04.03 - Clínicas, laboratórios, ambulatórios e congêneres, Hospitais, sanatórios manicômios, casas de saúde, prontos-socorros.</option><option value="04.04">04.04 - Instrumentação cirúrgica.</option><option value="04.05">04.05 - Acupuntura.</option><option value="04.06">04.06 - Enfermagem, inclusive serviços auxiliares.</option><option value="04.07">04.07 - Serviços farmacêuticos.</option><option value="04.08">04.08 - Terapia ocupacional, fisioterapia e fonoaudiologia.</option><option value="04.09">04.09 - Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental.</option><option value="04.10">04.10 - Nutrição.</option><option value="04.11">04.11 - Obstetrícia.</option><option value="04.12">04.12 - Odontologia.</option><option value="04.13">04.13 - Ortóptica.</option><option value="04.14">04.14 - Próteses sob encomenda.</option><option value="04.15">04.15 - Psicanálise.</option><option value="04.16">04.16 - Psicologia.</option><option value="04.17">04.17 - Casas de repouso e de recuperação, creches, asilos e congêneres.</option><option value="04.18">04.18 - Inseminação artificial, fertilização in vitro e congêneres.</option><option value="04.19">04.19 - Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres.</option><option value="04.20">04.20 - Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.</option><option value="04.21">04.21 - Unidade de atendimento, assistência ou tratamento móvel e congêneres.</option><option value="04.22">04.22 - Planos de medicina de grupo ou individual e convênios para prestação de assistência médica, hospitalar, odontológica e congêneres.</option><option value="04.23">04.23 - Outros planos de saúde que se cumpram através de serviços de terceiros contratados, credenciados, cooperados ou apenas pagos pelo operador do plano mediante indicação do beneficiário.</option><option value="05.01">05.01 - Medicina veterinária e zootecnia.</option><option value="05.02">05.02 - Hospitais, clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária.</option><option value="05.03">05.03 - Laboratórios de análise na área veterinária.</option><option value="05.04">05.04 - Inseminação artificial, fertilização in vitro e congêneres.</option><option value="05.05">05.05 - Bancos de sangue e de órgãos e congêneres.</option><option value="05.06">05.06 - Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.</option><option value="05.07">05.07 - Unidade de atendimento, assistência ou tratamento móvel e congêneres.</option><option value="05.08">05.08 - Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres.</option><option value="05.09">05.09 - Planos de atendimento e assistência médico-veterinária.</option><option value="06.01">06.01 - Barbearia, cabeleireiros, manicuros, pedicuros e congêneres.</option><option value="06.02">06.02 - Esteticistas, tratamento de pele, depilação e congêneres.</option><option value="06.03">06.03 - Banhos, duchas, sauna, massagens e congêneres.</option><option value="06.04">06.04 - Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas.</option><option value="06.05">06.05 - Centros de emagrecimento, spa e congêneres.</option><option value="07.01">07.01 - Engenharia, agronomia, agrimensura, arquitetura, geologia, urbanismo, paisagismo e congêneres.</option><option value="07.02">07.02 - Execução, por administração, empreitada ou subempreitada, de obras de construção civil, hidráulica ou elétrica e de outras obras semelhantes, inclusive sondagem, perfuração de poços, escavação, drenagem e irrigação, terraplanagem, pavimentação, concretagem e a instalação e montagem de produtos, peças e equipamentos</option><option value="07.03">07.03 - Elaboração de planos diretores, estudos de viabilidade, estudos organizacionais e outros, relacionados com obras e serviços de engenharia; elaboração de anteprojetos, projetos básicos e projetos executivos para trabalhos de engenharia.</option><option value="07.04">07.04 - Demolição.</option><option value="07.05">07.05 - Reparação, conservação e reforma de edifícios, estradas, pontes, portos e congêneres.</option><option value="07.06">07.06 - Colocação e instalação de tapetes, carpetes, assoalhos, cortinas, revestimentos de parede, vidros, divisórias, placas de gesso e congêneres, com material fornecido pelo tomador do serviço.</option><option value="07.07">07.07 - Recuperação, raspagem, polimento e lustração de pisos e congêneres.</option><option value="07.08">07.08 - Calafetação.</option><option value="07.09">07.09 - Varrição, coleta, remoção, incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resíduos quaisquer.</option><option value="07.10">07.10 - Limpeza, manutenção e conservação de vias e logradouros públicos, imóveis, chaminés, piscinas, parques, jardins e congêneres.</option><option value="07.11">07.11 - Decoração e jardinagem, inclusive corte e poda de árvores.</option><option value="07.12">07.12 - Controle e tratamento de efluentes de qualquer natureza e de agentes físicos, químicos e biológicos.</option><option value="07.13">07.13 - Dedetização, desinfecção, desinsetização, imunização, higienização, desratização, pulverização e congêneres.</option><option value="07.16">07.16 - Florestamento, reflorestamento, semeadura, adubação e congêneres.</option><option value="07.17">07.17 - Escoramento, contenção de encostas e serviços congêneres.</option><option value="07.18">07.18 - Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas, represas, açudes e congêneres.</option><option value="07.19">07.19 - Acompanhamento e fiscalização da execução de obras de engenharia, arquitetura e urbanismo.</option><option value="07.20">07.20 - Aerofotogrametria (inclusive interpretação), cartografia, mapeamento, levantamentos topográficos, batimétricos, geográficos, geodésicos, geológicos, geofísicos e congêneres.</option><option value="07.21">07.21 - Pesquisa, perfuração, cimentação, mergulho, perfilagem, concretação, testemunhagem, pescaria, estimulação e outros serviços relacionados com a exploração e explotação de petróleo, gás natural e de outros recursos minerais.</option><option value="07.22">07.22 - Nucleação e bombardeamento de nuvens e congêneres.</option><option value="08.01">08.01 - Ensino regular pré-escolar, fundamental, médio e superior.</option><option value="08.02">08.02 - Instrução, treinamento, orientação pedagógica e educacional, avaliação de conhecimentos de qualquer natureza.</option><option value="09.01">09.01 - Hospedagem de qualquer natureza em hotéis, apart-service condominiais, flat, apart-hotéis, hotéis residência, residence-service, suite service, hotelaria marítima, motéis, pensões e congêneres; ocupação por temporada com fornecimento de serviço .</option><option value="09.02">09.02 - Agenciamento, organização, promoção, intermediação e execução de programas de turismo, passeios, viagens, excursões, hospedagens e congêneres.</option><option value="09.03">09.03 - Guias de turismo.</option><option value="10.01">10.01 - Agenciamento, corretagem ou intermediação de câmbio, de seguros, de cartões de crédito, de planos de saúde e de planos de previdência privada.</option><option value="10.02">10.02 - Agenciamento, corretagem ou intermediação de títulos em geral, valores mobiliários e contratos quaisquer.</option><option value="10.03">10.03 - Agenciamento, corretagem ou intermediação de direitos de propriedade industrial, artística ou literária.</option><option value="10.04">10.04 - Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil (leasing), de franquia (franchising) e de faturização (factoring).</option><option value="10.05">10.05 - Agenciamento, corretagem ou intermediação de bens móveis ou imóveis, não abrangidos em outros itens ou subitens, inclusive aqueles realizados no âmbito de Bolsas de Mercadorias e Futuros, por quaisquer meios.</option><option value="10.06">10.06 - Agenciamento marítimo.</option><option value="10.07">10.07 - Agenciamento de notícias.</option><option value="10.08">10.08 - Agenciamento de publicidade e propaganda, inclusive o agenciamento de veiculação por quaisquer meios.</option><option value="10.09">10.09 - Representação de qualquer natureza, inclusive comercial.</option><option value="10.10">10.10 - Distribuição de bens de terceiros.</option><option value="11.01">11.01 - Guarda e estacionamento de veículos terrestres automotores, de aeronaves e de embarcações.</option><option value="11.02">11.02 - Vigilância, segurança ou monitoramento de bens e pessoas.</option><option value="11.03">11.03 - Escolta, inclusive de veículos e cargas.</option><option value="11.04">11.04 - Armazenamento, depósito, carga, descarga, arrumação e guarda de bens de qualquer espécie.</option><option value="12.01">12.01 - Espetáculos teatrais.</option><option value="12.02">12.02 - Exibições cinematográficas.</option><option value="12.03">12.03 - Espetáculos circenses.</option><option value="12.04">12.04 - Programas de auditório.</option><option value="12.05">12.05 - Parques de diversões, centros de lazer e congêneres.</option><option value="12.06">12.06 - Boates, taxi-dancing e congêneres.</option><option value="12.07">12.07 - Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais, festivais e congêneres.</option><option value="12.08">12.08 - Feiras, exposições, congressos e congêneres.</option><option value="12.09">12.09 - Bilhares, boliches e diversões eletrônicas ou não.</option><option value="12.10">12.10 - Corridas e competições de animais.</option><option value="12.11">12.11 - Competições esportivas ou de destreza física ou intelectual, com ou sem a participação do espectador.</option><option value="12.12">12.12 - Execução de música</option><option value="12.13">12.13 - Produção, mediante ou sem encomenda prévia, de eventos, espetáculos, entrevistas, shows, ballet, danças, desfiles, bailes, teatros, óperas, concertos, recitais, festivais e congêneres.</option><option value="12.14">12.14 - Fornecimento de música para ambientes fechados ou não, mediante transmissão por qualquer processo.</option><option value="12.15">12.15 - Desfiles de blocos carnavalescos ou folclóricos, trios elétricos e congêneres.</option><option value="12.16">12.16 - Exibição de filmes, entrevistas, musicais, espetáculos, shows, concertos, desfiles, óperas, competições esportivas, de destreza intelectual ou congêneres.</option><option value="12.17">12.17 - Recreação e animação, inclusive em festas e eventos de qualquer natureza.</option><option value="13.02">13.02 - Fonografia ou gravação de sons, inclusive trucagem, dublagem, mixagem e congêneres</option><option value="13.03">13.03 - Fotografia e cinematografia, inclusive revelação, ampliação, cópia, reprodução, trucagem e congêneres.</option><option value="13.04">13.04 - Reprografia, microfilmagem e digitalização.</option><option value="13.05">13.05 - Composição gráfica, fotocomposição, clicheria, zincografia, litografia, fotolitografia</option><option value="14.01">14.01 - Lubrificação, limpeza, lustração, revisão, carga e recarga, conserto, restauração, blindagem, manutenção e conservação de máquinas, veículos, aparelhos, equipamentos, motores, elevadores ou de qualquer objeto (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).</option><option value="14.02">14.02 - Assistência técnica.</option><option value="14.03">14.03 - Recondicionamento de motores (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).</option><option value="14.04">14.04 - Recauchutagem ou regeneração de pneus.</option><option value="14.05">14.05 - Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodização, corte, recorte, polimento, plastificação e congêneres, de objetos quaisquer.</option><option value="14.06">14.06 - Instalação e montagem de aparelhos, máquinas e equipamentos, inclusive montagem industrial, prestados ao usuário final, exclusivamente com material por ele fornecido.</option><option value="14.07">14.07 - Colocação de molduras e congêneres.</option><option value="14.08">14.08 - Encadernação, gravação e douração de livros, revistas e congêneres.</option><option value="14.09">14.09 - Alfaiataria e costura, quando o material for fornecido pelo usuário final, exceto aviamento.</option><option value="14.10">14.10 - Tinturaria e lavanderia.</option><option value="14.11">14.11 - Tapeçaria e reforma de estofamentos em geral.</option><option value="14.12">14.12 - Funilaria e lanternagem.</option><option value="14.13">14.13 - Carpintaria e serralheria.</option><option value="15.01">15.01 - Administração de fundos quaisquer, de consórcio, de cartão de crédito ou débito e congêneres, de carteira de clientes, de cheques pré-datados e congêneres.</option><option value="15.02">15.02 - Abertura de contas em geral, inclusive conta-corrente, conta de investimentos e aplicação e caderneta de poupança, no País e no exterior, bem como a manutenção das referidas contas ativas e inativas.</option><option value="15.03">15.03 - Locação e manutenção de cofres particulares, de terminais eletrônicos, de terminais de atendimento e de bens e equipamentos em geral.</option><option value="15.04">15.04 - Fornecimento ou emissão de atestados em geral, inclusive atestado de idoneidade, atestado de capacidade financeira e congêneres.</option><option value="15.05">15.05 - Cadastro, elaboração de ficha cadastral, renovação cadastral e congêneres, inclusão ou exclusão no Cadastro de Emitentes de Cheques sem Fundos, CCF ou em quaisquer outros bancos cadastrais.</option><option value="15.06">15.06 - Emissão, reemissão e fornecimento de avisos, comprovantes e documentos em geral; abono de firmas; coleta e entrega de documentos, bens e valores; comunicação com outra agência ou com a administração central; licenciamento eletrônico de veículos; transferência de veículos; agenciamento fiduciário ou depositário; devolução de bens em custódia.</option><option value="15.07">15.07 - Acesso, movimentação, atendimento e consulta a contas em geral, por qualquer meio ou processo, inclusive por telefone, fac-símile, internet e telex, acesso a terminais de atendimento, inclusive vinte e quatro horas; acesso a outro banco e a rede compartilhada; fornecimento de saldo, extrato e demais informações relativas a contas em geral, por qualquer meio ou processo.</option><option value="15.08">15.08 - Emissão, reemissão, alteração, cessão, substituição, cancelamento e registro de contrato de crédito; estudo, análise e avaliação de operações de crédito; emissão, concessão, alteração ou contratação de aval, fiança, anuência e congêneres; serviços relativos a abertura de crédito, para quaisquer fins.</option><option value="15.09">15.09 - Arrendamento mercantil (leasing) de quaisquer bens, inclusive cessão de direitos e obrigações, substituição de garantia, alteração, cancelamento e registro de contrato, e demais serviços relacionados ao arrendamento mercantil (leasing) .</option><option value="15.10">15.10 - Serviços relacionados a cobranças, recebimentos ou pagamentos em geral, de títulos quaisquer, de contas ou carnês, de câmbio, de tributos e por conta de terceiros, inclusive os efetuados por meio eletrônico, automático ou por máquinas de atendimento; fornecimento de posição de cobrança, recebimento ou pagamento; emissão de carnês, fichas de compensação, impressos e documentos em geral.</option><option value="15.11">15.11 - Devolução de títulos, protesto de títulos, sustação de protesto, manutenção de títulos, reapresentação de títulos, e demais serviços a eles relacionados.</option><option value="15.12">15.12 - Custódia em geral, inclusive de títulos e valores mobiliários.</option><option value="15.13">15.13 - Serviços relacionados a operações de câmbio em geral, edição, alteração, prorrogação, cancelamento e baixa de contrato de câmbio; emissão de registro de exportação ou de crédito; cobrança ou depósito no exterior; emissão, fornecimento e cancelamento de cheques de viagem; fornecimento, transferência, cancelamento e demais serviços relativos a carta de crédito de importação, exportação e garantias recebidas; envio e recebimento de mensagens em geral relacionadas a operações de câmbio.</option><option value="15.14">15.14 - Fornecimento, emissão, reemissão, renovação e manutenção de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.</option><option value="15.15">15.15 - Compensação de cheques e títulos quaisquer; serviços relacionados a depósito, inclusive depósito identificado, a saque de contas quaisquer, por qualquer meio ou processo, inclusive em terminais eletrônicos e de atendimento.</option><option value="15.16">15.16 - Emissão, reemissão, liquidação, alteração, cancelamento e baixa de ordens de pagamento, ordens de crédito e similares, por qualquer meio ou processo; serviços relacionados à transferência de valores, dados, fundos, pagamentos e similares, inclusive entre contas em geral.</option><option value="15.17">15.17 - Emissão, fornecimento, devolução, sustação, cancelamento e oposição de cheques quaisquer, avulso ou por talão.</option><option value="15.18">15.18 - Serviços relacionados a crédito imobiliário, avaliação e vistoria de imóvel ou obra, análise técnica e jurídica, emissão, reemissão, alteração, transferência e renegociação de contrato, emissão e reemissão do termo de quitação e demais serviços relacionados a crédito imobiliário.</option><option value="16.01">16.01 - Serviços de transporte de natureza municipal.</option><option value="17.01">17.01 - Assessoria ou consultoria de qualquer natureza, não contida em outros itens desta lista; análise, exame, pesquisa, coleta, compilação e fornecimento de dados e informações de qualquer natureza, inclusive cadastro e similares.</option><option value="17.02">17.02 - Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.</option><option value="17.03">17.03 - Planejamento, coordenação, programação ou organização técnica, financeira ou administrativa.</option><option value="17.04">17.04 - Recrutamento, agenciamento, seleção e colocação de mão-de-obra.</option><option value="17.05">17.05 - Fornecimento de mão-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadores, avulsos ou temporários, contratados pelo prestador de serviço.</option><option value="17.06">17.06 - Propaganda e publicidade, inclusive promoção de vendas, planejamento de campanhas ou sistemas de publicidade, elaboração de desenhos, textos e demais materiais publicitários.</option><option value="17.08">17.08 - Franquia (franchising).</option><option value="17.09">17.09 - Perícias, laudos, exames técnicos e análises técnicas.</option><option value="17.10">17.10 - Planejamento, organização e administração de feiras, exposições, congressos e congêneres.</option><option value="17.11">17.11 - Organização de festas e recepções; bufê (exceto o fornecimento de alimentação e bebidas, que fica sujeito ao ICMS).</option><option value="17.12">17.12 - Administração em geral, inclusive de bens e negócios de terceiros.</option><option value="17.13">17.13 - Leilão e congêneres.</option><option value="17.14">17.14 - Advocacia.</option><option value="17.15">17.15 - Arbitragem de qualquer espécie, inclusive jurídica.</option><option value="17.16">17.16 - Auditoria.</option><option value="17.17">17.17 - Análise de Organização e Métodos.</option><option value="17.18">17.18 - Atuária e cálculos técnicos de qualquer natureza.</option><option value="17.19">17.19 - Contabilidade, inclusive serviços técnicos e auxiliares.</option><option value="17.20">17.20 - Consultoria e assessoria econômica ou financeira.</option><option value="17.21">17.21 - Estatística.</option><option value="17.22">17.22 - Cobrança em geral.</option><option value="17.23">17.23 - Assessoria, análise, avaliação, atendimento, consulta, cadastro, seleção, gerenciamento de informações, administração de contas a receber ou a pagar e em geral, relacionados a operações de faturização (factoring).</option><option value="17.24">17.24 - Apresentação de palestras, conferências, seminários e congêneres.</option><option value="18.01">18.01 - Serviços de regulação de sinistros vinculados a contratos de seguros; inspeção e avaliação de riscos para cobertura de contratos de seguros; prevenção e gerência de riscos seguráveis e congêneres.</option><option value="19.01">19.01 - Serviços de distribuição e venda de bilhetes e demais produtos de loteria, bingos, cartões, pules ou cupons de apostas, sorteios, prêmios, inclusive os decorrentes de títulos de capitalização e congêneres.</option><option value="20.01">20.01 - Serviços portuários, ferroportuários, utilização de porto, movimentação de passageiros, reboque de embarcações, rebocador escoteiro, atracação, desatracação, serviços de praticagem, capatazia , armazenagem de qualquer natureza, serviços acessórios, movimentação de mercadorias, serviços de apoio marítimo, de movimentação ao largo, serviços de armadores, estiva, conferência, logística e congêneres.</option><option value="20.02">20.02 - Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros, armazenagem de qualquer natureza, capatazia, movimentação de aeronaves, serviços de apoio aeroportuários, serviços acessórios, movimentação de mercadorias, logística e congêneres.</option><option value="20.03">20.03 - Serviços de terminais rodoviários, ferroviários, metroviários, movimentação de passageiros, mercadorias, inclusive&nbsp;&nbsp;&nbsp;&nbsp; suas operações, logística e congêneres.</option><option value="21.01">21.01 - Serviços de registros públicos, cartorários e notariais.</option><option value="22.01">22.01 - Serviços de exploração de rodovia mediante cobrança de preço ou pedágio dos usuários, envolvendo execução de serviços de conservação, manutenção, melhoramentos para adequação de capacidade e segurança de trânsito, operação, monitoração, assistência aos usuários e outros serviços definidos em contratos, atos de concessão ou de permissão ou em normas oficiais.</option><option value="23.01">23.01 - Serviços de programação e comunicação visual, desenho industrial e congêneres.</option><option value="24.01">24.01 - Serviços de chaveiros, confecção de carimbos, placas, sinalização visual, banners, adesivos e congêneres.</option><option value="25.01">25.01 - Funerais, inclusive fornecimento de caixão, urna ou esquifes; aluguel de capela; transporte do corpo cadavérico; fornecimento de flores, coroas e outros paramentos; desembaraço de certidão de óbito; fornecimento de véu, essa e outros adornos; embalsamento, embelezamento, conservação ou restauração de cadáveres.</option><option value="25.02">25.02 - Cremação de corpos e partes de corpos cadavéricos.</option><option value="25.03">25.03 - Planos ou convênio funerários.</option><option value="25.04">25.04 - Manutenção e conservação de jazigos e cemitérios.</option><option value="26.01">26.01 - Serviços de coleta, remessa ou entrega de correspondências, documentos, objetos, bens ou valores, inclusive pelos correios e suas agências franqueadas; courrier e congêneres</option><option value="27.01">27.01 - Serviços de assistência social.</option><option value="28.01">28.01 - Serviços de avaliação de bens e serviços de qualquer natureza.</option><option value="29.01">29.01 - Serviços de biblioteconomia.</option><option value="30.01">30.01 - Serviços de biologia, biotecnologia e química.</option><option value="31.01">31.01 - Serviços técnicos em edificações, eletrônica, eletrotécnica, mecânica, telecomunicações e congêneres.</option><option value="32.01">32.01 - Serviços de desenhos técnicos.</option><option value="33.01">33.01 - Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres.</option><option value="34.01">34.01 - Serviços de investigações particulares, detetives e congêneres.</option><option value="35.01">35.01 - Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas.</option><option value="36.01">36.01 - Serviços de meteorologia.</option><option value="37.01">37.01 - Serviços de artistas, atletas, modelos e manequins.</option><option value="38.01">38.01 - Serviços de museologia.</option><option value="39.01">39.01 - Serviços de ourivesaria e lapidação.</option><option value="40.01">40.01 - Obras de arte sob encomenda.</option></select></td>
								<td class="boxField" id="UcServico_rcpt"><label for="UcServico"> <span>Cód municipal do serviço</span></label><input type="text" id="UcServico"  name="cServico" autocomplete="off" especialtype="string" maxsize="20"></td>
								<td class="boxField" id="UindIncentivo_rcpt" style="padding-top: 30px!important; padding-left: 20px!important;"><label for="UindIncentivo"><input type="checkbox" id="UindIncentivo"  name="indIncentivo" autocomplete="off" value="1"> <span>Possui incentivo fiscal</span></label></td>
							</tr><tr>
								<td class="boxField" id="meuUUF_rcpt"><label for="meuUUF"> <span>UF da prestação do serviço<b style="color:red">*</b></span></label><select id="meuUUF" onchange="GetMunicipio(this.value, '#cMunFG', '#UcPais_rcpt');" name="meuUUF" autocomplete="off"><option value=""></option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option><option value="EX">EX</option></select></td>
								<td class="boxField" id="cMunFG_rcpt"><label for="cMunFG"> <span>Município do serviço<b style="color:red">*</b></span></label><select id="cMunFG"  name="cMunFG" autocomplete="off"><option value="0">Selecione o estado</option></select></td>
								<td class="boxField" id="UcPais_rcpt" style="display:none"><label for="UcPais"> <span>País do serviço</span></label><select id="UcPais"  name="cPais" autocomplete="off"><option value="0">- Selecione o país -</option><option value="0132">Afeganistao</option><option value="7560">Africa do Sul</option><option value="0175">Albania, Republica da</option><option value="0230">Alemanha</option><option value="0370">Andorra</option><option value="0400">Angola</option><option value="0418">Anguilla</option><option value="0434">Antigua e Barbuda</option><option value="0477">Antilhas Holandesas</option><option value="0531">Arabia Saudita</option><option value="0590">Argelia</option><option value="0639">Argentina</option><option value="0647">Armenia, Republica da</option><option value="0655">Aruba</option><option value="0698">Australia</option><option value="0728">Austria</option><option value="0736">Azerbaijao, Republica do</option><option value="0779">Bahamas, Ilhas</option><option value="0809">Bahrein, Ilhas</option><option value="9950">Bancos Centrais</option><option value="0817">Bangladesh</option><option value="0833">Barbados</option><option value="0850">Belarus, Republica da</option><option value="0876">Belgica</option><option value="0884">Belize</option><option value="2291">Benin</option><option value="0906">Bermudas</option><option value="0973">Bolivia, Estado Plurinacional da</option><option value="0981">Bosnia-herzegovina, Republica da</option><option value="1015">Botsuana</option><option value="1058">Brasil</option><option value="1082">Brunei</option><option value="1112">Bulgaria, Republica da</option><option value="0310">Burkina Faso</option><option value="1155">Burundi</option><option value="1198">Butao</option><option value="1279">Cabo Verde, Republica de</option><option value="1457">Camaroes</option><option value="1414">Camboja</option><option value="1490">Canada</option><option value="1511">Canarias, Ilhas</option><option value="1546">Catar</option><option value="1376">Cayman, Ilhas</option><option value="1538">Cazaquistao, Republica do</option><option value="7889">Chade</option><option value="1589">Chile</option><option value="1600">China, Republica Popular</option><option value="1635">Chipre</option><option value="5118">Christmas, Ilha, Navidad</option><option value="7412">Cingapura</option><option value="1651">Cocos, Keeling, Ilhas</option><option value="1694">Colombia</option><option value="1732">Comores, Ilhas</option><option value="1775">Congo</option><option value="8885">Congo, Republica Democratica do</option><option value="1830">Cook, Ilhas</option><option value="1872">Coreia do Norte, Rep. Pop. Democr.</option><option value="1902">Coreia do Sul, Republica da</option><option value="1961">Costa Rica</option><option value="1937">Costa do Marfim</option><option value="1988">Coveite, Kuwait</option><option value="1953">Croacia, republica da</option><option value="1996">Cuba</option><option value="2321">Dinamarca</option><option value="7838">Djibuti</option><option value="2356">Dominica, Ilha</option><option value="2402">Egito</option><option value="6874">El Salvador</option><option value="2445">Emirados Arabes Unidos</option><option value="2399">Equador</option><option value="2437">Eritreia</option><option value="2470">Eslovaca, Republica</option><option value="2461">Eslovenia, Republica da</option><option value="2453">Espanha</option><option value="2496">Estados Unidos</option><option value="2518">Estonia, Republica da</option><option value="2534">Etiopia</option><option value="2550">Falkland, Ilhas Malvinas</option><option value="2593">Feroe, Ilhas</option><option value="8702">Fiji</option><option value="2674">Filipinas</option><option value="2712">Finlandia</option><option value="1619">Formosa, Taiwan</option><option value="2755">Franca</option><option value="2810">Gabao</option><option value="2852">Gambia</option><option value="2895">Gana</option><option value="2917">Georgia, Republica da</option><option value="2933">Gibraltar</option><option value="2976">Granada</option><option value="3018">Grecia</option><option value="3050">Groenlandia</option><option value="3093">Guadalupe</option><option value="3131">Guam</option><option value="3174">Guatemala</option><option value="1504">Guernsey, Ilha do Canal, inclui Alderney e Sark</option><option value="3379">Guiana</option><option value="3255">Guiana Francesa</option><option value="3298">Guine</option><option value="3344">Guine-Bissau</option><option value="3310">Guine-Equatorial</option><option value="3417">Haiti</option><option value="3450">Honduras</option><option value="3514">Hong Kong</option><option value="3557">Hungria, Republica da</option><option value="3573">Iemen</option><option value="3611">India</option><option value="3654">Indonesia</option><option value="3727">Ira, Republica Islamica do</option><option value="3697">Iraque</option><option value="3751">Irlanda</option><option value="3794">Islandia</option><option value="3832">Israel</option><option value="3867">Italia</option><option value="3913">Jamaica</option><option value="3999">Japao</option><option value="1508">Jersey, Ilha do Canal</option><option value="3964">Johnston, Ilhas</option><option value="4030">Jordania</option><option value="4111">Kiribati</option><option value="4200">Laos, Rep. Pop. Democr. do</option><option value="4235">Lebuan,ilhas</option><option value="4260">Lesoto</option><option value="4278">Letonia, Republica da</option><option value="4316">Libano</option><option value="4340">Liberia</option><option value="4383">Libia</option><option value="4405">Liechtenstein</option><option value="4421">Lituania, Republica da</option><option value="4456">Luxemburgo</option><option value="4472">Macau</option><option value="4499">Macedonia, Ant. Rep. Iugoslava</option><option value="4502">Madagascar</option><option value="4525">Madeira, Ilha da</option><option value="4553">Malasia</option><option value="4588">Malavi</option><option value="4618">Maldivas</option><option value="4642">Mali</option><option value="4677">Malta</option><option value="3595">Man, Ilha De</option><option value="4723">Marianas do Norte</option><option value="4740">Marrocos</option><option value="4766">Marshall, Ilhas</option><option value="4774">Martinica</option><option value="4855">Mauricio</option><option value="4880">Mauritania</option><option value="4885">Mayotte, Ilhas Francesas</option><option value="4936">Mexico</option><option value="0930">Mianmar, Birmania</option><option value="4995">Micronesia</option><option value="4901">Midway, Ilhas</option><option value="5053">Mocambique</option><option value="4944">Moldavia, Republica da</option><option value="4952">Monaco</option><option value="4979">Mongolia</option><option value="4985">Montenegro</option><option value="5010">Montserrat, Ilhas</option><option value="5070">Namibia</option><option value="5088">Nauru</option><option value="5177">Nepal</option><option value="5215">Nicaragua</option><option value="5258">Niger</option><option value="5282">Nigeria</option><option value="5312">Niue, Ilha</option><option value="5355">Norfolk, Ilha</option><option value="5380">Noruega</option><option value="5428">Nova Caledonia</option><option value="5487">Nova Zelandia</option><option value="5568">Oma</option><option value="9970">Organizacoes Internacionais</option><option value="5665">Pacifico, Ilhas do, possessao dos EUA</option><option value="5738">Paises Baixos, Holanda</option><option value="5754">Palau</option><option value="5800">Panama</option><option value="5452">Papua Nova Guine</option><option value="5762">Paquistao</option><option value="5860">Paraguai</option><option value="5894">Peru</option><option value="5932">Pitcairn, Ilha</option><option value="5991">Polinesia Francesa</option><option value="6033">Polonia, Republica da</option><option value="6114">Porto Rico</option><option value="6076">Portugal</option><option value="9903">Provisao de Navios e Aeronaves</option><option value="6238">Quenia</option><option value="6254">Quirguiz, Republica</option><option value="6289">Reino Unido</option><option value="6408">Republica Centro-africana</option><option value="6475">Republica Dominicana</option><option value="6602">Reuniao, Ilha</option><option value="6700">Romenia</option><option value="6750">Ruanda</option><option value="6769">Russia, Federacao da</option><option value="6858">Saara Ocidental</option><option value="6777">Salomao, Ilhas</option><option value="6904">Samoa</option><option value="6912">Samoa Americana</option><option value="6971">San Marino</option><option value="7102">Santa Helena</option><option value="7153">Santa Lucia</option><option value="6955">Sao Cristovao e Neves, Ilhas</option><option value="7005">Sao Pedro e Miquelon</option><option value="7200">Sao Tome e Principe, Ilhas</option><option value="7056">Sao Vicente e Granadinas</option><option value="7285">Senegal</option><option value="7358">Serra Leoa</option><option value="7370">Servia</option><option value="7315">Seychelles</option><option value="7447">Siria, Republica Arabe da</option><option value="7480">Somalia</option><option value="7501">Sri Lanka</option><option value="7544">Suazilandia</option><option value="7595">Sudao</option><option value="7641">Suecia</option><option value="7676">Suica</option><option value="7706">Suriname</option><option value="7722">Tadjiquistao, Republica do</option><option value="7765">Tailandia</option><option value="7803">Tanzania, Rep. Unida da</option><option value="7919">Tcheca, Republica</option><option value="7820">Territorio Brit. Oc. Indico</option><option value="7951">Timor Leste</option><option value="8001">Togo</option><option value="8109">Tonga</option><option value="8052">Toquelau, Ilhas</option><option value="8150">Trinidad e Tobago</option><option value="8206">Tunisia</option><option value="8230">Turcas e Caicos, Ilhas</option><option value="8249">Turcomenistao, Republica do</option><option value="8273">Turquia</option><option value="8281">Tuvalu</option><option value="8311">Ucrania</option><option value="8338">Uganda</option><option value="8451">Uruguai</option><option value="8478">Uzbequistao, Republica do</option><option value="5517">Vanuatu</option><option value="8486">Vaticano, Est. da Cidade do</option><option value="8508">Venezuela</option><option value="8583">Vietna</option><option value="8630">Virgens, Ilhas, Britanicas</option><option value="8664">Virgens, Ilhas, E.U.A.</option><option value="8737">Wake, Ilha</option><option value="8907">Zambia</option><option value="6653">Zimbabue</option><option value="8958">Zona do Canal do Panama</option></select></td>
								<td class="boxField" id="meuU2UF_rcpt"><label for="meuU2UF"> <span>UF do imposto<b style="color:red">*</b></span></label><select id="meuU2UF" onchange="GetMunicipio(this.value, '#UcMun');" name="meuU2UF" autocomplete="off"><option value=""></option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></td>
								<td class="boxField" id="UcMun_rcpt"><label for="UcMun"> <span>Município do imposto<b style="color:red">*</b></span></label><select id="UcMun"  name="cMun" autocomplete="off"><option value="0">Selecione o estado</option></select></td>
							</tr><tr>
								<td class="boxField" id="UvDescIncond_rcpt"><label for="UvDescIncond"> <span>Desconto incondic. ($)</span></label><input type="text" id="UvDescIncond"  name="vDescIncond" autocomplete="off" class="money"></td>
								<td class="boxField" id="UvDescCond_rcpt"><label for="UvDescCond"> <span>Desconto condicionado ($)</span></label><input type="text" id="UvDescCond"  name="vDescCond" autocomplete="off" class="money"></td>
								<td class="boxField" id="UvISSRet_rcpt"><label for="UvISSRet"> <span>Retenção do ISS ($)</span></label><input type="text" id="UvISSRet"  name="vISSRet" autocomplete="off" class="money"></td>
								<td class="boxField" id="UvOutro_rcpt"><label for="UvOutro"> <span>Outras retenções ($)</span></label><input type="text" id="UvOutro"  name="vOutro" autocomplete="off" class="money"></td>
							</tr><tr>
								<td class="boxField" id="UvDeducao_rcpt"><label for="UvDeducao"> <span>Dedução da BC ($)</span></label><input type="text" id="UvDeducao"  name="vDeducao" autocomplete="off" class="money"></td>
								<td class="boxField" id="UvBC_rcpt"><label for="UvBC"> <span>Valor Base Cálculo ($)</span></label><input type="text" id="UvBC"  name="vBC" autocomplete="off" readonly="readonly" class="readonlyField  money" ></td>
								<td class="boxField" id="UvAliq_rcpt"><label for="UvAliq"> <span>Alíquota ISSQN (%)</span></label><input type="text" id="UvAliq"  name="vAliq" autocomplete="off" class="percent"></td>
								<td class="boxField" id="UvISSQN_rcpt"><label for="UvISSQN"> <span>Valor ISSQN</span></label><input type="text" id="UvISSQN" readonly="readonly" tabindex="-1" readonly="readonly" class="readonlyField " name="vISSQN" autocomplete="off" ></td>
							</tr>
						</table>
					</div>

					<div class="boxProduto" >
						<div id="meuCtrltSTCheck_rcpt"><label for="meuCtrltSTCheck"><input type="checkbox" id="meuCtrltSTCheck"  name="meuCtrltSTCheck" autocomplete="off" value="1"> <span>Informar dados para controle da ST</span></label></div>
						<div id="quadroCtrltST" class="bottomSpace" style="display: none;">
							<table id="tableCtrltST">
								<tr>
									<td class="boxField" id="ICEST_rcpt"><label for="ICEST"> <span>Código CEST</span></label><input type="text" id="ICEST"  name="cest" autocomplete="off" especialtype="integer" exactsize="6,7"></td>
									<td class="boxField" id="IindEscala_rcpt"><label for="IindEscala"> <span>Indicador de Escala Relevante</span></label><select id="IindEscala"  name="ind_escala" autocomplete="off"><option value=""></option><option value="S">Produzido em Escala Relevante</option><option value="N">Produzido em Escala NÃO Relevante</option></select></td>
									<td class="boxField" id="ICNPJFab_rcpt"><label for="ICNPJFab"> <span>CNPJ do Fabricante da Mercadoria</span></label><input type="text" id="ICNPJFab"  name="cnpj_fabricante" autocomplete="off" especialtype="cpfcnpj"></td>
									<td class="boxField" id="IcBenef_rcpt"><label for="IcBenef"> <span>Cód. Benefício Fiscal na UF</span></label><input type="text" id="IcBenef"  name="beneficio_fiscal" autocomplete="off" especialtype="string" exactsize="8,10"></td>
								</tr>
							</table>
						</div>

						<div id="meuNVECheck_rcpt"><label for="meuNVECheck"><input type="checkbox" id="meuNVECheck"  name="meuNVECheck" autocomplete="off" value="1"> <span>Informar códigos NVE</span></label></div>
						<div id="quadroNVE" class="bottomSpace" style="display: none;">
							<table id="tablenve">
								
									<tr>
									<td id="INVE_rcpt" class="boxField"><label for="INVE"> <span>NVE (até 8 códigos, separados por vírgula)</span></label><input type="text" id="INVE"  name="nve" style="width:100%;" autocomplete="off" especialtype="string" maxsize="63"><br>
									<br><b>NVE</b> - Nomenclatura de Valor Aduaneiro e Estatística. É uma codificação opcional que detalha alguns NCMs.<br>É formada por 2 letras seguidas de 4 números.</td>
								</tr>
								
							</table>
							
						</div>
						
						<h2>IPI</h2>
						<table id="tableipi" class="bottomSpace">
							<tr>
								<td class="boxField" id="OCST_rcpt"><label for="OCST"> <span>Situação Tributária</span></label><select id="OCST"  name="situacao_tributaria" autocomplete="off"><option value="-1">- Não desejo usar -</option><option value="00">00: Entrada com recuperação de crédito</option><option value="01">01: Entrada tributada com alíquota zero</option><option value="02">02: Entrada isenta</option><option value="03">03: Entrada não-tributada</option><option value="04">04: Entrada imune</option><option value="05">05: Entrada com suspensão</option><option value="49">49: Outras entradas</option><option value="50">50: Saída tributada</option><option value="51">51: Saída tributada com alíquota zero</option><option value="52">52: Saída isenta</option><option value="53">53: Saída não-tributada</option><option value="54">54: Saída imune</option><option value="55">55: Saída com suspensão</option><option value="99">99: Outras saídas</option></select></td>
								<td class="boxField" id="OclEnq_rcpt" style="display: none;"><label for="OclEnq"> <span>Classe cigarros e bebidas</span></label><input type="text" id="OclEnq"  name="clEnq" autocomplete="off" especialtype="string" maxsize="5"></td>
								<td class="boxField" id="OcSelo_rcpt" style="display: none;"><label for="OcSelo"> <span>Cód selo controle IPI</span></label><input type="text" id="OcSelo"  name="codigo_selo" autocomplete="off" especialtype="string"></td>
								<td class="boxField" id="OqSelo_rcpt" style="display: none;"><label for="OqSelo"> <span>Quant selo IPI</span></label><input type="text" id="OqSelo"  name="qtd_selo" autocomplete="off" especialtype="integer"></td>
							</tr><tr>
								<td class="boxField" id="OcEnq_rcpt" style="display: none;"><label for="OcEnq"> <span>Cód. enquadramento</span></label><input type="text" id="OcEnq"  name="codigo_enquadramento" autocomplete="off" value="999" especialtype="integer" maxsize="3"></td>
								<td class="boxField" id="OTipoCalc_rcpt" style="display: none;"><label for="OTipoCalc"> <span>Tipo de cálculo</span></label><select id="OTipoCalc"  name="IPI_TipoCalc" autocomplete="off"><option value="1">Porcentagem</option><option value="2" disabled>Em valor</option></select></td>
								<td class="boxField" id="OpIPI_rcpt" style="display: none;"><label for="OpIPI"> <span>Alíquota IPI (%)</span></label><input type="text" id="OpIPI"  name="aliquota" autocomplete="off" class="percent"></td>
							</tr>
						</table>

						<h2>ICMS</h2>
						<table id="tableicms" class="bottomSpace">
							<tr>
								<td class="boxField" id="NCST_rcpt"><label for="NCST"> <span>Situação Tributária</span></label><select id="NCST" class="itemDados ui-widget-content ui-corner-all" name="situacao_tributaria" autocomplete="off"><option value="00">00: Tributada integralmente</option><option value="10">10: Tributada com cobr. por subst. trib.</option><option value="20">20: Com redução de base de cálculo</option><option value="30">30: Isenta ou não trib com cobr por subst trib</option><option value="40">40: Isenta</option><option value="41">41: Não tributada</option><option value="50">50: Suspensão</option><option value="51">51: Diferimento</option><option value="60">60: ICMS cobrado anteriormente por subst trib</option><option value="70">70: Redução de Base Calc e cobr ICMS por subst trib</option><option value="90">90: Outros</option><option value="10Part">Partilha 10: Entre UF origem e destino ou definida na legislação com Subst Trib</option><option value="90Part">Partilha 90: Entre UF origem e destino ou definida na legislação - outros</option><option value="41ST">Repasse 41: ICMS ST retido em operações interestaduais com repasses do ST - Não Trib.</option><option value="60ST">Repasse 60: ICMS ST retido em operações interestaduais com repasses do ST - Cobrado ant.</option><option value="101" selected>Simples Nacional: 101: Com permissão de crédito</option><option value="102">Simples Nacional: 102: Sem permissão de crédito</option><option value="103">Simples Nacional: 103: Isenção do ICMS para faixa de receita bruta</option><option value="201">Simples Nacional: 201: Com permissão de crédito, com cobr ICMS por Subst Trib</option><option value="202">Simples Nacional: 202: Sem permissão de crédito, com cobr ICMS por Subst Trib</option><option value="203">Simples Nacional: 203: Isenção ICMS p/ faixa de receita bruta e cobr do ICMS por ST</option><option value="300">Simples Nacional: 300: Imune</option><option value="400">Simples Nacional: 400: Não tributada</option><option value="500">Simples Nacional: 500: ICMS cobrado antes por subst trib ou antecipação</option><option value="900">Simples Nacional: 900: Outros</option></select></td>
								<td class="boxField" id="NmodBC_rcpt" style="display: none;"><label for="NmodBC"> <span>Modalidade BC</span></label><select id="NmodBC" class="itemDados ui-widget-content ui-corner-all" name="NmodBC" autocomplete="off" disabled_="disabled_"><option value="0">Margem valor adicionado</option><option value="1">Pauta (valor)</option><option value="2">Preço tabelado máx. (valor)</option><option value="3">Valor da operação</option></select></td>
								<td class="boxField" id="NpRedBC_rcpt" style="display: none;"><label for="NpRedBC"> <span>Redução Base Calc (%)</span></label><input type="text" id="NpRedBC" class="itemDados ui-widget-content ui-corner-all" name="NpRedBC" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBC_rcpt" style="display: none;"><label for="NvBC"> <span>Base de cálculo ($)</span></label><input type="text" id="NvBC"  readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBC" autocomplete="off" especialtype="percent" style="" disabled_="disabled_"></td>
								<td class="boxField" id="NpICMS_rcpt" style="display: table-cell;"><label for="NpICMS"> <span>Alíquota do ICMS (%)</span></label><input type="text" id="NpICMS" class="itemDados ui-widget-content ui-corner-all" name="NpICMS" autocomplete="off" especialtype="percent"></td>
								<td class="boxField" id="NvICMS_rcpt" style="display: none;"><label for="NvICMS"> <span>Valor do ICMS ($)</span></label><input type="text" id="NvICMS" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvICMS" autocomplete="off" style="" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCSTRet_rcpt" style="display: none;"><label for="NvBCSTRet"> <span>BC ST UF origem ($)</span></label><input type="text" id="NvBCSTRet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCSTRet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCSTRetHidden_rcpt" style="display: none;"><input type="hidden" id="NvBCSTRetHidden" class="" name="NvBCSTRetHidden" autocomplete="off"></td>
								<td class="boxField" id="NpST_rcpt" style="display: none;"><label for="NpST"> <span>Alíq. Cons. Final (%)</span></label><input type="text" id="NpST" class="undefined ui-widget-content ui-corner-all" name="NpST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSSubstituto_rcpt" style="display: none;"><label for="NvICMSSubstituto"> <span>ICMS próprio Substituto ($)</span></label><input type="text" id="NvICMSSubstituto"  readonly="readonly" tabindex="-1" class="readonlyField  ui-widget-content ui-corner-all" name="NvICMSSubstituto" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSSTRet_rcpt" style="display: none;"><label for="NvICMSSTRet"> <span>ICMS ST retido ant. ($)</span></label><input type="text" id="NvICMSSTRet"  readonly="readonly" tabindex="-1" class="readonlyField  ui-widget-content ui-corner-all" name="NvICMSSTRet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCSTDest_rcpt" style="display: none;"><label for="NvBCSTDest"> <span>BC ST UF dest ($)</span></label><input type="text" id="NvBCSTDest"  readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCSTDest" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSSTDest_rcpt" style="display: none;"><label for="NvICMSSTDest"> <span>ICMS ST UF destino ($)</span></label><input type="text" id="NvICMSSTDest" readonly="readonly" tabindex="-1" class="readonlyField  ui-widget-content ui-corner-all" name="NvICMSSTDest" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpCredSN_rcpt" style="display: none;"><label for="NpCredSN"> <span>Alíq. cálc. créd. (%)</span></label><input type="text" id="NpCredSN" class="itemDados ui-widget-content ui-corner-all" name="NpCredSN" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvCredICMSSN_rcpt" style="display: none;"><label for="NvCredICMSSN"> <span>Valor créd. ICMS ($)</span></label><input type="text" id="NvCredICMSSN"  readonly="readonly" tabindex="-1" class="readonlyField  ui-widget-content ui-corner-all" name="NvCredICMSSN" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							</tr>
							<tr>
								<td class="boxField" id="NmodBCST_rcpt" style="display: none;"><label for="NmodBCST"> <span>Modalidade BC ST</span></label><select id="NmodBCST" class="itemDados ui-widget-content ui-corner-all" name="NmodBCST" autocomplete="off" disabled_="disabled_"><option value="">Selecione...</option><option value="0">Tabelado ou máx. sugerido</option><option value="1">Lista negativa (valor)</option><option value="2">Lista positiva (valor)</option><option value="3">Lista neutra (valor)</option><option value="4">Margem valor adic. (%)</option><option value="5">Pauta (valor)</option><option value="6">Valor da operação</option></select></td>
								<td class="boxField" id="NpMVAST_rcpt" style="display: none;"><label for="NpMVAST"> <span>Margem valor adic. (%)</span></label><input type="text" id="NpMVAST" class="itemDados ui-widget-content ui-corner-all" name="NpMVAST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="meuPautaST_rcpt" style="display: none;"><label for="meuPautaST"> <span>Preço unit. Pauta ST</span></label><input type="text" id="meuPautaST" class="itemDados ui-widget-content ui-corner-all" name="meuPautaST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpRedBCST_rcpt" style="display: none;"><label for="NpRedBCST"> <span>Redução Base ST (%)</span></label><input type="text" id="NpRedBCST" class="itemDados ui-widget-content ui-corner-all" name="NpRedBCST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCST_rcpt" style="display: none;"><label for="NvBCST"> <span>Base cálc ST ($)</span></label><input type="text" id="NvBCST" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpICMSST_rcpt" style="display: none;"><label for="NpICMSST"> <span>Alíq. ICMS ST (%)</span></label><input type="text" id="NpICMSST" class="itemDados ui-widget-content ui-corner-all" name="NpICMSST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSST_rcpt" style="display: none;"><label for="NvICMSST"> <span>Valor ICMS ST ($)</span></label><input type="text" id="NvICMSST" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvICMSST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpBCOp_rcpt" style="display: none;"><label for="NpBCOp"> <span>Perc. BC op. própria (%)</span></label><input type="text" id="NpBCOp" class="itemDados ui-widget-content ui-corner-all" name="NpBCOp" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NUFST_rcpt" style="display: none;"><label for="NUFST"> <span>UF pgto ICMS ST</span></label><select id="NUFST" class="itemDados ui-widget-content ui-corner-all" name="NUFST" autocomplete="off" disabled_="disabled_"><option value=""></option><option value="AC">AC</option><option value="AL">AL</option><option value="AM">AM</option><option value="AP">AP</option><option value="BA">BA</option><option value="CE">CE</option><option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option><option value="MA">MA</option><option value="MG">MG</option><option value="MS">MS</option><option value="MT">MT</option><option value="PA">PA</option><option value="PB">PB</option><option value="PE">PE</option><option value="PI">PI</option><option value="PR">PR</option><option value="RJ">RJ</option><option value="RN">RN</option><option value="RO">RO</option><option value="RR">RR</option><option value="RS">RS</option><option value="SC">SC</option><option value="SE">SE</option><option value="SP">SP</option><option value="TO">TO</option></select></td>
							</tr>
							<tr>
								<td class="boxField" id="NmotDesICMS_rcpt" style="display: table-cell;"><label for="NmotDesICMS"> <span>Motivo desoneração</span></label><select id="NmotDesICMS" class="itemDados ui-widget-content ui-corner-all" name="NmotDesICMS" autocomplete="off"><option value="0">- Não desejo usar -</option><option value="1">1: Táxi</option><option value="3">3: Produtor agropecuário</option><option value="4">4: Frotista/locadora</option><option value="5">5: Diplomático/consular</option><option value="6">6: Util. e Motoc. Amazônia Oc. e  Livre Com.</option><option value="7">7: SUFRAMA</option><option value="8">8: Venda a Órgãos Públicos</option><option value="9">9: Outros</option><option value="10">10: Deficiente condutor</option><option value="11">11: Deficiente não condutor</option><option value="16">16: Olimpíadas Rio 2016</option><option value="90">90: Solicitado pelo Fisco</option></select></td>
								<td class="boxField" id="NvICMSDeson_rcpt" style="display: table-cell;"><label for="NvICMSDeson"> <span>ICMS desonerado (**)</span></label><input type="text" id="NvICMSDeson" class="undefined ui-widget-content ui-corner-all money" name="NvICMSDeson" autocomplete="off" especialtype="percent"></td>
								<td class="boxField" id="NmotDesICMSST_rcpt" style="display: none;"><label for="NmotDesICMSST"> <span>Motivo desoneração ST</span></label><select id="NmotDesICMSST" class="itemDados ui-widget-content ui-corner-all" name="NmotDesICMSST" autocomplete="off" disabled_="disabled_"><option value="0">- Não desejo usar -</option><option value="3">3: Produtor agropecuário</option><option value="9">9: Outros</option><option value="12">12: Órgão de fomento e desenvolv. agropecuário</option></select></td>
								<td class="boxField" id="NvICMSSTDeson_rcpt" style="display: none;"><label for="NvICMSSTDeson"> <span>ICMS ST desonerado (***)</span></label><input type="text" id="NvICMSSTDeson" class="undefined ui-widget-content ui-corner-all" name="NvICMSSTDeson" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSOp_rcpt" style="display: none;"><label for="NvICMSOp"> <span>ICMS da operação ($)</span></label><input type="text" id="NvICMSOp" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvICMSOp" autocomplete="off" disabled_="disabled_"></td>
								<td class="boxField" idvICMSDif="NpDif_rcpt" style="display: none;"><label for="NpDif"> <span>Aliq. diferimento (%)</span></label><input type="text" id="NpDif" class="undefined ui-widget-content ui-corner-all" name="NpDif" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSDif_rcpt" style="display: none;"><label for="NvICMSDif"> <span>ICMS diferido ($)</span></label><input type="text" id="NvICMSDif" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvICMSDif" autocomplete="off" disabled_="disabled_"></td>
								<td class="descr" colspan="4" id="descrICMSDif" style="display: none;">
									Quando há diferimento, o Valor do ICMS é igual<br>
									ao ICMS da operação menos o ICMS diferido.
								</td>
							</tr>
							<tr>
								<td class="boxField" id="NvBCFCP_rcpt" style="display: none;"><label for="NvBCFCP"> <span>BC FCP ($)</span></label><input type="text" id="NvBCFCP" class="undefined ui-widget-content ui-corner-all" name="NvBCFCP" autocomplete="off" especialtype="percent" style="" disabled_="disabled_"></td>
								<td class="boxField" id="NpFCP_rcpt" style="display: table-cell;"><label for="NpFCP"> <span>Perc. FCP (%)</span></label><input type="text" id="NpFCP" class="undefined ui-widget-content ui-corner-all" name="NpFCP" autocomplete="off" especialtype="percent"></td>
								<td class="boxField" id="NvFCP_rcpt" style="display: none;"><label for="NvFCP"> <span>Valor FCP ($)</span></label><input type="text" id="NvFCP" readonly="readonly" tabindex="-1" class="readonlyField ui-widget-content ui-corner-all" name="NvFCP" autocomplete="off" especialtype="percent" style="" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCFCPST_rcpt" style="display: none;"><label for="NvBCFCPST"> <span>BC FCP retido ST ($)</span></label><input type="text" id="NvBCFCPST" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCFCPST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpFCPST_rcpt" style="display: none;"><label for="NpFCPST"> <span>Perc. FCP retido ST (%)</span></label><input type="text" id="NpFCPST" class="undefined ui-widget-content ui-corner-all" name="NpFCPST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvFCPST_rcpt" style="display: none;"><label for="NvFCPST"> <span>Valor FCP retido ST ($)</span></label><input type="text" id="NvFCPST" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvFCPST" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCFCPSTRet_rcpt" style="display: none;"><label for="NvBCFCPSTRet"> <span>BC FCP retido ant. ST ($)</span></label><input type="text" id="NvBCFCPSTRet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCFCPSTRet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpFCPSTRet_rcpt" style="display: none;"><label for="NpFCPSTRet"> <span>Perc. FCP retido ant. ST (%)</span></label><input type="text" id="NpFCPSTRet" class="undefined ui-widget-content ui-corner-all" name="NpFCPSTRet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvFCPSTRet_rcpt" style="display: none;"><label for="NvFCPSTRet"> <span>Valor FCP retido ant. ST ($)</span></label><input type="text" id="NvFCPSTRet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvFCPSTRet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvFCPEfet_rcpt" style="display: none;"><label for="NvFCPEfet"> <span>Valor FCP da operação ($)</span></label><input type="text" id="NvFCPEfet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvFCPEfet" autocomplete="off" disabled_="disabled_"></td>
								<td class="boxField" id="NpFCPDif_rcpt" style="display: none;"><label for="NpFCPDif"> <span>Perc. FCP diferido (%)</span></label><input type="text" id="NpFCPDif" class="undefined ui-widget-content ui-corner-all" name="NpFCPDif" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvFCPDif_rcpt" style="display: none;"><label for="NvFCPDif"> <span>Valor FCP diferido ($)</span></label><input type="text" id="NvFCPDif" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvFCPDif" autocomplete="off" disabled_="disabled_"></td>
							</tr>
							<tr>
								<td><div id="meuICMSEfetCheck_rcpt" class="boxChoice"><label for="meuICMSEfetCheck"><input type="checkbox" id="meuICMSEfetCheck" class="undefined ui-widget-content ui-corner-all" name="meuICMSEfetCheck" autocomplete="off" value="1" disabled_="disabled_"> <span>Informar dados do ICMS efetivo</span></label></div></td>
							</tr>
							<tr>
								<td class="boxField" id="NpRedBCEfet_rcpt" style="display: none;"><label for="NpRedBCEfet"> <span>Perc. de red. da BC efetiva (%)</span></label><input type="text" id="NpRedBCEfet" class="undefined ui-widget-content ui-corner-all" name="NpRedBCEfet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvBCEfet_rcpt" style="display: none;"><label for="NvBCEfet"> <span>Valor BC efetiva ($)</span></label><input type="text" id="NvBCEfet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvBCEfet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NpICMSEfet_rcpt" style="display: none;"><label for="NpICMSEfet"> <span>Alíq. ICMS efetiva (%)</span></label><input type="text" id="NpICMSEfet" class="undefined ui-widget-content ui-corner-all" name="NpICMSEfet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
								<td class="boxField" id="NvICMSEfet_rcpt" style="display: none;"><label for="NvICMSEfet"> <span>Valor ICMS efetivo ($)</span></label><input type="text" id="NvICMSEfet" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="NvICMSEfet" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							</tr>
						</table>

					</div>

					<h2>PIS</h2>
					<table id="tablepis" class="bottomSpace">
						<tr>
							<td class="boxField" id="QCST_rcpt"><label for="QCST"> <span>Situação Tributária</span></label><select id="QCST" class="itemDados ui-widget-content ui-corner-all" name="situacao_tributaria" autocomplete="off"><option value="01">01: Operação tributável (BC = Operação alíq. normal (cumul./não cumul.)</option><option value="02">02: Operação tributável (BC = valor da operação (alíquota diferenciada)</option><option value="03">03: Operação tributável (BC = quant. x alíq. por unidade de produto)</option><option value="04">04: Operação tributável (tributação monofásica, alíquota zero)</option><option value="05">05: Operação tributável (substituição tributária)</option><option value="06">06: Operação tributável (alíquota zero)</option><option value="07">07: Operação isenta da contribuição</option><option value="08">08: Operação sem incidência da contribuição</option><option value="09">09: Operação com suspensão da contribuição</option><option value="49">49: Outras Operações de Saída</option><option value="50">50: Direito a Crédito. Vinculada Exclusivamente a Receita Tributada no Mercado Interno</option><option value="51">51: Direito a Crédito. Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno</option><option value="52">52: Direito a Crédito. Vinculada Exclusivamente a Receita de Exportação</option><option value="53">53: Direito a Crédito. Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno</option><option value="54">54: Direito a Crédito. Vinculada a Receitas Tributadas no Mercado Interno e de Exportação</option><option value="55">55: Direito a Crédito. Vinculada a Receitas Não-Trib. no Mercado Interno e de Exportação</option><option value="56">56: Direito a Crédito. Vinculada a Rec. Trib. e Não-Trib. Mercado Interno e Exportação</option><option value="60">60: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita Tributada no Mercado Interno</option><option value="61">61: Créd. Presumido. Aquisição Vinc. Exclusivamente a Rec. Não-Trib. no Mercado Interno</option><option value="62">62: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita de Exportação</option><option value="63">63: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. no Mercado Interno</option><option value="64">64: Créd. Presumido. Aquisição Vinc. a Rec. Trib. no Mercado Interno e de Exportação</option><option value="65">65: Créd. Presumido. Aquisição Vinc. a Rec. Não-Trib. Mercado Interno e Exportação</option><option value="66">66: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. Mercado Interno e Exportação</option><option value="67">67: Crédito Presumido - Outras Operações</option><option value="70">70: Operação de Aquisição sem Direito a Crédito</option><option value="71">71: Operação de Aquisição com Isenção</option><option value="72">72: Operação de Aquisição com Suspensão</option><option value="73">73: Operação de Aquisição a Alíquota Zero</option><option value="74">74: Operação de Aquisição sem Incidência da Contribuição</option><option value="75">75: Operação de Aquisição por Substituição Tributária</option><option value="98">98: Outras Operações de Entrada</option><option value="99" selected>99: Outras operações</option></select></td>
							<td class="boxField" id="QTipoCalc_rcpt" style="display: none;"><label for="QTipoCalc"> <span>Tipo de cálculo</span></label><select id="QTipoCalc" class="undefined ui-widget-content ui-corner-all" name="QTipoCalc" autocomplete="off"><option value="1">Porcentagem</option><option value="2" disabled>Em valor</option></select></td>
							<td class="boxField" id="QvBC_rcpt" style="display: table-cell;"><label for="QvBC"> <span>Base Calc PIS</span></label><input type="text" id="QvBC" readonly="readonly" tabindex="-1" class="readonlyField ui-widget-content ui-corner-all" name="QvBC" autocomplete="off" especialtype="percent" style=""></td>
							<td class="boxField" id="QpPIS_rcpt" style="display: table-cell;"><label for="QpPIS"> <span>Alíquota PIS</span></label><input type="text" id="QpPIS" class="itemDados ui-widget-content ui-corner-all" name="aliquota" autocomplete="off" especialtype="percent"></td>
							<td class="boxField" id="QvAliqProd_rcpt" style="display: none;"><label for="QvAliqProd"> <span>Valor unid trib PIS</span></label><input type="text" id="QvAliqProd" class="itemDados ui-widget-content ui-corner-all" name="QvAliqProd" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							<td class="boxField" id="QvPIS_rcpt" style="display: table-cell;"><label for="QvPIS"> <span>Valor do PIS</span></label><input type="text" id="QvPIS" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="QvPIS" autocomplete="off" style=""></td>
						</tr>
						<tr>
							<td class="boxField" id="RTipoCalc_rcpt"><label for="RTipoCalc"> <span>Tipo de cálculo Subst. Trib.</span></label><select id="RTipoCalc" class="itemDados ui-widget-content ui-corner-all" name="RTipoCalc" autocomplete="off"><option value="0">- Não desejo usar -</option><option value="1" selected>Porcentagem</option><option value="2" disabled>Em valor</option></select></td>
							<td class="boxField" id="RvBC_rcpt" style="display: table-cell;"><label for="RvBC"> <span>Base Calc PIS ST</span></label><input type="text" id="RvBC" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="RvBC" autocomplete="off" especialtype="percent" style=""></td>
							<td class="boxField" id="RpPIS_rcpt" style="display: table-cell;"><label for="RpPIS"> <span>Alíquota PIS ST</span></label><input type="text" id="RpPIS" class="itemDados ui-widget-content ui-corner-all" name="RpPIS" autocomplete="off" especialtype="percent"></td>
							<td class="boxField" id="RvAliqProd_rcpt" style="display: none;"><label for="RvAliqProd"> <span>Valor unid trib PIS ST</span></label><input type="text" id="RvAliqProd" class="itemDados ui-widget-content ui-corner-all" name="RvAliqProd" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							<td class="boxField" id="RvPIS_rcpt" style="display: table-cell;"><label for="RvPIS"> <span>Valor do PIS ST</span></label><input type="text" id="RvPIS" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="RvPIS" autocomplete="off" style=""></td>
						</tr>
			</table>

					<h2>COFINS</h2>

					<table  id="tablecofins" class="bottomSpace">
						<tr>
							<td class="boxField" id="SCST_rcpt"><label for="SCST"> <span>Situação Tributária</span></label><select id="SCST" class="itemDados ui-widget-content ui-corner-all" name="situacao_tributaria" autocomplete="off"><option value="01">01: Operação tributável (BC = Operação alíq. normal (cumul./não cumul.)</option><option value="02">02: Operação tributável (BC = valor da operação (alíquota diferenciada)</option><option value="03">03: Operação tributável (BC = quant. x alíq. por unidade de produto)</option><option value="04">04: Operação tributável (tributação monofásica, alíquota zero)</option><option value="05">05: Operação tributável (substituição tributária)</option><option value="06">06: Operação tributável (alíquota zero)</option><option value="07">07: Operação isenta da contribuição</option><option value="08">08: Operação sem incidência da contribuição</option><option value="09">09: Operação com suspensão da contribuição</option><option value="49">49: Outras Operações de Saída</option><option value="50">50: Direito a Crédito. Vinculada Exclusivamente a Receita Tributada no Mercado Interno</option><option value="51">51: Direito a Crédito. Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno</option><option value="52">52: Direito a Crédito. Vinculada Exclusivamente a Receita de Exportação</option><option value="53">53: Direito a Crédito. Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno</option><option value="54">54: Direito a Crédito. Vinculada a Receitas Tributadas no Mercado Interno e de Exportação</option><option value="55">55: Direito a Crédito. Vinculada a Receitas Não-Trib. no Mercado Interno e de Exportação</option><option value="56">56: Direito a Crédito. Vinculada a Rec. Trib. e Não-Trib. Mercado Interno e Exportação</option><option value="60">60: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita Tributada no Mercado Interno</option><option value="61">61: Créd. Presumido. Aquisição Vinc. Exclusivamente a Rec. Não-Trib. no Mercado Interno</option><option value="62">62: Créd. Presumido. Aquisição Vinc. Exclusivamente a Receita de Exportação</option><option value="63">63: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. no Mercado Interno</option><option value="64">64: Créd. Presumido. Aquisição Vinc. a Rec. Trib. no Mercado Interno e de Exportação</option><option value="65">65: Créd. Presumido. Aquisição Vinc. a Rec. Não-Trib. Mercado Interno e Exportação</option><option value="66">66: Créd. Presumido. Aquisição Vinc. a Rec. Trib. e Não-Trib. Mercado Interno e Exportação</option><option value="67">67: Crédito Presumido - Outras Operações</option><option value="70">70: Operação de Aquisição sem Direito a Crédito</option><option value="71">71: Operação de Aquisição com Isenção</option><option value="72">72: Operação de Aquisição com Suspensão</option><option value="73">73: Operação de Aquisição a Alíquota Zero</option><option value="74">74: Operação de Aquisição sem Incidência da Contribuição</option><option value="75">75: Operação de Aquisição por Substituição Tributária</option><option value="98">98: Outras Operações de Entrada</option><option value="99" selected>99: Outras operações</option></select></td>
							<td class="boxField" id="STipoCalc_rcpt" style="display: none;"><label for="STipoCalc"> <span>Tipo de cálculo</span></label><select id="STipoCalc" class="undefined ui-widget-content ui-corner-all" name="STipoCalc" autocomplete="off"><option value="1">Porcentagem</option><option value="2" disabled>Em valor</option></select></td>
							<td class="boxField" id="SvBC_rcpt" style="display: none;"><label for="SvBC"> <span>Base Calc COFINS</span></label><input type="text" id="SvBC" class="undefined ui-widget-content ui-corner-all" name="SvBC" autocomplete="off" especialtype="percent" readonly="readonly" tabindex="-1" class="readonlyField" disabled_="disabled_"></td>
							<td class="boxField" id="SpCOFINS_rcpt" style="display: none;"><label for="SpCOFINS"> <span>Alíquota COFINS (%)</span></label><input type="text" id="SpCOFINS" class="itemDados ui-widget-content ui-corner-all" name="aliquota" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							<td class="boxField" id="SvAliqProd_rcpt" style="display: table-cell;"><label for="SvAliqProd"> <span>Valor do COFINS ($)</span></label><input type="text" id="SvAliqProd" class="itemDados ui-widget-content ui-corner-all" name="SvAliqProd" autocomplete="off" especialtype="percent"></td>
							<td class="boxField" id="SvCOFINS_rcpt" style="display: table-cell;"><label for="SvCOFINS"> <span>Valor unid trib COFINS</span></label><input type="text" id="SvCOFINS" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="SvCOFINS" autocomplete="off" style=""></td>
						</tr>
						<tr>
							<td class="boxField" id="TTipoCalc_rcpt"><label for="TTipoCalc"> <span>Tipo de cálculo Subst. Trib.</span></label><select id="TTipoCalc" class="itemDados ui-widget-content ui-corner-all" name="TTipoCalc" autocomplete="off"><option value="0">- Não desejo usar -</option><option value="1" selected>Porcentagem</option><option value="2" disabled>Em valor</option></select></td>
							<td class="boxField" id="TvBC_rcpt" style="display: table-cell;"><label for="TvBC"> <span>Base Calc COFINS ST</span></label><input type="text" id="TvBC"readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="TvBC" autocomplete="off" especialtype="percent" style=""></td>
							<td class="boxField" id="TpCOFINS_rcpt" style="display: table-cell;"><label for="TpCOFINS"> <span>Alíquota COFINS ST</span></label><input type="text" id="TpCOFINS" class="itemDados ui-widget-content ui-corner-all" name="TpCOFINS" autocomplete="off" especialtype="percent"></td>
							<td class="boxField" id="TvAliqProd_rcpt" style="display: none;"><label for="TvAliqProd"> <span>Valor unid trib COFINS ST</span></label><input type="text" id="TvAliqProd" class="itemDados ui-widget-content ui-corner-all" name="TvAliqProd" autocomplete="off" especialtype="percent" disabled_="disabled_"></td>
							<td class="boxField" id="TvCOFINS_rcpt" style="display: table-cell;"><label for="TvCOFINS"> <span>Valor do COFINS ST</span></label><input type="text" id="TvCOFINS" readonly="readonly" tabindex="-1" class="readonlyField undefined ui-widget-content ui-corner-all" name="TvCOFINS" autocomplete="off" style=""></td>
						</tr>
					</table>


			
					<div class="boxProduto" >
						<h2>Comércio exterior</h2> 
						<div id="meuUsarImp_rcpt" class="NFCeHide"><label for="meuUsarImp"><input type="checkbox" id="meuUsarImp"  name="meuUsarImp" autocomplete="off" value="1"> <span>Informar dados de importação</span></label></div>
						<div class="bottomSpace NFCeHide">
							<div id="quadroProdII" style="display: none;">
								<table id="tablemeuUsarImp">
									<tr>
										<td class="boxField" id="PvII_rcpt"><label for="PvII"> <span>Aliquota II</span></label><input type="text" id="PvII"  name="aliquota" autocomplete="off" class="money"></td>
										<td class="boxField" id="PvIOF_rcpt"><label for="PvIOF"> <span>Valor do IOF</span></label><input type="text" id="PvIOF"  name="iof" autocomplete="off" class="money"></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="width:100%;margin-top:30px;float:left">
					<button id="savebtnimpostos" class="btn btn-success">Salvar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
		
		