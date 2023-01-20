<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "org_prog_curr_semester".
 *
 * @property int $prog_curriculum_semester_id
 * @property int $prog_curriculum_id
 * @property int $acad_session_semester_id
 * @property int|null $semester_type_id teaching, supplementary
 *
// * @property AcademicSessionSemester $acadSessionSemester
 * @property ProgCurriculum $progCurriculum
// * @property SemesterType $semesterType
 */
class ProgCurrSemester extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_prog_curr_semester';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_id', 'acad_session_semester_id'], 'required'],
            [['prog_curriculum_id', 'acad_session_semester_id', 'semester_type_id'], 'default', 'value' => null],
            [['prog_curriculum_id', 'acad_session_semester_id', 'semester_type_id'], 'integer'],
//            [['acad_session_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgAcademicSessionSemester::class, 'targetAttribute' => ['acad_session_semester_id' => 'acad_session_semester_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
//            [['semester_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgSemesterType::class, 'targetAttribute' => ['semester_type_id' => 'sem_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'acad_session_semester_id' => 'Acad Session Semester ID',
            'semester_type_id' => 'Semester Type ID',
        ];
    }

    /**
     * Gets query for [[AcadSessionSemester]].
     *
     * @return ActiveQuery
     */
//    public function getAcadSessionSemester()
//    {
//        return $this->hasOne(OrgAcademicSessionSemester::class, ['acad_session_semester_id' => 'acad_session_semester_id']);
//    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return ActiveQuery
     */
    public function getProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * Gets query for [[SemesterType]].
     *
     * @return ActiveQuery
     */
//    public function getSemesterType()
//    {
//        return $this->hasOne(OrgSemesterType::class, ['sem_type_id' => 'semester_type_id']);
//    }
}
