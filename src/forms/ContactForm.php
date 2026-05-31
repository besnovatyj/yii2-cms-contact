<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Forms\BaseForm;
use yii\base\Model;

/**
 * Форма создания/редактирования записи адресной книги.
 */
class ContactForm extends BaseForm
{
    public string  $email = '';
    public ?string $name  = null;
    public ?string $phone = null;
    public ?string $notes = null;

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [['name', 'phone', 'notes'], 'string'],
            [['name', 'phone', 'notes'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'E-mail',
            'name'  => 'Имя',
            'phone' => 'Телефон',
            'notes' => 'Заметки',
        ];
    }
}
