<?php

declare(strict_types=1);

namespace Besnovatyj\Contact;

use common\components\module\BaseModule;

/**
 * Модуль контактных форм и адресной книги.
 */
class Module extends BaseModule
{
    public const bool EDITABLE = true;

    /** Идентификатор модуля в приложении */
    public const string MODULE_ID = 'Contact';

    /** Методы отправки уведомлений о новых сообщениях */
    public const string SEND_METHOD_NONE     = 'none';      // Нет отправки
    public const string SEND_METHOD_ADMIN    = 'admin';     // Только в админке
    public const string SEND_METHOD_EMAIL    = 'email';     // Электронная почта
    public const string SEND_METHOD_TELEGRAM = 'telegram';  // Telegram
    public const string SEND_METHOD_ALL      = 'all';       // Все каналы

    public static function getAdminMenu(): array
    {
        return require __DIR__ . '/config/adminMenu.php';
    }

    public static function getConfig(): array
    {
        return require __DIR__ . '/config/config.php';
    }

    public static function getOptions(): array
    {
        return require __DIR__ . '/config/options.php';
    }

    public static function getDIC(): array
    {
        return require __DIR__ . '/config/dic.php';
    }
}
