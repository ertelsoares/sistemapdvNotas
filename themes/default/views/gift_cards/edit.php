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
                        <?php $attrib = array('class' => 'validation', 'role' => 'form');
                        echo form_open("gift_cards/edit/".$gift_card->id, $attrib); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang("card_no", "card_no"); ?>
                                        <?php echo form_input('card_no', $gift_card->card_no, 'class="form-control" id="card_no" required="required"'); ?>
                                   </div>
                                   <div class="form-group">
                                    <?= lang("value", "value"); ?>
                                    <?php echo form_input('value', $gift_card->value, 'class="form-control" id="value" required="required"'); ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("expiry_date", "expiry"); ?>
                                    <div class="input-group">
                                        <?php echo form_input('expiry', $gift_card->expiry, 'class="form-control date" id="expiry"'); ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                           id="noExpir">Limpar</a></div>
                                          
                                   </div>
                                   <i style="font-size:13px;">Deixe vazio para sem data de expiração</i>
                                </div>

                                <div class="form-group">
                                    <?= form_submit('edit_gift_Card', lang('edit_gift_card'), 'class="btn btn-primary"'); ?>
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