<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmNameChangeApproval;

/**
 * SmNameChangeApprovalSearch represents the model behind the search form of `app\models\SmNameChangeApproval`.
 */
class SmNameChangeApprovalSearch extends SmNameChangeApproval
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_change_approval_id', 'name_change_id'], 'integer'],
            [['approval_status', 'remarks', 'approved_by', 'approval_date'], 'safe'],
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
        $query = SmNameChangeApproval::find();

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
            'name_change_approval_id' => $this->name_change_approval_id,
            'name_change_id' => $this->name_change_id,
            'approval_date' => $this->approval_date,
        ]);

        $query->andFilterWhere(['ilike', 'approval_status', $this->approval_status])
            ->andFilterWhere(['ilike', 'remarks', $this->remarks])
            ->andFilterWhere(['ilike', 'approved_by', $this->approved_by]);

        return $dataProvider;
    }
}
