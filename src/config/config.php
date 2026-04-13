<?php
return [
    'id'     => 'Contact',
    'params' => [
        'iconClass' => 'bi bi-envelope-at',

        /**
         * Метод отправки уведомлений о новых сообщениях.
         * Одна из констант Module::SEND_METHOD_*:
         *   'none'      — не отправлять, только сохранять в БД
         *   'admin'     — только в админке (семантически явный вариант none)
         *   'email'     — отправлять по электронной почте
         *   'telegram'  — отправлять в Telegram
         *   'all'       — отправлять всеми доступными каналами одновременно
         */
        'send_method' => 'admin',

        'telegram' => [
            'token'   => '', // contact_telegram_token
            'chat_id' => '', // contact_telegram_chat_id
        ],

        'email' => [
            'send_to' => '',
            'subject' => ': Отправлено сообщение с сайта',
        ],

        'directories' => false, // Если для работы модуля необходимы директории для статики
    ],
];
