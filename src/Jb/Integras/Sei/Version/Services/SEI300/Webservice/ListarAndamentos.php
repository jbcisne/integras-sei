<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ListarAndamentos extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $protocoloProcedimento = null;

    private $sinRetornarAtributos = 'S';

    private $andamentos = array();

    private $tarefas = array();

    private $tarefasModulos = array();

    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    private function loadParameters(array $args)
    {

        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimento');
        }

        if (isset($args[5])) {
            $this->sinRetornarAtributos = $args[5];
        }

        $this->protocoloProcedimento = $args[0];
        $this->andamentos = isset($args[1]) ? $args[1] : $this->andamentos;
        $this->tarefas = isset($args[2]) ? $args[2] : $this->tarefas;
        $this->tarefasModulos = isset($args[3]) ? $args[3] : $this->tarefasModulos;
        $this->idUnidade = isset($args[4]) ? $args[4] : $this->idUnidade;
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
                    'SinRetornarAtributos' => $this->sinRetornarAtributos,
                    'Andamentos' => $this->andamentos,
                    'Tarefas' => $this->tarefas,
                    'TarefasModulos' => $this->tarefasModulos
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
