<?php
namespace Jb\Integras;

/**
 * Interface de integração
 *
 *
 */
interface IntegrasInterface
{
    /**
     * efetua conexão com servidor remoto
     *
     * @return void
     * @throws exception\IntegrasConnectionException
     * */
    public function connect();
}
