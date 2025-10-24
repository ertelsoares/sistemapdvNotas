<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content" style="min-height: auto;">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                   <a href="<?= site_url('settings/restore'); ?>" class="pull-right btn btn-success">Restaurar backup</a> <a href="<?= site_url('settings/create_backup'); ?>" style="margin:0px 5px;" class="pull-right btn btn-primary">Criar backup</a> 
               </div>
            </div>
        </div>
    </div>
</section>                 
                    
<section class="content" style="min-height: auto;">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong><?= lang('database_backups'); ?></strong>: <?= lang('restore_heading'); ?></h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (! empty($dbs)) {
                                echo '<ul class="list-group">';
                                foreach ($dbs as $file) {
                                    $file = basename($file);
                                    echo '<li class="list-group-item">';
                                    $fname = explode("_", $file);
                                    $data_hora_ex = explode(".", end($fname));
                                    $date_string = substr($data_hora_ex[0], 0, 10);
                                    $time_string = substr($data_hora_ex[0], 11, 20);
                                    $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                                    $bkdate = $this->tec->hrld($date);
                                    echo '<strong>' . lang('backup_on') . ' <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-7px;">' . anchor('settings/download_database/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('settings/delete_database/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></strong>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong>Backup dos arquivos</strong>: <?= lang('restore_heading'); ?></h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (! empty($files)) {
                                echo '<ul class="list-group">';
                                foreach ($files as $file) {
                                    $file = basename($file);
                                    
                                    echo '<li class="list-group-item">';
                                    $fname = explode("_", $file);
                                    $data_hora_ex = explode(".", end($fname));
                                    $date_string = substr($data_hora_ex[0], 0, 10);
                                    $time_string = substr($data_hora_ex[0], 11, 20);
                                    $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                                    $bkdate = $this->tec->hrld($date);
                                    echo '<strong>' . lang('backup_on') . ' <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-7px;">' . anchor('settings/download_backup/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('settings/delete_backup/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></strong>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
	
	
	<div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><strong>Backup das Notas Fiscais</strong>:</h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (! empty($notas)) {
                                echo '<ul class="list-group">';
                                foreach ($notas as $file) {
                                    $file = basename($file);
                                    
                                    echo '<li class="list-group-item">';
                                    $fname = explode("_", $file);
                                    $data_hora_ex = explode(".", end($fname));
                                    $date_string = substr($data_hora_ex[0], 0, 10);
                                    $time_string = substr($data_hora_ex[0], 11, 20);
                                    $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                                    $bkdate = $this->tec->hrld($date);
                                    echo '<strong>Notas ('. $fname[3]. ') <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-7px;">' . anchor('settings/download_backup_notas/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('settings/delete_backup_notas/' . str_replace(".".$data_hora_ex[1], "", $file), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></strong>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
	
</section>

<div class="modal fade" id="wModal" tabindex="-1" role="dialog" aria-labelledby="wModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="wModalLabel"><?= lang('please_wait'); ?></h4>
            </div>
            <div class="modal-body">
                <?= lang('backup_modal_msg'); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#backup_files').click(function (e) {
            e.preventDefault();
            $('#wModalLabel').text('<?=lang('backup_modal_heading');?>');
            $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
            window.location.href = '<?= site_url('settings/backup_files'); ?>';
        });
        $('.restore_backup').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('restore_confirm');?>", function (result) {
                if (result) {
                    $('#wModalLabel').text('<?=lang('restore_modal_heading');?>');
                    $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
                    window.location.href = href;
                }
            });
        });
        $('.restore_db').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('restore_confirm');?>", function (result) {
                if (result) {
                    window.location.href = href;
                }
            });
        });
        $('.delete_file').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('delete_confirm');?>", function (result) {
                if (result) {
                    window.location.href = href;
                }
            });
        });
    });
</script>