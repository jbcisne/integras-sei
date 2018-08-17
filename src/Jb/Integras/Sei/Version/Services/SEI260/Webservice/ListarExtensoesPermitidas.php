<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;

/**
 * Serviço de recuperação de Extensões Permitidas no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ListarExtensoesPermitidas extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idUnidade = null;
    private $idArquivoExtensao = null;

    /**
     * recupera a lista de extensões de arquivos permitidos
     *
     * @param array|null $arguments 0=>idUnidade, 1=>idArquivoExtensao
     * @return \stdClass
     */
    public function run(array $arguments)
    {
        if (!isset($arguments[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }

        $this->idUnidade = $arguments[0];
        $this->idArquivoExtensao = isset($arguments[1]) ? $arguments[1] : null;

        return $this->extract();
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
                'listarExtensoesPermitidas',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'IdArquivoExtensao' => $this->idArquivoExtensao
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
