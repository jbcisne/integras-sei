<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ConsultarDocumento extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * Identificador da unidade no SEI.
     *
     * @var integer null
     */
    private $idUnidade = null;

    /**
     * Número do documento visível para o usuário, ex.: 0003934
     *
     * @var string null
     */
    private $protocoloDocumento = null;

    /**
     * Sinalizador para retorno do andamento de geração
     *
     * @var char null [S-N]
     */
    private $sinRetornarAndamentoGeracao = null;

    /**
     * Sinalizador para retorno das assinaturas do documento
     *
     * @var char null [S-N]
     */
    private $sinRetornarAssinaturas = null;

    /**
     * Sinalizador para retorno dos dados de publicação
     *
     * @var char null [S-N]
     */
    private $sinRetornarPublicacao = null;

    /**
     * Sinalizador para retorno dos campos do formulário
     *
     * @var char null [S-N]
     */
    private $sinRetornarCampos = null;

    /**
     * @param array|null argmuments [
     *     0=>protocoloDocumento
     *     1=>idUnidade
     *     2=>sinRetornarAndamentoGeracao
     *     3=>sinRetornarAssinaturas
     *     4=>sinRetornarPublicacao
     *     5=>sinRetornarCampos
     * ]
     * @return \stdClass
     */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

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
        $this->sinRetornarCampos = isset($args[5]) ? $args[5] : null;
    }

    /**
     * @return \stdClass
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
                    'ProtocoloDocumento' => $this->protocoloDocumento,
                    'SinRetornarAndamentoGeracao' => $this->sinRetornarAndamentoGeracao,
                    'SinRetornarAssinaturas' => $this->sinRetornarAssinaturas,
                    'SinRetornarPublicacao' => $this->sinRetornarPublicacao,
                    'SinRetornarCampos' => $this->sinRetornarCampos
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
