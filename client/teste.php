<?php
require 'phar://Integras.phar';

$integras = new \Integras();

$sei = $integras->sei('2.6.0');

print_r($sei->listarUnidades()); die;

