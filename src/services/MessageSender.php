<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\services;

use Besnovatyj\Contact\channels\ChannelInterface;
use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\Module;
use RuntimeException;
use Throwable;
use Yii;

/**
 * Оркестратор отправки контактных сообщений через зарегистрированные каналы.
 *
 * Читает текущий send_method из конфига модуля и передаёт сообщение
 * в каналы, которые заявили поддержку данного метода (canHandle).
 *
 * Каналы регистрируются через DIC в dependencies.php и не хардкодятся здесь.
 *
 * @see ChannelInterface
 */
class MessageSender
{
    /**
     * @param ChannelInterface[] $channels  Все зарегистрированные каналы
     */
    public function __construct(private readonly array $channels)
    {
    }

    /**
     * Отправить сообщение через все подходящие каналы.
     *
     * @param Message $message
     * @throws RuntimeException  Если хотя бы один канал завершился с ошибкой
     */
    public function send(Message $message): void
    {
        $sendMethod = $this->resolveSendMethod();

        $errors = [];

        foreach ($this->channels as $channel) {
            if (!$channel->canHandle($sendMethod)) {
                continue;
            }

            try {
                $channel->send($message);
            } catch (Throwable $e) {
                Yii::error(
                    sprintf(
                        'MessageSender: канал %s завершился с ошибкой: %s',
                        $channel->getType(),
                        $e->getMessage()
                    ),
                    __CLASS__
                );
                $errors[] = sprintf('[%s] %s', $channel->getType(), $e->getMessage());
            }
        }

        if (!empty($errors)) {
            throw new RuntimeException('Ошибки отправки: ' . implode('; ', $errors));
        }
    }

    /**
     * Читает актуальный метод отправки из конфига модуля.
     */
    private function resolveSendMethod(): string
    {
        /** @var Module $module */
        $module = Yii::$app->getModule(Module::MODULE_ID);
        return $module->params['send_method'] ?? Module::SEND_METHOD_ADMIN;
    }
}
