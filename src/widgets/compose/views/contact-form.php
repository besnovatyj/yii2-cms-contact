<?php

declare(strict_types=1);

use Besnovatyj\Altcha\widgets\AltchaWidget;
use Besnovatyj\Contact\forms\MessageForm;
use Besnovatyj\Contact\widgets\compose\ComposeWidget;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * Шаблон виджета формы отправки сообщения (Bootstrap 5).
 *
 * @var View  $this
 * @var ComposeWidget  $widget
 * @var MessageForm    $form
 */

// ─── Атрибуты корневого блока-обёртки ────────────────────────────────────────

$wrapperOptions         = $widget->options;
$wrapperOptions['class'] = trim(
    $widget->wrapperClass . (isset($wrapperOptions['class']) ? ' ' . $wrapperOptions['class'] : '')
);

?>
<div <?= Html::renderTagAttributes($wrapperOptions) ?>>

    <?php // ─── Заголовок ─────────────────────────────────────────────────────────── ?>

    <?php if ($widget->title !== ''): ?>
        <h2 class="mb-2"><?= Html::encode($widget->title) ?></h2>
    <?php endif ?>

    <?php if ($widget->subtitle !== null): ?>
        <p class="text-muted mb-4"><?= Html::encode($widget->subtitle) ?></p>
    <?php endif ?>

    <?php // ─── Форма ────────────────────────────────────────────────────────────── ?>

    <?php $activeForm = ActiveForm::begin([
        'action'  => Url::to(['/Contact/message/index']),
        'method'  => 'post',
        'options' => ['novalidate' => true],
    ]) ?>

        <?php if ($widget->showName): ?>
            <?= $activeForm->field($form, 'name')->textInput([
                'placeholder'  => 'Ваше имя',
                'autocomplete' => 'name',
            ]) ?>
        <?php endif ?>

        <?= $activeForm->field($form, 'email')->input('email', [
            'placeholder'  => 'Email',
            'autocomplete' => 'email',
        ]) ?>

        <?php if ($widget->showPhone): ?>
            <?= $activeForm->field($form, 'phone')->input('tel', [
                'placeholder'  => '+7 (___) ___-__-__',
                'autocomplete' => 'tel',
            ]) ?>
        <?php endif ?>

        <?php if ($widget->showSubject): ?>
            <?= $activeForm->field($form, 'subject')->textInput([
                'placeholder' => 'Тема сообщения',
            ]) ?>
        <?php endif ?>

        <?= $activeForm->field($form, 'body')->textarea([
            'rows'        => 5,
            'placeholder' => 'Текст вашего сообщения...',
        ]) ?>

        <?php // ─── ALTCHA captcha ──────── ?>

            <div class="mt-3">
                <?= AltchaWidget::widget([
                    'model'     => $form,
                    'attribute' => 'altcha',
                ]) ?>
            </div>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-start mt-3">
            <?= Html::submitButton(Html::encode($widget->submitLabel), [
                'class' => 'btn btn-primary',
            ]) ?>
        </div>

    <?php ActiveForm::end() ?>

    <?php // ─── Внутренний контент шорткода ─────────────────────────────────────── ?>

    <?php if (!empty($widget->content)): ?>
        <div class="contact-compose-extra mt-3">
            <?= $widget->content ?>
        </div>
    <?php endif ?>

</div>
