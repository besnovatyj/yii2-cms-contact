<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\entities\events;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\DomainEvents\EntityEvent;

/**
 * Событие: новое сообщение через контактную форму сохранено в БД.
 * Диспетчится из MessageRepository после успешного сохранения.
 */
class MessageSent extends EntityEvent
{
    /**
     * @param Message $entity
     */
    public function __construct(Message $entity)
    {
        parent::__construct($entity);
    }

    /**
     * @inheritDoc
     */
    protected function findEntity(int $id): ?Message
    {
        return Message::findOne($id);
    }

    /**
     * Возвращает сущность сообщения.
     */
    public function getMessage(): Message
    {
        /** @var Message */
        return $this->getEntity();
    }
}
