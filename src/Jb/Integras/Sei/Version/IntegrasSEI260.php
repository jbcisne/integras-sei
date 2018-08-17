<?php

namespace Jb\Integras\Sei\Version;

use \Jb\Integras\Sei\IntegrasSEI;
use \Jb\Integras\Sei\IntegrasConfigParamenterSEI;
use \Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesConnectorException;
use \Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesNotAvaliableException;
use \Jb\Integras\Sei\Version\Services\Exception\IntegrasServicesNotFountException;

/**
 * Integrador SEI versão 2.6.0
 *
 *
 */
class IntegrasSEI260 extends IntegrasSEI
{
    public function __construct(IntegrasConfigParamenterSEI $config)
    {
        parent::__construct($config);

        # estabelece conexao com o repositório de dados
        $this->connect();
    }

    /**
     * intercepta a chamada os serviços e os delega à uma classe especializada
     *
     * @param $service
     * @param mixed[] $arguments
     * @return mixed
     * @throws IntegrasServicesNotFountException
     * @throws IntegrasServicesNotAvaliableException
     * */
    public function __call($service, $arguments)
    {
        $service = self::normalizeServiceName($service);

        if (!$this->hasServiceImplemented($service)) {
            throw new IntegrasServicesNotFountException($service);
        }
        if (!$this->hasServiceAvaliable($service)) {
            throw new IntegrasServicesNotAvaliableException($service);
        }

        $namespace = $this->nsService($service);

        # cria instancia do serviço que será executado
        $service = new $namespace($this->config);

        # executa o serviço
        return $service->run($arguments);
    }

    public function connect()
    {
        $resourceType = $this->config->get(IntegrasConfigParamenterSEI::SYSTEM_DEFAULT_CONNECTION);
        $connector = 'connector' . ucfirst($resourceType);

        # estabelece conexao com o repositório de dados
        $this->$connector();
    }

    private function connectorWebservice()
    {
        $config = $this->config->get('connect');
        $WSConfig = $config['webservice'];

        $options = isset($WSConfig['options']) ? $WSConfig['options'] : array();

        try {
            $this->resource = new \SoapClient($WSConfig['wsdl'], $options);
        } catch (\Exception $e) {
            IntegrasServicesConnectorException::connectorFault($e);
        }

        if (!$this->resource) {
            IntegrasServicesConnectorException::connectorFault();
        }
    }

    private function connectorDatabase()
    {
        throw new IntegrasServicesConnectorException('Falta implementar conexão com banco de dados');
    }

    public function hasServiceImplemented($service)
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR
            . 'Services' . DIRECTORY_SEPARATOR
            . 'SEI' . $this->config->version() . DIRECTORY_SEPARATOR
            . ucfirst($this->config->get(IntegrasConfigParamenterSEI::SYSTEM_DEFAULT_CONNECTION)) . DIRECTORY_SEPARATOR
            . $service . '.php';

        return is_file($filename);
    }

    public function hasServiceAvaliable($service)
    {
        return array_search(lcfirst($service), $this->config->get('services')) !== false;
    }

    /**
     * retorna o namespace completo do serviço
     *
     * @param string $service
     * @return string
     * */
    private function nsService($service)
    {
        return __NAMESPACE__
            . '\\Services\\SEI' . $this->config->version()
            . '\\' . ucfirst($this->config->get(IntegrasConfigParamenterSEI::SYSTEM_DEFAULT_CONNECTION))
            . '\\' . $service;
    }

    private function normalizeServiceName($service)
    {
        return ucfirst($service);
    }
}
