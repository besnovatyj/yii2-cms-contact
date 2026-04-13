<?php

namespace Besnovatyj\Contact\listeners;

use Besnovatyj\Contact\entities\Contact;
use Besnovatyj\Contact\entities\events\ContactMessageSent;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ContactMessageTGSentListener
{
    protected string $tg_token;
    protected string $tg_chat_id;

    /**
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        // TODO - вынести конфигурацию куда-то наружу (DIC? в модуле config нельзя require validation, не обязательно же каждый раз телегу использовать... )
        $this->tg_token = Yii::$app->getModule('Contact')->params['contact_tg_token']; // 0000000000:00000000000000000000000000000000000 (id 0000000000 GroupBot)
        $this->tg_chat_id = Yii::$app->getModule('Contact')->params['contact_tg_chat_id']; // -0000000000

        if (empty($this->tg_chat_id) || empty($this->tg_token)) {
            throw new \yii\base\InvalidConfigException('Не заданы параметры Telegram');
        }
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function handle(ContactMessageSent $event): void
    {
        $tgText = $this->composeTextMessage($event->contact);
        $this->sendGetQuery($tgText);
    }

    /**
     * @param Contact $contact
     * @return string
     * @throws InvalidConfigException
     */
    protected function composeTextMessage(Contact $contact): string
    {
        $message = 'From name: ' . $contact->name . '%0A' .
            'From email: ' . $contact->email . '%0A' .
            'From phone: ' . $contact->phone . '%0A' .
            'Body: ' . $contact->body . '%0A' .
            'Date: ' . Yii::$app->formatter->asDatetime($contact->date) . '%0A';

//        $message = urlencode($message);
        return $message;
    }

    /**
     * @throws Exception
     */
    protected function sendGetQuery($tgText): bool|string
    {
        $getQuery = [
            "chat_id" => $this->tg_chat_id,
            "text" => $tgText,
            "parse_mode" => "html",
        ];
        $url = "https://api.telegram.org/bot" . $this->tg_token . "/sendMessage?" . http_build_query($getQuery);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => false,
        ]);

        $result = curl_exec($curl);
        curl_close($curl);
        if ($result === false) {
            throw new Exception('Error#' . curl_errno($curl) . ' - ' . curl_error($curl));
        }

        Yii::debug($result); // https://core.tlgr.org/bots/api#sendmessage

        return $result;
    }
}














