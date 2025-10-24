<?php

include "QRCodeGenerator.class.php";

function product_name($name)
{
    return character_limiter($name, (isset($Settings->char_per_line) ? ($Settings->char_per_line-8) : 35));
}

if ($modal) {
    echo '<div class="modal-dialog no-modal-header"><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>';
} else { ?>
    <!doctype html>
    <html>
    <head><meta charset="utf-8">
        
        <title><?= $page_title . ": " . $inv->id; ?></title>
        <base href="<?= base_url() ?>"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?= site_url(); ?>icon.ico"/>
        <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <style type="text/css" media="all">
            body { color: #000; }
            #wrapper { max-width: 480px; margin: 0 auto; padding-top: 20px; }
            .btn { border-radius: 0; margin-bottom: 5px; }         .table { border-radius: 3px; }
            .table th { background: #f5f5f5; }           .table th, .table td { vertical-align: middle !important; }
            h3 { margin: 5px 0; }
            @media print { .no-print { display: none; }
                #wrapper { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
            }
        </style>
    </head>
    <body>
<?php } ?>
<div id="wrapper">
    <div id="receiptData">
    <div class="no-print">
        <?php if ($message) { ?>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <?= is_array($message) ? print_r($message, true) : $message; ?>
            </div>
        <?php } ?>
      <?php if ($Settings->java_applet) { ?>
        <span class="col-xs-12"><a class="btn btn-block btn-primary" onClick="printReceipt()"><?= lang("print"); ?></a></span>
        <span class="col-xs-12"><a class="btn btn-block btn-info" type="button" onClick="openCashDrawer()"><?= lang('open_cash_drawer'); ?></a></span>
        <div style="clear:both;"></div>
    <?php } else { ?>

    <?php if($inv->nf_status=="" && $_GET["from"]!="sales_list"){ ?>
    <span class="col-xs-12">
        <a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
    </span>
    <?php } ?>
    <span class="pull-right col-xs-12">
        <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-primary" onClick="window.print();return false;">Imprimir</a>
    </span>

    <?php if($inv->nf_status=="" && $_GET["from"]!="sales_list"){ ?>
    <span class="pull-left col-xs-12" style="margin-bottom:30px;">
        <a onClick="gerarNFC()" href="javascript:void(0)"  class="btn btn-block btn-success">Gerar NFC-e</a></span>
    <?php } ?>
        
    <?php 
    } ?>
 
    </div>
    <?php
    
    $totalpagamentos = 0;
    foreach ($payments as $payment) {
        $totalpagamentos += $payment->amount;
    }   
    ?>
    <div id="receipt-data">
        <div class="text-center">
                <?php if($Settings->logo!=""){ echo "<p><img src='".PDV_URL_BASE.$Settings->logo."' style='max-height:70px;'></p>"; } ?>
                <?= $Settings->header; ?>
                <p>
                  <?= lang("customer").': '. trim($inv->customer_name . " " . $customer->cf1); ?><br>
                 <b><?= lang('sale').'/Cupom:</b> '.$inv->id; ?>&nbsp;&nbsp;&nbsp;&nbsp;<b>Data: </b><?php echo $this->tec->hrld($inv->date); ?>
                <?php if($created_by!=""){  echo "<br><b>Vendedor:</b> ". $created_by->first_name." ".$created_by->last_name; } ?>
                </p>
            <div style="clear:both;"></div>
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="text-center col-xs-6"><?=lang('description');?></th>
                        <th class="text-center col-xs-1"><?=lang('quantity');?></th>
                        <th class="text-center col-xs-2"><?=lang('price');?></th>
                        <th class="text-center col-xs-3"><?=lang('subtotal');?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $countItens = 0;
                $tax_summary = array();
                foreach ($rows as $row) {
                   
                    echo '<tr><td class="text-left">' . product_name($row->product_name) . '</td>';
                    echo '<td class="text-center">' . $this->tec->formatNumber($row->quantity, 3) . '</td>';
                    echo '<td class="text-right">';
                    if ($row->item_discount != 0) {
                        $price_with_discount = $this->tec->formatMoney($row->net_unit_price + $this->tec->formatDecimal($row->item_discount / $row->quantity));
                        $pr_tax = $row->tax_method ?
                        $this->tec->formatDecimal((($price_with_discount) * (($row->tax!="")?$row->tax:0)) / 100) :
                        $this->tec->formatDecimal((($price_with_discount) * (($row->tax!="")?$row->tax:0)) / (100 + (($row->tax!="")?$row->tax:0)));

                        echo '<del>' . $this->tec->formatMoney($price_with_discount+$pr_tax) . '</del><br>';
                        echo '-' . $this->tec->formatMoney($row->item_discount / $row->quantity) . '</br> ';
                    }

                    echo $this->tec->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) . '</td>';

                    // total do item
                    echo '<td class="text-right">'.$this->tec->formatMoney($row->subtotal) . '</td>';
                    
                    echo'</tr>';
                    $countItens++;
                }
                ?>
                </tbody>
                <tfoot>
                <tr><th colspan="2">Qtd Total de itens</th><th colspan="2" class="text-right"><?=$countItens;?></th></tr>
                <tr>
                    <th colspan="2">Total de Produtos:</th>
                    <th colspan="2" class="text-right">R$ <?= $this->tec->formatMoney($inv->total + $inv->product_tax); ?></th>
                </tr>
                <?php
                if ($inv->order_tax != 0) {
                    echo '<tr><th colspan="2">' . lang("order_tax") . ':</th><th colspan="2" class="text-right">' . $this->tec->formatMoney($inv->order_tax) . '</th></tr>';
                }
                if ($inv->order_discount != 0) {
                    echo '<tr><th colspan="2">' . lang("order_discount") . ':</th><th colspan="2" class="text-right">-' . $this->tec->formatMoney($inv->order_discount) . '</th></tr>';
                }

                if ($Settings->rounding) {
                    $round_total = $this->tec->roundNumber($inv->grand_total, $Settings->rounding);
                    $rounding = $this->tec->formatMoney($round_total - $inv->grand_total);
                ?>
                    <tr>
                        <th colspan="2"><?= lang("rounding"); ?>:</th>
                        <th colspan="2" class="text-right">R$ <?= $rounding; ?></th>
                    </tr>
                    <tr>
                        <th colspan="2">Total:</th>
                        <th colspan="2" class="text-right">R$ <?= $this->tec->formatMoney($inv->grand_total + $rounding); ?></th>
                    </tr>
                <?php
                } else {
                    $round_total = $inv->grand_total;
                ?>
                    <tr>
                        <th colspan="2">Total:</th>
                        <th colspan="2" class="text-right"> R$ <?= $this->tec->formatMoney($inv->grand_total); ?></th>
                    </tr>
                <?php } ?>
                <tr>
                        <th colspan="2">Total Pago:</th>
                        <th colspan="2" class="text-right">R$ <?= $this->tec->formatMoney($inv->paid); ?></th>
                    </tr>
                <?php 
                    if ($inv->paid < $round_total) { ?>
                   <tr>
                        <th colspan="2">Falta Pagar:</th>
                        <th colspan="2" class="text-right">R$ <?= $this->tec->formatMoney($inv->grand_total - $inv->paid); ?></th>
                    </tr>
                <?php } ?>
                <?php if ($inv->troco > 0) { ?>
                   <tr>
                        <th colspan="2">Troco:</th>
                        <th colspan="2" class="text-right">R$ <?= $this->tec->formatMoney($inv->troco); ?></th>
                    </tr>
                <?php } ?>

             
                
                </tfoot>
            </table>
           <?php
                if ($payments) {
                    foreach($meiopagamento as $pagamento){ $pag[$pagamento->cod] = $pagamento->nome; }
    
                    echo '<table class="table table-striped table-condensed"><tbody>';
                        echo '<tr>';
                            echo '<td><b>FORMA DE PAGAMENTO</b></td>';
                            echo '<td><b>' . strtoupper(lang("amount")) . '</b></td>';
                        echo '</tr>';
                
                        foreach ($payments as $payment) {
                            echo '<tr>';
                                echo '<td>' . $pag[$payment->paid_by] . '</td>';
                                echo '<td>R$ ' . $this->tec->formatMoney($payment->amount) . '</td>';
                        echo '</tr>';

                    }

                echo '</tbody></table>';

            }

            ?>

            <?= $inv->entrega_endereco ? '<p class="text-center"><b>ENTREGA:</b><br>' . $this->tec->decode_html($inv->entrega_endereco) . '</p>' : ''; ?>

            <?= $inv->note ? '<p class="text-center">' . $this->tec->decode_html($inv->note) . '</p>' : ''; ?>

        </div>
        <div style="clear:both;"></div>
    </div>
<?php if ($modal) {
    echo '</div></div></div></div>';
} else { ?>
<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
    <hr>
    <?php if ($message) { ?>
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <?= is_array($message) ? print_r($message, true) : $message; ?>
    </div>
<?php } ?>

<?php } ?>
 <div style="clear:both;"></div>
<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<style>.fancybox__slide {padding: 3% 1%!important;}</style>
<script>

     function gerarNFC(){
       
        $.fancybox.open(
            {
                src: '<?= site_url('pos/nfe') ?>/<?=$inv->id; ?>/1/emitir',
                type: "iframe",
                width: "100%",
                height: "100%",
                preload: false,
                touch: false,
                infobar: false,
                keyboard: false,
                clickOutside: false,
                clickSlide: false,
                modal: true,
                autoFocus: false,
                opts: {
                    infobar: false,
                    touch: false,
                    clickOutside: false,
                    clickSlide: false,
                    keyboard: false,
                },
                mobile: {
                    preventCaptionOverlap: false,
                    idleTime: false,
                    clickSlide: function(current, event) {
                        return current.type === "image" ? "toggleControls" : false;
                    }
                },
            });

     }
     
     
      function ClearData(){
         setTimeout(() => {
            //localStorage.clear();
            localStorage.removeItem('spos_discount');
            localStorage.removeItem('spos_tax');
            localStorage.removeItem('spos_note');
            localStorage.removeItem('spos_note');
            localStorage.removeItem('spos_discount');
            localStorage.removeItem('spos_entrega_endereco');
            localStorage.removeItem('spos_customer');
            localStorage.removeItem('spositems');
         }, 500);
     }
     
     <?php if($_GET["fim"] == "1"){?>
        ClearData();
    <?php } ?>

    <?php if($_GET["emitirnfc"] == "1"){?>
        setTimeout(() => {
            gerarNFC();
        }, 500);
    <?php } ?>
    
     </script>
</div>
</div>