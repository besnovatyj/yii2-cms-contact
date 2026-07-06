<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\ContactSearch;
use Besnovatyj\User\components\Helper;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Адресная книга';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Панель для HTMX-формы отправки письма -->
<div id="send-message-panel" class="mt-3"></div>
<div class="card">
    <div class="card-header d-flex align-items-center">
        <div class="card-title me-auto"><?= $this->title ?></div>
        <?php if (Helper::checkRoute('contact/create')): ?>
            <a href="<?= Url::to(['create']) ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить контакт
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                'name:text:Имя',
                'email:email:E-mail',
                'phone:text:Телефон',
                'notes:text:Заметки',
                'created_at:datetime:Добавлен',
                [
                    'class'    => ActionColumn::class,
                    'template' => Helper::filterActionColumn(['view', 'update', 'delete']) . ' {write}',
                    'buttons'  => [
                        'write' => static function ($url, Contact $model) {
                            return Html::a(
                                '<i class="bi bi-envelope"></i>',
                                ['send-message', 'id' => $model->id],
                                [
                                    'title'        => 'Написать',
                                    'class'        => 'btn btn-sm btn-outline-secondary',
                                    // HTMX: открыть форму в #send-message-panel без перезагрузки
                                    'hx-get'       => Url::to(['send-message', 'id' => $model->id]),
                                    'hx-target'    => '#send-message-panel',
                                    'hx-swap'      => 'innerHTML',
                                    'hx-push-url'  => 'false',
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
    <div class="card-footer clearfix">
        <nav aria-label="Навигация" class="nav-pagination">
            <?= LinkPager::widget([
                'pagination' => $dataProvider->getPagination(),
            ]) ?>
        </nav>
    </div>
</div>

