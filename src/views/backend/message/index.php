<?php

use backend\widgets\grid\ActionColumn;
use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\forms\MessageSearch;
use common\widgets\grid\SwitcherColumn;
use modules\user\components\Helper;
use yii\bootstrap5\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;

$sendStatusLabels = [
    Message::SEND_STATUS_PENDING => '<span class="badge bg-warning">Ожидает</span>',
    Message::SEND_STATUS_SENT    => '<span class="badge bg-success">Отправлено</span>',
    Message::SEND_STATUS_FAILED  => '<span class="badge bg-danger">Ошибка</span>',
    Message::SEND_STATUS_NO_SEND => '<span class="badge bg-secondary">Без отправки</span>',
];
?>

<div class="card rounded-0">
    <div class="card-header">
        <h3 class="card-title"><?= $this->title ?></h3>
    </div>
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
