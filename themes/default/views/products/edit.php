<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('update_info'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <?= form_open_multipart("products/edit/".$product->id, 'class="validation"');?>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('type', 'type'); ?>
                                <?php $opts = array('standard' => "Produto", 'service' => lang('service')); ?>
                                <?= form_dropdown('type', $opts, set_value('type', $product->type), 'class="form-control tip select2" id="type"  required="required" style="width:100%;"'); ?>
                            </div>
                                <div class="form-group">
                                    <?= lang('name', 'name'); ?> (máx. 120 caracteres)
                                    <?= form_input('name', $product->name, 'class="form-control tip" id="name" maxlength="120" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('code', 'code'); ?> <?= lang('can_use_barcode'); ?>
                                    <?= form_input('code', $product->code, 'class="form-control tip" id="code" maxlength="50" required="required"'); ?>
                                </div>
                                <div class="form-group st">
                                    <?= lang("barcode_symbology", "barcode_symbology") ?>
                                    <?php
                                    $bs = array('ean8' => 'EAN8', 'ean13' => 'EAN13', 'code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                                    echo form_dropdown('barcode_symbology', $bs, set_value('barcode_symbology', $product->barcode_symbology), 'class="form-control select2" id="barcode_symbology" required="required" style="width:100%;"');
                                    ?>
                                </div>

                                <div class="form-group">
                                    <?= lang('category', 'category'); ?>
                                    <?php
                                    $cat[''] = lang("select")." ".lang("category");
                                    foreach($categories as $category) {
                                        $cat[$category->id] = $category->name;
                                    }
                                    ?>
                                    <?= form_dropdown('category', $cat, $product->category_id, 'class="form-control select2 tip" id="category"  required="required"'); ?>
                                </div>
                              
                               <div class="form-group st">
                              	<label for="unit">Unidade de medida</label>
                                  <?php $opts = array('UN' => "Unidade", 'KG' => "Kilograma", 'PC' => 'Peça', 'CX' => 'CX', 'DZ' => 'DZ', 'CJ' => 'CJ','MT' => 'MT','M2' => 'M2','M3' => 'M3','FRD' => 'FRD','PCT' => 'PCT'); ?>
                                <?= form_dropdown('unit', $opts, $product->unit, 'class="form-control tip select2" id="unit"  required="required" style="width:100%;"'); ?>
                            		</div>

                                <div class="form-group st" >
                                    <?= lang('cost', 'cost'); ?>
                                    <?= form_input('cost', $this->tec->formatMoney($product->cost), 'class="form-control tip dinheiroinput" id="cost"  required="required"'); ?>
                                </div>

                                <div class="form-group st">
                                    <?= lang('Margem (%)', 'margem'); ?>
                                    <?= form_input('margem', (($product->cost>0)?((int)((($product->price/$product->cost)-1)*100)):""),  'class="form-control tip justnum" id="margem"'); ?>
                                </div>

                                <div class="form-group">
                                    <?= lang('price', 'price'); ?>
                                    <?= form_input('price', $this->tec->formatMoney($product->price), 'class="form-control tip dinheiroinput" id="price"  required="required"'); ?>
                                </div>
                                <input name='product_tax' type="hidden" value='0' id="product_tax">
                                <input name='tax_method'  type="hidden"  value='1' id="tax_method">

                                <div class="form-group st">
                                    <?= lang('Quantidade em estoque', 'Quantidade em estoque'); ?>
                                    <?= form_input('quantity', set_value('quantity', $this->tec->formatNumber($product->quantity, (($product->unit=="UN"||$product->unit=="PC"||$product->unit=="PCT")?0:3))), 'class="form-control tip quantidadeinput" id="quantity"  required="required"'); ?>
                                </div>

                                <div class="form-group st row" style="padding: 5px; background: #f4f4f4;">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang('Composição - Código de barras', 'composicao_codigo'); ?> 
                                            <?= form_input('composicao_codigo', $product->composicao_codigo, 'class="form-control tip" id="composicao_codigo"'); ?>
                                        </div> 
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang('Quantidade a descontar', 'composicao_quantidade'); ?> 
                                            <?= form_input('composicao_quantidade', $product->composicao_quantidade, 'class="form-control justnum tip" id="composicao_quantidade"'); ?>
                                        </div> 
                                    </div> 
        
                                </div> 


                                <div class="form-group st">
                                    <?= lang('alert_quantity', 'alert_quantity'); ?>
                                    <?= form_input('alert_quantity', set_value('alert_quantity', (int) $product->alert_quantity), 'class="form-control tip" id="alert_quantity"  required="required"'); ?>
                                </div>
                                
                                      
                                 <div class="form-group">
                                <?= lang('Comissão (%)', 'comissao'); ?>
                                    <?= form_input('comissao', set_value('comissao', $product->comissao), 'class="form-control tip dinheiroinput" type="number" id="comissao"'); ?>
                                </div>

                                <div class="form-group">
                                    <?= lang('image', 'image'); ?> PNG, JPG, GIF, (300px x 300px)
                                    <input type="file" name="userfile" id="image">
                                </div>
                            </div>
                            <div class="col-md-6">
                                

                            <h3>Configuração de Impostos <span style="font-size:14px;"> (<?php echo (($Settings->ativar_emissao_notas=="1")?'Obrigatório':'Não Obrigatório'); ?>)</span></h3>
                                <div class="form-group st">
                                    <label for="origem">Origem do produto</label>
                                    <?php $tm = array(0 => '0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8', 1 => '1 - Estrangeira - Importação direta, exceto a indicada no código 6', 2 => '2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7', 3 => '3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%', 4 => '4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes', 5 => '5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%', 6 => '6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural', 7 => '7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural', 8 => '8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%'); ?>
                                    <?= form_dropdown('origem', $tm, $product->origem, 'class="form-control tip select2" id="origem" style="width:100%;"'. (($Settings->ativar_emissao_notas=="1")?' required="required"':'')); ?>  
                                </div>

                                <div class="form-group">
                                <label for="cfop">CFOP (Dentro do Estado)</label>
                                  <?= form_dropdown('cfop', listaCFOPPRODUTOS, $product->cfop, 'class="form-control tip select2" id="cfop" style="width:100%;"'. (($Settings->ativar_emissao_notas=="1")?' required="required"':'')) ?>
                                </div>

                                    <div class="form-group">
                                    <label for="ncm">Código NCM</label>
                                        <?= form_input('ncm', set_value('ncm', $product->ncm), 'class="form-control tip" id="ncm"'. (($Settings->ativar_emissao_notas=="1")?' required="required"':'')); ?>
                                     </div>
                                    <div class="form-group st">
                                        <label for="cest">Código CEST</label>
                                            <?= form_input('cest', set_value('cest', $product->cest), 'class="form-control tip" id="cest"'); ?>
                                    </div>
                                    <div class="form-group">
                                   <label for="impostos">Grupo de Impostos</label>
                                                                    
                                    <?php
                                    $imp[''] = lang("select");
                                    foreach($impostos as $imposto) {
                                        if($imposto->tipo==1){ $impadd = "Produto - "; }else{ $impadd = "Serviço - "; }
                                        $imp[$imposto->id] = $impadd.$imposto->nome;
                                    }
                                    ?>
                                    <?= form_dropdown('imposto', $imp, set_value('imposto', $product->imposto), 'class="form-control select2 tip" id="impostos" style="width:100%;"'. (($Settings->ativar_emissao_notas=="1")?' required="required"':'')); ?>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <?= lang('details', 'details'); ?>
                            <?= form_textarea('details', $product->details, 'class="form-control tip redactor" id="details"'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_submit('edit_product', lang('edit_product'), 'class="btn btn-primary"'); ?>
                        </div>
                        <?= form_close();?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= $assets ?>dist/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/jquery.mask.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.dinheiroinput').mask("#.##0,00", {reverse: true});
        $('#alert_quantity').mask("00000");
        $('.justnum').mask("00000");

        $('.quantidadeinput').change(function(e) {
            var v = $(this).val();
            if(v!=""){
                const regex = /[^0-9|,|.]+/g;
                const subst = ``;
                const result = v.replace(regex, subst);
                $(this).val(result);
            }
        });

        $('#margem, #cost').on('change',function(e) {
            var m = $("#margem").val();
            if(m!=""){ 
                m = parseInt(m);
                var c = parseFloat(RealToDolar($("#cost").val()));
                var np = c + ((c/100) * m);
                console.log(c, m, np);
                $("#price").val(DolarToReal(np));
            }
        });

        function DolarToReal(numero = "", decimal = 2) {
            if(numero=="" || numero == null) return 0;
            return new Intl.NumberFormat('de-DE', { style: 'decimal', currency: 'EUR', minimumFractionDigits: decimal, maximumFractionDigits: decimal }).format(numero);
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
    });
</script>
<script type="text/javascript">

    var price = 0; cost = 0; items = {};
    $(document).ready(function() {
        $('#type').change(function(e) {
            var type = $(this).val();
            if(type == 'combo') {
                $('.st').slideUp();
                $('#ct').slideDown();
                //$('#cost').attr('readonly', true);
            } else if(type == 'service') {
                $('.st').slideUp();
                $('#ct').slideUp();
                $("#alert_quantity, #quantity").val("0");
                $("#cest").val("");
                $("#ncm").val("00");
                //$('#cost').attr('readonly', false);
            } else {
                $('#ct').slideUp();
                $('.st').slideDown();
                //$('#cost').attr('readonly', false);
            }
        });

        $("#add_item").autocomplete({
            source: '<?= site_url('products/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item(ui.item);
                    if (row) {
                        $(this).val('');
                    }
                } else {
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            delete items[id];
            $(this).closest('#row_' + id).remove();
        });


        $(document).on('change', '.rqty', function () {
            var item_id = $(this).attr('data-item');
            items[item_id].row.qty = (parseFloat($(this).val())).toFixed(2);
            add_product_item(null, 1);
        });

        $(document).on('change', '.rprice', function () {
            var item_id = $(this).attr('data-item');
            items[item_id].row.price = (parseFloat($(this).val())).toFixed(2);
            add_product_item(null, 1);
        });

        function add_product_item(item, noitem) {
            if (item == null && noitem == null) {
                return false;
            }
            if(noitem != 1) {
                item_id = item.row.id;
                if (items[item_id]) {
                    items[item_id].row.qty = (parseFloat(items[item_id].row.qty) + 1).toFixed(2);
                } else {
                    items[item_id] = item;
                }
            }
            price = 0;
            cost = 0;

            $("#prTable tbody").empty();
            $.each(items, function () {
                var item = this.row;
                var row_no = item.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + item.id + '"></tr>');
                tr_html = '<td><input name="combo_item_code[]" type="hidden" value="' + item.code + '"><span id="name_' + row_no + '">' + item.name + ' (' + item.code + ')</span></td>';
                tr_html += '<td><input class="form-control text-center rqty" name="combo_item_quantity[]" type="text" value="' + formatDecimal(item.qty) + '" data-id="' + row_no + '" data-item="' + item.id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                //tr_html += '<td><input class="form-control text-center rprice" name="combo_item_price[]" type="text" value="' + formatDecimal(item.price) + '" data-id="' + row_no + '" data-item="' + item.id + '" id="combo_item_price_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable");
                //price += formatDecimal(item.price*item.qty);
                cost += formatDecimal(item.cost*item.qty);
            });
            $('#cost').val(cost);
            return true;
        }
        var type = $('#type').val();
        if(type == 'combo') {
            $('.st').slideUp();
            $('#ct').slideDown();
            //$('#cost').attr('readonly', true);
        } else if(type == 'service') {
            $('.st').slideUp();
            $('#ct').slideUp().val("0");
            $('#cost, #quantity , #alert_quantity').val('0');
            $("#cest").val("");
            $("#ncm").val("00");
            //$('#cost').attr('readonly', false);
        } else {
            $('#ct').slideUp();
            $('.st').slideDown();
            //$('#cost').attr('readonly', false);
        }
        <?php
        if($this->input->post('type') == 'combo') {
            $c = sizeof($_POST['combo_item_code']);
            $items = array();
            for ($r = 0; $r <= $c; $r++) {
                if(isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                    $items[] = array('id' => $_POST['combo_item_id'][$r], 'row' => array('id' => $_POST['combo_item_id'][$r], 'name' => $_POST['combo_item_name'][$r], 'code' => $_POST['combo_item_code'][$r], 'qty' => $_POST['combo_item_quantity'][$r], 'cost' => $_POST['combo_item_cost'][$r]));
                }
            }
            echo '
            var ci = '.json_encode($items).';
            $.each(ci, function() { add_product_item(this); });
            ';
        } elseif(!empty($items)) {
            echo '
            var ci = '.json_encode($items).';
            $.each(ci, function() { add_product_item(this); });
            ';
        }
        ?>
    });

</script>