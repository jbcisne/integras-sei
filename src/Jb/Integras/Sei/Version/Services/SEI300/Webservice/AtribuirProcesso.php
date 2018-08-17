<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class AtribuirProcesso extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * @var string
     */
    private $idUnidade = null;
    /**
     * Número do processo visível para o usuário (com mascara)
     *
     * @var string
     */
    private $protocoloProcedimento = null;

    /**
     * Identificador do usuário no SIP
     *
     * @var string
     */
    private $idUsuario = null;

    /**
     * S/N - indica se o processo deve ser reaberto automaticamente (valor padrão N)
     *
     * @var string
     */
    private $sinReabrir = 'N';

    /**
     *
     * @param array|null argmuments [
     *     0=>idUnidade
     *     1=>protocoloProcedimento
     *     2=>idUsuario
     *     2=>sinReabrir
     * ]
     * @return integer
     * */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    /**
     * @param array $args
     */
    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }
        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('protocoloProcedimento');
        }
        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('idUsuario');
        }

        $this->idUnidade = $args[0];
        $this->protocoloProcedimento = $args[1];
        $this->idUsuario = $args[2];

        if (isset($args[3])) {
            $this->sinReabrir = $args[3];
        }
    }

    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                $this->getWsdlMethodCalledName(),
                array(
                    "SiglaSistema" => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    "IdentificacaoServico" => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'ProtocoloProcedimento' => $this->protocoloProcedimento,
                    'IdUsuario' => $this->idUsuario,
                    'SinReabrir' => $this->sinReabrir
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
