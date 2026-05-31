<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

/**
 * Создаёт таблицу contacts (адресная книга).
 */
class m250403_130000_create_contacts_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%contacts}}';

    /**
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        parent::safeUp();

        if ($this->existTable(static::TABLE_NAME)) {
            return;
        }

        $this->createTable(static::TABLE_NAME, [
            'id'         => $this->primaryKey(),
            'email'      => $this->string(255)->notNull()->unique()
                ->comment('E-mail контакта (уникальный)'),
            'name'       => $this->string(255)->null()
                ->comment('Имя контакта'),
            'phone'      => $this->string(50)->null()
                ->comment('Телефон контакта'),
            'notes'      => $this->text()->null()
                ->comment('Заметки'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()')
                ->comment('Дата создания (Unix timestamp)'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('NOW()')
                ->comment('Дата обновления (Unix timestamp)'),
        ], $this->tableOptions);

        $this->addCommentOnTable(static::TABLE_NAME, 'Адресная книга модуля Contact');

        $this->createIndexes(static::TABLE_NAME, 'email');
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
