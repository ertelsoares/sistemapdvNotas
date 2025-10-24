<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= $page_title.' | '.$Settings->site_name; ?></title>
<link rel="shortcut icon" href="<?=site_url(); ?>/icon.ico"/>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/iCheck/square/green.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/redactor/redactor.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>dist/css/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>dist/css/custom.css" rel="stylesheet" type="text/css" />
<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<style>
.content-header > h1 { color: #333;}
.content-wrapper{ background-color:#ebecf2; background-position: center center; background-repeat: no-repeat; background-size: cover;}
.skin-blue .main-header .navbar { background-color: #041028; }
.skin-blue .main-header .logo:hover { background-color: #041028; }
.skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side { background-color: #041028; }
.skin-blue .main-header .logo { background-color: #041028; color: #fff; border-bottom: 0 solid transparent; }
.skin-blue .sidebar a { color: #FFF; font-size: 20px;}
.skin-blue .sidebar a i{margin-right: 5px;}
.box{ border-top:0px!important;border-radius:10px; -webkit-box-shadow: 0 1px 15px 1px rgba(39,39,39,.1)!important;box-shadow: 0 1px 15px 1px rgba(39,39,39,.1)!important; }
.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a {border-radius: 0px;}
.skin-blue .sidebar-menu>li>.treeview-menu { margin-left:0px; padding: 10px 0px;background: #041028; border-radius: 0px;}
.skin-blue .treeview-menu>li>a { color: #fff!important;}
.btn-app{
background-color: #ffffff!important;
font-size: 15px!important;
height: auto!important;
border-radius:10px;
-webkit-box-shadow: 0 1px 15px 1px rgba(39,39,39,.1)!important;
box-shadow: 0 1px 15px 1px rgba(39,39,39,.1)!important;
}
.box-header>.fa, .box-header>.glyphicon, .box-header>.ion, .box-header .box-title{font-size:14px;}
.alerts .alert { border-radius: 9px!important;}
.btn {  border-radius: 10px; }
#product_image{width:100%}
.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a {
    background: #000;
    border-left-color: #000;
}
.treeview-menu>li:hover{background: #ffffff26;}
.treeview-menu>li.active{background: #ffffff26;}
@media (max-width: 767px){
.btn > i{
	font-size:18px;
    margin:2px;
	}
    .btn-group, .btn-group-vertical{
        width: 100%!important;
        display: flex!important;
    }
}
</style>
</head>
<body class="skin-blue fixed sidebar-mini <?php if(isset($_GET["isframe"]) && $_GET["isframe"]=="1"){ ?>sidebar-collapse<?php } ?>">
<div class="wrapper">
    <header class="main-header" <?php if(isset($_GET["isframe"]) && $_GET["isframe"]=="1"){ ?>style="display:none!important;"<?php } ?> >
        <a href="<?= site_url(); ?>" class="logo">
            <span class="logo-mini">PDV</span>
            <span class="logo-lg"><img src="<?php if($Settings->logo!=""){ echo base_url()."/".$Settings->logo; } else { echo base_url()."/logo.png"; } ?>" style="height:25px;"></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegação</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="hidden-xs hidden-sm"><a href="https://www.nfe.fazenda.gov.br/portal/disponibilidade.aspx" target="_blank">Disponibilidade NF-e</a></li>
                    <li class="hidden-xs hidden-sm"><a href="javascript:void(0)" class="clock"></a></li>

                    <?php if($Admin && $qty_alert_num) { ?>
                    <li>
                        <a href="<?= site_url('reports/alerts'); ?>">
                            <i class="fa fa-bullhorn"></i>
                            <span class="label label-warning"><?= $qty_alert_num; ?></span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($suspended_sales) { ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning"><?=sizeof($suspended_sales);?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?=lang('recent_suspended_sales');?></li>
                            <li>
                                <ul class="menu">
                                    <li>
                                    <?php
                                    foreach ($suspended_sales as $ss) {
                                        echo '<a href="'.site_url('pos/?hold='.$ss->id).'" class="load_suspended">'.$this->tec->hrld($ss->date).' ('.$ss->customer_name.')<br><strong>'.$ss->hold_ref.'</strong></a>';
                                    }
                                    ?>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="<?= site_url('sales/opened'); ?>"><?= lang('view_all'); ?></a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="dropdown user user-menu" style="padding-right:5px;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= base_url('uploads/avatars/avatar.png');?>" class="user-image" alt="Avatar" />
                            <span class="hidden-xs"><?= $this->session->userdata('first_name'); ?></span>
                        </a>
                        <ul class="dropdown-menu" style="padding-right:3px;">
                            <li class="user-header">
                                <img src="<?= base_url('uploads/avatars/avatar.png');?>" class="img-circle" alt="Avatar" />
                                <p>
                                    <?= $this->session->userdata('email'); ?>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= site_url('users/profile/'.$this->session->userdata('user_id')); ?>" class="btn btn-default btn-flat">Perfil</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?= site_url('logout'); ?>" class="btn btn-default btn-flat">Sair</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside <?php if(isset($_GET["isframe"]) && $_GET["isframe"]=="1"){ ?>style="display:none!important"<?php } ?> class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <!-- <li class="header"><?= lang('mian_navigation'); ?></li> -->

                <li class="mm_welcome"><a href="<?= site_url(); ?>"><i class="fa fa-dashboard"></i> <span><?= lang('dashboard'); ?></span></a></li>
                <li class="mm_pos"><a href="<?= site_url('pos'); ?>"><i class="fa fa-th"></i> <span><?= lang('pos'); ?></span></a></li>

                <?php if($Admin) { ?>
                <li class="treeview mm_products mm_categories">
                    <a href="#">
                        <i class="fa fa-barcode"></i>
                        <span><?= lang('products'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="products_index"><a href="<?= site_url('products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_products'); ?></a></li>
                        <li id="products_add"><a href="<?= site_url('products/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_product'); ?></a></li>
                        <li id="products_import"><a href="<?= site_url('products/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_products'); ?></a></li>
                        <li id="products_import"><a href="<?= site_url('products/importar_xml'); ?>"><i class="fa fa-circle-o"></i> Importar XML</a></li>
                        <li class="divider"></li>
                        <li id="products_impostos"><a href="<?= site_url('products/impostos'); ?>"><i class="fa fa-circle-o"></i> Grupo de Impostos</a></li>
                        <li id="products_impostos"><a href="<?= site_url('products/impostos_add_edit'); ?>"><i class="fa fa-circle-o"></i> Adicionar Grupo de Impostos</a></li>
                        <li class="divider"></li>
                        <li id="categories_index"><a href="<?= site_url('categories'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_categories'); ?></a></li>
                        <li id="categories_add"><a href="<?= site_url('categories/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_category'); ?></a></li>
                        <li id="categories_import"><a href="<?= site_url('categories/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_categories'); ?></a></li>
                    </ul>
                </li>
                <li class="treeview mm_sales">
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
                        <li class="divider"></li>
                        <li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
                        <li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
                    </ul>
                </li>

                <li class="treeview mm_auth mm_customers mm_suppliers">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span><?= lang('people'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="customers_index"><a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_customers'); ?></a></li>
                        <li id="customers_add"><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_customer'); ?></a></li>
                        <li class="divider"></li>
                     
                        <li id="auth_users"><a href="<?= site_url('users'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_users'); ?></a></li>
                        <li id="auth_add"><a href="<?= site_url('users/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_user'); ?></a></li>
                        <li class="divider"></li>
                           <li id="suppliers_index"><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_suppliers'); ?></a></li>
                        <li id="suppliers_add"><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_supplier'); ?></a></li>
                    </ul>
                </li>
                <li class="treeview mm_reports">
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
                <li class="treeview mm_settings">
					<a href="<?= site_url('settings'); ?>">
                        <i class="fa fa-cogs"></i>
                        <span><?= lang('settings'); ?></span>
						 <i class="fa fa-angle-left pull-right"></i>
                    </a>
					
                    <ul class="treeview-menu">
                        <li id="settings_index"><a href="<?= site_url('settings'); ?>"><i class="fa fa-circle-o"></i> <?= lang('settings'); ?></a></li>
                        <li id="settings_backups"><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-circle-o"></i> Backups</a></li>
                       
                    </ul>
					
                </li>
                <?php } else { ?>

                    <?php if($UserPerfil != "vendas"){?>
                    <li class="treeview mm_products mm_categories">
                    <a href="#">
                        <i class="fa fa-barcode"></i>
                        <span><?= lang('products'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="products_index"><a href="<?= site_url('products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_products'); ?></a></li>
                        <li id="products_add"><a href="<?= site_url('products/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_product'); ?></a></li>
                        <li id="products_import"><a href="<?= site_url('products/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_products'); ?></a></li>
                        <li class="divider"></li>
                        <li id="products_impostos"><a href="<?= site_url('products/impostos'); ?>"><i class="fa fa-circle-o"></i> Grupo de Impostos</a></li>
                        <li id="products_impostos"><a href="<?= site_url('products/impostos_add_edit'); ?>"><i class="fa fa-circle-o"></i> Adicionar Grupo de Impostos</a></li>
                        <li class="divider"></li>
                        <li id="categories_index"><a href="<?= site_url('categories'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_categories'); ?></a></li>
                        <li id="categories_add"><a href="<?= site_url('categories/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_category'); ?></a></li>
                        <li id="categories_import"><a href="<?= site_url('categories/import'); ?>"><i class="fa fa-circle-o"></i> <?= lang('import_categories'); ?></a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="treeview mm_sales">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span><?= lang('sales'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="sales_index"><a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_sales'); ?></a></li>
                        <li id="sales_opened"><a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_opened_bills'); ?></a></li>
                        <li class="divider"></li>
                        <li id="sales_notasfiscais"><a href="<?= site_url('sales/notasfiscais'); ?>"><i class="fa fa-circle-o"></i> Notas Fiscais</a></li>
                        <li id="sales_creador_notas"><a href="<?= site_url('sales/creador_notas'); ?>"><i class="fa fa-circle-o"></i> Emissor Nota Fiscal</a></li>
                    </ul>
                </li>
                <?php if($UserPerfil != "vendas"){?>
                <li class="treeview mm_purchases">
                    <a href="#">
                        <i class="fa fa-plus"></i>
                        <span><?= lang('expenses'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
                        <li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="treeview mm_customers">
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
                <?php if($UserPerfil != "vendas"){?>
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
                
                <?php } ?>

                <!--<li class="treeview">
                    <a style="font-size:12px;" href="https://tudo-net.com/saas?utm_source=pdvnfe" target="_blank" style="font-size:12px;">
                    Desenvolvido por <b>TudoNet</b>
                    </a>
                </li>-->
            </ul>
        </section>
    </aside>

    <div class="content-wrapper" <?php if(isset($_GET["isframe"]) && $_GET["isframe"]=="1"){ ?>style="background-color: #ffffff!important;margin-left: 0px!important;padding: 0px!important;"<?php } ?>>
        <section class="content-header">
            <h1><?= $page_title; ?></h1>
            <ol class="breadcrumb"  <?php if(isset($_GET["isframe"]) && $_GET["isframe"]=="1"){ ?>style="display:none!important"<?php } ?>>
                <li><a href="<?= site_url(); ?>"><i class="fa fa-dashboard"></i> <?= lang('home'); ?></a></li>
                <?php
                foreach ($bc as $b) {
                    if ($b['link'] === '#') {
                        echo '<li class="active">' . $b['page'] . '</li>';
                    } else {
                        echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
                    }
                }
                ?>
            </ol>
        </section>

        <div class="col-lg-12 alerts">
            <div id="custom-alerts" style="display:none;">
                <div class="alert alert-dismissable">
                    <div class="custom-msg"></div>
                </div>
            </div>
            <?php if($error)  { ?>
            <div 
            </div>
          
            <div 
               
            
            
           
                
               
                
            
            <?php } ?>
        </div>
