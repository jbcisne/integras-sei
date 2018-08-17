<?php
namespace Jb\Integras\Sei\Version\Services\Exception;

class IntegrasServicesNotFountException extends IntegrasServicesException
{
    public function __construct($service)
    {
        parent::__construct(sprintf('O serviço "%s" não existe ou não está disponível', $service));
    }
}
