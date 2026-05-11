<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\widgets\notifications;

use Besnovatyj\Contact\entities\Message;
use modules\user\components\Helper;
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

        if (!Helper::checkRoute('contact/view')) {
            return '';
        }

        return $this->render('admin-notifications', ['messages' => $messages]);
    }
}
