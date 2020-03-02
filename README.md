# Integrador com SEI


Version 0.2.0 - Inclusão de configuração para ignorar a checagem de SSL durante o uso do CURL

Install
-------

```
composer require jbcisne/integras-sei
```

Usage
-----

```php
use Jb\Integras\Sei\IntegrasSEI;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;

$config = (object)require_once './client/config.php';

...
$seiCfg = new IntegrasConfigParamenterSEI($config, '3.0.0');
$sei = IntegrasSEI::factory($seiCfg);

$result = $sei->consultarProcedimento('50603.002985/2017-43');
or 
$result = $sei->consultarProcedimento('50603002985201743');
...

```

List of available methods
-----
See classes in src/Jb/Integras/Sei/Version/Services/[version]/Webservice