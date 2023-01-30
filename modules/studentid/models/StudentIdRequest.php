<?php

namespace app\modules\studentid\models;

use yii\base\InvalidConfigException;
use yii\db\DataReader;
use yii\db\Exception;

class StudentIdRequest extends \app\models\SmStudentIdRequest
{


    public $prog_type_name;
    public $prog_curriculum_id;
    public $student_category_id;
    public $reg_no;

    /**
     * @throws InvalidConfigException
     */

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['request_id', 'reg_no', 'prog_type_name'], 'safe'];

        return $rules;
    }

    public function attributeLabels(): array
    {
        $labels = parent::attributeLabels();

        $labels['request_type_id'] = 'Request type';
        $labels['student_prog_curr_id'] = 'Current programme';
        $labels['prog_full_name'] = 'Programme';
        $labels['std_category_name'] = 'Academic level';
        $labels['request_date'] = 'Request date';
        $labels['status_id'] = 'Request status';
        $labels['source'] = 'Request reason';

        $labels['student_category_id'] = $labels['std_category_name'];
        $labels['id_request_type'] = $labels['request_type_id'];
        $labels['prog_curriculum_id'] = $labels['prog_full_name'];
        return $labels;
    }

    public function attributeHints(): array
    {
        $hints = parent::attributeHints();

        $hints['source'] = 'Request reason';
        return $hints;
    }

    /**
     * @param int $pk
     * @return array|DataReader|null
     * @throws Exception
     */
    public static function findOneByPk(int $pk): DataReader|array|null
    {
        $query = self::baseQuery();

        $query .= <<<FILTER
            WHERE sm_student_id_request.request_id = :requestId
FILTER;

        $query = self::getDb()->createCommand($query);
        $data = $query->bindValues(['requestId' => $pk])->queryOne();
        if (!$data) {
            return null;
        }
        return $data;
    }

    /**
     * @param int $currProgId
     * @return DataReader|array|null
     * @throws Exception
     */
    public static function findOneByCurrProgId(int $currProgId, int $statusId): DataReader|array|null
    {
        $query = self::baseQuery();

        $query .= <<<FILTER
            WHERE sm_student_id_request.student_prog_curr_id = :currProgId
            AND sm_student_id_request.status_id = :statusId
FILTER;

        $query = self::getDb()->createCommand($query);
        $data = $query->bindValues([
            'currProgId' => $currProgId,
            'statusId' => $statusId
        ])->queryOne();
        if (!$data) {
            return null;
        }
        return $data;
    }

    private static function baseQuery(): string
    {
        return <<<SQL
SELECT
	sm_student_id_request.request_id,
	sm_student_id_request.student_prog_curr_id,
	sm_student_id_request.request_date,
	sm_student_id_request.status_id,
	sm_student_programme_curriculum.registration_number AS reg_no,
	sm_student_programme_curriculum.prog_curriculum_id,
	sm_student_category.std_category_name,
	org_programmes.prog_code,
	org_programmes.prog_short_name,
	org_programmes.prog_full_name,
	org_programmes.prog_type_id,
	org_programmes.prog_cat_id,
	org_prog_type.prog_type_code,
	org_prog_type.prog_type_name,
	sm_student.student_number,
	concat(sm_student.surname,' ',sm_student.other_names) as full_name,
	COALESCE ( sm_student.id_no, sm_student.passport_no ) AS id_pp,
	org_programme_curriculum.start_date,
	org_programme_curriculum.end_date
FROM
	sm_student_id_request
	INNER JOIN sm_student_programme_curriculum ON sm_student_id_request.student_prog_curr_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
	INNER JOIN org_programmes ON org_programme_curriculum.prog_id = org_programmes.prog_id
	INNER JOIN sm_student_category ON sm_student_programme_curriculum.student_category_id = sm_student_category.std_category_id
	INNER JOIN org_prog_type ON org_programmes.prog_type_id = org_prog_type.prog_type_id
	INNER JOIN sm_student ON sm_student_programme_curriculum.student_id = sm_student.student_id
SQL;
    }
}
