<?php

namespace Jb\Integras;

/**
 * Interface de configuração do Integrador
 *
 *
 */
interface IntegrasConfigParamenterInterface
{
    /**
     * retorna null se a chave informada não existir
     *
     * @param string $key
     * @return mixed|null
     * */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return IntegrasConfigParamenterInterface
     * */
    public function set($key, $value);

    /**
     * retorna true se a chave informada esitver registrada
     *
     * @param  [type]  $key [description]
     * @return boolean      [description]
     */
    public function has($key);
}
