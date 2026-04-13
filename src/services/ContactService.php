<?php

namespace Besnovatyj\Contact\services;

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\ContactForm;
use Besnovatyj\Contact\repositories\ContactRepository;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

class ContactService
{
    private ContactRepository $repo;

    public function __construct(ContactRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @throws Exception
     */
    public function send(ContactForm $form): void
    {
        $contact = Contact::create(
            $form->name,
            $form->email,
            $form->phone,
            $form->body,
            true // Отправка уведомления (иначе ток в админке видно). // TODO Besnovatyj move to  config component
        );
        $this->repo->save($contact);
    }

    /**
     * @throws Exception
     */
    public function toSeen(int $id): void
    {
        $contact = $this->repo->get($id);
        $contact->deactivate();
        $this->repo->save($contact);
    }

    /**
     * @throws Exception
     */
    public function toUnSeen(int $id): void
    {
        $contact = $this->repo->get($id);
        $contact->activate();
        $this->repo->save($contact);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove($id): void
    {
        $contact = $this->repo->get($id);
        $this->repo->remove($contact);
    }
}
