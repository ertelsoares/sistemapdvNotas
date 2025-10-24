<?php
$v = "?v=1";

    if($this->input->get('customer')){
        $v .= "&customer=".$this->input->get('customer');
    }
    if($this->input->get('user')){
        $v .= "&user=".$this->input->get('user');
    }
    if($this->input->get('start_date')){
        $v .= "&start_date=".$this->input->get('start_date');
    }
    if($this->input->get('end_date')) {
        $v .= "&end_date=".$this->input->get('end_date');
    }
    if($this->input->get('ispaid')) {
        $v .= "&ispaid=".$this->input->get('ispaid');
    }
    if($this->input->get('tipo')) {
        $v .= "&tipo=".$this->input->get('tipo');
    }
?>

<script>
    $(document).ready(function () {

        function eventFired(t){
            var v_total = 0;
            var v_tax = 0;
            var v_descontos = 0;
            var v_valortotal = 0;
            var v_pago = 0;
            var v_faltapagar = 0;

          $('#SLRData tbody tr').each(function() {

              v = $(this).find("td:nth-child(4)").text().replace(".", "").replace(",", ".");
              v_total += parseFloat((v=="" || v==0 || v==null)? 0: v);

              v = $(this).find("td:nth-child(5)").text().replace(".", "").replace(",", ".");
              v_tax += parseFloat((v=="" || v==0 || v==null)? 0: v);

              v = $(this).find("td:nth-child(6)").text().replace(".", "").replace(",", ".");
              v_descontos += parseFloat((v=="" || v==0|| v==null)? 0: v);
              
              v = $(this).find("td:nth-child(7)").text().replace(".", "").replace(",", ".");
              v_valortotal += parseFloat((v=="" || v==0|| v==null)? 0: v);
              
              v = $(this).find("td:nth-child(8)").text().replace(".", "").replace(",", ".");
              v_troco = $(this).find("td:nth-child(9)").text().replace(".", "").replace(",", ".");
              v = parseFloat((v=="" || v==0 || v==null)? 0: v);
              v_troco = parseFloat((v_troco=="" || v_troco<=0 || v_troco==null)? 0: v_troco);
              v_pago += (v - v_troco);
              
              v_troco = $(this).find("td:nth-child(9)").text().replace(".", "").replace(",", ".");
              v_faltapagar += (v_troco<0) ? (v_troco * -1) : 0;
              
          });
          
           $('#SLRData tbody').append('<tr class="totais"><td></td><td></td><td></td><td ><div style="text-align:right">'+numberToReal(v_total)+'</div></td><td ><div style="text-align:right">'+numberToReal(v_tax)+'</div></td><td ><div style="text-align:right">'+numberToReal(v_descontos)+'</div></td><td ><div style="text-align:right">'+numberToReal(v_valortotal)+'</div></td><td ><div style="text-align:right">'+numberToReal(v_pago)+'</div></td><td ><div style="text-align:right">'+numberToReal(v_faltapagar)+'</div></td><td></td><td></td></tr>');
                   
     }

     function statusPag(i){
		  if(i=="Pago"){var color = "green"; }
		  else if(i=="Parcial"){var color = "#ff9800"; }
		  else{ var color = "red"; }

		  return "<span style='font-weight:bold;color:"+color+"'>"+i+"</span>";
	  }
     
     function numberToReal(numero = "", decimal = 2) {
         if(numero=="" || numero == null) return 0;
          return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
      }

        dt = $('#SLRData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 0, "desc" ]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': false, 
            'bServerSide': false,
            'sAjaxSource': '<?= site_url('reports/get_sales/'. $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, {"mRender":hrld}, null, {"mRender":currencyFormat}, {"mRender":currencyFormat}, {"mRender":currencyFormat}, {"mRender":currencyFormat}, {"mRender":currencyFormat},  {"mRender":currencyFormat}, {"mRender":currencyFormat},  {"mRender":statusPag}],
            "fnDrawCallback": function() {
              eventFired( 'fnDrawCallback' );
            },
              "fnInitComplete": function(oSettings, json) {
                  //eventFired( 'Init' );
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form').hide();
        <?php if($_GET["customer"]!=""){ ?>
            $("#form").slideToggle();
        <?php } ?>
        $('.toggle_form').click(function(){
            $("#form").slideToggle();
            return false;
        });
    });
</script>
<style type="text/css">
.table td { text-align: center; }
.table > tbody > tr:last-child > td { background: #cfcfcf; font-size: 16px; font-weight: 700; ;}
@media print {
.linkcustomer_1,.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .box-header, #form, table.dataTable.table-condensed thead .sorting:after, table.dataTable.table-condensed thead .sorting_asc:after, table.dataTable.table-condensed thead .sorting_desc:after{display:none;}
.linkcustomer_2{display:block!important;}
.box { box-shadow: 0!important; }
.content-header h1{display:block!important}
}
</style>
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="#" class="btn btn-default btn-sm toggle_form pull-right"><?= lang("show_hide"); ?></a>
                    <h3 class="box-title"><?= lang('customize_report'); ?></h3>
                </div>
                <div class="box-body">
                        <div id="form" class="panel panel-warning">
                            <div class="panel-body">
                            <?=form_open('reports', array('method'=>'get')); ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                        <?php
                                        $cu[0] = lang("select")." ".lang("customer");
                                        foreach($customers as $customer){
                                            $cu[$customer->id] = $customer->name;
                                            if(!empty($customer->cf1)){ 
                                                if(!empty($customer->name)){
                                                    $cu[$customer->id] .= " ({$customer->cf1})"; 
                                                }else{
                                                    $cu[$customer->id] .= $customer->cf1; 
                                                }
                                            } 
                                        }
                                        echo form_dropdown('customer', $cu, (!empty($_GET['customer']) ?$_GET['customer'] : ""), 'class="form-control select2" style="width:100%" id="customer"'); ?>
                                    </div>
                                </div>
                                <? if($isAdmin==true){ ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label" for="user"><?= lang("user"); ?>/Vendedor</label>
                                            <?php
                                            $us[0] = "Selecione...";
                                            foreach ($users as $user) {
                                                $us[$user->id] = $user->first_name . " " . $user->last_name;
                                            }
                                            echo form_dropdown('user', $us, (!empty($_GET['user']) ? $_GET['user'] : ""), 'class="form-control select2" id="user" data-placeholder="' . lang("select") . " " . lang("user") . '" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                <? } ?>
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

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="ispaid">Estado</label>
                                        <?
                                        $p = array("0" => "Selecione...", "1" => "Pago", "2" => "Parcial e Não pago", "3" => "Parcial", "4" => "Não pago");
                                        echo form_dropdown('ispaid', $p, (!empty($_GET['ispaid']) ? $_GET['ispaid'] : ""), 'class="form-control select2" id="ispaid" style="width:100%;"');?>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>  <a  href="./reports" class="btn btn-warning">Limpar</a>
                                </div>
                            </div>
                            <?= form_close();?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php if($this->input->get('customer')) { ?>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6"><button class="btn btn-primary btn-lg btn-block" style="cursor:default;"><strong><?= lang("total_sales"); ?>: <?=$total_sales?></strong></button></div>
                        <div class="col-md-6"><button class="btn btn-success btn-lg btn-block" style="cursor:default;"><strong><?= lang("total_sales_value"); ?>: <?=$total_sales_value ? $total_sales_value : 0;?></strong></button></div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="SLRData" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th class="col-sm-1">Venda</th>
                                            <th class="col-sm-2"><?= lang("date"); ?></th>
                                            <th class="col-sm-2"><?= lang("customer"); ?></th>
                                            <th class="col-sm-1"><?= lang("total"); ?></th>
                                            <th class="col-sm-1"><?= lang("tax"); ?></th>
                                            <th class="col-sm-1"><?= lang("discount"); ?></th>
                                            <th class="col-sm-1"><?= lang("grand_total"); ?></th>
                                            <th class="col-sm-1"><?= lang("paid"); ?></th>
                                            <th class="col-sm-1"><?= lang("balance"); ?></th>
                                            <th class="col-sm-1">Troco</th>
                                            <th class="col-sm-1">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    

                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= $assets ?>dist/js/jquery.mask.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            locale: "pt-br"
        });
        $('.datetimepicker').mask('00/00/0000 00:00');
    });
</script>

