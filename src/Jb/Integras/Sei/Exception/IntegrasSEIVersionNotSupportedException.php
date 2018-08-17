<?php
namespace Jb\Integras\Sei\Exception;

use Jb\Integras\Exception\IntegrasConfigParamenterException;

class IntegrasSEIVersionNotSupportedException extends IntegrasConfigParamenterException
{
    public function __construct($version)
    {
        parent::__construct(
            sprintf('A versão %s do SEI! não é suportada para esta operação.', $version)
        );
    }
}
