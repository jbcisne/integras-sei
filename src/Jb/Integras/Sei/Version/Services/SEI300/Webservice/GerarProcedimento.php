<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class GerarProcedimento extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;
    private $procedimento = null;
    private $documentos = null;
    private $procedimentosRelacionados = null;
    private $unidadesEnvio = null;
    private $sinManterAbertoUnidade = null;
    private $sinEnviarEmailNotificacao = null;
    private $dataRetornoProgramado = null;
    private $diasRetornoProgramado = null;
    private $sinDiasUteisRetornoProgramado = null;
    private $idMarcador = null;
    private $textoMarcador = null;

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
            IntegrasServicesArgumentException::argumentRequired('procedimento');
        }
        if (!is_array($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('procedimento');
        }
        if (!isset($args[2])) {
            $nuMaxDocs = $this->config->get(IntegrasConfigParamenterSEI::SEI_WS_NUM_MAX_DOCS);
            if (!is_array($args[2])) {
                IntegrasServicesArgumentException::argumentTypeFail('documentos');
            } elseif (count($args[2]) > $nuMaxDocs) {
                throw new IntegrasServicesException(
                    'A geração do Processo excede a quantidade máxima (' . $nuMaxDocs . ') de ' .
                    'documentos permitidos para envio ao SEI'
                );
            }
        }
        if (isset($args[3]) && !is_array($args[3])) {
            IntegrasServicesArgumentException::argumentTypeFail('procedimentosRelacionados');
        }
        if (isset($args[4]) && !is_array($args[4])) {
            IntegrasServicesArgumentException::argumentTypeFail('unidadesEnvio');
        }

        $this->idUnidade = $args[0];
        $this->procedimento = $args[1];
        $this->documentos = isset($args[2]) ? $args[2] : null;
        $this->procedimentosRelacionados = isset($args[3]) ? $args[3] : null;
        $this->unidadesEnvio = isset($args[4]) ? $args[4] : null;
        $this->sinManterAbertoUnidade = isset($args[5]) ? $args[5] : null;
        $this->sinEnviarEmailNotificacao = isset($args[6]) ? $args[6] : null;
        $this->dataRetornoProgramado = isset($args[7]) ? $args[7] : null;
        $this->diasRetornoProgramado = isset($args[8]) ? $args[8] : null;
        $this->sinDiasUteisRetornoProgramado = isset($args[9]) ? $args[9] : null;
        $this->idMarcador = isset($args[10]) ? $args[10] : null;
        $this->textoMarcador = isset($args[11]) ? $args[11] : null;
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
                    'Procedimento' => $this->procedimento,
                    'Documentos' => $this->documentos,
                    'ProcedimentosRelacionados' => $this->procedimentosRelacionados,
                    'UnidadesEnvio' => $this->unidadesEnvio,
                    'SinManterAbertoUnidade' => $this->sinManterAbertoUnidade,
                    'SinEnviarEmailNotificacao' => $this->sinEnviarEmailNotificacao,
                    'DataRetornoProgramado' => $this->dataRetornoProgramado,
                    'DiasRetornoProgramado' => $this->diasRetornoProgramado,
                    'SinDiasUteisRetornoProgramado' => $this->sinDiasUteisRetornoProgramado,
                    'IdMarcador' => $this->idMarcador,
                    'TextoMarcador' => $this->textoMarcador
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
