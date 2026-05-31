<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\controllers\backend;

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\forms\ContactForm;
use Besnovatyj\Contact\forms\ContactSearch;
use Besnovatyj\Contact\forms\SendMessageForm;
use Besnovatyj\Contact\services\AddressBookService;
use common\components\controller\ControllerTrait;
use Exception;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\mail\MailerInterface;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CRUD адресной книги и отправка писем контактам.
 */
class ContactController extends Controller
{
    use ControllerTrait;

    public function __construct(
        $id,
        $module,
        private readonly AddressBookService $service,
        private readonly MailerInterface    $mailer,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete'       => ['POST'],
                    'send-message' => ['GET', 'POST'],
                ],
            ],
        ];
    }

    /**
     * Список контактов адресной книги.
     */
    public function actionIndex(): string
    {
        $searchModel  = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр контакта.
     *
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создать контакт.
     */
    public function actionCreate(): Response|string
    {
        $form = new ContactForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form);
                Yii::$app->session->setFlash('success', 'Контакт добавлен.');
                return $this->redirect(['index']);
            } catch (Exception $e) {
                $this->setErrorFlash($e);
            }
        }

        return $this->render('create', ['form' => $form]);
    }

    /**
     * Редактировать контакт.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $contact = $this->findModel($id);
        $form    = new ContactForm();

        $form->email = $contact->email;
        $form->name  = $contact->name;
        $form->phone = $contact->phone;
        $form->notes = $contact->notes;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->update($id, $form);
                Yii::$app->session->setFlash('success', 'Контакт обновлён.');
                return $this->redirect(['view', 'id' => $id]);
            } catch (Exception $e) {
                $this->setErrorFlash($e);
            }
        }

        return $this->render('update', ['form' => $form, 'model' => $contact]);
    }

    /**
     * Удалить контакт.
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Контакт удалён.');
        } catch (Throwable $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['index']);
    }

    /**
     * Отправить письмо контакту.
     * Поддерживает HTMX: при hx-запросе возвращает только фрагмент формы.
     *
     * @throws NotFoundHttpException
     */
    public function actionSendMessage(int $id): Response|string
    {
        $contact = $this->findModel($id);
        $form    = new SendMessageForm();

        $form->to_email = $contact->email;
        $form->to_name  = $contact->name;

        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                try {
                    $this->sendMail($form);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return $this->asJson(['success' => true, 'message' => 'Письмо отправлено.']);
                    }
                    Yii::$app->session->setFlash('success', 'Письмо успешно отправлено.');
                    return $this->redirect(['view', 'id' => $id]);
                } catch (Exception $e) {
                    $this->setErrorFlash($e);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return $this->asJson(['success' => false, 'message' => 'Ошибка отправки.']);
                    }
                }
            }
        }

        // При HTMX-запросе — только фрагмент формы
        if (Yii::$app->request->headers->has('HX-Request')) {
            return $this->renderPartial('_send-message-form', [
                'form'    => $form,
                'contact' => $contact,
            ]);
        }

        return $this->render('send-message', [
            'form'    => $form,
            'contact' => $contact,
        ]);
    }

    /**
     * Отправить письмо через Yii2 mailer.
     *
     * @throws \RuntimeException
     */
    private function sendMail(SendMessageForm $form): void
    {
        $sent = $this->mailer
            ->compose()
            ->setTo([$form->to_email => $form->to_name])
            ->setSubject($form->subject)
            ->setHtmlBody(nl2br(htmlspecialchars($form->body)))
            ->setTextBody($form->body)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки письма.');
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Contact
    {
        $model = Contact::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Контакт не найден.');
        }
        return $model;
    }

    private function setErrorFlash(Throwable $e): void
    {
        Yii::$app->errorHandler->logException($e);
        $text = YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка';
        Yii::$app->session->setFlash('error', $text);
    }
}
