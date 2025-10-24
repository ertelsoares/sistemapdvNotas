<?php

ini_set('display_errors', 0);

if(isset($_POST['cnpj'])){
   
    try {
        $cnpj = str_replace(array(".", " ", "/", "-"), "", $_POST['cnpj']);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.receitaws.com.br/v1/cnpj/'.$cnpj,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $dados = curl_exec($curl);
        $dados = json_decode($dados, true);
        curl_close($curl);

    } catch (\Exception $th) {
        $dados = "";
    }
    
    if($dados!="" && $dados["status"]!="ERROR"){
        $dados["cnpj"] = $_POST['cnpj'];
        $dados["cidade_cap"] = ucwords(strtolower($dados["municipio"]));
        $dados["municipio"] = ucwords(strtolower($dados["municipio"]));
        $dados = json_encode($dados);
    }else{
        header("location: buscarCnpjform.php?err=1&cnpj=".$_POST['cnpj']."&msg=".$dados["message"]);
        exit;
        
    }

}
?>
<script>

var dados = '<?php echo $dados; ?>';

if(dados!=""){
    parent.ResultadosReceita(dados, <?=$tipo?>);
    parent.jQuery.colorbox.close();   
}else{
    alert("Não foi possível carregar os dados, tente novamente em alguns minutos");
    parent.jQuery.colorbox.close();  
}
</script>