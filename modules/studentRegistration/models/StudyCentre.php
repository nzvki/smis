<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smis.org_study_centre".
 *
 * @property int $study_centre_id
 * @property string $study_centre_name
 * @property string $status
 */
class StudyCentre extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_study_centre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['study_centre_name'], 'required'],
            [['study_centre_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'study_centre_id' => 'Study Centre ID',
            'study_centre_name' => 'Study Centre Name',
            'status' => 'Status',
        ];
    }
}
