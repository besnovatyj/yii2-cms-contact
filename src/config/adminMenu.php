<?php
return [
    // Messages
    [
        'label'     => 'Сообщения',
        'iconClass' => 'bi bi-card-text me-1',
        'url'       => ['/Contact/backend/message/index'],
        'active'    => static function () {
            return str_contains(\Yii::$app->request->url, 'Contact/backend/message');
        },
        '_meta' => [
            'placements' => [
                [
                    'location'      => 'left-sidebar',
                    'group'         => 'Contact',
                    'groupIcon'     => 'bi bi-envelope-at',
                    'priority'      => 100,
                    'groupPriority' => 50,
                ],
            ],
        ],
    ],
    // Address Book
    [
        'label'     => 'Адресная книга',
        'iconClass' => 'bi bi-person-lines-fill me-1',
        'url'       => ['/Contact/backend/contact/index'],
        'active'    => static function () {
            return str_contains(\Yii::$app->request->url, 'Contact/backend/contact');
        },
        '_meta' => [
            'placements' => [
                [
                    'location'      => 'left-sidebar',
                    'group'         => 'Contact',
                    'groupIcon'     => 'bi bi-envelope-at',
                    'priority'      => 200,
                    'groupPriority' => 50,
                ],
            ],
        ],
    ],
];
