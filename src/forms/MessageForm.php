<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Altcha\validators\AltchaValidator;
use Besnovatyj\Forms\BaseForm;
use Yii;

/**
 * Форма отправки сообщения через контактную форму (фронтенд).
 */
class MessageForm extends BaseForm
{
    public ?string $name    = null;
    public ?string $phone   = null;
    public string  $email   = '';
    public ?string $subject = null;
    public string  $body    = '';

    /**
     * Payload ALTCHA (base64 JSON). Заполняется web component'ом автоматически
     * в момент решения proof-of-work задачи.
     * Валидируется только когда модуль Altcha зарегистрирован в приложении.
     */
    public string $altcha = '';

    public function __construct($config = [])
    {
        // Подставляем email авторизованного пользователя
        if (!Yii::$app->user->isGuest) {
            $this->email = Yii::$app->user->identity->email ?? '';
        }
        parent::__construct($config);
    }

    /**
     * Проверяет, зарегистрирован ли модуль Altcha в приложении.
     * Решение принимается на сервере — клиент не может обойти капчу.
     */
    public function isCaptchaRequired(): bool
    {
        return Yii::$app->getModule('Altcha') !== null;
    }

    public function rules(): array
    {
        return [
            [['email', 'body'], 'required'],
            [['name', 'phone', 'subject', 'body'], 'string'],
            ['email', 'email'],
            // Капча: валидируется если модуль Altcha зарегистрирован (решение сервера, не клиента)
            ['altcha', AltchaValidator::class,
                'skipOnEmpty' => false,
                'when' => fn(self $model): bool => $model->isCaptchaRequired(),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'    => 'Ваше имя',
            'email'   => 'E-mail',
            'phone'   => 'Телефон',
            'subject' => 'Тема',
            'body'    => 'Текст сообщения',
        ];
    }
}
