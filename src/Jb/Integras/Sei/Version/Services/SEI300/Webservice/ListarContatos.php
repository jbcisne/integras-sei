<?php
namespace Jb\Integras\Sei\Version\Services\SEI300\Webservice;

use Jb\Integras\Sei\version\IntegrasSEI300;
use Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use Jb\Integras\Sei\Version\Services\ExtractInterface;
use Jb\Integras\Sei\Version\Services\IntegrasServiceInterface;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesArgumentException;
use Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesRetrieveException;

class ListarContatos extends IntegrasSEI300 implements IntegrasServiceInterface, ExtractInterface
{

    private $idUnidade = null;

    private $idTipoContato = null;

    private $paginaRegistros = null;

    private $paginaAtual = null;

    private $sigla = null;

    private $nome = null;

    private $cpf = null;

    private $cnpj = null;

    private $matricula = null;

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

        if (!isset($args[1])) {
            IntegrasServicesArgumentException::argumentRequired('idTipoContato');
        }

        if (!isset($args[2])) {
            IntegrasServicesArgumentException::argumentRequired('paginaRegistros');
        }

        if (!isset($args[3])) {
            IntegrasServicesArgumentException::argumentRequired('paginaAtual');
        }

        if (!isset($args[4])) {
            IntegrasServicesArgumentException::argumentRequired('sigla');
        }

        if (!isset($args[5])) {
            IntegrasServicesArgumentException::argumentRequired('nome');
        }

        if (!isset($args[6])) {
            IntegrasServicesArgumentException::argumentRequired('cpf');
        }

        if (!isset($args[7])) {
            IntegrasServicesArgumentException::argumentRequired('cnpj');
        }

        if (!isset($args[8])) {
            IntegrasServicesArgumentException::argumentRequired('matricula');
        }

        $this->idUnidade = isset($args[0]) ? $args[0] : null;
        $this->idTipoContato = isset($args[1]) ? $args[1] : null;
        $this->paginaRegistros = isset($args[2]) ? $args[2] : null;
        $this->paginaAtual = isset($args[3]) ? $args[3] : null;
        $this->sigla = isset($args[4]) ? $args[4] : null;
        $this->nome = isset($args[5]) ? $args[5] : null;
        $this->cpf = isset($args[6]) ? $args[6] : null;
        $this->cnpj = isset($args[7]) ? $args[7] : null;
        $this->matricula = isset($args[8]) ? $args[8] : null;
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
                    'IdTipoContato' => $this->idTipoContato,
                    'PaginaRegistros' => $this->paginaRegistros,
                    'PaginaAtual' => $this->paginaAtual,
                    'Sigla' => $this->sigla,
                    'Nome' => $this->nome,
                    'Cpf' => $this->cpf,
                    'Cnpj' => $this->cnpj,
                    'Matricula' => $this->matricula
                )
            );
        } catch (\SoapFault $e) {
            IntegrasServicesRetrieveException::retrieveFault($e);
        }
    }
}
