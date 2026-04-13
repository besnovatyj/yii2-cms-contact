<?php

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\SendMessageForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $form SendMessageForm */
/* @var $contact Contact */

$formId = 'send-message-form-' . $contact->id;
$action = Url::to(['send-message', 'id' => $contact->id]);
?>

<div class="card rounded-0 border-primary">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <span class="me-auto">
            <i class="bi bi-envelope"></i>
            Письмо для <?= Html::encode($contact->name ?: $contact->email) ?>
        </span>
        <!-- Закрыть панель -->
        <button type="button" class="btn-close btn-close-white"
                hx-on:click="document.getElementById('send-message-panel').innerHTML = ''"></button>
    </div>
    <div class="card-body">
        <form id="<?= $formId ?>"
              hx-post="<?= $action ?>"
              hx-target="#send-message-panel"
              hx-swap="innerHTML">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

            <div class="mb-3">
                <label class="form-label">Кому</label>
                <input type="text" class="form-control" value="<?= Html::encode($contact->name ?: $contact->email) ?>" disabled>
                <?= Html::hiddenInput('SendMessageForm[to_email]', $contact->email) ?>
                <?= Html::hiddenInput('SendMessageForm[to_name]', $contact->name) ?>
            </div>

            <div class="mb-3">
                <label class="form-label" for="<?= $formId ?>-subject">
                    Тема <span class="text-danger">*</span>
                </label>
                <input type="text" id="<?= $formId ?>-subject"
                       name="SendMessageForm[subject]"
                       class="form-control <?= $form->hasErrors('subject') ? 'is-invalid' : '' ?>"
                       value="<?= Html::encode($form->subject) ?>"
                       required>
                <?php if ($form->hasErrors('subject')): ?>
                    <div class="invalid-feedback"><?= Html::encode($form->getFirstError('subject')) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label" for="<?= $formId ?>-body">
                    Текст <span class="text-danger">*</span>
                </label>
                <textarea id="<?= $formId ?>-body"
                          name="SendMessageForm[body]"
                          class="form-control <?= $form->hasErrors('body') ? 'is-invalid' : '' ?>"
                          rows="6"
                          required><?= Html::encode($form->body) ?></textarea>
                <?php if ($form->hasErrors('body')): ?>
                    <div class="invalid-feedback"><?= Html::encode($form->getFirstError('body')) ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Отправить
                </button>
                <button type="button" class="btn btn-secondary"
                        hx-on:click="document.getElementById('send-message-panel').innerHTML = ''">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>
