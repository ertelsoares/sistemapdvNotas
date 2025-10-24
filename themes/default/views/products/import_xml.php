<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('enter_info'); ?></h3>
                </div>
                <div class="box-body">

                    <?= form_open_multipart("products/importar_xml");?>
                    <div class="form-group">
                        <?= lang("upload_file", 'nfxml'); ?>
                        <input type="file" name="nfxml" accept=".xml" id="nfxml">
                    </div>
                    <div class="form-group">
                        <?= lang("Porcentagem para atualizar o preço (Não obrigatório)", 'fator'); ?>
                        <?= form_input('fator', '', 'class="form-control" placeholder="" id="fator"'); ?>
                        <br>
                        Exemplo: 50% (Representa margem de 50% a mais no preço de venda em relação ao custo)
                    </div>

                    <div class="form-group">
                        <?= form_submit('import', lang('import'), 'class="btn btn-primary"'); ?>
                    </div>
                    <?= form_close();?>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
