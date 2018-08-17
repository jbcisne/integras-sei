<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\SEI260\Parse\ParseHtml;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de recuperação de processos no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ConsultarProcedimento extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idUnidade = null;
    private $protocoloProcedimento = null;
    private $sinRetornarAssuntos = null;
    private $sinRetornarInteressados = null;
    private $sinRetornarObservacoes = null;
    private $sinRetornarAndamentoGeracao = null;
    private $sinRetornarAndamentoConclusao = null;
    private $sinRetornarUltimoAndamento = null;
    private $sinRetornarUnidadesProcedimentoAberto = null;
    private $sinRetornarProcedimentosRelacionados = null;
    private $sinRetornarProcedimentosAnexados = null;


    /**
     * recupera a lista de unidades
     *
     * @param array|null $arguments
     * @return \stdClass
     * */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);
        $objData = $this->extract();
        $this->appendData($objData);

        return $objData;
    }

    /**
     * @param array $args
     */
    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimento');
        }

        $this->protocoloProcedimento = $args[0];
        $this->idUnidade = isset($args[1]) ? $args[1] : null;
        $this->sinRetornarAssuntos = isset($args[2]) ? $args[2] : null;
        $this->sinRetornarInteressados = isset($args[3]) ? $args[3] : null;
        $this->sinRetornarObservacoes = isset($args[4]) ? $args[4] : null;
        $this->sinRetornarAndamentoGeracao = isset($args[5]) ? $args[5] : null;
        $this->sinRetornarAndamentoConclusao = isset($args[6]) ? $args[6] : null;
        $this->sinRetornarUltimoAndamento = isset($args[7]) ? $args[7] : null;
        $this->sinRetornarUnidadesProcedimentoAberto = isset($args[8]) ? $args[8] : null;
        $this->sinRetornarProcedimentosRelacionados = isset($args[9]) ? $args[9] : null;
        $this->sinRetornarProcedimentosAnexados = isset($args[10]) ? $args[10] : null;
    }

    /**
     * efetua a extração dos dados do webservice
     *
     * @return \stdClass
     * @throws IntegrasServicesRetrieveException
     **/
    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                'consultarProcedimento',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'ProtocoloProcedimento' => $this->protocoloProcedimento,
                    'SinRetornarAssuntos' => $this->sinRetornarAssuntos,
                    'SinRetornarInteressados' => $this->sinRetornarInteressados,
                    'SinRetornarObservacoes' => $this->sinRetornarObservacoes,
                    'SinRetornarAndamentoGeracao' => $this->sinRetornarAndamentoGeracao,
                    'SinRetornarAndamentoConclusao' => $this->sinRetornarAndamentoConclusao,
                    'SinRetornarUltimoAndamento' => $this->sinRetornarUltimoAndamento,
                    'SinRetornarUnidadesProcedimentoAberto' => $this->sinRetornarUnidadesProcedimentoAberto,
                    'SinRetornarProcedimentosRelacionados' => $this->sinRetornarProcedimentosRelacionados,
                    'SinRetornarProcedimentosAnexados' => $this->sinRetornarProcedimentosAnexados,
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }

    private function appendData(&$objData)
    {
        $parse = new ParseHtml();
        $parse->loadFromUrl($objData->LinkAcesso);

        $objData->Documentos = $parse->documentos($this->config->get('url_base'));
        $objData->Historicos = $parse->andamentos();
    }
}
