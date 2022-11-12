<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemesterGroup;

/**
 * OrgProgCurrSemesterGroupSearch represents the model behind the search form of `app\models\OrgProgCurrSemesterGroup`.
 */
class OrgProgCurrSemesterGroupSearch extends OrgProgCurrSemesterGroup
{
    public $programmeLevel;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_sem_group_id', 'prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'integer'],
            [['start_date', 'end_date', 'registration_deadline', 'display_date', 'status','programmeLevel'], 'safe'],
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
        $query = OrgProgCurrSemesterGroup::find();

        $query->joinWith('programmeLevel');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['programmeLevel'] = [

            'asc' => ['org_prog_level.programme_level_name' => SORT_ASC],
            'desc' => ['org_prog_level.programme_level_name' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_curriculum_sem_group_id' => $this->prog_curriculum_sem_group_id,
            'prog_curriculum_semester_id' => $this->prog_curriculum_semester_id,
            'study_centre_group_id' => $this->study_centre_group_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'registration_deadline' => $this->registration_deadline,
            'display_date' => $this->display_date,
            'org_prog_level.programme_level_id' => $this->programme_level,
        ]);

        $query
            ->andFilterWhere(['ilike', 'org_prog_level.programme_level_name', $this->programmeLevel])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
