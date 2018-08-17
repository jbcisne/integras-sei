<?php
namespace Jb\Integras\Sei\Version\Services\Exception;

use Jb\Integras\Exception\IntegrasException;

class IntegrasServicesArgumentException extends IntegrasException
{
    public static $STR_ERR_MSG_ARGUMENT_REQUIRED = array(
        'text' => 'O argumento \'%s\' é obrigatório',
        'code' => 0x3001
    );

    public static $STR_ERR_MSG_ARGUMENT_TYPE_FAIL = array(
        'text' => 'O argumento \'%s\' deve ser um %s.',
        'code' => 0x3002
    );

    public static function argumentRequired($argumentName)
    {
        throw new self(
            sprintf(static::$STR_ERR_MSG_ARGUMENT_REQUIRED['text'], $argumentName),
            static::$STR_ERR_MSG_ARGUMENT_REQUIRED['code']
        );
    }

    public static function argumentTypeFail($argumentName, $argumentType = 'array')
    {
        throw new self(
            sprintf(static::$STR_ERR_MSG_ARGUMENT_TYPE_FAIL['text'], $argumentName, $argumentType),
            static::$STR_ERR_MSG_ARGUMENT_TYPE_FAIL['code']
        );
    }

}
