<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cr_programme_curr_lecture_timetable".
 *
 * @property int $lecture_timetable_id
 * @property int $timetable_id
 * @property int|null $lecture_room_id
 * @property int|null $day_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int|null $class_code
 *
 * @property CrProgCurrTimetable $timetable
 */
class CrProgrammeCurrLectureTimetable extends \app\extended\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_programme_curr_lecture_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lecture_timetable_id', 'timetable_id'], 'required'],
            [['lecture_timetable_id', 'timetable_id', 'lecture_room_id', 'day_id', 'class_code'], 'default', 'value' => null],
            [['lecture_timetable_id', 'timetable_id', 'lecture_room_id', 'day_id', 'class_code'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['lecture_timetable_id'], 'unique'],
            [['timetable_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrProgCurrTimetable::class, 'targetAttribute' => ['timetable_id' => 'timetable_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lecture_timetable_id' => 'Lecture Timetable ID',
            'timetable_id' => 'Timetable ID',
            'lecture_room_id' => 'Lecture Room ID',
            'day_id' => 'Day ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'class_code' => 'Class Code',
        ];
    }

    /**
     * Gets query for [[Timetable]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetable()
    {
        return $this->hasOne(CrProgCurrTimetable::class, ['timetable_id' => 'timetable_id']);
    }
}
