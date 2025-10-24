<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head><meta charset="utf-8">
	
	<title><?= $page_title.' | '.$Settings->site_name; ?></title>
	<link rel="shortcut icon" href="<?= site_url(); ?>icon.ico"/>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>plugins/redactor/redactor.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>dist/css/jquery-ui.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>dist/css/custom.css?<?=date("ymdhis")?>" rel="stylesheet" type="text/css" />
	<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
	<style>.content-header>h1{color:#fff}.content-wrapper{margin-right:0!important;background-size:cover;background-image:url('<?=base_url();?>fundo.jpg');background-repeat:no-repeat}.skin-blue .main-header .navbar{background-color:#041028}.skin-blue .left-side,.skin-blue .main-sidebar,.skin-blue .wrapper{background-color:#041028}.skin-blue .main-header .logo{background-color:#041028;color:#fff;border-bottom:0 solid transparent}.skin-blue .sidebar a{color:#fff;font-size:16px}.select2-hidden-accessible{-webkit-appearance:textfield}table.layout-table{background-color:#091b38!important}.pos button.edit{font-size:16px}.pos .contents{margin-left:80px}.btn-group .btn{border-radius:25px!important}.form-control-lg,.input-group-lg>.form-control,.input-group-lg>.input-group-append>.btn,.input-group-lg>.input-group-append>.input-group-text,.input-group-lg>.input-group-prepend>.btn,.input-group-lg>.input-group-prepend>.input-group-text{padding:.5rem 1rem;font-size:32px!important;line-height:4.5;height:52px}.text-right{font-size:18px}.opacity-div{opacity:.5}.navbar-nav>li>a:hover {background: #ffffff24!important;}.sidebar-mini.sidebar-collapse .sidebar-menu>li:hover>a>span:not(.pull-right), .sidebar-mini.sidebar-collapse .sidebar-menu>li:hover>.treeview-menu{min-width:220px!important;}</style>
	<style>
	@media (max-width: 767px){
	.ui-menu .ui-menu-item{
	font-size:20px;
	}
	.pos #pos {
	padding: 0px!important;
	}

	.pos table td{padding-right:0px!important}

	#payment {
	width: 200px!important;
	font-size: 20px!important;
	}

	.pos .input-qty {
    font-size:20px!important
}

	}

</style>
</head>
<body class="skin-blue sidebar-collapse sidebar-mini pos">
	<div class="wrapper">

		<header class="main-header">
			<a href="<?= site_url(); ?>" class="logo">
				<span class="logo-mini">PDV</span>
				<span class="logo-lg"><b>PDV</b></span>
			</a>
			<nav class="navbar navbar-default navbar-static-top">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-pos-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-pos-navbar-collapse-1">

					<ul class="nav navbar-nav navbar-right" style="margin: 0px!important;">
                        <li><a href="#" class="clock"></a></li>
						<li><a href="<?= site_url('pos/view_bill'); ?>?hold=<?php echo $_GET["hold"];?>&hold_ref=<?=$reference_note?>" target="_blank"><i class="fa fa-file-text-o"></i> <?= lang('gerar_orcamento'); ?></a></li>
						<?php if($suspended_sales) { ?>
						<li class="notifications-menu">
						    <a href="<?=site_url("sales/opened");?>">
								<?=lang("list_opened_bills");?>
						        <span class="label label-warning"><?=sizeof($suspended_sales);?></span>
						    </a>
						</li>
						<?php } ?>
						<?php if($Admin) { ?>
						<li><a href="<?= site_url('pos/today_sale'); ?>" data-toggle="ajax"><?= lang('today_sale'); ?></a></li>
						<?php } ?>
						<li><a href="<?= site_url('pos/register_details'); ?>" data-toggle="ajax"><?= lang('register_details'); ?></a></li>
						<li><a href="<?= site_url('pos/close_register'); ?>" data-toggle="ajax"><?= lang('close_register'); ?></a></li>
				
						<li class="dropdown user user-menu notshow">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?= base_url('uploads/avatars/avatar.png') ?>" class="user-image" alt="Avatar" />
								<span><?= $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img src="<?= base_url('uploads/avatars/avatar.png') ?>" class="img-circle" alt="Avatar" />
									<p>
										<?= $this->session->userdata('email'); ?>
									</p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= site_url('users/profile/'.$this->session->userdata('user_id')); ?>" class="btn btn-default btn-flat"><?= lang('profile'); ?></a>
									</div>
									<div class="pull-right">
										<a href="<?= site_url('logout'); ?>" class="btn btn-default btn-flat"><?= lang('sing_out'); ?></a>
									</div>
								</li>
							</ul>
						</li>
						
					</ul>
				</div>
			</nav>
		</header>

		<aside class="main-sidebar">
			<section class="sidebar">
				<ul class="sidebar-menu">
					<li class="header"><?= lang('mian_navigation'); ?></li>

					<li id="mm_welcome"><a href="<?= site_url(); ?>"><i class="fa fa-dashboard"></i> <span><?= lang('dashboard'); ?></span></a></li>
					<li id="mm_pos"><a href="<?= site_url('pos'); ?>"><i class="fa fa-th"></i> <span><?= lang('pos'); ?></span></a></li>

					<?php if($Admin) { ?>
						<li class="treeview" id="mm_products">
							<a href="#">
								<i class="fa fa-barcode"></i>
								<span><?= lang('products'); ?></span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li id="products_index"><a href="<?= site_url('products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_products'); ?></a></li>
								<li id="products_add"><a href="<?= site_url('products/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_product'); ?></a></li>
								<li id="products_import_csv"><a href="<?= site_url('products/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_products'); ?></a></li>
								<li class="divider"></li>
								<li id="categories_index"><a href="<?= site_url('categories'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_categories'); ?></a></li>
								<li id="categories_add"><a href="<?= site_url('categories/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_category'); ?></a></li>
								<li id="categories_import"><a href="<?= site_url('categories/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_categories'); ?></a></li>
								</ul>
							</li>
			
							<li class="treeview" id="mm_sales">
								<a href="#">
									<i class="fa fa-shopping-cart"></i>
									<span><?= lang('sales'); ?></span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li id="sales_index"><a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_sales'); ?></a></li>
									<li id="sales_opened"><a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_opened_bills'); ?></a></li>
									<li id="sales_index"><a href="<?= site_url('sales/?ispaid=2'); ?>"><i class="fa fa-circle-o"></i> Controle de Fiado</a></li>
									<li class="divider"></li>
									<li id="sales_notasfiscais"><a href="<?= site_url('sales/notasfiscais'); ?>"><i class="fa fa-circle-o"></i> Notas Fiscais</a></li>
                        			<li id="sales_creador_notas"><a href="<?= site_url('sales/creador_notas'); ?>"><i class="fa fa-circle-o"></i> Emissor Nota Fiscal</a></li>
								</ul>
							</li>
							<li class="treeview mm_purchases">
			                    <a href="#">
			                        <i class="fa fa-plus"></i>
			                        <span><?= lang('purchases'); ?></span>
			                        <i class="fa fa-angle-left pull-right"></i>
			                    </a>
			                    <ul class="treeview-menu">
			                        <li id="purchases_index"><a href="<?= site_url('purchases'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_purchases'); ?></a></li>
			                        <li id="purchases_add"><a href="<?= site_url('purchases/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_purchase'); ?></a></li>
								 </ul>
			                </li>
			
							<li class="treeview mm_auth mm_customers mm_suppliers">
			                    <a href="#">
			                        <i class="fa fa-users"></i>
			                        <span><?= lang('people'); ?></span>
			                        <i class="fa fa-angle-left pull-right"></i>
			                    </a>
			                    <ul class="treeview-menu">
			                        <li id="auth_users"><a href="<?= site_url('users'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_users'); ?></a></li>
			                        <li id="auth_add"><a href="<?= site_url('users/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_user'); ?></a></li>
			                        <li class="divider"></li>
			                        <li id="customers_index"><a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_customers'); ?></a></li>
			                        <li id="customers_add"><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_customer'); ?></a></li>
			                        <li class="divider"></li>
			                        <li id="suppliers_index"><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_suppliers'); ?></a></li>
			                        <li id="suppliers_add"><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_supplier'); ?></a></li>
			                    </ul>
			                </li>
							<li class="treeview" id="mm_reports">
								<a href="#">
									<i class="fa fa-bar-chart-o"></i>
									<span><?= lang('reports'); ?></span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li id="reports_daily_sales"><a href="<?= site_url('reports/daily_sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('daily_sales'); ?></a></li>
									<li id="reports_monthly_sales"><a href="<?= site_url('reports/monthly_sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('monthly_sales'); ?></a></li>
									<li id="reports_index"><a href="<?= site_url('reports'); ?>"><i class="fa fa-circle-o"></i> <?= lang('sales_report'); ?></a></li>
									<li class="divider"></li>
									<li id="reports_payments"><a href="<?= site_url('reports/payments'); ?>"><i class="fa fa-circle-o"></i> <?= lang('payments_report'); ?></a></li>
									<li class="divider"></li>
									<li id="reports_registers"><a href="<?= site_url('reports/registers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('registers_report'); ?></a></li>
									<li class="divider"></li>
									<li id="reports_top_products"><a href="<?= site_url('reports/top_products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('top_products'); ?></a></li>
									<li id="reports_products"><a href="<?= site_url('reports/products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('products_report'); ?></a></li>
								</ul>
							</li>
							<li class="treeview" id="mm_settings">
							<a href="<?= site_url('settings'); ?>">
									<i class="fa fa-cogs"></i>
									<span><?= lang('settings'); ?></span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
							</li>
						<?php } else { ?>
							<li id="mm_products"><a href="<?= site_url('products'); ?>"><i class="fa fa-barcode"></i> <span><?= lang('products'); ?></span></a></li>
							<li id="mm_categories"><a href="<?= site_url('categories'); ?>"><i class="fa fa-folder-open"></i> <span><?= lang('categories'); ?></span></a></li>
							<li class="treeview" id="mm_sales">
								<a href="#">
									<i class="fa fa-shopping-cart"></i>
									<span><?= lang('sales'); ?></span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li id="sales_index"><a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_sales'); ?></a></li>
									<li id="sales_opened"><a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_opened_bills'); ?></a></li>
								</ul>
							</li>
							<!--<li class="treeview mm_purchases">
			                    <a href="#">
			                        <i class="fa fa-plus"></i>
			                        <span><?= lang('expenses'); ?></span>
			                        <i class="fa fa-angle-left pull-right"></i>
			                    </a>
			                    <ul class="treeview-menu">
			                        <li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
			                        <li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
			                    </ul>
			                </li>-->
							<li class="treeview" id="mm_customers">
								<a href="#">
									<i class="fa fa-users"></i>
									<span><?= lang('customers'); ?></span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li id="customers_index"><a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_customers'); ?></a></li>
									<li id="customers_add"><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_customer'); ?></a></li>
								</ul>
							</li>
							<li class="treeview mm_suppliers">
			                    <a href="#">
			                        <i class="fa fa-users"></i>
			                        <span><?= lang('suppliers'); ?></span>
			                        <i class="fa fa-angle-left pull-right"></i>
			                    </a>
			                    <ul class="treeview-menu">
			                        <li id="suppliers_index"><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_suppliers'); ?></a></li>
			                        <li id="suppliers_add"><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_supplier'); ?></a></li>
			                    </ul>
			                </li>
							<?php } ?>
						</ul>
					</section>
				</aside>

				<div class="content-wrapper" style="margin-left:0px!important">

					<div class="col-lg-12 alerts">
						<?php if($error)  { ?>
						<div class="">
							
							
							
						</div>
						
						<div class="">
							
							
							
						</div>
						<?php } ?>
					</div>

					<table style="width:100%;" class="layout-table">
						<tr>
							<td>
							<div class="contents" id="right-col">
										<div >
										<a href="#" id="open-cat-btn" data-toggle="control-sidebar"  style="outline:none;" class="sidebar-icon"><button type="button" style="font-size: 22px; outline:none; width: 100%; border-radius: 25px; background: #f29c12; border: 0px; color: #FFF; margin-bottom: 10px;">Ver categorias de produtos <i class="fa fa-folder sidebar-icon"></i></button></a>
										</div>
										<div id="item-list">
											<div class="items">
												<?php echo $products; ?>
											</div>
										</div>
										<div class="product-nav">
											<div class="btn-group btn-group-justified">
												<div class="btn-group">
													<button style="z-index:10002;" class="btn btn-warning pos-tip btn-flat" type="button" id="previous"><i class="fa fa-chevron-left"></i></button>
												</div>
												<div class="btn-group">
												<a href='<?= site_url('/sales/iframeredi?link='.site_url('products/add?isframe=1')); ?>' style="float:right;margin: 1px 0px;" data-toggle='ajax' title='Adicionar produto' class='tip btn btn-warning btn-flat'><i class='fa fa-plus'></i> Adicionar Produto</a>
												</div>
												<div class="btn-group">
												<a href='<?= site_url('/sales/iframeredi?link='.urlencode(site_url('customers/add?isframe=1&reload_topframe=1'))); ?>' style="float:right;margin: 1px 0px;" data-toggle='ajax' title='Adicionar cliente' class='tip btn btn-warning btn-flat'><i class='fa fa-plus'></i> Adicionar Cliente</a>
												</div>
												<div class="btn-group">
													<button style="z-index:10004;" class="btn btn-warning pos-tip btn-flat" type="button" id="next"><i class="fa fa-chevron-right"></i></button>
												</div>
											</div>
										</div>
									</div>
								</td>
							<td style="width: 460px;padding-right:10px;">

								<div id="pos">
									<?= form_open('pos', 'id="pos-sale-form" onsubmit="return validarEnvioForm()"'); ?>
									<div class="well well-sm" id="leftdiv">
										<div id="lefttop" style="margin-bottom:5px;">
											<div class="form-group" style="margin-bottom:5px;">
												<div class="input-group">
													<?php foreach($customers as $customer){ $cus[$customer->id] = $customer->name. " (".$customer->cf1.")"; } ?>
													<?= form_dropdown('customer_id', $cus, set_value('customer_id', $Settings->default_customer), 'id="spos_customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control select2" style="width:100%;"'); ?>
													<div class="input-group-addon no-print" style="padding:0px;">
														<a href="#" id="add-customer" class="external" data-toggle="modal" data-target="#myModal"><b  style="padding: 3px 5px; background: #FF9800; color: #FFF; font-size: 22px;">CPF/CNPJ</b></a><a id="modal_add_cliente" href='<?= site_url('/sales/iframeredi?link='.urlencode(site_url('customers/add?isframe=1&reload_topframe=1'))); ?>' data-toggle='ajax' title='Adicionar cliente' class='tip'><b  style="padding: 3px 5px; background: #d58004; color: #FFF; font-size: 21px;"><i class='fa fa-plus'></i> Cliente</b></a>
													</div>
												</div>
												<div style="clear:both;"></div>
											</div>
											<div class="form-group" style="margin-bottom:5px;">
												<input type="text" name="code" id="add_item" class="form-control form-control-lg" placeholder="<?=lang('search__scan')?>" />
											</div>
										</div>
					
										<div id="print">
											<div id="list-table-div">
												<table id="posTable" class="table table-striped table-condensed table-hover list-table" style="margin:0;">
													<thead>
														<tr class="success">
															<th><?=lang('product')?></th>
															<th style="width: 15%;text-align:center;"><?=lang('price')?></th>
															<th style="width: 15%;text-align:center;"><?=lang('qty')?></th>
															<th style="width: 20%;text-align:center;"><?=lang('subtotal')?></th>
															<th style="width: 20px;" class="satu"><i class="fa fa-trash-o"></i></th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
											<div style="clear:both;"></div>
											<div id="totaldiv">
												<table id="totaltbl" class="table table-condensed totals" style="margin-bottom:10px;">
													<tbody>
														<tr class="info">
															<td width="25%"><?=lang('total_items')?></td>
															<td class="text-right" style="padding-right:10px;"><span id="count">0</span></td>
															<td width="25%"><?=lang('total')?></td>
															<td class="text-right" colspan="2"><span id="total">0</span></td>
														</tr>
														<tr class="info">
															<td width="25%"><a class="btn btn-warning btn-block btn-sm" href="#" id="add_discount">Editar <?=lang('discount')?> <i class="fa fa-edit"></i></a></td>
															<td class="text-right" style="padding-right:10px;"><span id="ds_con">0,00</span></td>
															<td width="25%"><a href="#" class="btn btn-warning btn-block btn-sm" id="add_tax">Editar <?=lang('order_tax')?> <i class="fa fa-edit"></i></a></td>
															<td class="text-right"><span id="ts_con">0,00</span></td>
														</tr>
														<tr class="success">
															<td colspan="2" style="font-weight:bold;"><?=lang('total_payable')?></td>
															<td class="text-right" colspan="2" style="font-weight:bold;"><span id="total-payable">0</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div id="botbuttons" class="col-xs-12 text-center">
											<div class="row">
												<div class="col-xs-5" style="padding: 0;">
													<div class="btn-group-vertical btn-block">
														<button type="button" class="btn <?php if($_GET["hold"]!=""){ echo "btn-success"; } else { echo "btn-warning"; } ?> btn-block btn-flat"
														id="suspend"><?php if($_GET["hold"]!=""){ echo lang('save'). " (".$reference_note.")";  } else { echo lang('suspend_sale'); } ?></button>
														<button type="button" class="btn btn-danger btn-block btn-flat"
														id="reset"><?= lang('cancel'); ?></button>
													</div>

												</div>
												<div class="col-xs-7" style="padding: 0;">
													<button type="button" class="btn btn-success btn-block btn-flat" id="<?= $eid ? 'submit-sale' : 'payment'; ?>" <?= $eid ? 'submit-sale' : (($user_info->permitir_fechar_venda=="0")?'disabled':''); ?> style="height:67px;width: 100%;font-size:30px;"><?= $eid ? lang('submit') : "Finalizar Venda"; ?></button>
												</div>
											</div>

										</div>
										<div class="clearfix"></div>
										<span id="hidesuspend"></span>
										<input type="hidden" name="spos_note" value="" id="spos_note">
										<input type="hidden" name="spos_entrega_endereco" value="" id="spos_entrega_endereco">

										<div id="payment-con">
										    
										<!--<input type="hidden" name="amount" id="amount_val" value="<?= $eid ? $sale->paid : ''; ?>"/>-->
										<input type="hidden" name="amount[]" id="amount_val1" value=""/>
										<input type="hidden" name="amount[]" id="amount_val2" value=""/>
										<input type="hidden" name="amount[]" id="amount_val3" value=""/>
										<input type="hidden" name="paid_by[]" id="paid_by1" value="cash"/>
										<input type="hidden" name="paid_by[]" id="paid_by2" value="stripe"/>
										<input type="hidden" name="paid_by[]" id="paid_by3" value="CC"/>
										<input type="hidden" name="balance_amount" id="balance_val" value=""/>
										<input type="hidden" name="cc_no" id="cc_no_val" value=""/>
										<input type="hidden" name="paying_gift_card_no" id="paying_gift_card_no_val" value=""/>
										<input type="hidden" name="cc_holder" id="cc_holder_val" value=""/>
										<input type="hidden" name="cheque_no" id="cheque_no_val" value=""/>
										<input type="hidden" name="cc_month" id="cc_month_val" value=""/>
										<input type="hidden" name="cc_year" id="cc_year_val" value=""/>
										<input type="hidden" name="cc_type" id="cc_type_val" value=""/>
										<input type="hidden" name="cc_cvv2" id="cc_cvv2_val" value=""/>
										<input type="hidden" name="balance" id="balance_val" value=""/>
										<input type="hidden" name="payment_note" id="payment_note_val" value=""/>
										</div>
										<input type="hidden" name="customer" id="customer" value="<?=$Settings->default_customer?>" />
										<input type="hidden" name="order_tax" id="tax_val" value="" />
										<input type="hidden" name="order_discount" id="discount_val" value="" />
										<input type="hidden" name="count" id="total_item" value="" />
										<input type="hidden" name="did" id="is_delete" value="<?=$sid;?>" />
										<input type="hidden" name="eid" id="is_delete" value="<?=$eid;?>" />
										<input type="hidden" name="hold_ref" id="hold_ref" value="" />
										<input type="hidden" name="total_items" id="total_items" value="0" />
										<input type="hidden" name="total_quantity" id="total_quantity" value="0" />
										<input type="hidden" name="vendedor" id="vendedor_input" value="" />
										<input type="submit" id="submit" value="Submit Sale" style="display: none;" />
										<?php if($totalpago_open!="") { ?>
										<input type="hidden" id="amount_999" value="<?php echo number_format($totalpago_open, 2,',','.');?>" class="amount"/>
										<input type="hidden" id="amount_val999" value=""/>
										<?php } ?>

									</div>
									<?=form_close();?>
								</div>

							</td>

						</tr>
					</table>
				</div>
			</div>

			<aside class="control-sidebar control-sidebar-dark" id="categories-list">
				<div class="tab-content">
					<div class="tab-pane active" id="control-sidebar-home-tab">
						<ul class="control-sidebar-menu">
							<?php
							foreach($categories as $category) {
								echo '<li><a href="#" class="category'.($category->id == $Settings->default_category ? ' active' : '').'" id="'.$category->id.'">';
								if($category->image) {
									echo '<div class="menu-icon"><img src="'.base_url('uploads/thumbs/'.$category->image).'" alt="" class="img-thumbnail img-circle img-responsive"></div>';
								} else {
									echo '<i class="menu-icon fa fa-folder-open bg-red"></i>';
								}
								echo '<div class="menu-info"><h4 class="control-sidebar-subheading">'.$category->code.'</h4><p>'.$category->name.'</p></div>
							</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		</aside>
		<div class="control-sidebar-bg"></div>
	</div>
</div>
<div id="order_tbl" style="display:none;"><span id="order_span"></span>
	<table id="order-table" class="prT table table-striped table-condensed" style="width:100%;margin-bottom:0;"></table>
</div>
<div id="bill_tbl" style="display:none;"><span id="bill_span"></span>
	<table id="bill-table" width="100%" class="prT table table-striped table-condensed" style="width:100%;margin-bottom:0;"></table>
	<table id="bill-total-table" width="100%" class="prT table table-striped table-condensed" style="width:100%;margin-bottom:0;"></table>
</div>

<div class="modal" data-easein="flipYIn" id="posModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal" data-easein="flipYIn" id="posModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>

<div class="modal" data-easein="flipYIn" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
			</div>
			<div class="modal-body">
				<p><?= lang('enter_info'); ?></p>

				<div class="alert alert-danger gcerror-con" style="display: none;">
					<button data-dismiss="alert" class="close" type="button">x</button>
					<span id="gcerror"></span>
				</div>
				<div class="form-group">
					<?= lang("card_no", "gccard_no"); ?> *
					<div class="input-group">
						<?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
						<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#" id="genNo">Gerar número <i class="fa fa-cogs"></i></a></div>
					</div>
				</div>
				<input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>
				<div class="form-group">
					<?= lang("Valor em crédito (Ex.: 100,00)", "gcvalue"); ?> *
					<?php echo form_input('gcvalue', '', 'class="form-control dinheiroinput" id="gcvalue"'); ?>
				</div>
				<div class="form-group">
					<?= lang("Preço do vale presente (Ex.: 50,00)", "gcprice"); ?> *
					<?php echo form_input('gcprice', '0,00', 'class="form-control dinheiroinput" id="gcprice"'); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=lang('close')?> (esc)</button>
				<button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal" data-easein="flipYIn" id="dsModal" tabindex="-1" role="dialog" aria-labelledby="dsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="dsModalLabel"><?= lang('discount_title'); ?></h4>
			</div>
			<div class="modal-body">
			<select id='get_ds_select' class='form-control form-control-lg kb-pad' onchange="$('#get_ds').removeClass('dinheiroinput'); $('#get_ds').removeClass('percentinput');$('#get_ds').addClass(this.value);"><option value="dinheiroinput">Valor</option><option value="percentinput">Porcentagem</option></select><br>
			
				<input type='text' class='form-control form-control-lg kb-pad' id='get_ds' onClick='this.select();' value=''>

				<label class="checkbox" for="apply_to_order">
					<input type="radio" name="apply_to" value="order" id="apply_to_order" checked="checked"/>
					<?= lang('apply_to_order') ?>
				</label>
				<!--<label class="checkbox" for="apply_to_products">
					<input type="radio" name="apply_to" value="products" id="apply_to_products"/>
					<?= lang('apply_to_products') ?>
				</label>-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal"><?=lang('close')?> (esc)</button>
				<button type="button" id="updateDiscount" class="btn btn-primary btn-lg"><?= lang('update') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal" data-easein="flipYIn" id="tsModal" tabindex="-1" role="dialog" aria-labelledby="tsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="tsModalLabel"><?= lang('tax_title'); ?></h4>
			</div>
			<div class="modal-body">
			<select id='get_ts_select' class='form-control form-control-lg kb-pad' onchange="$('#get_ts').removeClass('dinheiroinput'); $('#get_ts').removeClass('percentinput');$('#get_ts').addClass(this.value);"><option value="dinheiroinput">Valor</option><option value="percentinput">Porcentagem</option></select><br>
				<input type='text' class='form-control form-control-lg kb-pad' id='get_ts' onClick='this.select();' value=''>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal"><?=lang('close')?> (esc)</button>
				<button type="button" id="updateTax" class="btn btn-primary btn-lg"><?= lang('update') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal" data-easein="flipYIn" id="proModal" tabindex="-1" role="dialog" aria-labelledby="proModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="proModalLabel">
					<?=lang('payment')?>
				</h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped">
					<tr>
						<th style="width:25%;"><?= lang('net_price'); ?></th>
						<th style="width:25%;"><span id="net_price"></span></th>
						<th style="width:25%;"><?= lang('product_tax'); ?></th>
						<th style="width:25%;"><span id="pro_tax"></span> <span id="pro_tax_method"></span></th>
					</tr>
				</table>
				<input type="hidden" id="row_id" />
				<input type="hidden" id="item_id" />
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<?=lang('unit_price', 'nPrice')?>
							<input type="text" class="form-control form-control-lg kb-pad dinheiroinput" id="nPrice" onClick="this.select();" placeholder="<?=lang('new_price')?>">
						</div>
						<!--<div class="form-group">
							<?=lang('discount', 'nDiscount')?>
							<input type="text" class="form-control form-control-lg kb-pad dinheiroinput" id="nDiscount" onClick="this.select();" placeholder="<?=lang('discount')?>">
						</div>-->
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<?=lang('quantity', 'nQuantity')?>
							<input type="text" class="form-control form-control-lg kb-pad" id="nQuantity" onClick="this.select();" placeholder="<?=lang('current_quantity')?>">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label for="nComment">Comentários</label> <textarea class="form-control kb-text" id="nComment"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=lang('close')?> (esc)</button>
				<button class="btn btn-success" id="editItem"><?=lang('update')?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal" data-easein="flipYIn" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="susModalLabel"><?= lang('suspend_sale'); ?></h4>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<?php if(TIPONEGOCIO=="restaurante") { ?>
						
						<?= lang("reference_note_especifico", "reference_note"); ?>
						<?php 
						if($reference_note!=""){
							echo form_input('reference_note', $reference_note, 'class="form-control form-control-lg kb-text" id="reference_note"');
						
						}else{

							if($_GET["hold_sugestao"]!=""){
								$reference_note = $_GET["hold_sugestao"];
							}

							if($this->Settings->total_mesas>0){
								$m = array();
								$m[""] = "Selecione...";
								$m["*****"] = "-- Personalizado --";
								for($x = 1; $x <= $this->Settings->total_mesas; $x++){ 
									if(empty($mesas["mesa_".$x])){
										// so mostramos as mesas livres
										$m["mesa_".$x] = "Mesa ".$x;
									}
								} 
								?>
								<?php echo form_dropdown('reference_note', $m, $reference_note, 'class="form-control form-control-lg select-2" onchange="if(this.value==\'*****\'){$(\'#reference_note_personalizado_input\').show().focus();}else{$(\'#reference_note_personalizado_input\').val(\'\').hide();}" id="reference_note"'); ?>

								<?php echo form_input('reference_note_personalizado_input', '', 'class="form-control form-control-lg kb-text" style="display:none;margin-top:10px;" placeholder="Digite um referência" id="reference_note_personalizado_input"'); ?>

							<?php } else { ?>
								<?php echo form_input('reference_note', $reference_note, 'class="form-control form-control-lg kb-text" id="reference_note"'); ?>
							<?php }

						} ?>

					<?php } else { ?>
						<?= lang("reference_note", "reference_note"); ?>
						<?php echo form_input('reference_note', $reference_note, 'class="form-control form-control-lg kb-text" id="reference_note"'); ?>
					<?php } ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=lang('close')?> (esc)</button>
				<button type="button" id="suspend_sale" class="btn btn-primary"><?= lang('submit') ?></button>
			</div>
		</div>
	</div>
</div>


<div class="modal" data-easein="flipYIn" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel" aria-hidden="true"></div>
<div class="modal" data-easein="flipYIn" id="opModal" tabindex="-1" role="dialog" aria-labelledby="opModalLabel" aria-hidden="true"></div>

<div class="modal" data-easein="flipYIn" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-success">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title" id="payModalLabel">
					<?=lang('payment')?>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="font16">
							<table class="table table-bordered table-condensed" style="margin-bottom: 0;">
								<tbody>
									<tr>
										<td width="25%" style="border-right-color: #FFF !important;"><?= lang("total_items"); ?></td>
										<td width="25%" class="text-right"><span id="item_count" style="font-size:25px;">0,00</span></td>
										<td width="25%" style="border-right-color: #FFF !important;"><?= lang("total_payable"); ?></td>
										<td width="25%" class="text-right"><span id="twt" style="font-size:27px;">0,00</span></td>
									</tr>
									<tr>
										<td style="border-right-color: #FFF !important;"><?= lang("total_paying"); ?></td>
										<td class="text-right"><span id="total_paying" style="font-size:27px;">0,00</span></td>
										<td style="border-right-color: #FFF !important;"><?= lang("balance"); ?> ou <?= lang("change"); ?></td>
										<td class="text-right"><span id="balance" style="font-size:27px;">0,00</span></td>
									</tr>
								</tbody>
							</table>
							<div class="clearfix"></div>
						</div>
							<div class="col-xs-6">
								<div class="input-group" style="">
										<?php
										$vend[""] = "Selecione o vendedor...";
										foreach($vendedores as $vendedor){ $vend[$vendedor->id] = $vendedor->first_name.' '.$vendedor->last_name; } ?>
									<?= form_dropdown('vendedor', $vend, set_value('vendedor', ''), 'id="spos_vendedor" onchange="$(\'#vendedor_input\').val(this.value)" data-placeholder="' . lang("select") . ' o vendedor" required="required" class="form-control select2" style="width:100%;"'); ?>
								</div>
							</div>
							
							<div class="col-xs-6" style="font-size: 16px; font-weight: 700;height: 35px;">
								<?php if($totalpago_open!=""){ ?>
									<div class="input-group" style="background: #f39c11; color: #000000; padding: 3px 10px;">
									VALOR JÁ PAGO: R$ <?php echo number_format($totalpago_open, 2,',','.');?>
									</div>
								<?php } ?>
							</div>
							
						
							<div class="col-xs-6">
								<div class="form-group">
									<?= lang('Endereço de Entrega', 'entrega_endereco'); ?>
									<input name="entrega_endereco" id="entrega_endereco" class="pa form-control kb-text">
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group">
									<?= lang('Anotações', 'note'); ?>
									<input name="note" id="note" class="pa form-control kb-text">
								</div>
							</div>

							<div class="col-xs-6" style="background: #0000003d;">
								<div class="form-group">
										<?= lang("amount", "amount_1"); ?>
									<input type="text" id="amount_1"
									class="pa form-control kb-pad amount dinheiroinput"/>
								</div>
							</div>
							<div class="col-xs-6" style="background: #0000006b;">
								<div class="form-group">
								<?= lang("paying_by", "paidby_1"); ?>
									<?php 
										$pag = array(); 
										$pag["cash"] = "Dinheiro";
									?>
									<?= form_dropdown('paid_by[]', $pag, set_value('paid_by', 'cash'), ' id="paidby_1" class="form-control paid_by select2" style="width:100%;"'); ?>
								
								</div>
							</div>
							
								<div class="col-xs-6" style="background: #0000003d;">
								<div class="form-group">
										<?= lang("amount", "amount_2"); ?>
									<input type="text" id="amount_2"
									class="pa form-control kb-pad amount dinheiroinput"/>
								</div>
							</div>
							<div class="col-xs-6" style="background: #0000006b;">
								<div class="form-group">
								<?= lang("paying_by", "paidby_2"); ?>
									
									<?php
									$pag = array(); 
									$pag[""] = "Selecione o pagamento...";
									$pag["stripe"] = "Cartão de Débito";
									$pag["CC"] = "Cartão de Crédito";
									?>
									<?= form_dropdown('paid_by[]', $pag, set_value('paid_by', 'stripe'), ' id="paidby_2" class="form-control paid_by select2" style="width:100%;"'); ?>
								
								</div>
							</div>
							
								<div class="col-xs-6" style="background: #0000003d;">
								<div class="form-group">
										<?= lang("amount", "amount_3"); ?>
									<input type="text" id="amount_3"
									class="pa form-control kb-pad amount dinheiroinput"/>
								</div>
							</div>
							<div class="col-xs-6" style="background: #0000006b;">
								<div class="form-group">
								<?= lang("paying_by", "paidby_3"); ?>
									
									<?php
									$pag = array(); 
									$pag[""] = "Selecione o pagamento...";
									$pag["pix"] = "PIX";
									
									foreach($meiopagamento as $pagamento){ $pag[$pagamento->cod] = $pagamento->nome; } 
									unset($pag["cash"]);
									?>
									<?= form_dropdown('paid_by[]', $pag, set_value('paid_by', 'CC'), ' id="paidby_3" class="form-control paid_by select2" style="width:100%;"'); ?>
								
								</div>
							</div>
							<div id="paid_pix" style="display:none;margin-top:5px;" class="col-xs-12 col-12 col-md-12">
								<div class="form-group">
									<button class="btn btn-warning btn-block" type="button" onclick="gerarpix()">Gerar PIX</button>
								</div>
							</div>
							
						</div>

						
						<div class="col-xs-12">
							<div class="form-group gc" style="display: none;">
								<?= lang("gift_card_no", "gift_card_no"); ?>
								<input type="text" id="gift_card_no"
								class="pa form-control kb-pad gift_card_no gift_card_input"/>
								<div id="gc_details"></div>
							</div>

							</div>
							<div class="pcheque" style="display:none;">
							<div class="form-group">Cheque
								<input type="text" id="cheque_no"
								class="form-control cheque_no  kb-text"/>
							</div>

					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer" style="background:#f4f4f4">
			<button type="button" class="btn btn-sm btn-danger pull-left" data-dismiss="modal"> <?=lang('close')?> (esc)</button>
				<button class="btn btn-warning" type="button" id="<?= $eid ? '' : 'submit-sale'; ?>_nopag">Finalizar sem pagamento total</button>
			<button class="btn btn-primary" style="font-size:22px;" type="button" id="<?= $eid ? '' : 'submit-sale'; ?>">Finalizar</button>
		</div>
	</div>
</div>
</div>
</div>
<div class="modal" data-easein="flipYIn" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="cModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
				<h4 class="modal-title" id="cModalLabel">
					CPF/CNPJ na Nota
				</h4>
			</div>
			<?= form_open('pos/add_customer', 'id="customer-form"'); ?>
			<div class="modal-body">
				<input type="hidden" name='tipo_cad' value='1'>
				<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
				<div class="row">
					<div class="col-xs-9">
						<div class="form-group">
							<label class="control-label" id="titlecpfnanota" for="cf1">
								CPF
							</label>
							<button style="button" class="btn btn-sm btn-warning" onclick="$('#titlecpfnanota').text('CPF');$('#cf1').removeClass('cnpj').addClass('cpf');">CPF</button> <button style="button" class="btn btn-sm btn-warning" onclick="$('#titlecpfnanota').text('CNPJ');$('#cf1').removeClass('cpf').addClass('cnpj');">CNPJ</button>
							<br>
							<?= form_input('cf1', '', 'class="form-control form-control-lg kb-text cpf" required="true" style="width:100%;" id="cf1"'); ?>
							<input name="name" type="hidden" value="Cliente">
						</div>
					</div>
			</div>
			<div class="modal-footer" style="margin-top:0;">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <?=lang('close')?> (esc)</button>
				<button type="submit" class="btn btn-primary" id="add_customer"> <?=lang('submit')?> </button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div class="modal" data-easein="flipYIn" id="customerModalAdd" tabindex="-1" role="dialog" aria-labelledby="cModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
				<h4 class="modal-title" id="cModalLabel">
					<?=lang('add_customer')?> (Para NF todos devem estar preencihos corretamente.)
				</h4>
			</div>
			<?= form_open('pos/add_customer', 'id="customer-form"'); ?>
			<div class="modal-body">
				<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
        <div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label" for="tipo">
								Tipo Cadastro (1 = Pessoa Fisica / 2 = Pessoa Jurídica)
							</label>
							<?= form_input('tipo_cad', $customer->tipo_cad, 'class="form-control input-sm kb-text" id="tipo"'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label" for="code">
								<?= lang("name"); ?>
							</label>
							<?= form_input('name', '', 'class="form-control input-sm kb-text" id="cname"'); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cemail">
								<?= lang("email_address"); ?>
							</label>
							<?= form_input('email', '', 'class="form-control input-sm kb-text" id="cemail"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="phone">
								<?= lang("phone"); ?>
							</label>
							<?= form_input('phone', '', 'class="form-control input-sm kb-pad" id="cphone"');?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf1">
								<?= lang("cf1"); ?>
							</label>
							<?= form_input('cf1', '', 'class="form-control input-sm kb-text" id="cf1"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf2">
								<?= lang("cf2"); ?>
							</label>
							<?= form_input('cf2', '', 'class="form-control input-sm kb-text" id="cf2"');?>
						</div>
					</div>
				</div>
        <!-- Campos personalizados-->
        <div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cf1">
								Endereço
							</label>
							<?= form_input('endereco', '', 'class="form-control input-sm kb-text" id="cf1"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="numero">
								Número
							</label>
							<?= form_input('numero', '', 'class="form-control input-sm kb-text" id="numero"');?>
						</div>
					</div>
				</div>
        
        		<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="complemento">
								Complemento
							</label>
							<?= form_input('complemento', '', 'class="form-control input-sm kb-text" id="complemento"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="bairro">
								Bairro
							</label>
							<?= form_input('bairro', '', 'class="form-control input-sm kb-text" id="bairro"');?>
						</div>
					</div>
				</div>
        
        		<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cidade">
							 Cidade
							</label>
							<?= form_input('cidade', '', 'class="form-control input-sm kb-text" id="cidade"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="codigocidade">
								Código IBGE Cidade
							</label>
							<?= form_input('codigocidade', '', 'class="form-control input-sm kb-text" id="codigocidade"');?>
						</div>
					</div>
				</div>
        
        <div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="estado">
							 Estado (Ex.: SP)
							</label>
							<?= form_input('estado', '', 'class="form-control input-sm kb-text" id="estado"'); ?>
						</div>
					</div>
				</div>
        
        <div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="cep">
							 CEP
							</label>
							<?= form_input('cep', '', 'class="form-control input-sm kb-text" id="cep"'); ?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="control-label" for="pais">
								País
							</label>
							<?= form_input('pais', 'BRASIL', 'class="form-control input-sm kb-text" id="pais"');?>
						</div>
					</div>
				</div>
        <!-- Fim campos personalizados -->

			</div>
			<div class="modal-footer" style="margin-top:0;">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <?=lang('close')?> </button>
				<button type="submit" class="btn btn-primary" id="add_customer"> <?=lang('add_customer')?> </button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script src="<?= $assets ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/redactor/redactor.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/select2/select2.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/formvalidation/js/formValidation.popular.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/formvalidation/js/framework/bootstrap.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/common-libs.js?<?=date("ymdhis")?>" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/app.js?<?=date("ymdhis")?>" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/pages/all.js?<?=date("ymdhis")?>" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/custom.js?<?=date("ymdhis")?>" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/velocity/velocity.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/velocity/velocity.ui.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/parse-track-data.js" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/pos.js?<?=date("ymdhis")?>" type="text/javascript"></script>
<script src="<?= $assets ?>dist/js/jquery.mask.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/sweetalert2/sweetalert2.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $('.date').mask('00/00/0000');
  $('.time').mask('00:00:00');
  $('.date_time').mask('00/00/0000 00:00:00');
  $('.cep').mask('00000-000');
  $('.phone').mask('0000-0000');
  $('.phone_with_ddd').mask('(00) 0000-0000');
  $('.cpf').mask('000.000.000-00', {reverse: true});
  $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
  $('.dinheiroinput2').mask('000.000.000.000.000,00', {reverse: true});
  $('.dinheiroinput').mask("#.##0,00", {reverse: true});
  $('.percentinput').mask('###%', {reverse: true});
});
</script>
<script type="text/javascript">
	var base_url = '<?=base_url();?>', assets = '<?= $assets ?>';
	var dateformat = '<?=$Settings->dateformat;?>', timeformat = '<?= $Settings->timeformat ?>';
	<?php unset($Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->timezone, $Settings->setting_id, $Settings->default_email, $Settings->version, $Settings->stripe); ?>
	var Settings = <?= json_encode($Settings); ?>;
	var sid = false, username = '<?=$this->session->userdata('username');?>', spositems = {};
	$(window).load(function () {
		$('#mm_<?=$m?>').addClass('active');
		$('#<?=$m?>_<?=$v?>').addClass('active');
	});
	var pro_limit = <?=$Settings->pro_limit?>, java_applet = 0, count = 1, total = 0, an = 1, p_page = 0, page = 0, cat_id = <?=$Settings->default_category?>, tcp = <?=$tcp?>;
	var gtotal = 0, order_discount = 0, order_tax = 0, protect_delete = <?= ($Admin) ? 0 : ($Settings->pin_code ? 1 : 0); ?>;
	var order_data = '', bill_data = '';
	var lang = new Array();
	lang['code_error'] = '<?= lang('code_error'); ?>';
	lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
	lang['r_u_sure_sale'] = '<?= lang('r_u_sure_sale'); ?>';
	lang['please_add_product'] = '<?= lang('please_add_product'); ?>';
	lang['paid_less_than_amount'] = '<?= lang('paid_less_than_amount'); ?>';
	lang['x_suspend'] = '<?= lang('x_suspend'); ?>';
	lang['discount_title'] = '<?= lang('discount_title'); ?>';
	lang['update'] = '<?= lang('update'); ?>';
	lang['tax_title'] = '<?= lang('tax_title'); ?>';
	lang['leave_alert'] = '<?= lang('leave_alert'); ?>';
	lang['close'] = '<?= lang('close'); ?>';
	lang['delete'] = '<?= lang('delete'); ?>';
	lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
	lang['wrong_pin'] = '<?= lang('wrong_pin'); ?>';
	lang['file_required_fields'] = '<?= lang('file_required_fields'); ?>';
	lang['enter_pin_code'] = '<?= lang('enter_pin_code'); ?>';
	lang['incorrect_gift_card'] = '<?= lang('incorrect_gift_card'); ?>';
	lang['card_no'] = '<?= lang('card_no'); ?>';
	lang['value'] = '<?= lang('value'); ?>';
	lang['balance'] = '<?= lang('balance'); ?>';
	lang['unexpected_value'] = '<?= lang('unexpected_value'); ?>';
	lang['inclusive'] = '<?= lang('inclusive'); ?>';
	lang['exclusive'] = '<?= lang('exclusive'); ?>';
	lang['total'] = '<?= lang('total'); ?>';
	lang['total_items'] = '<?= lang('total_items'); ?>';
	lang['order_tax'] = '<?= lang('order_tax'); ?>';
	lang['order_discount'] = '<?= lang('order_discount'); ?>';
	lang['total_payable'] = '<?= lang('total_payable'); ?>';
	lang['rounding'] = '<?= lang('rounding'); ?>';
	lang['grand_total'] = '<?= lang('grand_total'); ?>';
	lang['type_reference_note'] = '<?= lang('type_reference_note'); ?>';

	$(document).ready(function() {
		posScreen();
		$('#spos_customer').select2('val', '<?=$Settings->default_customer;?>');
		<?php if($this->session->userdata('rmspos')) { ?>
		if (get('spositems')) { remove('spositems'); }
		if (get('spos_discount')) { remove('spos_discount'); }
		if (get('spos_tax')) { remove('spos_tax'); }
		if (get('spos_note')) { remove('spos_note'); }
		if (get('spos_entrega_endereco')) { remove('spos_entrega_endereco'); }
		if (get('spos_customer')) { remove('spos_customer'); }
		if (get('spos_vendedor')) { remove('spos_vendedor'); }
		if (get('amount')) { remove('amount'); }
		<?php $this->tec->unset_data('rmspos'); } ?>

		if(get('rmspos')) {
			if (get('spositems')) { remove('spositems'); }
			if (get('spos_discount')) { remove('spos_discount'); }
			if (get('spos_tax')) { remove('spos_tax'); }
			if (get('spos_entrega_endereco')) { remove('spos_entrega_endereco'); }
			if (get('spos_customer')) { remove('spos_customer'); }
			if (get('spos_vendedor')) { remove('spos_vendedor'); }
			if (get('amount')) { remove('amount'); }
			remove('rmspos');
		}
		<?php if($sid) { ?>

			sid = true;
			store('spositems', JSON.stringify(<?=$items;?>));
			store('spos_discount', '<?=$suspend_sale->order_discount_id;?>');
			store('spos_tax', '<?=$suspend_sale->order_tax_id;?>');
			store('spos_customer', '<?=$suspend_sale->customer_id;?>');
			$('#spos_customer').select2('val', '<?=$suspend_sale->customer_id;?>');
			store('spos_vendedor', '<?=$suspend_sale->vendedor;?>');
			$('#spos_vendedor').select2('val', '<?=$suspend_sale->vendedor;?>');
			store('rmspos', '1');
			$('#tax_val').val('<?=$suspend_sale->order_tax_id;?>');
			$('#discount_val').val('<?=$suspend_sale->order_discount_id;?>');
		<?php } elseif($eid) { ?>

			store('spositems', JSON.stringify(<?=$items;?>));
			store('spos_discount', '<?=$sale->order_discount_id;?>');
			store('spos_tax', '<?=$sale->order_tax_id;?>');
			store('spos_customer', '<?=$sale->customer_id;?>');
			$('#spos_customer').select2('val', '<?=$sale->customer_id;?>');
			store('spos_vendedor', '<?=$sale->customer_id;?>');
			$('#spos_vendedor').select2('val', '<?=$sale->vendedor;?>');
			store('rmspos', '1');
			$('#tax_val').val('<?=$sale->order_tax_id;?>');
			$('#discount_val').val('<?=$sale->order_discount_id;?>');
		<?php } else { ?>
			if(! get('spos_discount')) {
				store('spos_discount', '<?=$Settings->default_discount;?>');
				$('#discount_val').val('<?=$Settings->default_discount;?>');
			}
			if(! get('spos_tax')) {
				store('spos_tax', '<?=$Settings->default_tax_rate;?>');
				$('#tax_val').val('<?=$Settings->default_tax_rate;?>');
			}
		<?php } ?>

		if (ots = get('spos_tax')) {
		    $('#tax_val').val(ots);
		}
		if (ods = get('spos_discount')) {
		    $('#discount_val').val(ods);
		}
		if(Settings.display_kb == 1) { display_keyboards(); }
		nav_pointer();
		loadItems();
		read_card();
		bootbox.addLocale('bl',{OK:'<?= lang('ok'); ?>',CANCEL:'<?= lang('no'); ?>',CONFIRM:'<?= lang('yes'); ?>'});
		bootbox.setDefaults({closeButton:false,locale:"bl"});
		<?php if($eid) { ?>
			$('#suspend').attr('disabled', true);
			$('#print_order').attr('disabled', true);
			$('#print_bill').attr('disabled', true);
		<?php } ?>
	});
	
	window.setInterval(function(){
        $.ajax( base_url + "welcome/ping" ).done(function() {});
    }, 60000);
</script>
</body>
</html>