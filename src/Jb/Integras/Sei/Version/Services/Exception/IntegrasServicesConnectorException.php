<?php
namespace Jb\Integras\Sei\Version\Services\Exception;

use Jb\Integras\Exception\IntegrasException;

class IntegrasServicesConnectorException extends IntegrasException
{
    public static $STR_ERR_MSG_CONNECTOR_FAULT = array(
        'text' => 'Erro ao recuperar os dados',
        'code' => 0x2001
    );

    public static function connectorFault(\Exception $e = null)
    {
        $msg = static::$STR_ERR_MSG_CONNECTOR_FAULT['text'];
        if ($e) {
            $msg .= ' [' . $e->getMessage() . ']';
        }

        throw new self(
            $msg,
            static::STR_ERR_MSG_CONNECTOR_FAULT['code'],
            $e
        );
    }

}
