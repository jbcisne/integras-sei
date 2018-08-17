<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de recuperação de Tipos de Procedimento no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class ListarTiposProcedimento extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    private $idUnidade = null;
    private $idSerie = null;

    /**
     * Eecupera a lista dos Tipos de Procedimento
     *
     * @param array|null $arguments 0=>idUnidade, 1=>idSeries
     * @return array
     */
    public function run(array $arguments = null)
    {
        $this->idUnidade = isset($arguments[0]) ? $arguments[0] : null;
        $this->idSerie = isset($arguments[1]) ? $arguments[1] : null;

        return $this->extract();
    }

    /**
     * Efetua a extração dos dados do webservice
     *
     * @return array
     * @throws IntegrasServicesRetrieveException
     **/
    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                'listarTiposProcedimento',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'IdSeries' => $this->idSerie
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
