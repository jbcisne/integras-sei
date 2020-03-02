<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\SEI300\Parse\ParseHtml;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ConsultarProcedimento extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;
    private $protocoloProcedimento = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarAssuntos = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarInteressados = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarObservacoes = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarAndamentoGeracao = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarAndamentoConclusao = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarUltimoAndamento = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarUnidadesProcedimentoAberto = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarProcedimentosRelacionados = null;

    /**
     *
     *
     * @var char null [S-N]
     */
    private $sinRetornarProcedimentosAnexados = null;

    /**
     *
     * @param array|null argmuments [
     *     0=>protocoloProcedimento
     *     1=>idUnidade
     *     2=>sinRetornarAssuntos
     *     3=>sinRetornarInteressados
     *     4=>sinRetornarObservacoes
     *     5=>sinRetornarAndamentoGeracao
     *     6=>sinRetornarAndamentoConclusao
     *     7=>sinRetornarUltimoAndamento
     *     8=>sinRetornarUnidadesProcedimentoAberto
     *     9=>sinRetornarProcedimentosRelacionados
     *     10=>sinRetornarProcedimentosAnexados
     * ]
     * @return \stdClass
     */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);
        $objData = $this->extract();
        $this->appendData($objData);

        return $objData;
    }

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
     * @return \stdClass
     * @throws IntegrasServicesRetrieveException
     */
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
                    'SinRetornarAssuntos' => $this->sinRetornarAssuntos,
                    'SinRetornarInteressados' => $this->sinRetornarInteressados,
                    'SinRetornarObservacoes' => $this->sinRetornarObservacoes,
                    'SinRetornarAndamentoGeracao' => $this->sinRetornarAndamentoGeracao,
                    'SinRetornarAndamentoConclusao' => $this->sinRetornarAndamentoConclusao,
                    'SinRetornarUltimoAndamento' => $this->sinRetornarUltimoAndamento,
                    'SinRetornarUnidadesProcedimentoAberto' => $this->sinRetornarUnidadesProcedimentoAberto,
                    'SinRetornarProcedimentosRelacionados' => $this->sinRetornarProcedimentosRelacionados,
                    'SinRetornarProcedimentosAnexados' => $this->sinRetornarProcedimentosAnexados
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }

    private function appendData(&$objData)
    {
        $parse = new ParseHtml($this->config->get('curl_ssl_verify'));
        $parse->loadFromUrl($objData->LinkAcesso);

        $objData->Documentos = $parse->documentos($this->config->get('url_base'));
        $objData->Historicos = $parse->andamentos();
    }

}
