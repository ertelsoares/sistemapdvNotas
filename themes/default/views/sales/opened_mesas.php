<style type="text/css">.mesas_div{height: 80px; padding: 20px 0px; text-align: center; font-size: 20px; color: #FFF; margin: 5px 0px; border-radius: 3px; cursor: pointer;}.ocupado{background:#ff9000;}.livre{background:#28b72e}.personalizado{background:blue}</style>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-body">
						<div class="row">
						<?php for($x = 1; $x <= $this->Settings->total_mesas; $x++){
							$status = "livre";
							if(!empty($vendas_abertas["mesa_".$x])){
								$status = "ocupado";
							}
							?>
							<div class="col-sm-3">
								<div class="mesas_div <?=$status?>" onclick="accMesa('<?=$vendas_abertas["mesa_".$x]["id"];?>','mesa_<?=$x;?>')">Mesa <?=$x;?><br>
								<?php if(!empty($vendas_abertas["mesa_".$x])){ ?><span id="saldo" style="font-size:15px;">Total: R$ <?=number_format($vendas_abertas["mesa_".$x]["grand_total"], 2, ',', '.');?> / Saldo: R$ <?=number_format($vendas_abertas["mesa_".$x]["faltatotal"], 2, ',', '.');?></span><?php } ?>
							</div>
							</div>
						<?php } ?>

						<?php foreach($vendas_abertas as $k => $v){
							$status = "personalizado";
							if (strpos($k, 'mesa_') === false) {
							?>
							<div class="col-sm-3">
								<div class="mesas_div <?=$status?>" onclick="accMesa('<?=$v["id"];?>','')"><?=$v["hold_ref"];?><br>
								<span id="saldo" style="font-size:15px;">Total: R$ <?=number_format($v["grand_total"], 2, ',', '.');?> / Saldo: R$ <?=number_format($v["faltatotal"], 2, ',', '.');?></span>
							</div>
							</div>
						<?php } } ?>
						</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal" data-easein="flipYIn" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			</div>
			<div class="modal-body">
				<div class='text-center'>
					<a id="menu_0"  title='<?=lang("click_to_add")?>' class=' btn btn-success btn-flat' data-toggle='ajax' style="width: 100%;"><i class='fa fa-list'></i> <?=lang("gerar_orcamento")?></a><br><br>
					<a id="menu_1"  title='<?=lang("click_to_add")?>' class=' btn btn-info btn-flat' style="width: 100%;"><i class='fa fa-th-large'></i> <?=lang("click_to_add")?></a><br><br>
					<a id="menu_2"  title='<?=lang("view_payments")?>' class=' btn btn-primary btn-flat' data-toggle='ajax' style="width: 100%;"><i class='fa fa-money'></i> <?=lang("view_payments")?></a> <br>
					<a id="menu_3"  title='<?=lang("add_payment")?>' class=' btn btn-primary btn-flat' data-toggle='ajax' style="width: 100%;"><i class='fa fa-briefcase'></i> <?=lang("add_payment")?></a><br><br>
					<a id="menu_4"   onClick="return confirm('<?=lang('alert_x_holded')?>')" title='<?=lang("delete_sale")?>' class=' btn btn-danger btn-flat' style="width: 100%;"><i class='fa fa-trash-o'></i> <?=lang("delete_sale")?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function accMesa(holdid, mesaid){
	if(holdid==""){
		window.location = "<?=site_url('pos')?>?hold_sugestao="+mesaid;
	}else{
		$("#menu_0").attr('href', '<?=site_url('/sales/iframeredi?link=').urlencode(site_url('pos/view_bill?notshowpdv=1'));?>%26hold='+holdid);
		$("#menu_1").attr('href', '<?=site_url('pos')?>?hold='+holdid);
		$("#menu_2").attr('href', '<?=site_url('sales/payments')?>/open_'+holdid);
		$("#menu_3").attr('href', '<?=site_url('sales/add_payment')?>/open_'+holdid);
		$("#menu_4").attr('href', '<?=site_url('sales/delete_holded')?>/'+holdid);

		$('#susModal').modal('show');
	}
}
</script>