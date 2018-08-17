<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class EnviarProcesso extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $protocoloProcedimento = null;

    private $unidadesDestino = null;

    private $sinManterAbertoUnidade = null;

    private $sinRemoverAnotacao = null;

    private $sinEnviarEmailNotificacao = null;

    private $dataRetornoProgramado = null;

    private $diasRetornoProgramado = null;

    private $sinDiasUteisRetornoProgramado = null;

    private $sinReabrir = null;

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
            IntegrasServicesArgumentException::argumentRequired('unidadesDestino');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('sinManterAbertoUnidade');
        }

        if (!isset($args[4])) {
            IntegrasServicesArgumentException::argumentRequired('sinRemoverAnotacao');
        }

        if (!isset($args[5])) {
            IntegrasServicesArgumentException::argumentRequired('sinEnviarEmailNotificacao');
        }

        if (!isset($args[6])) {
            IntegrasServicesArgumentException::argumentRequired('dataRetornoProgramado');
        }

        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->protocoloProcedimento = isset($args[1]) ? $args[1] : null;
        $this->unidadesDestino = isset($args[2]) ? $args[2] : null;
        $this->sinManterAbertoUnidade = isset($args[3]) ? $args[3] : null;
        $this->sinRemoverAnotacao = isset($args[4]) ? $args[4] : null;
        $this->sinEnviarEmailNotificacao = isset($args[5]) ? $args[5] : null;
        $this->dataRetornoProgramado = isset($args[6]) ? $args[6] : null;
        $this->diasRetornoProgramado = isset($args[7]) ? $args[7] : null;
        $this->sinDiasUteisRetornoProgramado = isset($args[8]) ? $args[8] : null;
        $this->sinReabrir = isset($args[9]) ? $args[9] : null;
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
                    'UnidadesDestino' => $this->unidadesDestino,
                    'SinManterAbertoUnidade' => $this->sinManterAbertoUnidade,
                    'SinRemoverAnotacao' => $this->sinRemoverAnotacao,
                    'SinEnviarEmailNotificacao' => $this->sinEnviarEmailNotificacao,
                    'DataRetornoProgramado' => $this->dataRetornoProgramado,
                    'DiasRetornoProgramado' => $this->diasRetornoProgramado,
                    'SinDiasUteisRetornoProgramado' => $this->sinDiasUteisRetornoProgramado,
                    'SinReabrir' => $this->sinReabrir
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
