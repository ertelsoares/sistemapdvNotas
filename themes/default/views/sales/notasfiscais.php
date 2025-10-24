<script>
	$(document).ready(function () {
		$('#SLData').dataTable({
			"aLengthMenu": [[10, 25, 50, 50, 100, -1], [10, 25, 50, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 0, "desc" ]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
			'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/get_notasfiscais') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender":hrld}, null, {"mRender": function ( data, type, row ) {
				if(data!="" && data!=null) { if(data=="55"){ var cc = "NF"; }else{ var cc = "NFC"; } } else{ var cc = ""; }
				return cc;
				}}, null, null, {"bSortable":false, "bSearchable": false}]
		});
	});
	
	
	function exportXML(modelo){
	    
		$.ajax({url: base_url + "api-nfe/gerador/ExportarXML.php?codigoUF=<?= $Settings->codigoUF ?>&cnpj=<? echo str_replace(array(".", ",", "-", " ", "/"), "", $Settings->vat_no); ?>&modelo=" + modelo +"&mes=" + $("#messelect").val() + "&ano=" + $("#anoselect").val(), type: "POST", dataType: 'jsonp', success: function(data){
			  if(data.result){ 
				   window.open(base_url + "api-nfe/gerador/" + data.url, '_blank');
			  }else{
				  alert("Erro ao exportar notas modelo ("+ modelo +"), tente novamente");
			  }
		   }
		});
		   
	   }
</script>

<section class="content">
	<div class="row">
	
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('list_results'); ?></h3>
				</div>
				<div class="box-body">
				<select id="messelect" style="width:170px;">
            	       <option value="01" <? if(date("m")=="01") echo "selected='selected'";?>>Janeiro</option>
            	       <option value="02" <? if(date("m")=="02") echo "selected='selected'";?>>Fevereiro</option>
            	       <option value="03" <? if(date("m")=="03") echo "selected='selected'";?>>Março</option>
            	       <option value="04" <? if(date("m")=="04") echo "selected='selected'";?>>Abril</option>
            	       <option value="05" <? if(date("m")=="05") echo "selected='selected'";?>>Maio</option>
            	       <option value="06" <? if(date("m")=="06") echo "selected='selected'";?>>Junho</option>
            	       <option value="07" <? if(date("m")=="07") echo "selected='selected'";?>>Julho</option>
            	       <option value="08" <? if(date("m")=="08") echo "selected='selected'";?>>Agosto</option>
            	       <option value="09" <? if(date("m")=="09") echo "selected='selected'";?>>Setembro</option>
            	       <option value="10" <? if(date("m")=="10") echo "selected='selected'";?>>Outubro</option>
            	       <option value="11" <? if(date("m")=="11") echo "selected='selected'";?>>Novembro</option>
            	       <option value="12" <? if(date("m")=="12") echo "selected='selected'";?>>Dezembro</option>
            	   </select> 

				   <select id="anoselect" style="width:100px;">
				   <?php for($x = 18; $x <= date("y"); $x++): ?>
             	       <option value="<?=$x;?>" <? if($x == date("y")) echo "selected='selected'";?>>20<?=$x;?></option>
						<?php endfor; ?>
            	   </select> 

				   <button type="button" onclick="exportXML(65)" class="btn btn-success">Exportar NFC</button> <button type="button" onclick="exportXML(55)"  class="btn btn-success">Exportar NF</button>  <a onClick="MyWindow3=window.open('<?= site_url('pos/nfe_contingencia') ?>', 'MyWindow3','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=700,height=800'); return false;" href="javascript:void(0)"  class="btn btn-warning">Enviar Notas Contingência</a></span>
      
            	 </div> 
				<div class="box-body">
					<div class="table-responsive">
						<table id="SLData" class="table table-striped table-bordered table-condensed table-hover">
							<thead>
								<tr class="active">
								<th style="width: 50px;"><?php echo $this->lang->line("date"); ?></th>
									<th class="col-xs-1">Número</th>
									<th class="col-xs-1">Modelo</th>
									<th class="col-xs-1">Status</th>
									<th class="col-xs-1">Chave</th>
									<th style="width:40px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</section>