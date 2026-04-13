<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\channels;

use Besnovatyj\Contact\entities\Message;

/**
 * Интерфейс канала отправки уведомлений о контактных сообщениях.
 *
 * Каждая реализация отвечает за один способ отправки (email, Telegram и т.д.).
 * Каналы регистрируются в DIC и передаются в MessageSender.
 */
interface ChannelInterface
{
    /**
     * Возвращает идентификатор канала (например, 'email', 'telegram').
     * Используется MessageSender для выборки каналов по send_method.
     */
    public function getType(): string;

    /**
     * Проверяет, должен ли данный канал обрабатывать указанный метод отправки.
     *
     * @param string $sendMethod  Одна из констант Module::SEND_METHOD_*
     */
    public function canHandle(string $sendMethod): bool;

    /**
     * Отправить уведомление о новом сообщении.
     *
     * @param Message $message
     * @throws \RuntimeException  При ошибке отправки
     */
    public function send(Message $message): void;
}
