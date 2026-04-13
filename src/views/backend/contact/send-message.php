<?php

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\SendMessageForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form SendMessageForm */
/* @var $contact Contact */

$this->title = 'Написать: ' . ($contact->name ?: $contact->email);
$this->params['breadcrumbs'][] = ['label' => 'Адресная книга', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($contact->name ?: $contact->email), 'url' => ['view', 'id' => $contact->id]];
$this->params['breadcrumbs'][] = 'Написать';
?>

<?= $this->render('_send-message-form', ['form' => $form, 'contact' => $contact]) ?>
