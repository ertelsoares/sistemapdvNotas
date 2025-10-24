<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close no-print" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('register_details').' ('.lang('opened_at').': '.$this->tec->hrld($this->session->userdata('register_open_time')).')'; ?> <a href="#" onclick="window.print();"><i class="fa fa-print no-print"></i></a></h4>

        </div>
        <div class="modal-body">
            <table width="100%" class="stable" style="color: #000;">
                
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
				
				<?php if ($stripesales->stripe>0) { ?>
                <tr>
					<td style="border-bottom: 1px solid #DDD;"><h4>Vendas em <?= lang('stripe'); ?>:</h4></td>
					<td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
							<span>R$ <?= $this->tec->formatMoney($stripesales->paid ? $stripesales->paid : '0.00'); ?></span>
						</h4></td>
				</tr>
				<?php } ?>
					
					
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
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em Transferência bancária:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($transfsales->paid ? $transfsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>
				
				<?php if($outrosales->paid>0){ ?>
				<tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4>Vendas em  -Outros:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($outrosales->paid ? $outrosales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
				<?php } ?>

                    
                <?php if($Admin){ ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span>R$ <?= $this->tec->formatMoney($totalsales->paid ? $totalsales->paid : '0.00'); ?></span>
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

                <?php if($Admin){ ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('total_cash'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span>R$ <strong><?= $this->tec->formatMoney( ( ($cashsales->paid ? $cashsales->paid + ($this->session->userdata('cash_in_hand')) : $this->session->userdata('cash_in_hand')) + $register_details->total_reforco) - $register_details->total_sangrias); ?></strong></span>
                        </h4></td>
                </tr>
                
                <?php } ?>

            </table>
            <br><br>
            <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#operate-register-form').toggle()" style="width: 100%;"  class="no-print">Sangria / Reforço de caixa</a>
                
            <?php
                echo form_open_multipart("pos/acc_operation_register", array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'operate-register-form','style'=>'display:none;background: #eaeaea; padding: 30px; margin-top: 10px; border-radius: 5px;')); ?>
                <div class="form-group">
                    <?= lang('Tipo de operação', 'tipo') ?>
                    <?=  form_dropdown('tipo', array("1" => "Sangria", "2" => "Reforço (Adição)"), '', 'id="tipo" class="form-control"'); ?>
                </div>
                
                 <div class="form-group">
                    <?= lang('Descrição', 'informacao') ?>
                    <?= form_input('informacao', '', 'id="informacao" autocomplete="none" required="required" class="form-control"'); ?>
                </div>

                <div class="form-group">
                    <?= lang('Valor', 'valor') ?>
                    <?= form_input('valor', '', 'id="valor" required="required" class="form-control form-control dinheiroinput"'); ?>
                </div>

                <!--<div class="form-group">
                    <?= lang('Senha Master de Desbloqueio PDV', 'senhaoperador') ?>
                    <?= form_password('senhaoperador', '', 'id="senhaoperador" autocomplete="none" required="required" class="form-control"'); ?>
                </div>-->

                <?php echo form_submit('salvar', lang('salvar'), 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
                <div class="clearfix"></div>

        </div>
    </div>

</div>
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>
