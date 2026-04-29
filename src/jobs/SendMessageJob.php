<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\jobs;

use Besnovatyj\DomainEvents\jobs\Job;

/**
 * Задание очереди для отправки контактного сообщения через каналы уведомлений.
 *
 * При выполнении передаёт управление в SendMessageJobHandler.
 *
 * @see SendMessageJobHandler
 */
class SendMessageJob extends Job
{
    /**
     * ID сообщения в таблице contact_mails.
     */
    public int $messageId;

    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }
}
