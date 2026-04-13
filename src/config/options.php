<?php
// Все опции должны быть определены при установке (подключении) модуля в приложение
return [

    // Метод отправки
    'contact_send_method' => [
        'path'        => 'modules.Contact.params.send_method',
        'label'       => '[Contact] Метод отправки уведомлений',
        'description' => "Yii::\$app->getModule('Contact')->params['send_method']",
        'category'    => 'Contact',
        'rules'       => [
            ['required'],
            ['in', 'range' => ['none', 'admin', 'email', 'telegram', 'all']],
        ],
        'inputOptions' => [
            'type'  => 'dropdown',
            'items' => [
                'none'     => 'Нет отправки (только сохранить в БД)',
                'admin'    => 'Только в админке',
                'email'    => 'Электронная почта',
                'telegram' => 'Telegram',
                'all'      => 'Всё вместе (email + Telegram)',
            ],
        ],
    ],

    // Telegram
    'contact_telegram_token' => [
        'path'        => 'modules.Contact.params.telegram.token',
        'label'       => '[Contact] Telegram API token (уникальный ключ бота)',
        'description' => "Yii::\$app->getModule('Contact')->params['telegram']['token']",
        'category'    => 'Contact',
        'rules'       => [],
        'inputOptions' => ['type' => 'input'],
    ],
    'contact_telegram_chat_id' => [
        'path'        => 'modules.Contact.params.telegram.chat_id',
        'label'       => '[Contact] Telegram chat ID для отправки сообщений',
        'description' => "Yii::\$app->getModule('Contact')->params['telegram']['chat_id']",
        'category'    => 'Contact',
        'rules'       => [],
        'inputOptions' => ['type' => 'input'],
    ],

    // Email
    'contact_email_send_to' => [
        'path'        => 'modules.Contact.params.email.send_to',
        'label'       => '[Contact] Email-адрес для получения сообщений',
        'description' => "Yii::\$app->getModule('Contact')->params['email']['send_to']",
        'category'    => 'Contact',
        'rules'       => [['email']],
        'inputOptions' => ['type' => 'input'],
    ],
    'contact_email_subject' => [
        'path'        => 'modules.Contact.params.email.subject',
        'label'       => '[Contact] Суффикс темы письма (добавляется к названию сайта)',
        'description' => "Yii::\$app->getModule('Contact')->params['email']['subject']",
        'category'    => 'Contact',
        'rules'       => [],
        'inputOptions' => ['type' => 'input'],
    ],
];
