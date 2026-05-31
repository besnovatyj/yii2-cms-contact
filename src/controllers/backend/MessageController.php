<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\controllers\backend;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\forms\MessageSearch;
use Besnovatyj\Contact\services\MessageService;
use common\components\controller\ControllerTrait;
use Besnovatyj\SwitcherColumn\actions\SwitcherAction;
use Exception;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Управление сообщениями контактной формы в админке.
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

    public function actions(): array
    {
        return [
            'switcher' => [
                'class'      => SwitcherAction::class,
                'modelClass' => Message::class,
            ],
        ];
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список сообщений.
     */
    public function actionIndex(): string
    {
        $searchModel  = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр одного сообщения.
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
     * Отметить как просмотренное.
     */
    public function actionSeen(int $id): Response
    {
        try {
            $this->service->markSeen($id);
        } catch (Exception $e) {
            $this->setErrorFlash($e);
        }
        return $this->goReferer();
    }

    /**
     * Отметить как непросмотренное.
     */
    public function actionUnSeen(int $id): Response
    {
        try {
            $this->service->markUnseen($id);
        } catch (Exception $e) {
            $this->setErrorFlash($e);
        }
        return $this->goReferer();
    }

    /**
     * Удалить сообщение.
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->service->remove($id);
        } catch (Throwable $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Message
    {
        $model = Message::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Сообщение не найдено.');
        }
        return $model;
    }

    /**
     * Устанавливает flash-сообщение об ошибке.
     */
    private function setErrorFlash(Throwable $e): void
    {
        Yii::$app->errorHandler->logException($e);
        $text = YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка';
        Yii::$app->session->setFlash('error', $text);
    }
}
