<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content" style="min-height: auto;">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="<?= site_url('settings/backups'); ?>" class="pull-right btn btn-primary"><i class="icon fa fa-database"></i> Ver backups</a>
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
                    <h3 class="box-title"><strong>Restaurar banco de dados</strong>: Atençao: Açao é irreversível</h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                           <?php echo form_open_multipart("settings/restore_database", 'class="validation"'); ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang('db', 'Arquivo do banco de dados'); ?>
										<input type="file" name="userfile" accept=".db" id="db">
									</div>
								</div>
							</div>

							<div class="form-group">
								<?= form_submit('restaurar', 'Restaurar', 'class="btn btn-primary"'); ?>
							</div>

                        <?php echo form_close();?>
							
							
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
                    <h3 class="box-title"><strong>Restaurar arquivos</strong>: Atençao: Açao é irreversível</h3>
                </div>
                <div class="box-body">

                     <div class="row">
                        <div class="col-md-12">
                           <?php echo form_open_multipart("settings/restore_backup", 'class="validation"'); ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang('zip', 'Arquivo ZIP do backup'); ?>
										<input type="file" name="userfile" accept=".zip" id="zip">
									</div>
								</div>
							</div>

							<div class="form-group">
								<?= form_submit('restaurar', 'Restaurar', 'class="btn btn-primary"'); ?>
							</div>

                        <?php echo form_close();?>
							
							
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
                    <h3 class="box-title"><strong>Restaurar Notas Fiscais</strong></h3>
                </div>
                <div class="box-body">

                     <div class="row">
                        <div class="col-md-12">
                           <?php echo form_open_multipart("settings/restore_backup_notas", 'class="validation"'); ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang('zip_notas', 'Arquivo ZIP do backup'); ?>
										<input type="file" name="userfile" accept=".zip" id="zip_notas">
									</div>
								</div>
							</div>

							<div class="form-group">
								<?= form_submit('restaurar', 'Restaurar', 'class="btn btn-primary"'); ?>
							</div>

                        <?php echo form_close();?>
							
							
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