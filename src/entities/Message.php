<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\entities;

use Besnovatyj\Contact\entities\events\MessageSent;
use Besnovatyj\DomainEvents\AggregateRoot;
use Besnovatyj\DomainEvents\EventTrait;
use DomainException;
use yii\db\ActiveRecord;

/**
 * Сообщение, отправленное через контактную форму.
 *
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $subject
 * @property string $body
 * @property int $date
 * @property int $seen
 * @property string $send_status
 * @property string|null $send_method
 */
class Message extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    /** Статус просмотра администратором */
    public const int VIEW_STATUS_NEW  = 0;
    public const int VIEW_STATUS_SEEN = 1;

    /** Статус отправки через каналы уведомлений */
    public const string SEND_STATUS_PENDING = 'pending';
    public const string SEND_STATUS_SENT    = 'sent';
    public const string SEND_STATUS_FAILED  = 'failed';
    public const string SEND_STATUS_NO_SEND = 'no_send';

    /**
     * Фабричный метод создания нового сообщения.
     *
     * @param string|null $name
     * @param string $email
     * @param string|null $phone
     * @param string|null $subject
     * @param string $body
     * @param string $sendMethod  Метод отправки, актуальный на момент создания
     * @return self
     */
    public static function create(
        ?string $name,
        string  $email,
        ?string $phone,
        ?string $subject,
        string  $body,
        string  $sendMethod,
    ): self {
        $message = new static();
        $message->name        = $name;
        $message->email       = $email;
        $message->phone       = $phone;
        $message->subject     = $subject;
        $message->body        = $body;
        $message->date        = new \DateTimeImmutable()->format('Y.m.d H:i:s');
        $message->seen        = self::VIEW_STATUS_NEW;
        $message->send_method = $sendMethod;
        $message->send_status = self::SEND_STATUS_PENDING;
        $message->recordEvent(new MessageSent($message)); // TODO: Вынос в сервис `$entity->recordEvent()`. Там после этого `$repo->save();`
        return $message;
    }

    /**
     * Возвращает true, если сообщение ещё не просмотрено администратором.
     */
    public function isNew(): bool
    {
        return $this->seen === self::VIEW_STATUS_NEW;
    }

    /**
     * Отметить сообщение как просмотренное.
     *
     * @throws DomainException
     */
    public function markSeen(): void
    {
        if (!$this->isNew()) {
            throw new DomainException('Сообщение уже просмотрено.');
        }
        $this->seen = self::VIEW_STATUS_SEEN;
    }

    /**
     * Отметить сообщение как непросмотренное.
     *
     * @throws DomainException
     */
    public function markUnseen(): void
    {
        if ($this->isNew()) {
            throw new DomainException('Сообщение уже отмечено как новое.');
        }
        $this->seen = self::VIEW_STATUS_NEW;
    }

    /**
     * Обновить статус отправки.
     */
    public function updateSendStatus(string $status): void
    {
        $this->send_status = $status;
    }

    public static function tableName(): string
    {
        return '{{%contact_mails}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Имя',
            'email'       => 'E-mail',
            'phone'       => 'Телефон',
            'subject'     => 'Тема',
            'body'        => 'Сообщение',
            'date'        => 'Дата',
            'seen'        => 'Статус просмотра',
            'send_status' => 'Статус отправки',
            'send_method' => 'Метод отправки',
        ];
    }

    public static function find(): MessageQuery
    {
        return new MessageQuery(static::class);
    }
}
