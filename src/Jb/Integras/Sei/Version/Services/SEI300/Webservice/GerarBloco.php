<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class GerarBloco extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $tipo = null;

    private $descricao = null;

    private $unidadesDisponibilizacao = null;

    private $documentos = null;

    private $sinDisponibilizar = null;

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
            IntegrasServicesArgumentException::argumentRequired('tipo');
        }

        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('descricao');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('unidadesDisponibilizacao');
        }

        if (!isset($args[4])) {
            IntegrasServicesArgumentException::argumentRequired('documentos');
        }

        if (!isset($args[5])) {
            IntegrasServicesArgumentException::argumentRequired('sinDisponibilizar');
        }

        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->tipo = isset($args[1]) ? $args[1] : null;
        $this->descricao = isset($args[2]) ? $args[2] : null;
        $this->unidadesDisponibilizacao = isset($args[3]) ? $args[3] : null;
        $this->documentos = isset($args[4]) ? $args[4] : null;
        $this->sinDisponibilizar = isset($args[5]) ? $args[5] : null;
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
                    'Tipo' => $this->tipo,
                    'Descricao' => $this->descricao,
                    'UnidadesDisponibilizacao' => $this->unidadesDisponibilizacao,
                    'Documentos' => $this->documentos,
                    'SinDisponibilizar' => $this->sinDisponibilizar
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
