<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\widgets\dashboard;

use Besnovatyj\Contact\entities\Message;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Плитка дашборда: число непрочитанных (новых) сообщений контактных форм со ссылкой к списку.
 *
 * Рендерит только тело карточки — «каркас» рисует модуль дашборда.
 */
class UnreadMessagesTile extends Widget
{
    public function run(): string
    {
        $unread = (int)Message::find()->where(['seen' => Message::VIEW_STATUS_NEW])->count();

        $colorClass = $unread > 0 ? 'text-warning' : 'text-muted';
        $counter = Html::tag('div',
            Html::tag('span', (string)$unread, ['class' => "display-6 fw-bold lh-1 {$colorClass}"])
            . Html::tag('span', 'непрочитанных', ['class' => 'text-muted ms-2']),
            ['class' => 'd-flex align-items-baseline']);

        $link = Html::a(
            '<i class="bi bi-envelope me-1"></i>К сообщениям',
            Url::to(['/Contact/backend/message/index']),
            ['class' => 'btn btn-sm btn-outline-primary mt-3']
        );

        return $counter . $link;
    }
}
