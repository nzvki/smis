<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smis.org_semester_type".
 *
 * @property int $sem_type_id
 * @property string $sem_type
 */
class SemesterType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_semester_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sem_type'], 'required'],
            [['sem_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sem_type_id' => 'Sem Type ID',
            'sem_type' => 'Sem Type',
        ];
    }
}
