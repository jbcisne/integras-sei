<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ListarUnidades extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * Opcional. Tipo do processo cadastrado no serviço.
     *
     * @var null
     */
    private $idTipoProcedimento = null;

    /**
     * Opcional. Tipo do documento cadastrado no serviço.
     * @var null
     */
    private $idSerie = null;

    /**
     * @param array|null argmuments [
     *     0=>idTipoProcedimento
     *     1=>idSerie
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
        $this->idTipoProcedimento = isset($args[0]) ? $args[0] : null;
        $this->idSerie = isset($args[1]) ? $args[1] : null;
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
                    'IdTipoProcedimento' => $this->idTipoProcedimento,
                    'IdSerie' => $this->idSerie
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
