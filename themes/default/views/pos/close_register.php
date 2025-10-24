<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('register_details').' ('.lang('opened_at').': '.$this->tec->hrld($this->session->userdata('register_open_time')).')'; ?> <a href="#" onclick="window.print();"><i class="fa fa-print no-print"></i></a></h4>
            <h4 class="modal-title" id="myModalLabel">Fechamento do Caixa: <?php echo date("d/m/Y H:i:s"); ?></h4>
       
        </div>
        <?= form_open("pos/close_register/" . $user_id); ?>
        <div class="modal-body">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_in_hand'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($this->session->userdata('cash_in_hand')); ?></span></h4>
                    </td>
                </tr>

                <?php if($Admin){ ?>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_sale'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($cashsales->paid ? $cashsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <?php } ?>

                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>PIX:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($pixsales->paid ? $pixsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>

                <tr>
                    <td style="border-bottom: 1px solid <?= (!isset($Settings->stripe)) ? '#DDD' : '#EEE'; ?>;"><h4><?= lang('cc_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid <?= (!isset($Settings->stripe)) ? '#DDD' : '#EEE'; ?>;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($ccsales->paid ? $ccsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>

				<tr>
					<td style="border-bottom: 1px solid #DDD;"><h4>Vendas em <?= lang('stripe'); ?>:</h4></td>
					<td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
							<span>R$ <?= $this->tec->formatMoney($stripesales->paid ? $stripesales->paid : '0.00'); ?></span>
						</h4></td>
				</tr>

					
				<?php if($chsales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('ch_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($chsales->paid ? $chsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>
				
				<?php if($boletosales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em Boleto:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($boletosales->paid ? $boletosales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>
				
				<?php if($fiadosales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em Fiado:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($fiadosales->paid ? $fiadosales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>
				
				<?php if($transfsales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em Transferência:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($transfsales->paid ? $transfsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>
				
				<?php if($outrosales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em Outros:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($outrosales->paid ? $outrosales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>

                <?php if($Admin){ ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($totalsales->total ? $totalsales->total : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <?php } ?>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($expenses->total ? $expenses->total : '0.00'); ?></span>
                        </h4></td>
                </tr>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4>Reforços de caixa (+):</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span>R$ <?=$this->tec->formatMoney($register_details->total_reforco); ?></span>
                        </h4></td>
                </tr>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4>Sangrias de caixa (-):</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span>R$ <?=$this->tec->formatMoney($register_details->total_sangrias); ?></span>
                        </h4></td>
                </tr>
                
                 <?php if(!empty($register_details->note_sangrias)){ 
                    
                $notas_ref= json_decode($register_details->note_sangrias, true);
                foreach($notas_ref as $n){
                    echo '<tr><td colspan="2">'.$n.'</td></tr>';
                }
                
                } ?>

                <?php 
                $total_cash = ($cashsales->paid ? $cashsales->paid + ($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand')) : (($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand'))));
                $total_cash -= ($expenses->total ? $expenses->total : 0.00);
                $total_cash += ($register_details->total_reforco ? $register_details->total_reforco : 0.00);
                $total_cash -= ($register_details->total_sangrias ? $register_details->total_sangrias : 0.00);
                ?>
                <?php if($Admin){ ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('total_cash'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span><strong>R$ <?= $this->tec->formatMoney($total_cash); ?></strong></span>
                        </h4></td>
                </tr>
                <?php } ?>
            </table>

                <?php

                if ($suspended_bills) {
                    echo '<hr><h4>' . lang('opened_bills') . '</h4><table class="table table-hovered table-bordered"><thead><tr><th>' . lang('customer') . '</th><th>' . lang('date') . '</th><th>' . lang('reference') . '</th><th>' . lang('amount') . '</th><th><i class="fa fa-trash-o"></i></th></tr></thead><tbody>';
                    foreach ($suspended_bills as $bill) {
                        echo '<tr><td>' . $bill->customer_name . '</td><td>' . $this->tec->hrld($bill->date) . '</td><td class="col-xs-4">' . $bill->hold_ref . '</td><td class="text-right">' . $bill->grand_total . '</td><td class="text-center"><a class="tip no-print" title="' . lang("delete_bill") . '" href="' . site_url('sales/delete_holded/' . $bill->id) . '" onclick="return confirm(\''.lang('alert_x_holded').'\')"><i class="fa fa-trash-o"></i></a></td></tr>';
                    }
                    echo '</tbody></table>';
                }

                ?>
                <hr>
                <div class="row no-print">
                    <div class="col-sm-6">
                       <div class="form-group">
                            <?= lang("total_cash", "total_cash_submitted"); ?>
                            <?= form_input('total_cash_submitted', (isset($_POST['total_cash_submitted']) ? $_POST['total_cash_submitted'] : "" ), 'class="form-control dinheiroinput" id="total_cash_submitted" required="required"'); ?>
                        </div>
                       
                        <?= form_hidden('total_cc_slips', $ccsales->total_cc_slips); ?>
                        <?= form_hidden('total_cc_slips_submitted', (isset($_POST['total_cc_slips_submitted']) ? $_POST['total_cc_slips_submitted'] : $ccsales->total_cc_slips), 'id="total_cc_slips_submitted"'); ?>
                        <?= form_hidden('total_cheques', $chsales->total_cheques); ?>
                        <?= form_hidden('total_cheques_submitted', (isset($_POST['total_cheques_submitted']) ? $_POST['total_cheques_submitted'] : $chsales->total_cheques), 'id="total_cheques_submitted"'); ?>
						
                        <?= form_hidden('total_cash_open_caixa', $this->session->userdata('cash_in_hand')); ?>
                        <?= form_hidden('total_cash', $total_cash); ?>
                        <?= form_hidden('total_vendas_cash', $cashsales->paid); ?>
						<?= form_hidden('total_vendas_cc', $ccsales->paid); ?>
						<?= form_hidden('total_vendas_stripe', $stripesales->paid); ?>
						<?= form_hidden('total_vendas_pix', $pixsales->paid); ?>
						<?= form_hidden('total_vendas_other', $outrosales->paid); ?>
						<?= form_hidden('total_vendas_boleto', $boletosales->paid); ?>
						<?= form_hidden('total_vendas_transf', $transfsales->paid); ?>
                        <?= form_hidden('total_vendas_fiado', $fiadosales->paid); ?>
                        <?= form_hidden('total_vendas', $totalsales->total); ?>
                        <?= form_hidden('total_reforco', $register_details->total_reforco); ?>
                        <?= form_hidden('total_sangrias', $register_details->total_sangrias); ?>

                    </div>
                    <div class="col-sm-6">
                        <?php if ($suspended_bills) { ?>
                            <div class="form-group">
                                <?= lang("transfer_opened_bills", "transfer_opened_bills"); ?>
                                <?php $u = $user_id ? $user_id : $this->session->userdata('user_id');
                                $usrs[-1] = lang('delete_all');
                                $usrs[0] = lang('leave_opened');
                                foreach ($users as $user) {
                                    if ($user->id != $u) {
                                        $usrs[$user->id] = $user->first_name . ' ' . $user->last_name;
                                    }
                                }
                                ?>
                                <div class="clearfix"></div>
                                <?= form_dropdown('transfer_opened_bills', $usrs, (isset($_POST['transfer_opened_bills']) ? $_POST['transfer_opened_bills'] : 0), 'class="form-control input-tip select2" id="transfer_opened_bills" required="required" style="width:100%;" readonly'); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group no-print">
                    <label for="note"><?= lang("note"); ?></label>
                    <?= form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control redactor" id="note" style="margin-top: 10px; height: 100px;"'); ?>
                </div>

            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal"><?=lang('close')?></button>
                <?= form_submit('close_register', lang('close_register'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({minimumResultsForSearch:6});
    });
</script>
<script>
$( document ).ready(function() {
(function(e){e.fn.priceFormat=function(t){var n={prefix:"US$ ",suffix:"",centsSeparator:".",thousandsSeparator:",",limit:false,centsLimit:2,clearPrefix:false,clearSufix:false,allowNegative:false,insertPlusSign:false,clearOnEmpty:false};var t=e.extend(n,t);return this.each(function(){function m(e){if(n.is("input"))n.val(e);else n.html(e)}function g(){if(n.is("input"))r=n.val();else r=n.html();return r}function y(e){var t="";for(var n=0;n<e.length;n++){char_=e.charAt(n);if(t.length==0&&char_==0)char_=false;if(char_&&char_.match(i)){if(f){if(t.length<f)t=t+char_}else{t=t+char_}}}return t}function b(e){while(e.length<l+1)e="0"+e;return e}function w(t,n){if(!n&&(t===""||t==w("0",true))&&v)return"";var r=b(y(t));var i="";var f=0;if(l==0){u="";c=""}var c=r.substr(r.length-l,l);var h=r.substr(0,r.length-l);r=l==0?h:h+u+c;if(a||e.trim(a)!=""){for(var m=h.length;m>0;m--){char_=h.substr(m-1,1);f++;if(f%3==0)char_=a+char_;i=char_+i}if(i.substr(0,1)==a)i=i.substring(1,i.length);r=l==0?i:i+u+c}if(p&&(h!=0||c!=0)){if(t.indexOf("-")!=-1&&t.indexOf("+")<t.indexOf("-")){r="-"+r}else{if(!d)r=""+r;else r="+"+r}}if(s)r=s+r;if(o)r=r+o;return r}function E(e){var t=e.keyCode?e.keyCode:e.which;var n=String.fromCharCode(t);var i=false;var s=r;var o=w(s+n);if(t>=48&&t<=57||t>=96&&t<=105)i=true;if(t==8)i=true;if(t==9)i=true;if(t==13)i=true;if(t==46)i=true;if(t==37)i=true;if(t==39)i=true;if(p&&(t==189||t==109||t==173))i=true;if(d&&(t==187||t==107||t==61))i=true;if(!i){e.preventDefault();e.stopPropagation();if(s!=o)m(o)}}function S(){var e=g();var t=w(e);if(e!=t)m(t);if(parseFloat(e)==0&&v)m("")}function x(){n.val(s+g())}function T(){n.val(g()+o)}function N(){if(e.trim(s)!=""&&c){var t=g().split(s);m(t[1])}}function C(){if(e.trim(o)!=""&&h){var t=g().split(o);m(t[0])}}var n=e(this);var r="";var i=/[0-9]/;if(n.is("input"))r=n.val();else r=n.html();var s=t.prefix;var o=t.suffix;var u=t.centsSeparator;var a=t.thousandsSeparator;var f=t.limit;var l=t.centsLimit;var c=t.clearPrefix;var h=t.clearSuffix;var p=t.allowNegative;var d=t.insertPlusSign;var v=t.clearOnEmpty;if(d)p=true;n.bind("keydown.price_format",E);n.bind("keyup.price_format",S);n.bind("focusout.price_format",S);if(c){n.bind("focusout.price_format",function(){N()});n.bind("focusin.price_format",function(){x()})}if(h){n.bind("focusout.price_format",function(){C()});n.bind("focusin.price_format",function(){T()})}if(g().length>0){S();N();C()}})};e.fn.unpriceFormat=function(){return e(this).unbind(".price_format")};e.fn.unmask=function(){var t;var n="";if(e(this).is("input"))t=e(this).val();else t=e(this).html();for(var r in t){if(!isNaN(t[r])||t[r]=="-")n+=t[r]}return n}})(jQuery)
$('.dinheiroinput').priceFormat({
	prefix: '',
    centsSeparator: ',',
    thousandsSeparator: '.'
});
});		
</script>
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>
