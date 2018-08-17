<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ListarHipotesesLegais extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    /**
     *
     * 1 - restrito, 2 - sigiloso
     *
     * @var integer
     */
    private $nivelAcesso = null;

    /**
     * @param array|null argmuments [
     *     0=>idUnidade
     *     1=>nivelAcesso
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
        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->nivelAcesso = isset($args[1]) ? $args[1] : null;
    }

    /**
     * @return array
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
                    'NivelAcesso' => $this->nivelAcesso
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
