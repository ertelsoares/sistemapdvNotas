<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('enter_info'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <?php $attrib = array('class' => 'validation', 'role' => 'form');
                        echo form_open("gift_cards/add", $attrib); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <?= lang("card_no", "card_no"); ?>
                                    <div class="input-group">
                                        <?php echo form_input('card_no', '', 'class="form-control" id="card_no" required="required"'); ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                           id="genNo">Gerar número <i
                                           class="fa fa-cogs"></i></a></div>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                    <?= lang("value", "value"); ?>
                                    <?php echo form_input('value', '', 'class="form-control dinheiroinput" id="value" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("expiry_date", "expiry"); ?>
                                    <div class="input-group">
                                        <?php echo form_input('expiry', '', 'class="form-control date" id="expiry"'); ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                           id="noExpir">Limpar</a></div>
                                   </div>
                                   <i style="font-size:13px;">Deixe vazio para sem data de expiração</i>
                                </div>
                                <div class="form-group">
                                    <?= form_submit('add_gift_Card', lang('add_gift_card'), 'class="btn btn-primary"'); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                    <div class="clearfix"></div>
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
        var no = generateCardNo();
        $('#card_no').val(no);
        $('#genNo').click(function () {
            no = generateCardNo();
            $('#card_no').val($('#card_no').masked(no));
            return false;
        });
        $('#noExpir').click(function () {
            $('#expiry').val("");
            return false;
        });
        $("#expiry").datetimepicker({
            format: 'DD/MM/YYYY',
            locale: "pt-br"
        });
        $('#card_no').mask("0000 0000 0000 0000");
        $('.date').mask('00/00/0000');
        $('.dinheiroinput').mask("#.##0,00", {reverse: true});
        setTimeout(() => {
           try {
            $('li.treeview.mm_sales > a').trigger("click");
           } catch (error) {
           }
       }, 100);
    });
</script>