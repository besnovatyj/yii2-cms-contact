<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact;

use Besnovatyj\Kernel\module\CmsModule;
use Besnovatyj\Contracts\module\DeclaresModule;
use Besnovatyj\Contracts\module\ProvidesAdminMenu;
use Besnovatyj\Contracts\module\ProvidesMigrations;
use Besnovatyj\Contracts\module\ProvidesOptions;

/**
 * Модуль контактных форм и адресной книги.
 */
class Module extends CmsModule implements
    DeclaresModule, ProvidesAdminMenu,
    ProvidesMigrations, ProvidesOptions
{
    public const bool EDITABLE = true;
    public const string VERSION = '1.0.0';
    public const string MODULE_ID = 'Contact';

    /** Методы отправки уведомлений о новых сообщениях */
    public const string SEND_METHOD_NONE     = 'none';      // Нет отправки
    public const string SEND_METHOD_ADMIN    = 'admin';     // Только в админке
    public const string SEND_METHOD_EMAIL    = 'email';     // Электронная почта
    public const string SEND_METHOD_TELEGRAM = 'telegram';  // Telegram
    public const string SEND_METHOD_ALL      = 'all';       // Все каналы

    public static function getDIC(): array
    {
        // TODO 'config/container.php' or extra.bootstrap ? А еще рядом есть ootstrap.php, в общем, надо разобраться, куда какие бутстрапы, может компонент придумать? Тут и консоль и очереди и свои DIC настройки
        return require __DIR__ . '/config/dic.php';
    }
    public static function moduleId(): string { return self::MODULE_ID; }
    public static function moduleVersion(): string { return self::VERSION; }
    public static function isEditable(): bool { return self::EDITABLE; }
    public static function adminMenu(): array { return require __DIR__.'/config/adminMenu.php'; }
    public static function moduleConfig(): array { return require __DIR__.'/config/config.php'; }
    public static function options(): array { return require __DIR__.'/config/options.php'; }
    public static function migrationPath(): string { return __DIR__.'/migrations'; }
    public static function migrationNamespace(): ?string { return __NAMESPACE__.'\\migrations'; }

}
