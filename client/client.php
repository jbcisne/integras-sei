<?php
require '../vendor/autoload.php';

use Jb\Integras\Sei\IntegrasSEI;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;

$config = (object)require_once './config.php';

try {

//    $seiCfg = new IntegrasConfigParamenterSEI($config, '3.0.0');
    $seiCfg = new IntegrasConfigParamenterSEI($config, '2.6.0');
    $x = IntegrasSEI::factory($seiCfg);

    $filePath = '../docs/SEI-Instalacao-v2.6.0.pdf';
    $fileContent = file_get_contents($filePath);
//    echo '<pre>';
//    print_r($x->adicionarArquivo(
//        'SEI-Instalacao-v2.6.0.pdf'
//        ,filesize($filePath)
//        ,md5($fileContent)
//        ,base64_encode($fileContent)
//        ,null
//    ));


    echo '<pre>';
//    3.0.0
//    print_r($x->consultarProcedimento('9999.0000001/2017-62'));
//    print_r($x->reabrirProcesso('9999.0000001/2017-62'));
//    2.6.0
    print_r($x->consultarProcedimento('99911111.000002/2017-00'));

    die;
} catch (\Exception $e) {
    echo '<pre>';
    print_r($e->getMessage());
    die('<b>File: </b>' . __FILE__ . ' <br/><b>Linha: </b>' . __LINE__);
}

