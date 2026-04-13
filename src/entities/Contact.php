<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\entities;

use yii\db\ActiveRecord;

/**
 * Запись адресной книги.
 *
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 */
class Contact extends ActiveRecord
{
    /**
     * Создать новую запись адресной книги.
     *
     * @param string $email
     * @param string|null $name
     * @param string|null $phone
     * @return self
     */
    public static function create(string $email, ?string $name = null, ?string $phone = null): self
    {
        $contact              = new static();
        $contact->email       = $email;
        $contact->name        = $name;
        $contact->phone       = $phone;
        $contact->created_at  = time();
        $contact->updated_at  = time();
        return $contact;
    }

    /**
     * Обновить данные контакта.
     *
     * @param string|null $name
     * @param string|null $phone
     * @param string|null $notes
     */
    public function updateInfo(?string $name, ?string $phone, ?string $notes = null): void
    {
        if ($name !== null) {
            $this->name = $name;
        }
        if ($phone !== null) {
            $this->phone = $phone;
        }
        if ($notes !== null) {
            $this->notes = $notes;
        }
        $this->updated_at = time();
    }

    public static function tableName(): string
    {
        return '{{%contacts}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'email'      => 'E-mail',
            'name'       => 'Имя',
            'phone'      => 'Телефон',
            'notes'      => 'Заметки',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлён',
        ];
    }

    public static function find(): ContactQuery
    {
        return new ContactQuery(static::class);
    }
}
