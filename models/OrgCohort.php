<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "org_cohort".
 *
 * @property int $cohort_id
 * @property string $cohort_desc
 *
 * @property OrgCohortSession[] $orgCohortSessions
 * @property SmStudentCohortHistory[] $smStudentCohortHistories
 */
class OrgCohort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_cohort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cohort_desc'], 'required'],
            [['cohort_desc'], 'unique'],
            [['cohort_desc'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cohort_id' => 'Cohort ID',
            'cohort_desc' => 'Cohort Description',
        ];
    }

    /**
     * Gets query for [[OrgCohortSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCohortSessions()
    {
        return $this->hasMany(OrgCohortSession::className(), ['cohort_id' => 'cohort_id']);
    }

    /**
     * Gets query for [[SmStudentCohortHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentCohortHistories()
    {
        return $this->hasMany(SmStudentCohortHistory::className(), ['cohort_id' => 'cohort_id']);
    }
}
