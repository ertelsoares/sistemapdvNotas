<?php

//error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once '../bootstrap.php';

use \NFePHP\DA\Common\DaCommon;
use NFePHP\DA\NFe\Danfce;
use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\Dacce;
use NFePHP\DA\NFe\Daevento;

$iscancelada = false;

if($_GET['tipo']=="conti" || $_GET['tipo']=="processamento" || $_GET['tipo']=="teste"){

	$docxml = file_get_contents("../gerador/xml/assinadas/".$_GET['chave'].".xml");

	$xml= simplexml_load_string($docxml) or die(json_encode(array("error" => 'Nota nao encontrada.')));
	$cnpj = $xml->infNFe->emit->CNPJ;
	$modelo = $xml->infNFe->ide->mod;

} elseif($_GET['tipo']=="cancelado" || $_GET['tipo']=="cancelada"){
    
    $iscancelada = true;

	$docxml = file_get_contents("../gerador/xml/canceladas/".$_GET['chave'].".xml");
	$xml= simplexml_load_string($docxml) or die(json_encode(array("error" => 'Nota nao encontrada.')));
	$cnpj = $xml->NFe->infNFe->emit->CNPJ;
	$modelo = $xml->NFe->infNFe->ide->mod;

} elseif($_GET['tipo']=="correcao"){

	$docxml = file_get_contents("../gerador/xml/correcao/".$_GET['chave']."-".$_GET['sequencia'].".xml");
	$xml = simplexml_load_string($docxml) or die(json_encode(array("error" => 'Nota nao encontrada.')));
	$correcao = true;

}else{

	$docxml = file_get_contents("../gerador/xml/autorizadas/".$_GET['chave'].".xml");
	$xml= simplexml_load_string($docxml) or die(json_encode(array("error" => 'Nota nao encontrada.')));
	$cnpj = $xml->NFe->infNFe->emit->CNPJ;
	$modelo = $xml->NFe->infNFe->ide->mod;

}

$img = null;
if($_GET["logo"]!="" && $_GET["logo"]!="logo.png"){
	$img = "../../".$_GET["logo"];
}

if($modelo == "65"){

	try {

		$tamanhopapel = $_REQUEST["tamanhopapel"];
		if($tamanhopapel=="") $tamanhopapel = 80;
		$tamanhopapel = (int) $tamanhopapel;
		if($tamanhopapel>80) $tamanhopapel = 80;
		if($tamanhopapel<58) $tamanhopapel = 58;
		$tamanhopapel = $tamanhopapel - 4; 

		$danfce = new Danfce($docxml);
		$danfce->debugMode(false);//seta modo debug, deve ser false em produção
		$danfce->setPaperWidth(80); //seta a largura do papel em mm max=80 e min=58
		$danfce->setMargins(2);//seta as margens
		$danfce->setDefaultFont('arial');//altera o font pode ser 'times' ou 'arial'
		$danfce->setOffLineDoublePrint(true); //ativa ou desativa a impressão conjunta das via do consumidor e da via do estabelecimento qnado a nfce for emitida em contingência OFFLINE
		//$danfce->setPrintResume(true); //ativa ou desativa a impressao apenas do resumo
		//$danfce->setViaEstabelecimento(); //altera a via do consumidor para a via do estabelecimento, quando a NFCe for emitida em contingência OFFLINE
    	
    	if($iscancelada==true) {
    	    $danfce->setAsCanceled(); //força marcar nfce como cancelada 
    	}
		$danfce->creditsIntegratorFooter('TudoNet - www.tudo-net.com');
		$pdf = $danfce->render($img);
		header('Content-Type: application/pdf');
		echo $pdf;
 
	} catch (InvalidArgumentException $e) {
		echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
	}

}elseif($modelo == "55"){

	try {

	    $danfe = new Danfe($docxml);
		$danfe->debugMode(false);
		$danfe->creditsIntegratorFooter('TudoNet - www.tudo-net.com');
		$pdf = $danfe->render($img);

		header('Content-Type: application/pdf');
		echo $pdf;

	} catch (InvalidArgumentException $e) {
	    echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
	}

}elseif($correcao===true){

	try {
		
		
		$daevento = new Daevento($docxml, $_GET['a']);
		$daevento->debugMode(false);
		$daevento->creditsIntegratorFooter('TudoNet - www.tudo-net.com');
		$pdf = $daevento->render($img);
		header('Content-Type: application/pdf');
		echo $pdf;

	} catch (InvalidArgumentException $e) {
	    echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
	}

}




