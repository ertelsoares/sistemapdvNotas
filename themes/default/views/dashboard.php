<?php 
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

(defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<script src="<?= $assets ?>plugins/highchart/highcharts.js"></script>
<script src="<?= $assets ?>plugins/highchart/exporting.js"></script>
<?php
if($chartData) {
    foreach ($chartData as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $sales[] = $month_sale->total;
        $tax[] = $month_sale->tax;
        $discount[] = $month_sale->discount;
    }
} else {
    $months[] = '';
    $sales[] = '';
    $tax[] = '';
    $discount[] = '';
}
?>

<script type="text/javascript">

    $(document).ready(function () {
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                stops: [[0, color], [1, Highcharts.Color(color).brighten(-0.3).get('rgb')]]
            };
        });
        <?php if($chartData) { ?>
        $('#chart').highcharts({
            chart: { },
            credits: { enabled: false },
            exporting: { enabled: false },
            title: { text: '' },
            xAxis: { categories: [<?php foreach($months as $month) { echo "'".$month."', "; } ?>] },
            yAxis: { min: 0, title: "" },
            tooltip: {
                shared: true,
                followPointer: true,
                headerFormat: '<div class="well well-sm" style="margin-bottom:0;"><span style="font-size:12px">{point.key}</span><table class="table table-striped" style="margin-bottom:0;">',
                pointFormat: '<tr><td style="color:{series.color};padding:4px">{series.name}: </td>' +
                '<td style="color:{series.color};padding:4px;text-align:right;"> <b>{point.y}</b></td></tr>',
                footerFormat: '</table></div>',
                useHTML: true, borderWidth: 0, shadow: false,
                style: {fontSize: '14px', padding: '0', color: '#000000'}
            },
            plotOptions: {
                series: { stacking: 'normal' }
            },
            series: [{
                type: 'column',
                name: '<?= $this->lang->line("tax"); ?>',
                data: [<?= implode(', ', $tax); ?>]
            },
            {
                type: 'column',
                name: '<?= $this->lang->line("discount"); ?>',
                data: [<?= implode(', ', $discount); ?>]
            },
            {
                type: 'column',
                name: '<?= $this->lang->line("sales"); ?>',
                data: [<?= implode(', ', $sales); ?>]
            }
            ]
        });
        <?php } ?>
        <?php if ($topProducts) { ?>
$('#chart2').highcharts({
    chart: { },
    title: { text: '' },
    credits: { enabled: false },
    exporting: { enabled: false },
    tooltip: {
        shared: true,
        followPointer: true,
        headerFormat: '<div class="well well-sm" style="margin-bottom:0;"><span style="font-size:12px">{point.key}</span><table class="table table-striped" style="margin-bottom:0;">',
        pointFormat: '<tr><td style="color:{series.color};padding:4px">{series.name}: </td>' +
        '<td style="color:{series.color};padding:4px;text-align:right;"> <b>{point.y}</b></td></tr>',
        footerFormat: '</table></div>',
        useHTML: true, borderWidth: 0, shadow: false,
        style: {fontSize: '14px', padding: '0', color: '#000000'}
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: false
        }
    },

    series: [{
        type: 'pie',
        name: '<?=$this->lang->line('total_sold')?>',
        data: [
        <?php
        foreach($topProducts as $tp) {
            echo "['".$tp->product_name." (".$tp->product_code.")', ".$tp->quantity."],";

        } ?>
        ]
    }]
});
<?php } ?>
});

</script>
<?php if($alertaVencimento!="" && $_GET["welcome"]=="1"){?>
<div class="col-lg-12 alerts">
<div class="alert alert-warning alert-dismissable">
<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
<h4>	<i class="icon fa fa-check"></i> Alerta</h4>
<p><?=$alertaVencimento;?></p>            </div>
</div>
<?php } ?>

<section class="content" style="max-width:1300px">
    <div class="row">

        <div class="col-xs-12" style="margin:20px 0px;">
            <div class="box-1 box-primary" style="text-align: left;">
                <a class="btn btn-app" href="<?= site_url('sales/creador_notas'); ?>">
                    <i class="fa fa-file-text-o"></i> Emissor Nota Fiscal
                </a>
                <a class="btn btn-app" href="<?= site_url('pos'); ?>">
                    <i class="fa fa-th"></i> <?= lang('pos'); ?>
                </a>
                <?php if($UserPerfil != "vendas"){?>
                <a class="btn btn-app" href="<?= site_url('products'); ?>">
                    <i class="fa fa-barcode"></i> <?= lang('products'); ?>
                </a>
                <?php } ?>
                <a class="btn btn-app" href="<?= site_url('sales'); ?>">
                    <i class="fa fa-shopping-cart"></i> <?= lang('sales'); ?>
                </a>
                <a class="btn btn-app" href="<?= site_url('sales/opened'); ?>">
                    <span class="badge bg-yellow"><?php echo (!is_bool($suspended_sales))? sizeof($suspended_sales) : "";?></span>
                    <i class="fa fa-bell-o"></i> <?= lang('opened_bills'); ?>
                </a>
                <a class="btn btn-app" href="<?= site_url('customers'); ?>">
                    <i class="fa fa-users"></i> <?= lang('customers'); ?>
                </a>
                <?php if($Admin) { ?>
                    <a class="btn btn-app" href="<?= site_url('users'); ?>">
                    <i class="fa fa-users"></i> <?= lang('users'); ?>
                </a>
                <a class="btn btn-app" href="<?= site_url('reports'); ?>">
                    <i class="fa fa-bar-chart-o"></i> <?= lang('reports'); ?>
                </a>
                <a class="btn btn-app" href="<?= site_url('settings'); ?>">
                    <i class="fa fa-cogs"></i> <?= lang('settings'); ?>
                </a>
                <?php } ?>
            </div>
        </div>
        <div class="col-xs-12" style="margin-top:10px;">
        <div class='text-right'><div class='btn-group'><a class='tip btn btn-primary btn-lg' id='' <?php if($filtro=="hoje"){ echo "disabled='disabled'";}?> href='<?= site_url(); ?>?f=hoje' title=''>Hoje</a> <a class='tip btn btn-primary btn-lg' id='' <?php if($filtro=="semana"){ echo "disabled='disabled'";}?> href='<?= site_url(); ?>?f=semana' title=''>Esta Semana</a> <a class='tip btn btn-primary btn-lg' id='' <?php if($filtro=="mes"){ echo "disabled='disabled'";}?> href='<?= site_url(); ?>?f=mes' title=''>Este Mês</a></div></div>
        </div>

        <div class="col-xs-12" style="margin-top:10px;">
            <div class="row">
                <?php if($Admin) { ?>
                <div class="col-md-4">
                    <div class="box box-primary box-totais">
                        <div class="filter_icon"><i class="fa fa-exclamation" style="background:#eb2a1c;padding: 20px 33px"></i></div>
                        <div class="filter_info"><span class="tipo">DESPESAS</span><br><span class="valor">R$ <?=$totalDespesas?></span></div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-md-4">
                    <div class="box box-primary box-totais">
                        <div class="filter_icon"><i class="fa fa-shopping-cart" style="background: #097edb; padding: 20px 22px;"></i></div>
                        <div class="filter_info"><span class="tipo">VENDAS</span><br><span class="valor">R$ <?=$totalVendas?></span></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary box-totais">
                        <div class="filter_icon"><i class="fa fa-money" style="background: #ff9800; padding: 20px 20px;"></i></div>
                        <div class="filter_info"><span class="tipo">VENDAS EM ABERTO</span><br><span class="valor">R$ <?=$totalEmaberto?></span></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="row" style="margin-top:20px;">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><b><?= lang('sales_chart').' ('.date('Y').')'?></b></h3>
                </div>
                <div class="box-body">
                    <div id="chart" style="height:300px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><b><?= lang('top_products').' ('.date('F Y').')'; ?></b></h3>
                </div>
                <div class="box-body">
                    <div id="chart2" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
            <!-- anuncios -->
            <div class="col-xs-12" style="margin:10px 0px;">
            <?php include("promotions.php"); ?>
        </div>
    </div>
</section>
<style>
.box-totais{height:100px;}
.filter_icon{width: 100px; padding-top: 10px; text-align: center; float: left;}
.filter_icon i{font-size: 40px; border-radius: 50%; color:#FFF}
.filter_info{padding:10px;}
.filter_info > .tipo{font-size:20px;color: #787878; font-size: 20px; }
.filter_info > .valor{font-size:30px; font-weight:bold}
</style>