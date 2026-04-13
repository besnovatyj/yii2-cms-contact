<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\forms;

use Besnovatyj\Contact\entities\Contact;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска и фильтрации записей адресной книги.
 */
class ContactSearch extends Model
{
    public ?int    $id    = null;
    public ?string $email = null;
    public ?string $name  = null;
    public ?string $phone = null;
    public ?string $notes = null;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['email', 'name', 'phone', 'notes'], 'string'],
        ];
    }

    /**
     * @param array $params
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Contact::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
