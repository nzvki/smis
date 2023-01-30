<?php

namespace app\modules\studentid\models\search;

use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdStatus;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Exception;

/**
 * app\modules\studentid\models\search\StudentIdSearch represents the model behind the search form about `app\modules\studentid\models\StudentId`.
 */
class StudentIdSearch extends StudentId
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode', 'request_id'], 'integer'],
            [['issuance_date', 'valid_from', 'valid_to', 'id_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string $studentIdStatus
     * @return SqlDataProvider
     * @throws Exception
     */
    public function search(array $params, string $studentIdStatus = StudentIdStatus::ID_READY): SqlDataProvider
    {
        $this->load($params);

        $count = self::getDb()->createCommand('SELECT COUNT(*) FROM sm_student_id')->queryScalar();

        $query = <<<STUDENT_ID
SELECT
	sm_student_id.student_id_serial_no,
	sm_student_id.student_prog_curr_id,
	sm_student_id.issuance_date,
	sm_student_id.valid_from,
	sm_student_id.valid_to,
	sm_student_id.printing_date,
	sm_student_id.barcode,
	sm_student_id.id_status,
	sm_student_programme_curriculum.registration_number,
	sm_student_programme_curriculum.prog_curriculum_id,
	sm_student_programme_curriculum.student_category_id,
	sm_student_category.std_category_name,
	concat(sm_student.surname,' ',sm_student.other_names) as full_name,
	org_programmes.prog_code,
	org_programmes.prog_short_name,
	org_programmes.prog_full_name
FROM
	sm_student_id
	INNER JOIN sm_student_programme_curriculum ON sm_student_id.student_prog_curr_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
	INNER JOIN org_programmes ON org_programme_curriculum.prog_id = org_programmes.prog_id
	INNER JOIN sm_student ON sm_student_programme_curriculum.student_id = sm_student.student_id
	INNER JOIN sm_student_category ON sm_student_programme_curriculum.student_category_id = sm_student_category.std_category_id
STUDENT_ID;

        $filters = [];

        $filters[] = "sm_student_id.id_status =  '$studentIdStatus'";


        if (count($filters) == 1) {
            $query .= ' WHERE ' . implode($filters);
        } elseif (count($filters) > 1) {
            $query .= ' WHERE ' . implode(' AND ', $filters);
        }

        return new SqlDataProvider([
            'sql' => $query,
            'db' => self::getDb(),
            'key' => 'student_id_serial_no',
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 15
            ]
        ]);
    }
}
