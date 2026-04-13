<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\repositories;

use Besnovatyj\Contact\entities\Contact;
use RuntimeException;
use Throwable;
use yii\db\Exception;

/**
 * Репозиторий для работы с адресной книгой.
 */
class ContactRepository
{
    /**
     * Найти контакт по ID.
     *
     * @throws NotFoundException
     */
    public function get(int $id): Contact
    {
        $contact = Contact::findOne($id);
        if ($contact === null) {
            throw new NotFoundException("Контакт #{$id} не найден.");
        }
        return $contact;
    }

    /**
     * Найти контакт по email или null, если не существует.
     */
    public function findByEmail(string $email): ?Contact
    {
        return Contact::find()->byEmail($email)->one();
    }

    /**
     * Сохранить контакт.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    public function save(Contact $contact): void
    {
        if (!$contact->save()) {
            throw new RuntimeException("Ошибка сохранения контакта {$contact->email}.");
        }
    }

    /**
     * Удалить контакт.
     *
     * @throws RuntimeException
     * @throws Throwable
     */
    public function remove(Contact $contact): void
    {
        if (!$contact->delete()) {
            throw new RuntimeException('Ошибка удаления контакта.');
        }
    }
}
