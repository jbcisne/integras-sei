<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de recuperação de processos no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ListarUnidades extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idSerie = null;
    private $idTipoProcedimento = null;

    /**
     * recupera a lista de unidades
     *
     * @param array|null $arguments 0=>idTipoProcedimento, 1=>idSerie
     * @return \stdClass
     */
    public function run(array $arguments = null)
    {
        $this->idTipoProcedimento = isset($arguments[0]) ? $arguments[0] : null;
        $this->idSerie = isset($arguments[1]) ? $arguments[1] : null;

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
                'listarUnidades',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdTipoProcedimento' => $this->idTipoProcedimento,
                    'IdSerie' => $this->idSerie
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
