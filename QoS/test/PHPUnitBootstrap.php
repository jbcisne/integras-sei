<?php

/**
 * define o caminho do projeto
 * */
$rootFolderName = 'integras';

# não altere a partir deste ponto
$rootPath = explode($rootFolderName, __DIR__);
$rootPath = current($rootPath) . $rootFolderName . DIRECTORY_SEPARATOR;

$QoSRootPath = $rootPath . 'QoS'     . DIRECTORY_SEPARATOR;
$library     = $rootPath . 'src' . DIRECTORY_SEPARATOR;
$vendorPath  = $rootPath . 'vendor'  . DIRECTORY_SEPARATOR;

require_once $vendorPath . 'Psr4AutoloaderClass.php';
$loader = new \Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('src', $library);

