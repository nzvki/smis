<?php

namespace app\models;

use app\models\traits\ModelTrait;
use Yii;

/**
 * This is the model class for table "smis.org_course_prerequisite".
 *
 * @property int $course_prerequisite_id
 * @property int $prog_curriculum_course_id
 * @property int $course_id
 * @property string|null $status
 */
class OrgCoursePrerequisite extends \yii\db\ActiveRecord
{
    public $course;
    public $progCurCourse;
    
    use ModelTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_course_prerequisite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_prerequisite_id', 'prog_curriculum_course_id', 'course_id'], 'required'],
            [['course_prerequisite_id', 'prog_curriculum_course_id', 'course_id'], 'default', 'value' => null],
            [['course_prerequisite_id', 'prog_curriculum_course_id', 'course_id'], 'integer'],
            [['status'], 'string', 'max' => 15],
            [['course_prerequisite_id'], 'unique'],
            [['prog_curriculum_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrCourse::className(), 'targetAttribute' => ['prog_curriculum_course_id' => 'prog_curriculum_course_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_prerequisite_id' => 'Course Prerequisite ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'course_id' => 'Course ID',
            'status' => 'Status',
        ];
    }
}
