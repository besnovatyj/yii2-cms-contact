<?php

use Besnovatyj\Contact\channels\EmailChannel;
use Besnovatyj\Contact\channels\TelegramChannel;
use Besnovatyj\Contact\jobs\SendMessageJobHandler;
use Besnovatyj\Contact\repositories\ContactRepository;
use Besnovatyj\Contact\repositories\MessageRepository;
use Besnovatyj\Contact\services\AddressBookService;
use Besnovatyj\Contact\services\MessageSender;
use Besnovatyj\Contact\services\MessageService;
use common\components\dispatcher\dispatchers\SimpleEventDispatcher;

/**
 * DIC-конфигурация модуля Contact.
 *
 * Возвращает массив вида:
 *   [
 *     'singletons' => [ className => definition, ... ],
 *   ]
 *
 * Читается в Bootstrap::bootstrap() и регистрируется в Yii::$container.
 *
 * Добавить новый канал отправки:
 *   1. Создать класс, реализующий ChannelInterface
 *   2. Зарегистрировать его синглтон здесь
 *   3. Добавить его в массив каналов MessageSender ниже
 */
return [
    'singletons' => [

        // --- Каналы отправки ---
        EmailChannel::class => static function () {
            // EmailChannel требует MailerInterface из Yii::$app->mailer
            return new EmailChannel(\Yii::$app->mailer);
        },
        TelegramChannel::class => TelegramChannel::class,

        // --- MessageSender получает все зарегистрированные каналы ---
        // Чтобы добавить новый канал — просто внесите его сюда
        MessageSender::class => static function () {
            return new MessageSender([
                \Yii::$container->get(EmailChannel::class),
                \Yii::$container->get(TelegramChannel::class),
            ]);
        },

        // --- Репозитории ---
        MessageRepository::class => static function () {
            return new MessageRepository(
                \Yii::$container->get(SimpleEventDispatcher::class)
            );
        },
        ContactRepository::class => ContactRepository::class,

        // --- Сервисы ---
        MessageService::class => static function () {
            return new MessageService(
                \Yii::$container->get(MessageRepository::class)
            );
        },
        AddressBookService::class => static function () {
            return new AddressBookService(
                \Yii::$container->get(ContactRepository::class)
            );
        },

        // --- Queue job handler ---
        SendMessageJobHandler::class => static function () {
            return new SendMessageJobHandler(
                \Yii::$container->get(MessageSender::class)
            );
        },

    ],
];
