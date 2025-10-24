<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('enter_info'); ?></h3>
                </div>
                <div class="box-body">

                    <div class="well well-sm">
                        <a download="exemplo_importar_produtos.xlsx" href="<?= base_url('files/csv/exemplo_importar_produtos.xlsx'); ?>"  target="_blank" class="btn btn-info btn-sm pull-right"><i class="fa fa-download"></i> <?= lang("download_sample_file"); ?></a>
                        
                        
                        <p>Arquivos permitidos: Excel (.xlsx)<br><br><b style="color:red">IMPORTANTE:</b> A primeira linha deve estar sempre e o nome das colunas não devem ser alterados.
                            <br><br>Colunas fixas, não alterar nada, sem os acentos:<br>
                            <b>codigo</b>: Código de barras ou código do produto<br>
                            <b>nome</b>: Nome do produto / serviço<br>
                            <b>preço</b>: Preço de venda<br>
                            <b>custo</b>: Custo do produto<br>
                            <b>estoque</b>: Quantidade de produtos em estoque<br>
                            <b>categoria</b>: Nome da categoria<br>
                            <b>ncm</b>: Código NCM<br>
                            <b>origem</b>: Código de origem: 0 - Nacional<br>
                            <b>cfop</b>: Código CFOP<br>
                            <b>tipo</b>: Tipo: produto ou serviço<br>
                            </p>
                    </div>

                    <?= form_open_multipart("products/import");?>
                    <div class="form-group">
                        <?= lang("upload_file", 'csv_file'); ?>
                        <input type="file" name="userfile" id="csv_file" accept=".xlsx">
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
