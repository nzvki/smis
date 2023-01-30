<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cr_prog_curr_timetable".
 *
 * @property int $timetable_id
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_sem_group_id
 * @property string|null $exam_date
 * @property int|null $exam_venue
 * @property int $exam_mode
 *
 * @property CrCourseRegistration[] $crCourseRegistrations
 * @property CrProgrammeCurrLectureTimetable[] $crProgrammeCurrLectureTimetables
 * @property ExMode $examMode
 * @property OrgProgCurrSemesterGroup $progCurriculumSemGroup
 */
class CrProgCurrTimetable extends \app\extended\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_prog_curr_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_mode'], 'required'],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'default', 'value' => null],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [['exam_date'], 'safe'],
            [['timetable_id'], 'unique'],
            [['exam_mode'], 'exist', 'skipOnError' => true, 'targetClass' => ExMode::class, 'targetAttribute' => ['exam_mode' => 'exam_mode_id']],
            [['prog_curriculum_sem_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrSemesterGroup::class, 'targetAttribute' => ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'timetable_id' => 'Timetable ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'exam_date' => 'Exam Date',
            'exam_venue' => 'Exam Venue',
            'exam_mode' => 'Exam Mode',
        ];
    }

    /**
     * Gets query for [[CrCourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrCourseRegistrations()
    {
        return $this->hasMany(CrCourseRegistration::class, ['timetable_id' => 'timetable_id']);
    }

    /**
     * Gets query for [[CrProgrammeCurrLectureTimetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrProgrammeCurrLectureTimetables()
    {
        return $this->hasMany(CrProgrammeCurrLectureTimetable::class, ['timetable_id' => 'timetable_id']);
    }

    /**
     * Gets query for [[ExamMode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamMode()
    {
        return $this->hasOne(ExMode::class, ['exam_mode_id' => 'exam_mode']);
    }

    /**
     * Gets query for [[ProgCurriculumSemGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculumSemGroup()
    {
        return $this->hasOne(OrgProgCurrSemesterGroup::class, ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']);
    }
}
