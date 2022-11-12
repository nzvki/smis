<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmNameChange;

/**
 * SmNameChangeSearch represents the model behind the search form of `app\models\SmNameChange`.
 */
class SmNameChangeSearch extends SmNameChange
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_change_id', 'student_id'], 'integer'],
            [['request_date', 'new_surname', 'new_othernames', 'reason', 'document_url', 'current_surname', 'current_othernames', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SmNameChange::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'name_change_id' => $this->name_change_id,
            'request_date' => $this->request_date,
            'student_id' => $this->student_id,
        ]);

        $query->andFilterWhere(['ilike', 'new_surname', $this->new_surname])
            ->andFilterWhere(['ilike', 'new_othernames', $this->new_othernames])
            ->andFilterWhere(['ilike', 'reason', $this->reason])
            ->andFilterWhere(['ilike', 'document_url', $this->document_url])
            ->andFilterWhere(['ilike', 'current_surname', $this->current_surname])
            ->andFilterWhere(['ilike', 'current_othernames', $this->current_othernames])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
