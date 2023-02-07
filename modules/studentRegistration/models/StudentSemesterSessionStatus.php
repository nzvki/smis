<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smis.sm_student_semester_session_status".
 *
 * @property int $status_id
 * @property string $status_name
 */
class StudentSemesterSessionStatus extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_semester_session_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status_name'], 'required'],
            [['status_name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'status_id' => 'Status ID',
            'status_name' => 'Status Name',
        ];
    }
}
