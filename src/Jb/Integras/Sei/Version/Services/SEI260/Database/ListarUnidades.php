<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Database;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;

/**
 * Serviço de recuperação de processos no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ListarUnidades extends IntegrasSEI260 implements IntegrasServiceInterface
{
    public function run(array $arguments = null)
    {
        return empty($arguments)
            ? 'zero kill!'
            : $this->delegateForMethodWithParameter($arguments);
    }

    private function delegateForMethodWithParameter(array $arguments)
    {
        # note que os parâmetros informados na origem da chamda são encapsulado
        # em um array. assim, o primeiro argumento está disponível em
        # $arguments[0], o segundo em $arguments[1] e assim por diante
        return json_encode($arguments[0]);
    }
}
