<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class DesanexarProcesso extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $protocoloProcedimentoPrincipal = null;

    private $protocoloProcedimentoAnexado = null;

    private $motivo = null;

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
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimentoPrincipal');
        }

        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimentoAnexado');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('motivo');
        }

        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->protocoloProcedimentoPrincipal = isset($args[1]) ? $args[1] : null;
        $this->protocoloProcedimentoAnexado = isset($args[2]) ? $args[2] : null;
        $this->motivo = isset($args[3]) ? $args[3] : null;
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
                    'ProtocoloProcedimentoPrincipal' => $this->protocoloProcedimentoPrincipal,
                    'ProtocoloProcedimentoAnexado' => $this->protocoloProcedimentoAnexado,
                    'Motivo' => $this->motivo
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
