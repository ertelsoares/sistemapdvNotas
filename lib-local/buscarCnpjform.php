<?php
ini_set('display_errors', 0);
require_once '../api-nfe/bootstrap.php';
use JansenFelipe\CnpjGratis\CnpjGratis;
$tipo = 2;

function is_connected()
{
    $connected = @fsockopen("www.google.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}

if(is_connected()==false){
    die("<h2>Não será possivel encontrar os dados na receita, você necesita estar conectado a internet.");
}
if($tipo == 1){
    $params = CnpjGratis::getParams();
}
?>
<?php if($tipo == 1){ ?>
<div style="width:100%;text-align:center">
<img src="data:image/png;base64,<?php echo $params['captchaBase64'] ?>" />
<br>
<?php } ?>
<?php if($_GET["err"]==1){ 
    echo "<br><span style='color:red;font-size:15px;font-family:arial'>"; 
    echo ($_GET["msg"]!="")? $_GET["msg"] : "Erro ao consultar"; 
    echo "</span>"; 
}
?>
<form  name="receita" method="POST" onsubmit="return validar();" action="./buscarCnpj.php" style="width:100%;text-align:center">
    <input type="hidden" name="cookie" value="<?php echo $params['cookie'] ?>" />
    <input type="hidden" name="tipo" value="<?php echo $tipo ?>" /><br>
    <?php if($tipo == 1){ ?>
        <input type="text" id="captcha" name="captcha" style="font-size:20px;padding: 5px; width:90%;margin: 5px 0px" onchange="if(this.value!=''){ this.value = this.value.toUpperCase();}" placeholder="Captcha" /><br>
    <?php }?>
    <input type="text" id="cnpj" class="cnpj" required="required" name="cnpj" value="<?php echo $_GET["cnpj"];?>" style="font-size:20px;padding: 5px; width:90%;margin: 5px 0px" placeholder="CNPJ" /><br>
    <button id="botaoenvio" type="submit" style="font-size:20px;padding: 5px; width:90%;margin: 5px 0px;" >
    <span id="buscan" style="display:none">Buscado... Aguarde...</span><span id="consul">Consultar</span></button>
</form>
</div>
<script type="text/javascript" src="../themes/default/assets/creador_notas_files/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../themes/default/assets/creador_notas_files/jquery.mask.min-1541517054.js"></script>
<script>
function validar(){
    
    if(document.getElementById("cnpj").value != ""){
        document.getElementById("consul").style.display = "none";
        document.getElementById("buscan").style.display = "block";
        document.getElementById("botaoenvio").disabled = true;
        return true;
    }

    return false;

}

$(document).ready(function() {
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
});
</script>