<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Contact\entities\Message;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Message */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$sendStatusLabels = [
    Message::SEND_STATUS_PENDING => '<span class="badge bg-warning">Ожидает</span>',
    Message::SEND_STATUS_SENT    => '<span class="badge bg-success">Отправлено</span>',
    Message::SEND_STATUS_FAILED  => '<span class="badge bg-danger">Ошибка отправки</span>',
    Message::SEND_STATUS_NO_SEND => '<span class="badge bg-secondary">Без отправки</span>',
];
?>

<div class="card">
    <div class="card-header d-md-flex justify-content-md-between">
        <div><?= Html::encode($this->title) ?></div>
        <div class="card-tools">
            <?php if ($model->isNew()): ?>
                <a href="<?= Url::to(['/Contact/backend/message/seen', 'id' => $model->id]) ?>"
                   class="btn btn-sm btn-danger">Новое</a>
            <?php else: ?>
                <a href="<?= Url::to(['/Contact/backend/message/un-seen', 'id' => $model->id]) ?>"
                   class="btn btn-sm btn-success">Просмотрено</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model'      => $model,
            'attributes' => [
                'id',
                'name:text:Имя',
                ['label' => 'E-mail', 'value' => $model->email, 'format' => 'email'],
                'phone:text:Телефон',
                'subject:text:Тема',
                'body:html:Сообщение',
                'date:datetime:Дата',
                [
                    'label'  => 'Статус отправки',
                    'value'  => $sendStatusLabels[$model->send_status] ?? Html::encode($model->send_status),
                    'format' => 'raw',
                ],
                'send_method:text:Метод отправки',
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix"></div>
</div>
