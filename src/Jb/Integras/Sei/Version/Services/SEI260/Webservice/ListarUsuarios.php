<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;

/**
 * Serviço de recuperação de Usuários no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ListarUsuarios extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idUnidade = null;
    private $idUsuario = null;

    /**
     * Recupera a lista de Usuários
     *
     * @param array|null $arguments 0=>idUnidade, 1=>idUsuario
     * @return array
     */
    public function run(array $arguments)
    {
        if (!isset($arguments[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }

        $this->idUnidade = $arguments[0];
        $this->idUsuario = isset($arguments[1]) ? $arguments[1] : null;

        return $this->extract();
    }

    /**
     * efetua a extração dos dados do webservice
     *
     * @return array
     * @throws IntegrasServicesRetrieveException
     **/
    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                'listarUsuarios',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'IdUsuario' => $this->idUsuario
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
