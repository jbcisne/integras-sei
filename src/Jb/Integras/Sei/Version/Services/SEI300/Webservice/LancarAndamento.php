<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class LancarAndamento extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $protocoloProcedimento = null;

    private $idTarefa = null;

    private $idTarefaModulo = null;

    private $atributos = null;

    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    private function loadParameters(array $args)
    {

        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }

        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimento');
        }

        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('idTarefa');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('idTarefaModulo');
        }

        if (!isset($args[4])) {
            IntegrasServicesArgumentException::argumentRequired('atributos');
        }

        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->protocoloProcedimento = isset($args[1]) ? $args[1] : null;
        $this->idTarefa = isset($args[2]) ? $args[2] : null;
        $this->idTarefaModulo = isset($args[3]) ? $args[3] : null;
        $this->atributos = isset($args[4]) ? $args[4] : null;
    }

    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                $this->getWsdlMethodCalledName(),
                array(
                    "SiglaSistema" => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    "IdentificacaoServico" => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'ProtocoloProcedimento' => $this->protocoloProcedimento,
                    'IdTarefa' => $this->idTarefa,
                    'IdTarefaModulo' => $this->idTarefaModulo,
                    'Atributos' => $this->atributos
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
