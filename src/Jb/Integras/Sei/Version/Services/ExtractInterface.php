<?php
namespace Jb\Integras\Sei\Version\Services;

/**
 * Interface de serviço integrador do SEI. Cada serviço deverá implementar a
 * recuperação dos dados no método 'run'
 *
 */
interface ExtractInterface
{
    public function extract();
}
