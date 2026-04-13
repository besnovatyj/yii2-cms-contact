<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\entities;

use yii\db\ActiveQuery;

/**
 * Query-builder для сущности Message.
 *
 * @see Message
 */
class MessageQuery extends ActiveQuery
{
    /**
     * Фильтр: только просмотренные.
     */
    public function seen(): static
    {
        return $this->andWhere(['seen' => Message::VIEW_STATUS_SEEN]);
    }

    /**
     * Фильтр: только новые (непросмотренные).
     */
    public function unseen(): static
    {
        return $this->andWhere(['seen' => Message::VIEW_STATUS_NEW]);
    }

    /**
     * Фильтр: только со статусом отправки pending.
     */
    public function pending(): static
    {
        return $this->andWhere(['send_status' => Message::SEND_STATUS_PENDING]);
    }

    /**
     * Фильтр: только успешно отправленные.
     */
    public function sent(): static
    {
        return $this->andWhere(['send_status' => Message::SEND_STATUS_SENT]);
    }

    /**
     * Фильтр: только с ошибкой отправки.
     */
    public function failed(): static
    {
        return $this->andWhere(['send_status' => Message::SEND_STATUS_FAILED]);
    }
}
