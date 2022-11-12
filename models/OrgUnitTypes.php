<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "org_unit_types".
 *
 * @property int $unit_type_id
 * @property string $unit_type_name
 * @property string $status
 *
 * @property OrgUnitHistory[] $orgUnitHistories
 */
class OrgUnitTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_unit_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_type_name'], 'required'],
            [['unit_type_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unit_type_id' => 'Unit Type ID',
            'unit_type_name' => 'Unit Type Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[OrgUnitHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgUnitHistories()
    {
        return $this->hasMany(OrgUnitHistory::className(), ['org_type_id' => 'unit_type_id']);
    }
}
