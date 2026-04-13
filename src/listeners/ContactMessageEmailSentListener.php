<?php

namespace Besnovatyj\Contact\listeners;

use Besnovatyj\Contact\entities\events\ContactMessageSent;
use RuntimeException;
use Yii;
use yii\mail\MailerInterface;

class ContactMessageEmailSentListener
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(ContactMessageSent $event): void
    {
        $this->sendEmail($event);
    }
    private function sendEmail(ContactMessageSent $event): void
    {
        $contact = $event->contact;

        $sent = $this->mailer->compose(
                ['html' => 'contact/body-html', 'text' => 'contact/body-text'],
                ['contact' => $contact]
            )
            ->setTo(Yii::$app->getModule('Contact')->params['email']['send_to'])
            ->setSubject(Yii::$app->getModule('Config')->params['frontend']['app']['name'] . Yii::$app->getModule('Contact')->params['email']['subject'])
            ->send();
        if (!$sent) {
            throw new RuntimeException('Ошибка отправки E-mail.');
        }
    }
}














