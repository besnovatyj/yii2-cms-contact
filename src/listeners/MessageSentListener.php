<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\listeners;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\entities\events\MessageSent;
use Besnovatyj\Contact\jobs\SendMessageJob;
use Besnovatyj\Contact\Module;
use Besnovatyj\Contact\services\AddressBookService;
use Throwable;
use Yii;

/**
 * Главный слушатель события MessageSent.
 *
 * Выполняет два действия:
 *  1. Синхронизирует адресную книгу (всегда).
 *  2. Если send_method требует внешней отправки — помещает SendMessageJob в очередь.
 *     Если send_method = none/admin — сразу обновляет статус на no_send.
 */
readonly class MessageSentListener
{
    public function __construct(private AddressBookService $addressBookService)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->getMessage();

        // 1. Сохранить отправителя в адресную книгу
        try {
            $this->addressBookService->syncFromMessage($message);
        } catch (Throwable $e) {
            Yii::error('MessageSentListener: ошибка синхронизации адресной книги: ' . $e->getMessage(), __CLASS__);
        }

        // 2. Определить нужна ли внешняя отправка
        $sendMethod = $message->send_method ?? Module::SEND_METHOD_ADMIN;

        if ($this->requiresExternalSend($sendMethod)) {
            Yii::$app->queue->push(new SendMessageJob($message->id));
        } else {
            // Не нужна внешняя отправка — сразу ставим статус
            $message->updateSendStatus(Message::SEND_STATUS_NO_SEND);
            $message->save(false);
        }
    }

    /**
     * Проверяет, нужна ли внешняя отправка для данного метода.
     */
    private function requiresExternalSend(string $sendMethod): bool
    {
        return !in_array($sendMethod, [
            Module::SEND_METHOD_NONE,
            Module::SEND_METHOD_ADMIN,
        ], true);
    }
}
