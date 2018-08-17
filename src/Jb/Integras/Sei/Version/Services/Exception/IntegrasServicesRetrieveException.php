<?php
namespace Jb\Integras\Sei\Version\Services\Exception;

use Jb\Integras\Exception\IntegrasException;

class IntegrasServicesRetrieveException extends IntegrasException
{
    public static $STR_ERR_MSG_RETRIEVE_FAULT = array(
        'text' => 'Erro ao recuperar os dados',
        'code' => 0x1001
    );

    public static function retrieveFault(\SoapFault $e = null)
    {
        $msg = static::$STR_ERR_MSG_RETRIEVE_FAULT['text'];
        if ($e) {
            $msg .= ' [' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . ']';
        }
        throw new self(
            $msg,
            static::$STR_ERR_MSG_RETRIEVE_FAULT['code'],
            $e
        );
    }


}
