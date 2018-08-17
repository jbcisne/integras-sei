<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de consulta de Documentos no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ConsultarDocumento extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    /**
     * @var string
     */
    private $idUnidade = null;
    /**
     * @var string Numero do documento exibido na arvore do processo no SEI
     */
    private $protocoloDocumento = null;
    /**
     * @var char|null S ou N
     */
    private $sinRetornarAndamentoGeracao = null;
    /**
     * @var char|null S ou N
     */
    private $sinRetornarAssinaturas = null;
    /**
     * @var char|null S ou N
     */
    private $sinRetornarPublicacao = null;

    /**
     * Recupera os dados do Documento
     *
     * @param array|null argmuments [
     *  0=>protocoloDocumento,
     *  1=>idUnidade,
     *  2=>sinRetornarAndamentoGeracao
     *  3=>sinRetornarAssinaturas
     *  4=>sinRetornarPublicacao
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
     */
    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloDocumento');
        }

        $this->protocoloDocumento = $args[0];
        $this->idUnidade = isset($args[1]) ? $args[1] : null;
        $this->sinRetornarAndamentoGeracao = isset($args[2]) ? $args[2] : null;
        $this->sinRetornarAssinaturas = isset($args[3]) ? $args[3] : null;
        $this->sinRetornarPublicacao = isset($args[4]) ? $args[4] : null;
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
                'consultarDocumento',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'ProtocoloDocumento' => $this->protocoloDocumento,
                    'SinRetornarAndamentoGeracao' => $this->sinRetornarAndamentoGeracao,
                    'SinRetornarAssinaturas' => $this->sinRetornarAssinaturas,
                    'SinRetornarPublicacao' => $this->sinRetornarPublicacao,
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }

}
