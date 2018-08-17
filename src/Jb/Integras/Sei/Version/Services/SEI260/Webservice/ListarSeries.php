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
class ListarSeries extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idUnidade = null;
    private $idTipoProcedimento = null;

    /**
     * recupera a lista dos tipos de Documento (Series)
     *
     * @param array|null $arguments 0=>idUnidade, 1=>idTipoProcedimento
     * @return \stdClass
     */
    public function run(array $arguments = null)
    {
        $this->idUnidade = isset($arguments[0]) ? $arguments[0] : null;
        $this->idTipoProcedimento = isset($arguments[1]) ? $arguments[1] : null;

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
                'listarSeries',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'IdTipoProcedimento' => $this->idTipoProcedimento
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
