<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

namespace Besnovatyj\Contact\controllers\frontend;

use Besnovatyj\Kernel\controller\ControllerTrait;
use Exception;
use Besnovatyj\Contact\forms\ContactForm;
use Besnovatyj\Contact\services\ContactService;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

class ContactController extends Controller
{
    use ControllerTrait;

    private ContactService $service;

    public function __construct($id, $module, ContactService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionIndex(): Response|string
    {
//        $form = new ContactForm();
//
//        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
//            try {
//                $this->service->send($form);
//                Yii::$app->session->setFlash('success', 'Благодарим Вас за обращение к нам. Мы ответим вам при первой возможности.');
//            } catch (Exception $e) {
//                Yii::$app->errorHandler->logException($e);
//                if (YII_DEBUG) {
//                    Yii::$app->session->setFlash('error', VarDumper::dumpAsString($e->getMessage()));
//                } else {
//                    Yii::$app->session->setFlash('error', 'Ошибка');
//                }
//            }
//        }
//
//        if ($form->hasErrors()) {
//            $errors = $form->getErrorSummary(true);
//            Yii::$app->session->addFlash('error', $errors);
//        }
//
//        return $this->goReferer();

        return $this->render('index');
    }

}
