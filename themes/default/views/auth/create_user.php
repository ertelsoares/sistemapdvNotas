<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('enter_info'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-6">
                        <?= form_open('auth/create_user', array("autocomplete" => "nope")); ?>
                        <div class="form-group">
                            <?= lang("group", "group"); ?>
                            <?php
                            $gp[""] = "";
                            foreach ($groups as $group) {
                                $gp[$group['id']] = $group['description'];
                            }
                            echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : 2), 'id="group" data-placeholder="' . lang("select") . ' ' . lang("group") . '" required="required" class="form-control input-tip select2" style="width:100%;"');
                            ?>
                        </div>
                        <div class="form-group">
                            <?= lang('first_name', 'first_name'); ?>
                            <?= form_input('first_name', set_value('first_name'), 'class="form-control tip" id="first_name"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('last_name', 'last_name'); ?>
                            <?= form_input('last_name', set_value('last_name'), 'class="form-control tip" id="last_name"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('phone', 'phone'); ?>
                            <?= form_input('phone', set_value('phone'), 'class="form-control tip" id="phone"'); ?>
                        </div>
                        <input name="male" value="male" class="form-control tip" id="username" type="hidden">
                        <div class="form-group">
                            <?= lang('email', 'email'); ?>
                            <?= form_input('email', set_value('email'), 'class="form-control tip" autocomplete="off" onchange="$(\'#username\').val(this.value)" id="email" required="required"'); ?>
                        </div>

                        <input name="username" value="<?=$_POST['email'];?>" class="form-control tip" id="username" type="hidden">

                        <div class="form-group">
                            <?= lang('password', 'password'); ?>
                            <?= form_password('password', '', 'class="form-control tip" autocomplete="off" id="password"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('confirm_password', 'confirm_password'); ?>
                            <?= form_password('confirm_password', '', 'class="form-control tip" id="confirm_password"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('status', 'status'); ?>
                            <?php
                            $opt = array('' => '', 1 => lang('active'), 0 => lang('inactive'));
                            echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : 1), 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" class="form-control input-tip select2" style="width:100%;"');
                            ?>
                        </div>
                        
                        <div class="form-group">
                            <?= lang('Permitir fechar vendas', 'permitir_fechar_venda'); ?>
                            <?php
                            $opt = array(1 => "Sim", 0 => "Não");
                            echo form_dropdown('permitir_fechar_venda', $opt, (isset($_POST['permitir_fechar_venda']) ? $_POST['permitir_fechar_venda'] : 1), 'id="status" class="form-control input-tip select2" style="width:100%;"');
                            ?>
                        </div>
                        
                        <div class="form-group">
                            <?= form_submit('add_user', lang('add_user'), 'class="btn btn-primary"'); ?>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>