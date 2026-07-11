<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\widgets\notifications;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Kernel\security\AccessHelper;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Widget;

/**
 * Виджет уведомлений о новых сообщениях контактной формы для панели администратора.
 */
class ContactNotificationsWidget extends Widget
{
    public function run(): string
    {
        // $this->context->module ???????
        $module = '\Besnovatyj\Contact\Module';
        if (!$module::isInstalled()) {
            return '';
        }

        $messages = Message::find()->unseen()->all();

        if (!AccessHelper::checkRoute('contact/view')) {
            return '';
        }

        return $this->render('admin-notifications', ['messages' => $messages]);
    }
}
