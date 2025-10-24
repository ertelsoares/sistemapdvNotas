<?php
$v = "?v=1";
    if($this->input->get('status')!=""){
        $v .= "&status=".$this->input->get('status');
    }
    if($this->input->get('start_date')){
        $v .= "&start_date=".$this->input->get('start_date');
    }
    if($this->input->get('end_date')) {
        $v .= "&end_date=".$this->input->get('end_date');
    }
?>
<script>
    $(document).ready(function () {
        function attach(x) {
            if(x !== null) {
                return '<a href="<?=base_url();?>uploads/'+x+'" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-chain"></i></a>';
            }
            return '';
        }

        function eventFired(t){
          var v_pago = 0;

          $('#expData tbody tr').each(function() {

              v = $(this).find("td:nth-child(4)").text().replace(".", "").replace(",", ".");
              v_pago += parseFloat((v=="" || v==0 || v==null)? 0: v);
              
          });
          
           $('#expData tbody').append('<tr><td></td><td></td><td></td><td ><div style="text-align:right">'+numberToReal(v_pago)+'</div></td><td></td><td></td><td></td><td></td></tr>');
                   
     }
     
     function numberToReal(numero = "", decimal = 2) {
         if(numero=="" || numero == null) return 0;
          return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
      }
      
function statuspago(x){
    if(x=="0"){
        return "<span style='color:red'>Não Pago</span>";
    }else if(x=="1"){
        return "<span style='color:green'>Pago</span>";
    }else{
         return "-";
    }
}
        $('#expData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 0, "desc" ]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('purchases/get_expenses'.$v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender":statuspago}, {"mRender":hrsd}, null, {"mRender":currencyFormat}, null, null, {"mRender":attach, "bSortable":false, "bSearchable": false},{"bSortable":false, "bSearchable": false}],
            "fnDrawCallback": function() {
              eventFired( 'fnDrawCallback' );
            },
              "fnInitComplete": function(oSettings, json) {
                  //eventFired( 'Init' );
            }
        });

    });

</script>
<style type="text/css">
.table td { text-align: center; }
.table > tbody > tr:last-child > td { background: #cfcfcf; font-size: 16px; font-weight: 700;}
@media print {
a, button, .linkcustomer_1,.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .box-header, #form, table.dataTable.table-condensed thead .sorting:after, table.dataTable.table-condensed thead .sorting_asc:after, table.dataTable.table-condensed thead .sorting_desc:after{display:none!important;}
.linkcustomer_2{display:block!important;}
.box { box-shadow: 0!important; }
.content-header h1{display:block!important}
}
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('#form').hide();
                $('.toggle_form').click(function(){
            $("#form").slideToggle();
            return false;
        });
    });
</script>
<script src="<?= $assets ?>dist/js/jquery.mask.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var spoitems = {};
    var lang = new Array();
    lang['code_error'] = '<?= lang('code_error'); ?>';
    lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
    lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".datetimepicker").datetimepicker({
            format: 'DD/MM/YYYY',
            locale: "pt-br"
        });
        $('.datetimepicker').mask('00/00/0000');
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                	<div class="box-header">
					<a href="#" class="btn btn-default btn-sm toggle_form pull-right"><?= lang("show_hide"); ?></a>
					<h3 class="box-title"><?= lang('list_results'); ?></h3>
				</div>
				<div class="box-body">
				<div id="form" class="panel panel-warning" style="display:none">
                    <div class="panel-body">
                        <?=form_open('purchases/expenses', array('method'=>'get')); ?>

                        <div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label" for="status">Estado</label>
										<?php
										$us[""] = "Todos";
										$us["1"] = "Pago";
										$us["0"] = "Não pago";
										echo form_dropdown('status', $us, $_GET['status'], 'class="form-control select2" id="status" style="width:100%;"');
										?>
									</div>
								</div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="start_date"><?= lang("start_date"); ?></label>
                                    <?= form_input('start_date', (!empty($_GET['start_date']) ? $_GET['start_date'] : ""), 'class="form-control datetimepicker" onfocus="this.select();" id="start_date"');?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="end_date"><?= lang("end_date"); ?></label>
                                    <?= form_input('end_date', (!empty($_GET['end_date']) ? $_GET['end_date'] : ""), 'class="form-control datetimepicker" onfocus="this.select();" id="end_date"');?>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>  <a  href="<?=site_url('purchases/expenses');?>" class="btn btn-warning">Limpar</a>
                            </div>
                        </div>
                        <?= form_close();?>
                    </div>
				</div>
                <div class="box-body">
                    <div class="table-responsive">
                    <table id="expData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th class="col-xs-2">Pago</th>
                            <th class="col-xs-2">Vencimento</th>
                            <th class="col-xs-2"><?php echo $this->lang->line("reference"); ?></th>
                            <th class="col-xs-1"><?php echo $this->lang->line("amount"); ?></th>
                            <th class="col-xs-4"><?php echo $this->lang->line("note"); ?></th>
                            <th class="col-xs-2"><?php echo $this->lang->line("created_by"); ?></th>
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
                            <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
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