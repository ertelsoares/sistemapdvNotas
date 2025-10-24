<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
	
		<div class="col-xs-12">
			<div class="box box-primary">
				
				<div class="box-body">

                     <?php if($_GET["tipo"]!="senha"){ ?>
                   
                        <div class="col-lg-6">
                            <p><?= lang('update_info'); ?></p>
                            <?=form_open('auth/edit_user/' . $user->id);?>
                            <h4>Editar dados</h4>
                            <div class="form-group">
                                <?= lang('first_name', 'first_name'); ?>
                                <?= form_input('first_name', $user->first_name, 'class="form-control tip" id="first_name"  required="required"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('last_name', 'last_name'); ?>
                                <?= form_input('last_name', $user->last_name, 'class="form-control tip" id="last_name"  required="required"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('phone', 'phone'); ?>
                                <?= form_input('phone', $user->phone, 'class="form-control tip" id="phone"'); ?>
                            </div>

                            <input name="male" value="male" class="form-control tip" id="username" type="hidden">

                            <?php if ($Admin && $id != $this->session->userdata('user_id')) { ?>

                                <div class="form-group">
                                    <?= lang("group", "group"); ?>
                                    <?php
                                    $gp[""] = "";
                                    foreach ($groups as $group) {
                                        $gp[$group['id']] = $group['name'];
                                    }
                                    echo form_dropdown('group', $gp, $user->group_id, 'id="group" data-placeholder="' . lang("select") . ' ' . lang("group") . '" class="form-control input-tip select2" style="width:100%;"');
                                    ?>
                                </div>

                                <input name="username" value="<?=sha1($id);?>" class="form-control tip" id="username" type="hidden">

                                <div class="form-group">
                                    <?= lang('email', 'email'); ?>
                                    <?= form_input('email', $user->email, 'class="form-control tip" id="email"  required="required"'); ?>
                                </div>

                                <div class="panel panel-warning">
                                    <div class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                                    <div class="panel-body" style="padding: 5px;">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php echo lang('password', 'password'); ?>
                                                    <?php echo form_input($password); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php echo lang('confirm_password', 'password_confirm'); ?>
                                                    <?php echo form_input($password_confirm); ?>
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= lang('status', 'status'); ?>
                                    <?php
                                    $opt = array('' => '', 1 => lang('active'), 0 => lang('inactive'));
                                    echo form_dropdown('status', $opt, $user->active, 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" class="form-control input-tip select2" style="width:100%;"');
                                    ?>
                                </div>
                            
                        
                            <?php } else { ?>
                                
                                <input name="username" value="<?=sha1($id);?>" class="form-control tip" id="username" type="hidden">

                                <div class="form-group">
                                    <?= lang('email', 'email'); ?>
                                    <?= form_input('email', $user->email, 'class="form-control tip" id="email"  required="required"'); ?>
                                </div>

                            <?php } ?>


                          <div class="form-group">
                            <?= lang('Permitir fechar vendas', 'permitir_fechar_venda'); ?>
                            <?php
                            $opt = array(1 => "Sim", 0 => "Não");
                            echo form_dropdown('permitir_fechar_venda', $opt, (isset($_POST['permitir_fechar_venda']) ? $_POST['permitir_fechar_venda'] : $user->permitir_fechar_venda), 'id="status" class="form-control input-tip select2" style="width:100%;"');
                            ?>
                        </div>

                        <div class="form-group">
                           <a href="<?=site_url('users/profile/' . $user->id.'?tipo=senha');?>" class="btn btn-warning btn-sm">Alterar senha</a>
                        </div>
                        
                        <?php echo form_hidden('gender',"male"); ?>
                        <?php echo form_hidden('id', $id); ?>
                        <?php echo form_hidden($csrf); ?>
                        <div class="form-group">
                            <?= form_submit('update_user', lang('update'), 'class="btn btn-primary"'); ?>
                        </div>
                        <?= form_close(); ?>
                        <div class="clearfix"></div>
                    </div>
                              
                <?php } else { ?>

                    <div class="col-lg-6">
                        <div class="white-panel">
                            <p><?= lang('update_info'); ?></p>
                            <?php echo form_open("auth/change_password"); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Alterar senha</a>
                                    <div class="form-group">
                                        <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                        <?php echo form_password('old_password', '', 'class="form-control" id="curr_password"'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                        <br/>
                                        <?php echo form_password('new_password', '', 'class="form-control" id="new_password" pattern=".{8,}"'); ?>
                                    </div>

                                    <div class="form-group">
                                        <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                        <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" pattern=".{8,}"'); ?>

                                    </div>

                                    <?php echo form_input($user_id); ?>
                                    <div class="form-group">
                                        <?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</section>