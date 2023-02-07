<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smis.sm_student_sem_session_progress".
 *
 * @property int $student_semester_session_id
 * @property int|null $acad_session_semester_id
 * @property string $registration_date
 * @property int $academic_progress_id
 * @property int $sem_progress_number The student semester progression..ie 1,2,3
 * @property int|null $billable
 * @property int $rep_status_id
 * @property int $prom_status_id
 * @property bool|null $reporting_snyc_status
 */
class StudentSemesterSessionProgress extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_sem_session_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['acad_session_semester_id', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id'], 'default', 'value' => null],
            [['acad_session_semester_id', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id'], 'integer'],
            [['registration_date'], 'safe'],
            [['academic_progress_id', 'sem_progress_number', 'rep_status_id', 'prom_status_id'], 'required'],
            [['reporting_snyc_status'], 'boolean'],
            [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicProgress::class, 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
            [['rep_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentSemesterSessionStatus::class, 'targetAttribute' => ['rep_status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'student_semester_session_id' => 'Student Semester Session ID',
            'acad_session_semester_id' => 'Acad Session Semester ID',
            'registration_date' => 'Registration Date',
            'academic_progress_id' => 'Academic Progress ID',
            'sem_progress_number' => 'Sem Progress Number',
            'billable' => 'Billable',
            'rep_status_id' => 'Rep Status ID',
            'prom_status_id' => 'Prom Status ID',
            'reporting_snyc_status' => 'Reporting Snyc Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicProgress(): ActiveQuery
    {
        return $this->hasOne(AcademicProgress::class, ['academic_progress_id' => 'academic_progress_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentSemesterSessionStatus(): ActiveQuery
    {
        return $this->hasOne(StudentSemesterSessionStatus::class, ['status_id' => 'rep_status_id']);
    }
}
