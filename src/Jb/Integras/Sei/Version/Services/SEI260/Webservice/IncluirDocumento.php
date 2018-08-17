<?php
namespace Jb\Integras\Sei\Version\Services\SEI260\Webservice;

use Jb\Integras\Sei\Version\IntegrasSEI260;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

/**
 * Serviço de Inclusão de Documento no SEI versão 2.6.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 * */
class IncluirDocumento extends IntegrasSEI260 implements IntegrasServiceInterface, ExtractInterface
{
    /**
     * @var string
     */
    private $idUnidade = null;
    /**
     * Lista com as informações referente ao processo a sercriado
     *
     * @var array [
     *      Tipo => string [G = documento gerado, R = documento recebido (externo)]
     *      IdProcedimento => string [Identificador do processo onde o documento deve ser inserido, passar null quando
     *                                na mesma operação estiver sendo gerado o processo. Opcional se
     *                                ProtocoloProcedimento informado]
     *      IdSerie => string [Identificador do tipo de documento no SEI]
     *      Numero => string [Número do documento, passar null para documentos gerados com numeração controlada pelo
     *                          SEI. Para documentos externos informar o número ou nome complementar a ser exibido
     *                          na árvore de documentos do processo (o SEI não controla numeração de documentos
     *                          externos). Para documentos gerados com numeração informada, igualmente informar o
     *                          número por meio deste campo.]
     *      Data => string [Data do documento, obrigatório para documentos externos. Passar null para documentos
     *                      gerados] [dd/mm/YYYY]
     *      Descricao => string [Descrição do documento para documentos gerados. Passar null para documentos externos]
     *      Remetente => array(
     *      <!--You may enter the following 2 items in any order-->
     *          Sigla => string
     *          Nome => string
     *      )
     *      Interessados => array() [Informar um conjunto com os dados de interessados (ver estrutura Interessado).
     *                                  Se não existirem interessados deve ser informado um conjunto vazio.]
     *                              interessado = [Sigla => '', Nome => '']
     *      Destinatarios => array() [Informar um conjunto com os dados de destinatários (ver estrutura Destinatario).
     *                                Se não existirem destinatários deve ser informado um conjunto vazio.]
     *                                destinatario = [Sigla => '', Nome => '']
     *      Observacao => string [Texto da observação da unidade, passar null se não existir]
     *      NomeArquivo => string [Nome do arquivo, obrigatório para documentos externos. Passar null para
     *                              documentos gerados.]
     *      Conteudo => base64Binary [Conteúdo do arquivo codificado em Base64. Para documentos gerados será o
     *                                conteúdo da seção principal do editor HTML e para documentos externos será o
     *                                conteúdo do anexo.]
     *      <!--Optional:-->
     *      ConteudoMTOM => base64Binary [Conteúdo textual ou binário do documento. Este campo somente poderá ser
     *                                      utilizado para documentos externos. O sistema somente aceitará requisições
     *                                      com um dos atributos preenchidos: Conteudo ou ConteudoMTOM.]
     *      NivelAcesso => string [0 - público, 1 - restrito, 2 - sigiloso, Null – o documento assumirá o nível de
     *                             acesso e hipótese legal sugeridos para o tipo do processo, conforme cadastro no SEI.]
     *      <!--Optional:-->
     *      SinBloqueado => string [S/N - bloqueando o documento não será possível excluí-lo ou alterar seu conteúdo]
     * ]
     */
    private $documento = array();

    /**
     * Gera processo
     *
     * @param array|null argmuments [
     *     0=>idUnidade
     *     1=>documento
     * ]
     * @return \stdClass
     * */
    public function run(array $arguments = null)
    {
        $this->loadParameters($arguments);

        return $this->extract();
    }

    /**
     * @param array $args
     * @throws IntegrasServicesArgumentException
     * @throws IntegrasServicesException
     */
    private function loadParameters(array $args)
    {
        if (!isset($args[0])) {
            IntegrasServicesArgumentException::argumentRequired('idUnidade');
        }
        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('documento');
        }
        if (!is_array($args[1])) {
            IntegrasServicesArgumentException::argumentTypeFail('documento');
        }

        $this->idUnidade = $args[0];
        $this->documento = $args[1];
    }

    /**
     * efetua a execução do serviço para inclusão de processo
     *
     * @return \stdClass
     * @throws IntegrasServicesRetrieveException
     **/
    public function extract()
    {
        try {
            return $this->resource->__soapCall(
                'incluirDocumento',
                array(
                    'SiglaSistema' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_EXTERNAL_KEY_NAME),
                    'IdentificacaoServico' => $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_GROUPER_KEY_NAME),
                    'IdUnidade' => $this->idUnidade,
                    'Documento' => $this->documento,
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }

}
