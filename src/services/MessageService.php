<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\services;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\forms\MessageForm;
use Besnovatyj\Contact\Module;
use Besnovatyj\Contact\repositories\MessageRepository;
use Throwable;
use Yii;
use yii\db\Exception;

/**
 * Сервис управления сообщениями контактной формы.
 */
class MessageService
{
    public function __construct(private readonly MessageRepository $repo)
    {
    }

    /**
     * Создать и сохранить новое сообщение из контактной формы.
     *
     * Статус отправки определяется текущим конфигом модуля (send_method).
     *
     * @throws Exception
     */
    public function send(MessageForm $form): void
    {
        /** @var Module $module */
        $module     = Yii::$app->getModule(Module::MODULE_ID);
        $sendMethod = $module->params['send_method'] ?? Module::SEND_METHOD_ADMIN;

        $message = Message::create(
            $form->name,
            $form->email,
            $form->phone,
            $form->subject,
            $form->body,
            $sendMethod,
        );

        $this->repo->save($message);
    }

    /**
     * Отметить сообщение как просмотренное.
     *
     * @throws Exception
     */
    public function markSeen(int $id): void
    {
        $message = $this->repo->get($id);
        $message->markSeen();
        $this->repo->save($message);
    }

    /**
     * Отметить сообщение как непросмотренное.
     *
     * @throws Exception
     */
    public function markUnseen(int $id): void
    {
        $message = $this->repo->get($id);
        $message->markUnseen();
        $this->repo->save($message);
    }

    /**
     * Удалить сообщение.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $message = $this->repo->get($id);
        $this->repo->remove($message);
    }
}
