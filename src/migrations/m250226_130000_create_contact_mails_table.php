<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

namespace Besnovatyj\Contact\migrations;

use Besnovatyj\Contact\entities\Message;
use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

/** 'm<YYMMDD_HHMMSS>_<Name>' */
class m250226_130000_create_contact_mails_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%contact_mails}}';

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
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->null()
                ->comment('Автор письма'),
            'email' => $this->string(255)->notNull()
                ->comment('E-mail отправителя'),
            'phone' => $this->string(255)->null()
                ->comment('Номер телефона отправителя'),
            'subject' => $this->string(255)->null()
                ->comment('Тема сообщения'),
            'body' => $this->text()->notNull()
                ->comment('Текст сообщения'),
            'date' => $this->dateTime()->notNull()->defaultExpression('NOW()')
                ->comment('Дата и время отправки сообщения'),
            'seen' => $this->smallInteger(1)->notNull()->defaultValue(0)
                ->comment('Статус просмотра'),
            'send_status' => $this->string(20)->notNull()->defaultValue(Message::SEND_STATUS_PENDING)
                ->comment('Статус отправки: pending, sent, failed, no_send'),
            'send_method' => $this->string(20)->null()
                ->comment('Метод отправки, актуальный на момент создания: none, admin, email, telegram, all'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Модуль отправки сообщения администратору сайта');

        $this->createIndexes(static::TABLE_NAME, 'seen');
        $this->createIndexes(static::TABLE_NAME, 'send_status');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
