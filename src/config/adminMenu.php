<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Contracts\adminMenu\AdminMenuLocation;
use Besnovatyj\Contracts\adminMenu\AdminMenuPlacement;

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
                new AdminMenuPlacement(
                    location: AdminMenuLocation::LeftSidebar,
                    group: 'Contact',
                    groupIcon: 'bi bi-envelope-at',
                    groupPriority: 100,
                    priority: 100,
                ),
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
                new AdminMenuPlacement(
                    location: AdminMenuLocation::LeftSidebar,
                    group: 'Contact',
                    groupIcon: 'bi bi-envelope-at',
                    groupPriority: 100,
                    priority: 200,
                ),
            ],
        ],
    ],
];
