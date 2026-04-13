<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Forms\BaseForm;
use yii\base\Model;

/**
 * Форма отправки письма контакту из адресной книги (только из бэкенда).
 */
class SendMessageForm extends BaseForm
{
    public string  $to_email = '';
    public ?string $to_name  = null;
    public string  $subject  = '';
    public string  $body     = '';

    public function rules(): array
    {
        return [
            [['to_email', 'subject', 'body'], 'required'],
            ['to_email', 'email'],
            [['to_name', 'subject', 'body'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'to_email' => 'Кому (E-mail)',
            'to_name'  => 'Имя получателя',
            'subject'  => 'Тема',
            'body'     => 'Текст письма',
        ];
    }
}
