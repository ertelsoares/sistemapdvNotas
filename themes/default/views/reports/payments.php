<?php

foreach($meiopagamento as $pagamento){ $pag[$pagamento->cod] = $pagamento->nome; } 


$v = "?v=1";

if ($this->input->get('payment_ref')) {
    $v .= "&payment_ref=" . $this->input->get('payment_ref');
}
if ($this->input->get('sale_no')) {
    $v .= "&sale_no=" . $this->input->get('sale_no');
}
if ($this->input->get('customer')) {
    $v .= "&customer=" . $this->input->get('customer');
}
if ($this->input->get('paid_by')) {
    $v .= "&paid_by=" . $this->input->get('paid_by');
}
if ($this->input->get('user')) {
    $v .= "&user=" . $this->input->get('user');
}
if ($this->input->get('vendedor')) {
    $v .= "&vendedor=" . $this->input->get('vendedor');
}
if ($this->input->get('start_date')) {
    $v .= "&start_date=" . $this->input->get('start_date');
}
if ($this->input->get('end_date')) {
    $v .= "&end_date=" . $this->input->get('end_date');
}
?>
<script>
    $(document).ready(function () {
        var pb = [];
         <?php foreach($meiopagamento as $pagamento){ echo "pb['{$pagamento->cod}'] = '{$pagamento->nome}'; "; } ?>

        function paid_by(x) {
         return pb[x];
        }

        function eventFired(t){
           var v_total = 0;

          $('#PayRData tbody tr').each(function() {

              v = $(this).find("td:nth-child(5)").text().replace(".", "").replace(",", ".");
              v_total += parseFloat((v=="" || v==0 || v==null)? 0: v);
              
          });
          
           $('#PayRData tbody').append('<tr><td></td><td></td><td></td></td><td><td ><div style="text-align:right">'+numberToReal(v_total)+'</div></td></tr>');
                   
     }
     
     function numberToReal(numero = "", decimal = 2) {
         if(numero=="" || numero == null) return 0;
          return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
      }

        $('#PayRData').dataTable({  
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 0, "desc" ]], 
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': false, 
            'bServerSide': false,
            'sAjaxSource': '<?= site_url('reports/get_payments/'. $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender":hrld}, null, null, {"mRender":paid_by}, {"mRender":currencyFormat}],
            "fnDrawCallback": function() {
              eventFired( 'fnDrawCallback' );
            },
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form').hide();
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
                    <h3 class="box-title"><?= lang('customize_report'); ?><?php
                        if ($this->input->get('start_date')) {
                            echo " - De " . $this->input->get('start_date') . " até " . $this->input->get('end_date');
                        }
                        ?></h3>
                    </div>
                    <div class="box-body">
                        <div id="form" class="panel panel-warning">
                            <div class="panel-body">

                                <?= form_open("reports/payments", array('method'=>'get')); ?>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("payment_ref", "payment_ref"); ?>
                                            <?= form_input('payment_ref', (isset($_GET['payment_ref']) ? $_GET['payment_ref'] : ""), 'class="form-control tip" id="payment_ref"'); ?>

                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("sale_no", "sale_no"); ?>
                                            <?= form_input('sale_no', (isset($_GET['sale_no']) ? $_GET['sale_no'] : ""), 'class="form-control tip" id="sale_no"'); ?>

                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                            <?php
                                            $cu[0] = lang("select")." ".lang("customer");
                                            foreach($customers as $customer){
                                                $cu[$customer->id] = $customer->name;
                                            }
                                            echo form_dropdown('customer', $cu, set_value('customer'), 'class="form-control select2" style="width:100%" id="customer"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                            <label class="control-label" for="user">Vendedor</label>
                                            <?php
                                            $us[""] = "";
                                            foreach ($users as $user) {
                                                $us[$user->id] = $user->first_name . " " . $user->last_name;
                                            }
                                            echo form_dropdown('vendedor', $us, (isset($_GET['vendedor']) ? $_GET['vendedor'] : ""), 'class="form-control select2" id="vendedor" data-placeholder="' . lang("select") . ' o vendedor" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                            <?php
                                            $us[""] = "";
                                            foreach ($users as $user) {
                                                $us[$user->id] = $user->first_name . " " . $user->last_name;
                                            }
                                            echo form_dropdown('user', $us, (isset($_GET['user']) ? $_GET['user'] : ""), 'class="form-control select2" id="user" data-placeholder="' . lang("select") . " " . lang("user") . '" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                            <?= lang("paid_by", "paid_by"); ?>
                                                <?= form_dropdown('paid_by', $pag, (isset($_GET['paid_by']) ? $_GET['paid_by'] : ""), ' id="paid_by" class="form-control paid_by select2" style="width:100%;" required="required"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("start_date", "start_date"); ?>
                                            <?= form_input('start_date', (isset($_GET['start_date']) ? $_GET['start_date'] : ""), 'class="form-control datetimepicker" id="start_date"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("end_date", "end_date"); ?>
                                            <?= form_input('end_date', (isset($_GET['end_date']) ? $_GET['end_date'] : ""), 'class="form-control datetimepicker" id="end_date"'); ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>
                                    </div>
                                </div>
                                <?= form_close(); ?>

                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="table-responsive">
                            <table id="PayRData"
                            class="table table-bordered table-hover table-striped table-condensed reports-table">
                            <thead>
                                <tr>
                                    <th class="col-xs-3"><?= lang("date"); ?></th>
                                    <th class="col-xs-3"><?= lang("payment_ref"); ?></th>
                                    <th class="col-xs-2"><?= lang("sale_no"); ?></th>
                                    <th class="col-xs-2"><?= lang("paid_by"); ?></th>
                                    <th class="col-xs-2"><?= lang("amount"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
