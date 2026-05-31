<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Contact\forms\ContactForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ContactForm */

$this->title = 'Добавить контакт';
$this->params['breadcrumbs'][] = ['label' => 'Адресная книга', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <?php $activeForm = ActiveForm::begin() ?>

        <?= $activeForm->field($form, 'email')->input('email') ?>
        <?= $activeForm->field($form, 'name')->textInput() ?>
        <?= $activeForm->field($form, 'phone')->textInput() ?>
        <?= $activeForm->field($form, 'notes')->textarea(['rows' => 4]) ?>

        <div class="d-flex gap-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>
