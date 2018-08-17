<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;


use Jb\Integras\Sei\Version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Class AdicionarArquivo
 *
 * O serviço criará um arquivo no repositório de documentos e retornará seu identificador. O envio
 * do arquivo poderá ser particionado com chamadas posteriores ao serviço
 * adicionarConteudoArquivo.
 *  Após todos o conteúdo ser transferido o arquivo será ativado e poderá ser associado com um
 * documento externo no serviço de inclusão de documento (campo IdArquivo da estrutura
 * Documento). Neste caso, ao chamar o respectivo serviço o conteúdo não precisará ser informado
 * pois já foi enviado previamente.
 *  Quando o agendamento removerArquivosNaoUtilizados for executado serão excluídos todos os
 * arquivos com mais de 24 horas e que não foram completados ou que não foram associados com
 * um documento externo.
 *
 * @package Jb\Integras\Sei\Version\Services\SEI300\Webservice
 */
class AdicionarArquivo extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    /**
     * Valor informado no cadastro do serviço realizado no SEI
     * @var integer null
     */
    private $idUnidade = null;

    /**
     * Nome do arquivo
     * @var string null
     */
    private $nome = null;

    /**
     * Tamanho total do arquivo em bytes
     * @var integer null
     */
    private $tamanho = null;

    /**
     * MD5 do conteúdo total do arquivo
     * @var string null
     */
    private $hash = null;

    /**
     * Conteúdo total ou parcial codificado em Base64
     * @var string null
     */
    private $conteudo = null;

    /**
     * Adiciona arquivo
     *
     * @param array|null argmuments [
     *     0=>nome
     *     1=>tamanho
     *     2=>hash
     *     3=>conteudo
     *     4=>idUnidade
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
     * @return void
     */
    private function loadParameters(array $args)
    {

        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('nome');
        }

        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('tamanho');
        }

        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('hash');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('conteudo');
        }

        $this->nome = $args[0];
        $this->tamanho = $args[1];
        $this->hash = $args[2];
        $this->conteudo = $args[3];
        $this->idUnidade = isset($args[4]) ? $args[4] : null;
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
                    'Nome' => $this->nome,
                    'Tamanho' => $this->tamanho,
                    'Hash' => $this->hash,
                    'Conteudo' => $this->conteudo
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
