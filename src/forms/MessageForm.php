<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Hcaptcha\validators\HCaptchaValidator;
use Yii;
use yii\base\Model;

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
     * Токен hCaptcha. Заполняется JS-менеджером из бандла hcaptcha-виджета.
     * Валидируется только когда captchaEnabled === '1'.
     */
    public string $captchaToken = '';

    /**
     * Признак того, что форма была отрендерена с капчей (hidden-поле из ComposeWidget).
     * '1' — капча показана и обязательна; '' / '0' — капча не использовалась.
     */
    public string $captchaEnabled = '0';

    public function __construct($config = [])
    {
        // Подставляем email авторизованного пользователя
        if (!Yii::$app->user->isGuest) {
            $this->email = Yii::$app->user->identity->email ?? '';
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['email', 'body'], 'required'],
            [['name', 'phone', 'subject', 'body'], 'string'],
            ['email', 'email'],
            // Капча: валидируется только если форма была отрендерена с капчей
            ['captchaEnabled', 'boolean'],
            ['captchaToken', HCaptchaValidator::class,
                'skipOnEmpty' => false,
                'when' => static fn(self $model): bool => $model->captchaEnabled === '1',
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
