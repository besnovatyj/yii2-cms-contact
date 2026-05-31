<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\channels;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\Module;
use RuntimeException;
use Yii;
use yii\mail\MailerInterface;

/**
 * Канал отправки уведомлений по электронной почте.
 */
readonly class EmailChannel implements ChannelInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function getType(): string
    {
        return Module::SEND_METHOD_EMAIL;
    }

    /**
     * @inheritDoc
     */
    public function canHandle(string $sendMethod): bool
    {
        return in_array($sendMethod, [Module::SEND_METHOD_EMAIL, Module::SEND_METHOD_ALL], true);
    }

    /**
     * @inheritDoc
     *
     * @throws RuntimeException
     */
    public function send(Message $message): void
    {
        /** @var Module $module */
        $module = Yii::$app->getModule(Module::MODULE_ID);

        $sendTo  = $module->params['email']['send_to'] ?? '';
        $subject = ($module->params['email']['subject'] ?? ': Отправлено сообщение с сайта');

        // Добавляем название сайта к теме письма
        $appName = Yii::$app->getModule('Config')?->params['frontend']['app']['name'] ?? Yii::$app->name;
        $fullSubject = $appName . $subject;

        $sent = $this->mailer
            ->compose(
                ['html' => 'contact/body-html', 'text' => 'contact/body-text'],
                ['message' => $message]
            )
            ->setTo($sendTo)
            ->setSubject($fullSubject)
            ->send();

        if (!$sent) {
            throw new RuntimeException('Ошибка отправки E-mail.');
        }
    }
}
