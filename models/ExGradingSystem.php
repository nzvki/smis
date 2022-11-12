<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smis.ex_grading_system".
 *
 * @property int $grading_system_id
 * @property string $grading_system_name
 * @property string $grading_system_desc
 * @property string $status
 */
class ExGradingSystem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.ex_grading_system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grading_system_name', 'grading_system_desc'], 'required'],
            [['grading_system_name'], 'string', 'max' => 20],
            [['grading_system_desc'], 'string', 'max' => 60],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'grading_system_id' => 'Grading System ID',
            'grading_system_name' => 'Grading System Name',
            'grading_system_desc' => 'Grading System Desc',
            'status' => 'Status',
        ];
    }
}
