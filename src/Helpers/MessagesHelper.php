<?php

declare(strict_types=1);

namespace App\Helpers;

class MessagesHelper
{
    public const DEFAULT_MESSAGE = '00';
    public const DB_SAVING_ERROR_MESSAGE = '01';
    public const DB_CONNECTION_ERROR_MESSAGE = '02';
    public const USER_NOT_FOUND_MESSAGE = '03';
    public const USER_ROLE_EXISTS_MESSAGE = '04';
    public const USER_NEW_ROLE_MESSAGE = '05';
    public const USER_NEW_USER_MESSAGE = '06';

    public const MESSAGES_LIBRARY = [
        self::DEFAULT_MESSAGE => 'Nao catalogado',
        self::DB_SAVING_ERROR_MESSAGE => 'Ocorreu um erro durante o salvamento',
        self::DB_CONNECTION_ERROR_MESSAGE => 'Problemas de conexão com o banco de dados',
        self::USER_NOT_FOUND_MESSAGE => 'Usuario nao encontrado',
        self::USER_ROLE_EXISTS_MESSAGE => 'Role ja configurada para o usuario',
        self::USER_NEW_ROLE_MESSAGE => 'Role adicionada ao usuario',
        self::USER_NEW_USER_MESSAGE => 'Usuario criado com sucesso'
    ];

    public function getMessage(string $code): string
    {
        $message = self::MESSAGES_LIBRARY[self::DEFAULT_MESSAGE];

        if (array_key_exists($code, self::MESSAGES_LIBRARY)) {
            $message = self::MESSAGES_LIBRARY[$code];
        }

        return $message;
    }
}
