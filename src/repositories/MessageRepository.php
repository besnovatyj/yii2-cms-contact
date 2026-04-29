<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\repositories;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\DomainEvents\dispatchers\EventDispatcher;
use RuntimeException;
use Throwable;
use yii\db\Exception;

/**
 * Репозиторий для работы с сообщениями контактной формы.
 */
class MessageRepository
{
    public function __construct(private readonly EventDispatcher $dispatcher)
    {
    }

    /**
     * Найти сообщение по ID.
     *
     * @throws NotFoundException
     */
    public function get(int $id): Message
    {
        $message = Message::findOne($id);
        if ($message === null) {
            throw new NotFoundException("Сообщение #{$id} не найдено.");
        }
        return $message;
    }

    /**
     * Сохранить сообщение и задиспетчить накопленные события.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    public function save(Message $message): void
    {
        if (!$message->save()) {
            throw new RuntimeException(
                "Ошибка сохранения сообщения от {$message->email}: {$message->body}"
            );
        }
        $this->dispatcher->dispatchAll($message->releaseEvents());
    }

    /**
     * Удалить сообщение и задиспетчить накопленные события.
     *
     * @throws RuntimeException
     * @throws Throwable
     */
    public function remove(Message $message): void
    {
        if (!$message->delete()) {
            throw new RuntimeException('Ошибка удаления сообщения.');
        }
        $this->dispatcher->dispatchAll($message->releaseEvents());
    }
}
