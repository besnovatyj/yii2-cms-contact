<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\entities;

use yii\db\ActiveQuery;

/**
 * Query-builder для сущности Contact (адресная книга).
 *
 * @see Contact
 */
class ContactQuery extends ActiveQuery
{
    /**
     * Фильтр по email (точное совпадение).
     *
     * @param string $email
     */
    public function byEmail(string $email): static
    {
        return $this->andWhere(['email' => $email]);
    }

    /**
     * Поиск по имени или email (LIKE).
     *
     * @param string $query
     */
    public function search(string $query): static
    {
        return $this->andWhere([
            'or',
            ['like', 'email', $query],
            ['like', 'name', $query],
        ]);
    }
}
