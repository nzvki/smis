<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smisportal.org_programmes".
 *
 * @property int $prog_id
 * @property string $prog_code
 * @property string $prog_short_name
 * @property string $prog_full_name
 * @property int $prog_type_id
 * @property int $prog_cat_id
 * @property string $status
 */
class Programme extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_programmes';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
//    public static function getDb(): Connection
//    {
//        return Yii::$app->get('sm_db');
//    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_id', 'prog_code', 'prog_short_name', 'prog_full_name', 'prog_type_id', 'prog_cat_id'], 'required'],
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'default', 'value' => null],
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'integer'],
            [['prog_code'], 'string', 'max' => 6],
            [['prog_short_name'], 'string', 'max' => 100],
            [['prog_full_name'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 20],
            [['prog_id'], 'unique'],
//            [['prog_cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgCategory::class, 'targetAttribute' => ['prog_cat_id' => 'prog_cat_id']],
//            [['prog_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgType::class, 'targetAttribute' => ['prog_type_id' => 'prog_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_id' => 'Prog ID',
            'prog_code' => 'Prog Code',
            'prog_short_name' => 'Prog Short Name',
            'prog_full_name' => 'Prog Full Name',
            'prog_type_id' => 'Prog Type ID',
            'prog_cat_id' => 'Prog Cat ID',
            'status' => 'Status',
        ];
    }
}
