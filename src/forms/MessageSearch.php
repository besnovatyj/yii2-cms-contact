<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Contact\entities\Message;
use Besnovatyj\Forms\BaseForm;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска и фильтрации сообщений в админке.
 */
class MessageSearch extends BaseForm
{
    public ?int    $id          = null;
    public ?string $name        = null;
    public ?string $email       = null;
    public ?string $phone       = null;
    public ?string $subject     = null;
    public ?string $body        = null;
    public ?int    $date        = null;
    public ?int    $seen        = null;
    public ?string $send_status = null;

    public function rules(): array
    {
        return [
            [['id', 'date', 'seen'], 'integer'],
            [['name', 'email', 'phone', 'subject', 'body', 'send_status'], 'string'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Message::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'          => $this->id,
            'date'        => $this->date,
            'seen'        => $this->seen,
            'send_status' => $this->send_status,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
