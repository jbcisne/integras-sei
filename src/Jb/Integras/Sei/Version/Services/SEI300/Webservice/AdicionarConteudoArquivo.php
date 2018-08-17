<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Class AdicionarConteudoArquivo
 *
 * O sistema identificará automaticamente quando o conteúdo foi completado validando o tamanho
 * em bytes e o hash do conteúdo. Quando as condições forem satisfeitas o arquivo será ativado e
 * poderá ser utilizado nas chamadas de inclusão de documento.
 *
 * @package Jb\Integras\Sei\Version\Services\SEI300\Webservice
 */
class AdicionarConteudoArquivo extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * Valor informado no cadastro do serviço realizado no SEI
     * @var integer null
     */
    private $idUnidade = null;

    /**
     * Identificador do arquivo criado pelo serviço "adicionarArquivo"
     *
     * @var integer null
     */
    private $idArquivo = null;

    /**
     * Conteúdo codificado em Base64 para ser adicionado no arquivo
     *
     * @var string null
     */
    private $conteudo = null;

    /**
     *
     * @param array|null argmuments [
     *     0=>idArquivo
     *     1=>conteudo
     *     2=>idUnidade
     * ]
     * @return integer
     */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('idArquivo');
        }

        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('conteudo');
        }

        $this->idArquivo = $args[0];
        $this->conteudo = $args[1];
        $this->idUnidade = isset($args[2]) ? $args[2] : null;
    }

    /**
     * Excecuta a extração dos dados do serviço
     *
     * @return integer
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
                    'IdArquivo' => $this->idArquivo,
                    'Conteudo' => $this->conteudo
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
