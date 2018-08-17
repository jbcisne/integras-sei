<?php

namespace Jb\Integras\Sei;

use \Jb\Integras\IntegrasInterface;
use \Jb\Integras\Sei\Exception\IntegrasSEIVersionNotSupportedException;

/**
 * super classe do Integrador SEI
 *
 *
 */
abstract class IntegrasSEI implements IntegrasInterface
{
    /**
     * configuracao do integrador SEI
     *
     * @var IntegrasConfigParamenterSEI
     **/
    protected $config;

    /**
     * link com a fonte de dados
     *
     * @var \SoapClient|\PDOStatement
     */
    protected $resource;

    /**
     * @param IntegrasConfigParamenterSEI $config
     **/
    public function __construct(IntegrasConfigParamenterSEI $config)
    {
        $this->config = $config;
    }

    /**
     * retorna true se o serviço invocado existir, falso do contrário
     *
     * @param string $service
     * @return boolean
     **/
    abstract public function hasServiceImplemented($service);

    /**
     * retorna true se a versão do integrador do SEI existir, false do contrário
     *
     * @param IntegrasConfigParamenterSEI $config
     * @return bool
     */
    public static function classIntegraSeiFileExists(IntegrasConfigParamenterSEI $config)
    {
        $ds = DIRECTORY_SEPARATOR;
        return is_file(__DIR__ . sprintf('%1$sVersion%1$sIntegrasSEI%2$s.php', $ds, $config->version()));
    }

    /**
     * retorna o namespace do integrador SEI com base na versão
     *
     * @param IntegrasConfigParamenterSEI $config
     * @return string
     */
    public static function classIntegrasSEINamespace(IntegrasConfigParamenterSEI $config)
    {
        return __NAMESPACE__ . sprintf('\\Version\IntegrasSEI%s', $config->version());
    }

    /**
     * cria objeto integrador com SEI
     *
     * @param IntegrasConfigParamenterSEI $config
     * @return IntegrasSEI
     **/
    public static function factory(IntegrasConfigParamenterSEI $config)
    {
        if (!static::classIntegraSeiFileExists($config)) {
            throw new IntegrasSEIVersionNotSupportedException($config->versionRaw());
        }

        $namespace = static::classIntegrasSEINamespace($config);

        return new $namespace($config);
    }
}
