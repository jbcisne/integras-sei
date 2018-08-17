<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de Geração de Processo (Procedimento) no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class GerarProcedimento extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    /**
     * @var string
     */
    private $idUnidade = null;
    /**
     * Lista com as informações referente ao processo a sercriado
     *
     * @var array [
     *    IdTipoProcedimento => string,
     *    Especificacao => string,
     *    Assuntos => []|['CodigoEstruturado' => '024.112.a'],
     *    Interessados => []|['Sigla' => 'iddelogin', 'Nome' => 'nome do fulano'],
     *    Observacao => string
     *    NivelAcesso => 0 - público|1 - restrito|2 - sigiloso|null - Assume o padrão sugerido
     * ]
     */
    private $procedimento = array();

    /**
     * Lista com documentos a serem criados dentro do processo a ser criado.
     * Os atributos desta lista devem ser os mesmos usados na operação de incluir documento
     *
     * O número máximo de documentos por chamada é limitado através do parâmetro
     * SEI_WS_NUM_MAX_DOCS (menu Infra/Parâmetros).
     *
     * @var array|null
     */
    private $documentos = null;

    /**
     * Conjunto com Ids de processos que devem ser relacionados automaticamente com o novo processo
     *
     * @var array|null
     */
    private $procedimentosRelacionados = null;

    /**
     * Conjunto com Ids de unidades para envio do processo após a geração.
     * O processo ficará aberto na unidade geradora e nas unidades informadas neste parâmetro
     *
     * @var array|null
     */
    private $unidadesEnvio = null;

    /**
     * S/N - sinalizador indica se o processo deve ser mantido aberto na unidade de origem (valor padrão S)
     *
     * @var string|null
     */
    private $sinManterAbertoUnidade = null;

    /**
     * S/N - sinalizador indicando se deve ser enviado email de aviso para as unidades destinatárias (valor padrão N)
     *
     * @var string|null
     */
    private $sinEnviarEmailNotificacao = null;

    /**
     * Data para definição de Retorno Programado (valor padrão nulo)
     *
     * @var string|null 'xx/xx/xxxx'
     */
    private $dataRetornoProgramado = null;

    /**
     * Número de dias para o Retorno Programado (valor padrão nulo)
     *
     * @var string|null
     */
    private $diasRetornoProgramado = null;

    /**
     * S/N - sinalizador indica se o valor passado no parâmetro DiasRetornoProgramado corresponde
     * a dias úteis ou não (valor padrão N)
     *
     * @var string|null
     */
    private $sinDiasUteisRetornoProgramado = null;


    /**
     * Gera processo
     *
     * @param array|null argmuments [
     *     0=>idUnidade
     *     1=>procedimento
     *     2=>documentos
     * ]
     * @return \stdClass
     * */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    /**
     * @param array $args
     * @throws IntegrasServicesArgumentException
     * @throws IntegrasServicesException
     */
    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }
        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('procedimento');
        }
        if (!is_array($args[1])) {
            IntegrasServicesArgumentException::argumentTypeFail('procedimento');
        }
        if (isset($args[2])) {
            if (!is_array($args[2])) {
                IntegrasServicesArgumentException::argumentTypeFail('documentos');
            }
            $nuMaxDocs = $this->config->get(IntegrasConfigParamenterSEI::SEI_WS_NUM_MAX_DOCS);
            if (count($args[2]) > $nuMaxDocs) {
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
    }

    /**
     * efetua a execução do serviço para inclusão de processo
     *
     * @return \stdClass
     * @throws IntegrasServicesRetrieveException
     **/
    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                'gerarProcedimento',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'Procedimento' => $this->procedimento,
                    'Documentos' => $this->documentos,
                    'ProcedimentosRelacionados' => $this->procedimentosRelacionados,
                    'UnidadesEnvio' => $this->unidadesEnvio,
                    'SinManterAbertoUnidade' => $this->sinManterAbertoUnidade,
                    'SinEnviarEmailNotificacao' => $this->sinEnviarEmailNotificacao,
                    'DataRetornoProgramado' => $this->dataRetornoProgramado,
                    'DiasRetornoProgramado' => $this->diasRetornoProgramado,
                    'SinDiasUteisRetornoProgramad' => $this->sinDiasUteisRetornoProgramado
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }

}
