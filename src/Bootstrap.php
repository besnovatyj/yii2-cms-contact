<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact;

use Besnovatyj\Contact\entities\events\MessageSent;
use Besnovatyj\Contact\listeners\MessageSentListener;
use Besnovatyj\DomainEvents\dispatchers\SimpleEventDispatcher;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

/**
 * Bootstrap модуля Contact.
 *
 * Регистрирует DIC-зависимости и слушателей событий.
 * Должен выполняться при старте приложения — указать в composer.json:
 *   "extra": { "bootstrap": "Besnovatyj\\Contact\\Bootstrap" }
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function bootstrap($app): void
    {
        // 1. Регистрация DI-зависимостей из dependencies.php
        $dependencies = Module::getDIC();

        foreach ($dependencies['singletons'] ?? [] as $abstract => $concrete) {
            Yii::$container->setSingleton($abstract, $concrete);
        }

        // 2. Регистрация единственного слушателя событий.
        //    MessageSentListener сам решает: сохранить в адресную книгу + поставить job в очередь.
        /** @var SimpleEventDispatcher $dispatcher */
        $dispatcher = Yii::$container->get(SimpleEventDispatcher::class);
        $dispatcher->listen(MessageSent::class, MessageSentListener::class);
    }
}
