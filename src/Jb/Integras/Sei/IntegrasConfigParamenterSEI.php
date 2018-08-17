<?php

namespace Jb\Integras\Sei;

use \Jb\Integras\IntegrasConfigParamenterInterface;
use \Jb\Integras\Sei\Exception\IntegrasConfigParamenterSEIException;

/**
 * "Interface" de padronização de parêmtros de configuração do Integrador SEI
 *
 */
class IntegrasConfigParamenterSEI implements IntegrasConfigParamenterInterface
{
    const SYSTEM_DEFAULT_CONNECTION = 'system_default_connection';
    const SYSTEM_EXTERNAL_KEY_NAME = 'system_external';
    const SYSTEM_GROUPER_KEY_NAME = 'system_grouper';
    const SEI_WS_NUM_MAX_DOCS = 'sei_ws_num_max_docs';

    /**
     * nome das chaves obrigatórias nas configurações informadas
     */
    private static $internalKeys = array(
        'VERSION_KEY_NAME' => 'version',
        'SYSTEM_GROUPER_KEY_NAME' => 'system_grouper',
        'SYSTEM_EXTERNAL_KEY_NAME' => 'system_external',
        'SYSTEM_DEFAULT_CONNECTION' => 'system_default_connection',
        'SEI_WS_NUM_MAX_DOCS' => 'sei_ws_num_max_docs',
    );

    private $version;
    private $content;

    /**
     * @param object|array $content
     * @param  string $version
     * @throws IntegrasConfigParamenterSEIException
     * */
    public function __construct($content, $version)
    {
        if (!(is_array($content) || is_object($content))) {
            IntegrasConfigParamenterSEIException::invalidContentParamenter();
        }

        $this->version = $version;
        $this->content = (object)$content;

        $this->validVersion();
        $this->validExternalSystem();
        $this->validServiceGrouper();
        $this->copyInternalKeyDataToVersion();
    }

    public function get($key)
    {
        return $this->has($key)
            ? $this->content->$key
            : null;
    }

    public function has($key)
    {
        return isset($this->content->$key);
    }

    public function set($key, $value)
    {
        $this->content->$key = $value;
    }

    /**
     * retorna a versão do SEI que será manipulada
     *
     * @return string
     * @throws IntegrasConfigParamenterSEIException
     */
    public function version()
    {
        $this->validVersion();

        return preg_replace('/\./', null, $this->version);
    }

    public function versionRaw()
    {
        $this->validVersion();

        return $this->version;
    }

    /*
     * na prática este método ajusta a estrutura
     * do array de configuração para realidade do uso do componente
     * */
    private function copyInternalKeyDataToVersion()
    {
        # adiciona as chaves internas ao config da versão
        $contentVersion = $this->content->version[$this->version];
        $internalKeys = self::$internalKeys;
        unset($internalKeys['VERSION_KEY_NAME']);

        foreach ($internalKeys as $key) {
            $contentVersion[$key] = $this->content->$key;
        }

        $this->content = (object)$contentVersion;
    }

    private function validVersion()
    {
        if (!$this->version) {
            IntegrasConfigParamenterSEIException::versionNotFound();
        }
    }

    private function validExternalSystem()
    {
        if (!$this->has(static::$internalKeys['SYSTEM_EXTERNAL_KEY_NAME'])) {
            IntegrasConfigParamenterSEIException::systemExternalNotFound();
        }
    }

    private function validServiceGrouper()
    {
        if (!$this->has(static::$internalKeys['SYSTEM_GROUPER_KEY_NAME'])) {
            IntegrasConfigParamenterSEIException::serviceGrouperNotFound();
        }
    }
}
