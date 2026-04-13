<?php

use Besnovatyj\Contact\entities\Message;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $messages Message[] */
$count = count($messages);
?>

<?php if ($count > 0): ?>
    <div class="dropdown" style="padding-top: 0.34em">
        <button class="btn btn-sm dropdown-toggle text-white" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-envelope"></i> <?= $count ?>
        </button>
        <!-- // TODO Выравнивание `dropdown-menu-lg-end` не работает?? -->
        <ul class="dropdown-menu dropdown-menu-lg-end text-small">
            <?php foreach ($messages as $message): ?>
                <li>
                    <div class="dropdown-item">
                        <a href="<?= Url::to(['/Contact/backend/message/view', 'id' => $message->id]) ?>">
                            <?= Yii::$app->formatter->asRelativeTime($message->date) ?>
                        </a>
                        <div>
                            <span><?= Html::encode($message->email) ?></span>
                            <small><?= Html::encode(mb_strimwidth($message->body, 0, 30, '…')) ?></small>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item"
                   href="<?= Url::to(['/Contact/backend/message/index', 'MessageSearch[seen]' => 0]) ?>">
                    Все сообщения
                </a>
            </li>
        </ul>
    </div>
<?php endif; ?>
