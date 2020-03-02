<?php

namespace Jb\Integras\Sei\Version\Services\SEI300\Parse;

use Jb\Integras\Sei\Parse\Html;

/**
 * Classe para implementação de parse específicos do SEI versão 3.0.0
 *
 * @author Juliano Buzanello <jbcisne@gmail.com>
 * @version 0.0.1
 */
class ParseHtml extends Html
{
    public function __construct($ssl_verify = true)
    {
        $this->ssl_verify = $ssl_verify;
    }
}
