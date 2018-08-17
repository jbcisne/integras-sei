<?php
namespace Jb\Integras\Sei\Version\Services\Exception;

class IntegrasServicesNotAvaliableException extends IntegrasServicesException
{
    public function __construct($service)
    {
        parent::__construct(sprintf('O serviço "%s" não está disponível nas suas configurações', lcfirst($service)));
    }
}
