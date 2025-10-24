<?php

if($_GET["tipo"]==""){ // normal
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8">
    
    <title><?= lang('view_bill') . " | " . $Settings->site_name; ?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        html, body {
            background: #FFF;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            min-width: 100px;
            color: #333;
        }
        a { outline: none; }
        h2 { margin: 20px 0; }
        .bill {
            min-height: 600px;
            background: #FFF;
            margin: 20px 0px;
        }
        .content { display: none; }
    
        .with-promo-space .content {
            display: block;
        }
        .with-promo-space .bill {
            width: 100%;
            float: left;
            min-height: 600px;
        }
        td { vertical-align: middle !important; }
        #product-list {
            display: block;
            padding: 20px 0px;
        }
        #totals { border-top: 1px solid #ddd; }
        .preview_frame { width: 100%; }

      @media print {
     .btnprintdiv, .table td:last-child, .table th:last-child,  .dataTables_length, .dataTables_filter, .dataTables_info,.dataTables_paginate, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after,.box-header, .alerts, .main-footer { display:none; }
        .content{padding:0px!important}
        
      @media print {
     .btnprintdiv, .table td:last-child, .table th:last-child,  .dataTables_length, .dataTables_filter, .dataTables_info,.dataTables_paginate, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after,.box-header, .alerts, .main-footer { display:none; }
        .content{padding:0px!important}
        
        <?php if($_GET["printnew"]=="1"){?>
            .is_not_new, #totalTable{display:none!important}
        <?php } ?>
      }
</style>
</head>
<body class="with-promo-space">
<!-- just remove the class with-promo-space from body to make it full page -->
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div class="btnprintdiv" style="margin:0 auto;text-align:center">
   <a href="<?=site_url("pos/view_bill?hold=".$suspend_sale->id."&printall=1&notshowpdv=".$_GET["notshowpdv"])?>" id="web_print" class="btn btn-primary">Imprimir Tudo</a>
   <?php if(TIPONEGOCIO=="restaurante"){ ?>
     <a href="<?=site_url("pos/view_bill?hold=".$suspend_sale->id."&printnew=1&notshowpdv=".$_GET["notshowpdv"])?>" id="web_print" class="btn btn-success">Imprimir Novos</a>
   <?php } ?>
   <?php if($_GET["notshowpdv"]!="1"){ ?>
     <a href="<?=site_url("pos")?>" class="btn btn-warning">PDV</a>
     <a href="<?=site_url("sales/opened")?>" class="btn btn-warning"><?=lang("list_opened_bills");?></a>
     <?php } ?>
    </div>
<table align="center" style="width:100%;max-width:600px;">
    <tr>
        <td align="center">
<div>
    <div class="bill" id="bill">
        <div id="product-list">
            <table>
                <tr>
                <td align="center">
                <?= $Settings->header; ?>
                </td>
                </tr>
                <tr>
                    <?php if(TIPONEGOCIO=="restaurante"){ ?>
                    <td class="text-center"><span style="font-size:16px;">Comanda/Mesa: <b><?=strtoupper(str_replace("_", " ", $suspend_sale->hold_ref));?></b></span></td>
                    <?php } else { ?>
                    <td class="text-center"><span style="font-size:20px;">ORÇAMENTO</span><br><b>Ref: <?=$suspend_sale->hold_ref?></td>
                    <?php } ?>
                </tr>
                 <tr>
                    <td class="text-center"><span style="font-size:14px;">Data: <b><?=date("d/m/Y H:i", strtotime($suspend_sale->date));?></b></span></td>
                </tr>
                 <?php if(TIPONEGOCIO=="restaurante"){ ?>
                 <tr>
                    <td class="text-center"><span style="font-size:18px;">Senha: <b><?=$suspend_sale->chamada_numero;?></b></span></td>
                </tr>
                 <?php } ?>
            </table>
            <table style="margin-bottom: 0;" id="billTable" class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th width="50%" class="text-center"><?= lang('product'); ?></th>
                    <th width="15%" class="text-center"><?= lang('price'); ?></th>
                    <th width="15%" class="text-center"><?= lang('qty'); ?></th>
                    <th width="20%" class="text-center"><?= lang('subtotal'); ?></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="totals">
            <table style="width:100%; float:right; padding:5px; color:#000; background: #FFF;" id="totalTable">
                <tbody>
                <tr>
                    <td style="border-left: 1px solid #ddd; padding-left:10px; text-align:left; font-weight:normal;">
                        <?= lang('total_items'); ?>
                    </td>
                    <td style="text-align:right; padding-right:10px; font-weight:bold;"><span id="count">0</span></td>
                    <td style="padding-left:10px; text-align:left;"><?= lang('total'); ?></td>
                    <td style="border-right: 1px solid #ddd; text-align:right; padding-right:10px; font-weight:bold;">
                        <span id="total">0.00</span></td>
                </tr>
                <tr>
                    <td style="border-left: 1px solid #ddd; padding-left:10px; text-align:left; "><?= lang('order_discount'); ?></td>
                    <td style="text-align:right; padding-right:10px; font-weight:bold;">
                        <span id="ds_con">0.00</span></td>
                    <td style="padding-left:10px; text-align:left; font-weight:normal;"><?= lang('order_tax'); ?></td>
                    <td style="border-right: 1px solid #ddd; text-align:right; padding-right:10px; font-weight:bold;"><span id="ts_con">0.00</span></td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 5px 0px 5px 10px; text-align:left; font-size: 1.4em; border: 1px solid #333; font-weight:bold; background:#333; color:#FFF;">
                        <?= lang('total_payable'); ?>
                    </td>
                    <td colspan="2"
                        style="text-align:right; padding:5px 10px 5px 0px; font-size: 1.4em; border: 1px solid #333; font-weight:bold; background:#333; color:#FFF;">
                        <span id="total-payable">0.00</span></td>
                </tr>
                </tbody>
            </table>
            <div class="clearfix"></div>
        </div>
    </div>   
</div>
        </td>
</table>

<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification); ?>
<script type="text/javascript">
    var base_url = '<?=base_url();?>';
    var dateformat = '<?=$Settings->dateformat;?>', timeformat = '<?= $Settings->timeformat ?>';
    <?php unset($Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->timezone, $Settings->setting_id, $Settings->default_email, $Settings->version, $Settings->stripe, $Settings->validade_certificado, $Settings->stripe_publishable_key); ?>
    var Settings = <?= json_encode($Settings); ?>;
</script>

<script type="text/javascript">
    var count = 1, an = 1, product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0;

    function widthFunctions(e) {
        return;
        var wh = $(window).height(),
            tT = $('#totalTable').outerHeight(true);
        $('#bill').css("height", (wh - 70));
        $('#product-list').css("height", (wh - tT - 70));
        $('.preview_frame').css("height", (wh - 70));
    }
   //$(window).bind("resize", widthFunctions);
   // $(window).bind("load", widthFunctions);
    $(document).ready(function () {
        loadItems();
        /*window.setInterval(function () {
            loadItems();
        }, 1000);
        */
        
         <?php if($_GET["printnew"]=="1"){?>
           window.print();
        <?php } ?>
        
        <?php if($_GET["printall"]=="1"){?>
           window.print();
        <?php } ?>
        
    });
    function formatDecimal(x) {
        return parseFloat(parseFloat(x).toFixed(Settings.decimals));
    }
    function numberToReal(numero = "", decimal = 2) {
        if(numero=="" || numero == null) return 0;
        return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
    }
    function loadItems() {
    if (count == 1) {
        spositems = {};
    }

    // from 
    $dados = get('spositems');

    <?php if($_GET["hold"]!=""){ ?>
    $dados = '<? echo ($items)?>';
    <?php } ?>

    if ($dados) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        product_discount = 0;
        order_discount = 0;
        total_discount = 0;

        $("#billTable tbody").empty();
        spositems = JSON.parse($dados);

        $.each(spositems, function () {

            var item = this;
            var item_id = Settings.item_addition == 1 ? item.item_id : item.id;
            spositems[item_id] = item;

            var product_id = item.row.id, item_type = item.row.type, item_tax_method = parseFloat(item.row.tax_method), combo_items = item.combo_items, item_qty = item.row.qty, item_aqty = parseFloat(item.row.quantity), item_type = item.row.type, item_ds = item.row.discount, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            var unit_price = parseFloat(item.row.real_unit_price);

            var ds = item_ds ? item_ds : '0';
            var item_discount = formatDecimal(ds);
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = formatDecimal(parseFloat(((unit_price) * parseFloat(pds[0])) / 100));
                }
            }

            product_discount += formatDecimal(item_discount * item_qty);
            unit_price = formatDecimal(unit_price-item_discount);
            var item_price = unit_price;
            var pr_tax = parseInt(item.row.tax), pr_tax_val = 0;

            if (pr_tax !== null && pr_tax != 0) {
                if(item_tax_method == 0) {
                    pr_tax_val = formatDecimal((unit_price * parseFloat(pr_tax)) / (100+parseFloat(pr_tax)));
                    item_price -= pr_tax_val;
                    tax = '<?= lang('inclusive'); ?>';
                } else {
                    pr_tax_val = formatDecimal((unit_price * parseFloat(pr_tax)) / 100);
                    tax = '<?= lang('exclusive'); ?>';
                }
            }
            product_tax += formatDecimal(pr_tax_val * item_qty);
            unit_price = formatDecimal(unit_price+item_discount);
            var comment = (item.row.comment!=null && item.row.comment!="")? ' --'+ item.row.comment : "";
            var row_no = (new Date).getTime();
             var is_new = "is_not_new";
            try{
                 if(item.row.is_new=="1"){
                     is_new="is_new";
                 }else{
                     is_new = "is_not_new";
                 }
            }catch(e){}
            
            var newTr = $('<tr></tr>');
          
            tr_html = '<td>' + item_name + ' (' + item_code + ')'+ comment +'</td>';
            tr_html += '<td class="text-right">' + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + '</td>';
            tr_html += '<td class="text-center">' + numberToReal(item_qty, 3) + '</td>';
            tr_html += '<td class="text-right">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</td>';
            newTr.html(tr_html);
            newTr.addClass(is_new);
            newTr.prependTo("#billTable");
            total += ((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty));
            count += parseFloat(item_qty);
            an++;
            // $('#list-table-div').scrollTop(0);
        });


        var ds = get('spos_discount') ? get('spos_discount') : ($('#discount_val').val() ? $('#discount_val').val() : '0');
        if(ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            order_discount = (total*parseFloat(pds[0]))/100;
            $("#ds_con").text(formatMoney(order_discount));
        } else {
            order_discount = parseFloat(ds);
            $("#ds_con").text(formatMoney(order_discount));
        }

        var ts = get('spos_tax') ? get('spos_tax') : $('#tax_val').val();
        if(ts.indexOf("%") !== -1) {
            var pts = ts.split("%");
            order_tax = ((total-order_discount)*parseFloat(pts[0]))/100;
            $("#ts_con").text(formatMoney(order_tax));
        } else {
            order_tax = parseFloat(ts);
            $("#ts_con").text(formatMoney(order_tax));
        }

        var g_total = total - parseFloat(order_discount) + parseFloat(order_tax);
        grand_total = formatMoney(g_total);       
          $("#total-payable").text(grand_total);
         $("#total").text(formatMoney(total));
         $("#count").text((an-1)+' ('+formatMoney(count-1)+')');

        if (Settings.display_kb == 1) { display_keyboards(); }
        $('#add_item').focus();
    }
}
 </script>
<?php } elseif($_GET["tipo"]=="romaneio") {?>
<!DOCTYPE html>
<html>
<head>
    <title><?="Romaneio | " . $Settings->site_name; ?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        html, body {
            background: #FFF;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            min-width: 400px;
            color: #333;
        }
        a { outline: none; }
        h2 { margin: 20px 0; }
        .bill {
            min-height: 600px;
            background: #FFF;
            margin: 20px 0px;
        }
        .content { display: none; }
    
        .with-promo-space .content {
            display: block;
        }
        .with-promo-space .bill {
            width: 100%;
            float: left;
            min-height: 600px;
        }
        td { vertical-align: middle !important; }
        #product-list {
            display: block;
            padding: 20px 0px;
        }
        #totals { border-top: 1px solid #ddd; }
        .preview_frame { width: 100%; }

      @media print {
     .btnprintdiv, .table td:last-child, .table th:last-child,  .dataTables_length, .dataTables_filter, .dataTables_info,.dataTables_paginate, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after,.box-header, .alerts, .main-footer { display:none; }
        .content{padding:0px!important}
        
        <?php 
        
        if($_GET["printnew"]==""){?>
            .is_new, #totalTable{display:none!important}
        <?php }?>
        
      }
</style>
</head>
<body class="with-promo-space">
<div class="btnprintdiv" style="margin:0 auto;">
    <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-primary" onClick="window.print();return false;">Imprimir</a>
</div>
<table align="center">
<tr>
<td align="center">
<div>
    <div class="bill" id="bill">
        <div id="product-list">
            <table>
                <tr>
                <td align="center">
                <?= $Settings->header; ?>
                </td>
                </tr>
                <tr>
                    <?php if(TIPONEGOCIO=="restaurante"){ ?>
                    <td><span style="font-size:16px;">Comanda/Mesa: <b><?php echo $_GET["hold_ref"];?></b></span></td>
                    <?php } else { ?>
                    <td  align="center"><b>ROMANEIO DE ENTREGA</b></td>
                    <?php } ?>
                </tr>
            </table>
            <table style="width:100%; float:right; padding:5px; color:#000; background: #FFF;">
                <tbody>
                <tr>
                    <td style="padding:5px; width:90px; text-align:left; font-weight:normal;">
                       Cliente:
                    </td>
                    <td style=" padding:5px;text-align:left; font-weight:normal;">
                       <span id="contato">
                    </td>
                  
                </tr>
                <tr>
                    <td style="padding:5px; width:90px; text-align:left; font-weight:normal;">
                       Telefone:
                    </td>
                    <td style=" padding:5px;text-align:left; font-weight:normal;">
                       <span id="contato_telefone">
                    </td>
                  
                </tr>
                <tr>
                    <td style="padding:5px; width:90px; text-align:left; font-weight:normal;">
                       Endereço:
                    </td>
                    <td style=" padding:5px;text-align:left; font-weight:normal;">
                       <span id="contato_endereco">
                    </td>
                  
                </tr>
                </tbody>
            </table>
            <table style="margin-bottom: 0;" id="billTable" class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th width="50%" class="text-center"><?= lang('product'); ?></th>
                    <th width="15%" class="text-center"><?= lang('qty'); ?></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="totals">
            <table style="width:100%; float:right; padding:5px; color:#000; background: #FFF;" id="totalTable">
                <tbody>
                <tr>
                    <td style="border: 1px solid #ddd; padding-left:10px; text-align:left; font-weight:normal;">
                        <?= lang('total_items'); ?>
                    </td>

                    <td style="border: 1px solid #ddd; padding-left:10px; text-align:center; font-weight:normal;">
                       <span id="count">
                    </td>
                  
                </tr>
                </tbody>
            </table>

            <table style="width:100%; margin-top:10px; float:right; padding:5px; color:#000; background: #FFF;">
                <tbody>
                <tr>
                    <td style="padding:5px; width:100px; text-align:center; font-weight:normal;">
                        Prezado cliente, alertamos que todas as mercadorias deverão ser conferidas no ato da entrega, o canhoto deverá ser assinado, datado e carimbado, como forma de confirmação de recebimento. Divergências encontradas precisam ser anotadas no canhoto e comunicadas à nossa empresa, pelo telefone <?=$Settings->tel;?>, para o devido registro. Reclamações de avarías e faltas posteriores não serão aceitas.
                      
                    </td>
                   
                </tr>
                </tbody>
            </table>

            <table style="width:100%; margin-top:10px; float:right; padding:5px; color:#000; background: #FFF;">
                <tbody>
                <tr>
                    <td style="border: 1px solid #ddd; padding:5px; width:100px; text-align:left; font-weight:normal;">
                       Data
                    </td>
                    <td style="border: 1px solid #ddd; padding:5px;text-align:left; font-weight:normal;">
                    <?=date("d/m/Y H:i")?>
                    </td>
                </tr>
                </tbody>
            </table>

            <table style="width:100%; float:right; padding:5px; color:#000; background: #FFF;">
                <tbody>
                <tr>
                <td style="border: 1px solid #ddd; padding:10px 5px; width:100px; text-align:left; font-weight:normal;">
                       Recebido por:
                    </td>
                    <td style="border: 1px solid #ddd; padding:10px 5px; text-align:left; font-weight:normal;">

                    </td>
                </tr>
                </tbody>
            </table>

            <div class="clearfix"></div>
        </div>
    </div>   
</div>
</td>
</tr>
</table>
<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification); ?>
<script type="text/javascript">
    var base_url = '<?=base_url();?>';
    var dateformat = '<?=$Settings->dateformat;?>', timeformat = '<?= $Settings->timeformat ?>';
    <?php unset($Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->timezone, $Settings->setting_id, $Settings->default_email, $Settings->version, $Settings->stripe, $Settings->validade_certificado, $Settings->stripe_publishable_key); ?>
    var Settings = <?= json_encode($Settings); ?>;
</script>

<script type="text/javascript">
    var count = 1, an = 1, product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0;

    function widthFunctions(e) {
        return;
        var wh = $(window).height(),
            tT = $('#totalTable').outerHeight(true);
        $('#bill').css("height", (wh - 70));
        $('#product-list').css("height", (wh - tT - 70));
        $('.preview_frame').css("height", (wh - 70));
    }
    //$(window).bind("resize", widthFunctions);
    //$(window).bind("load", widthFunctions);
    $(document).ready(function () {
        loadItems();
        if(get('spos_customer')!=null && get('spos_customer')!="" && typeof get('spos_customer')!=undefined){
            selecionarCliente(get('spos_customer'));
        }
       
    });

    function selecionarCliente(id){
			$("#resultados_clientes").hide();
			$.getJSON("<?=site_url('customers/getCostumersbyID')?>?id=" + id, function(dados2){
				$("#contato").text(dados2.name +
                ((dados2.cf1!="")? ' / Documento: '+dados2.cf1: ''));
                $("#contato_telefone").text(dados2.phone + 
                ((dados2.email!="")? ' / Email: '+dados2.email: '')
                );
				$("#contato_endereco").text(dados2.endereco + 
                ((dados2.numero!="")? ', '+dados2.numero: '') + 
                ((dados2.bairro!="")? ', '+dados2.bairro: '') + 
                ((dados2.cidade!="")? ', '+dados2.cidade: '') + 
                ((dados2.estado!="")? ', '+dados2.estado: '') + 
                ((dados2.cep!="")? ', CEP: '+dados2.cep: '') + 
                ((dados2.complemento!="")? ', ('+dados2.complemento +')': '')
                );
			});
	}

    function formatDecimal(x) {
        return parseFloat(parseFloat(x).toFixed(Settings.decimals));
    }
    function numberToReal(numero = "", decimal = 2) {
        if(numero=="" || numero == null) return 0;
        return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR',minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
    }
    function loadItems() {
    if (count == 1) {
        spositems = {};
    }
    if (get('spositems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        product_discount = 0;
        order_discount = 0;
        total_discount = 0;

        $("#billTable tbody").empty();
        spositems = JSON.parse(get('spositems'));

        $.each(spositems, function () {

            var item = this;
            var item_id = Settings.item_addition == 1 ? item.item_id : item.id;
            spositems[item_id] = item;

            var product_id = item.row.id, item_type = item.row.type, item_tax_method = parseFloat(item.row.tax_method), combo_items = item.combo_items, item_qty = item.row.qty, item_aqty = parseFloat(item.row.quantity), item_type = item.row.type, item_ds = item.row.discount, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            var unit_price = parseFloat(item.row.real_unit_price);

            var ds = item_ds ? item_ds : '0';
            var item_discount = formatDecimal(ds);
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = formatDecimal(parseFloat(((unit_price) * parseFloat(pds[0])) / 100));
                }
            }

            product_discount += formatDecimal(item_discount * item_qty);
            unit_price = formatDecimal(unit_price-item_discount);
            var item_price = unit_price;
            var pr_tax = parseInt(item.row.tax), pr_tax_val = 0;

            if (pr_tax !== null && pr_tax != 0) {
                if(item_tax_method == 0) {
                    pr_tax_val = formatDecimal((unit_price * parseFloat(pr_tax)) / (100+parseFloat(pr_tax)));
                    item_price -= pr_tax_val;
                    tax = '<?= lang('inclusive'); ?>';
                } else {
                    pr_tax_val = formatDecimal((unit_price * parseFloat(pr_tax)) / 100);
                    tax = '<?= lang('exclusive'); ?>';
                }
            }
            product_tax += formatDecimal(pr_tax_val * item_qty);
            unit_price = formatDecimal(unit_price+item_discount);
            var comment = (item.row.comment!=null && item.row.comment!="")? ' --'+ item.row.comment : "";
            var row_no = (new Date).getTime();
            var newTr = $('<tr></tr>');
            tr_html = '<td>' + item_name + ' (' + item_code + ')'+comment+'</td>';
            //tr_html += '<td class="text-right">' + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + '</td>';
            tr_html += '<td class="text-center">' + numberToReal(item_qty, 3) + '</td>';
            //tr_html += '<td class="text-right">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</td>';
            newTr.html(tr_html);
            newTr.prependTo("#billTable");
            total += ((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty));
            count += parseFloat(item_qty);
            an++;
            // $('#list-table-div').scrollTop(0);
        });


        var ds = get('spos_discount') ? get('spos_discount') : ($('#discount_val').val() ? $('#discount_val').val() : '0');
        if(ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            order_discount = (total*parseFloat(pds[0]))/100;
            $("#ds_con").text(formatMoney(order_discount));
        } else {
            order_discount = parseFloat(ds);
            $("#ds_con").text(formatMoney(order_discount));
        }

        var ts = get('spos_tax') ? get('spos_tax') : $('#tax_val').val();
        if(ts.indexOf("%") !== -1) {
            var pts = ts.split("%");
            order_tax = ((total-order_discount)*parseFloat(pts[0]))/100;
            //$("#ts_con").text(formatMoney(order_tax));
        } else {
            order_tax = parseFloat(ts);
            //$("#ts_con").text(formatMoney(order_tax));
        }

        var g_total = total - parseFloat(order_discount) + parseFloat(order_tax);
        grand_total = formatMoney(g_total);       
          //$("#total-payable").text(grand_total);
         //$("#total").text(formatMoney(total));
         $("#count").text((an-1)+' ('+formatMoney(count-1)+')');

        if (Settings.display_kb == 1) { display_keyboards(); }
        $('#add_item').focus();
    }
}
</script>

<?php } // fim romaneio ?>
<script>
function formatMoney(x, symbol) {
        if (!symbol) {
            symbol = "";
        }
        return accounting.formatMoney(x, symbol, Settings.decimals, Settings.thousands_sep == 0 ? ' ' : Settings.thousands_sep, Settings.decimals_sep, "%s%v");
    }

    function get(name) {
        if (typeof (Storage) !== "undefined") {
            return localStorage.getItem(name);
        } else {
            alert('Please use a modern browser as this site needs localstroage!');
        }
    }

    (function (p, z) {
        function q(a) {
            return !!("" === a || a && a.charCodeAt && a.substr)
        }

        function m(a) {
            return u ? u(a) : "[object Array]" === v.call(a)
        }

        function r(a) {
            return "[object Object]" === v.call(a)
        }

        function s(a, b) {
            var d, a = a || {}, b = b || {};
            for (d in b)b.hasOwnProperty(d) && null == a[d] && (a[d] = b[d]);
            return a
        }

        function j(a, b, d) {
            var c = [], e, h;
            if (!a)return c;
            if (w && a.map === w)return a.map(b, d);
            for (e = 0, h = a.length; e < h; e++)c[e] = b.call(d, a[e], e, a);
            return c
        }

        function n(a, b) {
            a = Math.round(Math.abs(a));
            return isNaN(a) ? b : a
        }

        function x(a) {
            var b = c.settings.currency.format;
            "function" === typeof a && (a = a());
            return q(a) && a.match("%v") ? {
                pos: a,
                neg: a.replace("-", "").replace("%v", "-%v"),
                zero: a
            } : !a || !a.pos || !a.pos.match("%v") ? !q(b) ? b : c.settings.currency.format = {
                pos: b,
                neg: b.replace("%v", "-%v"),
                zero: b
            } : a
        }

        var c = {
            version: "0.4.1",
            settings: {
                currency: {symbol: "$", format: "%s%v", decimal: ".", thousand: ",", precision: 2, grouping: 3},
                number: {precision: 0, grouping: 3, thousand: ",", decimal: "."}
            }
        }, w = Array.prototype.map, u = Array.isArray, v = Object.prototype.toString, o = c.unformat = c.parse = function (a, b) {
            if (m(a))return j(a, function (a) {
                return o(a, b)
            });
            a = a || 0;
            if ("number" === typeof a)return a;
            var b = b || ".", c = RegExp("[^0-9-" + b + "]", ["g"]), c = parseFloat(("" + a).replace(/\((.*)\)/, "-$1").replace(c, "").replace(b, "."));
            return !isNaN(c) ? c : 0
        }, y = c.toFixed = function (a, b) {
            var b = n(b, c.settings.number.precision), d = Math.pow(10, b);
            return (Math.round(c.unformat(a) * d) / d).toFixed(b)
        }, t = c.formatNumber = c.format = function (a, b, d, i) {
            if (m(a))return j(a, function (a) {
                return t(a, b, d, i)
            });
            var a = o(a), e = s(r(b) ? b : {
                precision: b,
                thousand: d,
                decimal: i
            }, c.settings.number), h = n(e.precision), f = 0 > a ? "-" : "", g = parseInt(y(Math.abs(a || 0), h), 10) + "", l = 3 < g.length ? g.length % 3 : 0;
            return f + (l ? g.substr(0, l) + e.thousand : "") + g.substr(l).replace(/(\d{3})(?=\d)/g, "$1" + e.thousand) + (h ? e.decimal + y(Math.abs(a), h).split(".")[1] : "")
        }, A = c.formatMoney = function (a, b, d, i, e, h) {
            if (m(a))return j(a, function (a) {
                return A(a, b, d, i, e, h)
            });
            var a = o(a), f = s(r(b) ? b : {
                symbol: b,
                precision: d,
                thousand: i,
                decimal: e,
                format: h
            }, c.settings.currency), g = x(f.format);
            return (0 < a ? g.pos : 0 > a ? g.neg : g.zero).replace("%s", f.symbol).replace("%v", t(Math.abs(a), n(f.precision), f.thousand, f.decimal))
        };
        c.formatColumn = function (a, b, d, i, e, h) {
            if (!a)return [];
            var f = s(r(b) ? b : {
                symbol: b,
                precision: d,
                thousand: i,
                decimal: e,
                format: h
            }, c.settings.currency), g = x(f.format), l = g.pos.indexOf("%s") < g.pos.indexOf("%v") ? !0 : !1, k = 0, a = j(a, function (a) {
                if (m(a))return c.formatColumn(a, f);
                a = o(a);
                a = (0 < a ? g.pos : 0 > a ? g.neg : g.zero).replace("%s", f.symbol).replace("%v", t(Math.abs(a), n(f.precision), f.thousand, f.decimal));
                if (a.length > k)k = a.length;
                return a
            });
            return j(a, function (a) {
                return q(a) && a.length < k ? l ? a.replace(f.symbol, f.symbol + Array(k - a.length + 1).join(" ")) : Array(k - a.length + 1).join(" ") + a : a
            })
        };
        if ("undefined" !== typeof exports) {
            if ("undefined" !== typeof module && module.exports)exports = module.exports = c;
            exports.accounting = c
        } else"function" === typeof define && define.amd ? define([], function () {
            return c
        }) : (c.noConflict = function (a) {
            return function () {
                p.accounting = a;
                c.noConflict = z;
                return c
            }
        }(p.accounting), p.accounting = c)
    })(this);
</script>
</body>
</html>