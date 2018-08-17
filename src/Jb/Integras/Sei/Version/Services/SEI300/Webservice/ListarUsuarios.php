<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ListarUsuarios extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * Valor informado no cadastro do serviço realizado no SEI
     *
     * @var integer null
     */
    private $idUnidade = null;

    /**
     * Opcional. Filtra determinado usuário.
     *
     * @var integer null
     */
    private $idUsuario = null;


    /**
     * @param array|null argmuments [
     *     0=>idUnidade
     *     1=>idUsuario
     * ]
     * @return array
     */
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

        $this->idUnidade = $args[0];
        $this->idUsuario = isset($args[1]) ? $args[1] : null;
    }

    /**
     * Excecuta a extração dos dados do serviço
     *
     * @return array
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
                    'IdUsuario' => $this->idUsuario
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
