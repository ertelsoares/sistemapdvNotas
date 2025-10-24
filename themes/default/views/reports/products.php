<?php
$v = "?v=1";

    if($this->input->get('product')){
        $v .= "&product=".$this->input->get('product');
    }
    if($this->input->get('start_date')){
        $v .= "&start_date=".$this->input->get('start_date');
    }
    if($this->input->get('end_date')) {
        $v .= "&end_date=".$this->input->get('end_date');
    }


?>
<script>
    $(document).ready(function() {
        function image(n) {
            if(n !== null) {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/thumbs/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }
        function method(n) {
            return (n == 0) ? '<span class="label label-primary"><?= lang('inclusive'); ?></span>' : '<span class="label label-warning"><?= lang('exclusive'); ?></span>';
        }
        function numberToReal(numero = "", decimal = 2) {
           if(numero=="" || numero == null) return 0;
            return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
        }

        $('#fileData').dataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    download: "open"
                },
            ],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 1, "asc" ]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': false, 'bServerSide': false,
            'sAjaxSource': '<?= site_url('reports/get_products/'. $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null, {"mRender": function ( data, type, row ) { return numberToReal(data, 3); }, "bSearchable": false}, {"mRender":currencyFormat, "bSearchable": false}, {"mRender":currencyFormat, "bSearchable": false}, {"mRender":currencyFormat, "bSearchable": false}]
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
@media print {
.linkcustomer_1,.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .box-header, #form, table.dataTable.table-condensed thead .sorting:after, table.dataTable.table-condensed thead .sorting_asc:after, table.dataTable.table-condensed thead .sorting_desc:after{display:none;}
.linkcustomer_2{display:block!important;}
.box { box-shadow: 0!important; }
.content-header h1{display:block!important}
}
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="#" class="btn btn-default btn-sm toggle_form pull-right"><?= lang("show_hide"); ?></a>
                    <h3 class="box-title"><?= lang('customize_report'); ?></h3>
                </div>
                <div class="box-body">
                    <div id="form" class="panel panel-warning">
                        <div class="panel-body">
                        <?= form_open("reports/products", array('method'=>'get'));?>

                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label class="control-label" for="product"><?= lang("product"); ?></label>
                                    <?php
                                    $pr[0] = lang("select")." ".lang("product");
                                    foreach($products as $product){
                                        $pr[$product->id] = $product->name;
                                    }
                                    echo form_dropdown('product', $pr, set_value('product'), 'class="form-control select2" style="width:100%" id="product"');
                                    ?>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label class="control-label" for="start_date"><?= lang("start_date"); ?></label>
                                    <?= form_input('start_date', (isset($_GET['start_date']) ? $_GET['start_date'] : ""), 'class="form-control datetime datetimepicker" id="start_date"');?>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label class="control-label" for="end_date"><?= lang("end_date"); ?></label>
                                    <?= form_input('end_date', (isset($_GET['end_date']) ? $_GET['end_date'] : ""), 'class="form-control datetime datetimepicker" id="end_date"');?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>
                            </div>
                        </div>
                        <?= form_close();?>
                    </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="fileData" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                                    <thead>
                                        <tr class="active">
                                            <th class="col-xs-2"><?= lang("code"); ?></th>
                                            <th><?= lang("name"); ?></th>
                                            <th class="col-xs-1">Quant. <?= lang("sold"); ?></th>
                                            <th class="col-xs-1"><?= lang("cost"); ?></th>
                                            <th class="col-xs-1"><?= lang("income"); ?></th>
                                            <th class="col-xs-1"><?= lang("profit"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
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
