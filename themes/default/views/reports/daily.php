<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><span class='text-warning'>1) <?=$this->lang->line('tax')?> = <?= lang('orange'); ?></span> / 2) <?=$this->lang->line('discount')?> = <?= lang('grey'); ?> / <span class='text-success'>3) <?=$this->lang->line('total')?> de vendas = <?= lang('green'); ?></span> / 4) Total Geral</h3>
                </div>
                <div class="box-body">
                     <div class="col-sm-12 noprint" style="text-align:right;margin-bottom:10px;">
                        <button type="button" onclick="window.print()" class="btn btn-default btn-sm noprint">Imprimir</button>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <div class="info-box bg-aqua">
                                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?= lang('sales_value'); ?></span>
                                        <span class="info-box-number"><?= $this->tec->formatMoney($total_sales->total_amount) ?></span>
                                        <div class="progress">
                                            <div style="width: 100%" class="progress-bar"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_sales->total .' ' . lang('sales'); ?> |
                                            <?= $this->tec->formatMoney($total_sales->paid) . ' ' . lang('received') ?> |
                                            <?= $this->tec->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-plus"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?= lang('purchases_value'); ?></span>
                                        <span class="info-box-number"><?= $this->tec->formatMoney($total_purchases->total_amount) ?></span>
                                        <div class="progress">
                                            <div style="width: 0%" class="progress-bar"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_purchases->total ?> <?= lang('purchases'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-red">
                                    <span class="info-box-icon"><i class="fa fa-circle-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?= lang('expenses_value'); ?></span>
                                        <span class="info-box-number"><?= $this->tec->formatMoney($total_expenses->total_amount) ?></span>
                                        <div class="progress">
                                            <div style="width: 0%" class="progress-bar"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_expenses->total ?> <?= lang('expenses'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?= lang('profit_loss'); ?></span>
                                        <span class="info-box-number"><?= $this->tec->formatMoney($total_sales->total_amount-$total_purchases->total_amount-$total_expenses->total_amount) ?></span>
                                        <div class="progress">
                                            <div style="width: 100%" class="progress-bar"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_sales->total_amount.' - '.$total_purchases->total_amount.' - '.$total_expenses->total_amount;?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="calendar table-responsive">
                            <?=$calender?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>