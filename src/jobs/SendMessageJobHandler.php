<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\jobs;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\services\MessageSender;
use Throwable;
use Yii;
use yii\db\Exception;

/**
 * Обработчик задания SendMessageJob.
 *
 * Получает сообщение из БД, передаёт в MessageSender и обновляет статус отправки.
 */
readonly class SendMessageJobHandler
{
    public function __construct(private MessageSender $sender)
    {
    }

    /**
     * @param SendMessageJob $job
     * @throws Exception
     */
    public function handle(SendMessageJob $job): void
    {
        $message = Message::findOne($job->messageId);

        if ($message === null) {
            Yii::error("SendMessageJob: сообщение #{$job->messageId} не найдено.", __CLASS__);
            return;
        }

        try {
            $this->sender->send($message);
            $message->updateSendStatus(Message::SEND_STATUS_SENT);
        } catch (Throwable $e) {
            Yii::error(
                "SendMessageJob: ошибка отправки сообщения #{$job->messageId}: " . $e->getMessage(),
                __CLASS__
            );
            $message->updateSendStatus(Message::SEND_STATUS_FAILED);
        } finally {
            $message->save(false);
        }
    }
}
