<?php
namespace Jb\Integras\Sei\Exception;

use \Jb\Integras\Exception\IntegrasConfigParamenterException;

class IntegrasConfigParamenterSEIException extends IntegrasConfigParamenterException
{
    public static $STR_ERR_MSG_VERSION_NOT_FOUND = array(
        'text' => 'Versão do SEI não informada ou inválida',
        'code' => 0x0001
    );

    public static $STR_ERR_MSG_INVALID_TYPE_PARAMETER = array(
        'text' => 'É esperado um Array|Object como argumento',
        'code' => 0x0002
    );

    public static $STR_ERR_MSG_INVALID_SYSTEM_EXTERNAL = array(
        'text' => 'É esperado o identificador do sistema que fará acesso ao SEI',
        'code' => 0x0003
    );

    public static $STR_ERR_MSG_INVALID_SERVICE_GROUPER = array(
        'text' => 'É esperado o identificador do agrupador de serviços que fará acesso ao SEI',
        'code' => 0x0004
    );

    public static function invalidContentParamenter()
    {
        throw new self(
            static::$STR_ERR_MSG_INVALID_TYPE_PARAMETER['text'],
            static::$STR_ERR_MSG_INVALID_TYPE_PARAMETER['code']
        );
    }

    public static function versionNotFound()
    {
        throw new self(
            static::$STR_ERR_MSG_VERSION_NOT_FOUND['text'],
            static::$STR_ERR_MSG_VERSION_NOT_FOUND['code']
        );
    }

    public static function systemExternalNotFound()
    {
        throw new self(
            static::$STR_ERR_MSG_INVALID_SYSTEM_EXTERNAL['text'],
            static::$STR_ERR_MSG_INVALID_SYSTEM_EXTERNAL['code']
        );
    }

    public static function serviceGrouperNotFound()
    {
        throw new self(
            static::$STR_ERR_MSG_INVALID_SERVICE_GROUPER['text'],
            static::$STR_ERR_MSG_INVALID_SERVICE_GROUPER['code']
        );
    }
}
