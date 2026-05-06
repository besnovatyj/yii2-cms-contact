<?php

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\ContactForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ContactForm */
/* @var $model Contact */

$this->title = 'Редактировать: ' . ($model->name ?: $model->email);
$this->params['breadcrumbs'][] = ['label' => 'Адресная книга', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->name ?: $model->email), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body">
        <?php $activeForm = ActiveForm::begin() ?>

        <?= $activeForm->field($form, 'email')->input('email') ?>
        <?= $activeForm->field($form, 'name')->textInput() ?>
        <?= $activeForm->field($form, 'phone')->textInput() ?>
        <?= $activeForm->field($form, 'notes')->textarea(['rows' => 4]) ?>

        <div class="d-flex gap-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>
