<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Contact\entities\Contact;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Contact */

$this->title = $model->name ?: $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Адресная книга', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <div class="card-title me-auto"><?= Html::encode($this->title) ?></div>
        <div class="card-tools d-flex gap-2">
            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>"
               class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <!-- Кнопка "Написать" — загружает форму через HTMX -->
            <button class="btn btn-sm btn-primary"
                    hx-get="<?= Url::to(['send-message', 'id' => $model->id]) ?>"
                    hx-target="#send-message-panel"
                    hx-swap="innerHTML">
                <i class="bi bi-envelope"></i> Написать
            </button>
        </div>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model'      => $model,
            'attributes' => [
                'id',
                ['label' => 'E-mail', 'value' => $model->email, 'format' => 'email'],
                'name:text:Имя',
                'phone:text:Телефон',
                'notes:text:Заметки',
                'created_at:datetime:Добавлен',
                'updated_at:datetime:Обновлён',
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix"></div>
</div>

<!-- Панель для HTMX-формы отправки письма -->
<div id="send-message-panel" class="mt-3"></div>
