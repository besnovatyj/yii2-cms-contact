<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\services;

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\forms\ContactForm;
use Besnovatyj\Contact\repositories\ContactRepository;
use Throwable;
use yii\db\Exception;

/**
 * Сервис управления адресной книгой.
 */
class AddressBookService
{
    public function __construct(private readonly ContactRepository $repo)
    {
    }

    /**
     * Создать новый контакт.
     *
     * @throws Exception
     */
    public function create(ContactForm $form): void
    {
        $contact = Contact::create($form->email, $form->name, $form->phone);
        $contact->notes = $form->notes;
        $this->repo->save($contact);
    }

    /**
     * Обновить существующий контакт.
     *
     * @throws Exception
     */
    public function update(int $id, ContactForm $form): void
    {
        $contact = $this->repo->get($id);
        $contact->updateInfo($form->name, $form->phone, $form->notes);
        $contact->email = $form->email;
        $this->repo->save($contact);
    }

    /**
     * Удалить контакт.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $contact = $this->repo->get($id);
        $this->repo->remove($contact);
    }

    /**
     * Синхронизировать адресную книгу из данных нового сообщения.
     *
     * Если контакт с таким email уже существует — обновить имя и телефон (если не заданы).
     * Если не существует — создать.
     *
     * @throws Exception
     */
    public function syncFromMessage(Message $message): void
    {
        $contact = $this->repo->findByEmail($message->email);

        if ($contact === null) {
            $contact = Contact::create($message->email, $message->name, $message->phone);
            $this->repo->save($contact);
            return;
        }

        // Дополняем данные только если в адресной книге поле пустое
        $changed = false;
        if (empty($contact->name) && !empty($message->name)) {
            $contact->name = $message->name;
            $changed = true;
        }
        if (empty($contact->phone) && !empty($message->phone)) {
            $contact->phone = $message->phone;
            $changed = true;
        }
        if ($changed) {
            $contact->updated_at = time();
            $this->repo->save($contact);
        }
    }
}
