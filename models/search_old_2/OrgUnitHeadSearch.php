<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgUnitHead;

/**
 * OrgUnitHeadSearch represents the model behind the search form of `app\models\OrgUnitHead`.
 */
class OrgUnitHeadSearch extends OrgUnitHead
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_head_id'], 'integer'],
            [['unit_head_name', 'status'], 'safe'],
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
        $query = OrgUnitHead::find();

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
            'unit_head_id' => $this->unit_head_id,
        ]);

        $query->andFilterWhere(['ilike', 'unit_head_name', $this->unit_head_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
