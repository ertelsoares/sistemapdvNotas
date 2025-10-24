<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
       var dt = "";
       
    $(document).ready(function() {
        function image(n) {
            if(n !== null) {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/thumbs/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }

        function tipodeproduto(n) {
            if(n == "standard") {
                return "Produto";
            }else{
                return 'Serviço';
            }
            
        }
        
       function eventFired(t){
           
            var totalc = 0;
            var totalcosto = 0;
            var totalp = 0;
            var totalprice = 0;
            var v = 0;

            $('#fileData tbody tr').each(function() {
                v = 0;
                v = $(this).find("td:nth-child(7)").text().replace(".", "").replace(",", ".");
                totalc += parseFloat((v=="" || v==0 || v==null)? 0: v);

                v = $(this).find("td:nth-child(8)").text().replace(".", "").replace(",", ".");
                totalcosto += parseFloat((v=="" || v==0 || v==null)? 0: v);
                
                v = $(this).find("td:nth-child(9)").text().replace(".", "").replace(",", ".");
                totalp += parseFloat((v=="" || v==0|| v==null)? 0: v);
                
                v = $(this).find("td:nth-child(10)").text().replace(".", "").replace(",", ".");
                totalprice += parseFloat((v=="" || v==0 || v==null)? 0: v);
               
            });
            
             $('#fileData tbody').append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><div>'+numberToReal(totalc)+'</div></td><td><div>'+numberToReal(totalcosto)+'</div></td><td><div>'+numberToReal(totalp)+'</div></td><td><div>'+numberToReal(totalprice)+'</div></td><td></td></tr>');
                     
       }
       
       function numberToReal(numero = "", decimal = 2) {
           if(numero=="" || numero == null) return 0;
            return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
        }

        dt = $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[ 1, "asc" ]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/get_products') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender":image,"bSortable":false}, null, null, {"mRender":tipodeproduto}, null, {"mRender": function ( data, type, row ) { return numberToReal(data, 3); }}, <?= $Admin ? '{"mRender":currencyFormat},{"mRender":currencyFormat},' : ''; ?> {"mRender":currencyFormat}, <?= $Admin ? '{"mRender":currencyFormat},' : ''; ?> {"bSortable":false, "bSearchable": false}],
            
            "fnDrawCallback": function() {
               <?php if($Admin) { ?> eventFired( 'fnDrawCallback' );  <?php } ?> 
            },
              "fnInitComplete": function(oSettings, json) {
                  //eventFired( 'Init' );
            }
        });
        //{"data":"tax_method","render":method},
        $('#fileData').on('click', '.image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
        $('#fileData').on('click', '.barcode', function() {
            var a_href = $(this).attr('href');
            var code = $(this).attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
        $('#fileData').on('click', '.open-image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').find('.image').attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });

        $(document).on("focus", ".rcost", function() { 
            $(this).mask("#.##0,00", {reverse: true});
        });
    });
</script>
<style type="text/css">
.table td:first-child { padding: 1px; }
.table td, .text-right { text-align: center!important; }
.table td { text-align: center; }
.table > tbody > tr:last-child > td { background: #cfcfcf; font-size: 16px; font-weight: 700; ;}
@media print {
a, button, .linkcustomer_1,.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .box-header, #form, table.dataTable.table-condensed thead .sorting:after, table.dataTable.table-condensed thead .sorting_asc:after, table.dataTable.table-condensed thead .sorting_desc:after, .table td:first-child, .table td:last-child,  .table th:first-child, .table th:last-child,  .dataTables_length, .dataTables_filter, .dataTables_info,.dataTables_paginate, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after,.box-header, .alerts, .main-footer {display:none;}
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
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                        <div class="table-responsive">
                        <table id="fileData" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                            <thead>
                            <tr class="active">
                                <th style="max-width:30px;"><?= lang("image"); ?></th>
                                <th class="col-xs-1"><?= lang("code"); ?></th>
                                <th><?= lang("name"); ?></th>
                                <th class="col-xs-1"><?= lang("type"); ?></th>
                                <th class="col-xs-1"><?= lang("category"); ?></th>
                                <th class="col-xs-1">Estoque</th>
                                <?php if($Admin) { ?>
                                <th class="col-xs-1"><?= lang("cost"); ?></th>
                                <th class="col-xs-1">Estoque x Custo</th>
                                <?php } ?>
                                <th class="col-xs-1"><?= lang("price"); ?></th>
                                <?php if($Admin) { ?>
                                <th class="col-xs-1">Estoque x Preço</th>
                                <?php } ?>
                                <th style="width:160px;"><?= lang("actions"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                        <div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-labelledby="picModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                                        <h4 class="modal-title" id="myModalLabel">title</h4>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img id="product_image" src="" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
