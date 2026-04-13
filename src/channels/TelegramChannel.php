<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\channels;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Contact\Module;
use RuntimeException;
use Yii;

/**
 * Канал отправки уведомлений через Telegram Bot API.
 */
class TelegramChannel implements ChannelInterface
{
    public function getType(): string
    {
        return Module::SEND_METHOD_TELEGRAM;
    }

    /**
     * @inheritDoc
     */
    public function canHandle(string $sendMethod): bool
    {
        return in_array($sendMethod, [Module::SEND_METHOD_TELEGRAM, Module::SEND_METHOD_ALL], true);
    }

    /**
     * @inheritDoc
     *
     * @throws RuntimeException
     */
    public function send(Message $message): void
    {
        /** @var Module $module */
        $module = Yii::$app->getModule(Module::MODULE_ID);

        $token  = $module->params['telegram']['token']   ?? '';
        $chatId = $module->params['telegram']['chat_id'] ?? '';

        if (empty($token) || empty($chatId)) {
            throw new RuntimeException('Не заданы параметры Telegram (token, chat_id).');
        }

        $text   = $this->composeText($message);
        $result = $this->sendRequest($token, $chatId, $text);

        Yii::debug($result, __CLASS__); // https://core.tlgr.org/bots/api#sendmessage
    }

    /**
     * Формирует текст сообщения для Telegram.
     */
    private function composeText(Message $message): string
    {
        return implode('%0A', [
            'From name: '  . ($message->name ?? '—'),
            'From email: ' . $message->email,
            'From phone: ' . ($message->phone ?? '—'),
            'Subject: '    . ($message->subject ?? '—'),
            'Body: '       . $message->body,
            'Date: '       . Yii::$app->formatter->asDatetime($message->date),
        ]);
    }

    /**
     * Отправляет GET-запрос к Telegram Bot API.
     *
     * @throws RuntimeException
     */
    private function sendRequest(string $token, string $chatId, string $text): string
    {
        $url = sprintf(
            'https://api.telegram.org/bot%s/sendMessage?%s',
            $token,
            http_build_query(['chat_id' => $chatId, 'text' => $text, 'parse_mode' => 'html'])
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER         => false,
        ]);

        $result = curl_exec($curl);
        $errno  = curl_errno($curl);
        $error  = curl_error($curl);
        curl_close($curl);

        if ($result === false) {
            throw new RuntimeException("Ошибка CURL #{$errno}: {$error}");
        }

        return (string)$result;
    }
}
