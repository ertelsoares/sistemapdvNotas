<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $page_title.' | '.$Settings->site_name; ?></title>
    <link rel="shortcut icon" href="<?= site_url(); ?>icon.ico"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    .table th { text-align: center; }
    </style>
</head>
<body>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?= lang('purchase').' # '.$purchase->id; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="col-lg-12">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td class="col-xs-2"><?= lang('date'); ?></td>
                                                    <td class="col-xs-10"><?= $this->tec->hrld($purchase->date); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-xs-2"><?= lang('reference'); ?></td>
                                                    <td class="col-xs-10"><?= $purchase->reference; ?></td>
                                                </tr>
                                                <?php
                                                if($purchase->attachment) {
                                                    ?>
                                                    <tr>
                                                        <td class="col-xs-2"><?= lang('attachment'); ?></td>
                                                        <td class="col-xs-10"><a href="<?=base_url('uploads/'.$purchase->attachment);?>"><?= $purchase->attachment; ?></a></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if($purchase->note) {
                                                    ?>
                                                    <tr>
                                                        <td class="col-xs-2"><?= lang('note'); ?></td>
                                                        <td class="col-xs-10"><?= $purchase->note; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="active">
                                                        <th><?= lang('product'); ?></th>
                                                        <th class="col-xs-2"><?= lang('quantity'); ?></th>
                                                        <th class="col-xs-2"><?= lang('unit_cost'); ?></th>
                                                        <th class="col-xs-2"><?= lang('subtotal'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    if($items) {
                                                        foreach ($items as $item) {
                                                            echo '<tr>';
                                                            echo '<td>'.$item->product_name.' ('.$item->product_code.')</td>';
                                                            echo '<td class="text-center">'.number_format($item->quantity, 3, ',', '.').'</td>';
                                                            echo '<td class="text-right">'. number_format($item->cost, 2, ',', '.').'</td>';
                                                            echo '<td class="text-right">'.number_format(($item->quantity*$item->cost), 2, ',', '.').'</td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>

                                                </tbody>
                                                <thead>
                                                    <tr class="active">
                                                        <td><?= lang('total'); ?></td>
                                                        <td class="col-xs-2"></td>
                                                        <td class="col-xs-2"></td>
                                                        <td class="col-xs-2 text-right"><?=number_format($purchase->total, 2, ',', '.');?></td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div
    </section>
</body>
</html>
