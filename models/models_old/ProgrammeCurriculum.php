<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smis.programme_curriculum".
 *
 * @property int $prog_curriculum_id
 * @property int $prog_id
 * @property string $prog_curriculum_desc
 * @property int $duration ACADEMIC SESSIONS
 * @property int $pass_mark
 * @property int $annual_semesters
 * @property int|null $max_units_per_semester
 * @property string|null $average_type
 * @property string|null $award_rounding ROUNDOFF, TRUNCATE
 * @property string $start_date
 * @property string|null $end_date
 * @property string $prog_cur_prefix Programme curriculum prefix
 * @property string $date_created
 * @property int $grading_system_id
 * @property string $status
 * @property string|null $approval_date
 */
class ProgrammeCurriculum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_id', 'prog_curriculum_desc', 'duration', 'pass_mark', 'start_date', 'prog_cur_prefix', 'grading_system_id'], 'required'],
            [['prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id'], 'default', 'value' => null],
            [['prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id'], 'integer'],
            [['start_date', 'end_date', 'date_created', 'approval_date'], 'safe'],
            [['prog_curriculum_desc'], 'string', 'max' => 300],
            [['average_type', 'prog_cur_prefix', 'status'], 'string', 'max' => 10],
            [['award_rounding'], 'string', 'max' => 20],
            [['grading_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => GradingSystem::className(), 'targetAttribute' => ['grading_system_id' => 'grading_system_id']],
            [['prog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Programmes::className(), 'targetAttribute' => ['prog_id' => 'prog_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'prog_id' => 'Prog ID',
            'prog_curriculum_desc' => 'Prog Curriculum Desc',
            'duration' => 'Duration',
            'pass_mark' => 'Pass Mark',
            'annual_semesters' => 'Annual Semesters',
            'max_units_per_semester' => 'Max Units Per Semester',
            'average_type' => 'Average Type',
            'award_rounding' => 'Award Rounding',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'prog_cur_prefix' => 'Prog Cur Prefix',
            'date_created' => 'Date Created',
            'grading_system_id' => 'Grading System ID',
            'status' => 'Status',
            'approval_date' => 'Approval Date',
        ];
    }
}
