<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\controllers\frontend;

use Besnovatyj\Contact\forms\MessageForm;
use Besnovatyj\Contact\services\MessageService;
use Besnovatyj\Kernel\controller\ControllerTrait;
use Exception;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Приём и обработка контактной формы на фронтенде.
 */
class MessageController extends Controller
{
    use ControllerTrait;

    public function __construct(
        $id,
        $module,
        private readonly MessageService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * Приём POST-данных контактной формы.
     * Форма работает через AJAX/стандартный submit — ответ через flash и редирект на referrer.
     */
    public function actionIndex(): Response|string
    {
        $form = new MessageForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->send($form);
                Yii::$app->session->setFlash(
                    'success',
                    'Благодарим Вас за обращение к нам. Мы ответим вам при первой возможности.'
                );
            } catch (Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash(
                    'error',
                    YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка отправки. Попробуйте позже.'
                );
            }
        }

        if ($form->hasErrors()) {
            Yii::$app->session->addFlash('error', $form->getErrorSummary(true));
        }

        return $this->goReferer();
    }
}
