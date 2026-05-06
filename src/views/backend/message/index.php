<?php

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\forms\MessageSearch;
use Besnovatyj\SwitcherColumn\SwitcherColumn;
use modules\user\components\Helper;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MessageSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;

$sendStatusLabels = [
    Message::SEND_STATUS_PENDING => '<span class="badge bg-warning">Ожидает</span>',
    Message::SEND_STATUS_SENT    => '<span class="badge bg-success">Отправлено</span>',
    Message::SEND_STATUS_FAILED  => '<span class="badge bg-danger">Ошибка</span>',
    Message::SEND_STATUS_NO_SEND => '<span class="badge bg-secondary">Без отправки</span>',
];
?>

<div class="card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                'name',
                'email:email',
                'subject',
                [
                    'attribute' => 'body',
                    'value'     => static function (Message $model) {
                        return Html::a(
                            Html::encode(mb_strimwidth($model->body, 0, 60, '…')),
                            ['view', 'id' => $model->id]
                        );
                    },
                    'format'    => 'raw',
                    'label'     => 'Сообщение',
                ],
                'date:datetime',
                [
                    'attribute' => 'send_status',
                    'value'     => static function (Message $model) use ($sendStatusLabels) {
                        return $sendStatusLabels[$model->send_status] ?? Html::encode($model->send_status);
                    },
                    'format'    => 'raw',
                    'filter'    => [
                        Message::SEND_STATUS_PENDING => 'Ожидает',
                        Message::SEND_STATUS_SENT    => 'Отправлено',
                        Message::SEND_STATUS_FAILED  => 'Ошибка',
                        Message::SEND_STATUS_NO_SEND => 'Без отправки',
                    ],
                ],
                [
                    'class'     => SwitcherColumn::class,
                    'attribute' => 'seen',
                    'filter'    => [1 => 'Просмотрено', 0 => 'Новое'],
                ],
                [
                    'class'    => ActionColumn::class,
                    'template' => Helper::filterActionColumn(['view', 'delete']),
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
