<?php

namespace app\modules\studentRecords\controllers;

use app\models\AdmittedStudent;
use app\models\OrgCountry;
use app\models\search\AdmittedStudentSearch;
use app\models\search\StudentSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * ReportsController implements the actions for Student Reports.
 */
class ReportsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ],
        );
    }

    /**
     * Reports default page
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Student vs Sponsors List
     *
     * @param int $id
     * @return string
     */
    public function actionStudentsPerSponsor(int $id=0): string
    {
        if($id==='') {
            $id = 0;
        }
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['sponsor'=>$id]);
        $dataProvider->pagination = false;
        return $this->render('students-per-sponsor',[
            'id'=>$id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * StudentNationality Stats Report
     *
     * @return string
     */
    public function actionStudentNationalityStats(): string
    {
        $data = OrgCountry::find()->alias("CN")->select(["CN.nationality","COUNT(*) student_count"])
        ->innerJoinWith("students")->groupBy("CN.nationality")->asArray()->all();

        if(empty($data)){
            \Yii::$app->getSession()->setFlash('danger', [
                'type' => 'warning',
                'message' => 'No Data found!',
                'title' => 'Note Report',
            ]);
        }else{
            $columns = array_keys($data[0]);
            array_splice($columns,2);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => $columns
            ],
        ]);
        return $this->render('student-nationality-stats', [
            'dataProvider'=> $dataProvider,
            'columns' => $columns,
        ]);

    }

    /**
     * AdmittedStudent List Analysis
     *
     * @param string $intake
     * @return string
     */
    public function actionNominalAdmissionsAnalysis(string $intake=''): string
    {
        if(empty($intake)) $intake = 0;
        $columns=[];
        $md = AdmittedStudent::find()->alias("AS")->select(["AS.admission_status","COUNT(*) student_count"])
            ->groupBy("AS.admission_status");
//        exit;
        if($intake!==0){
            $md->andWhere(['intake_code'=>$intake]);
        }
        $data = $md->asArray()->all();


        if(empty($data)){
            \Yii::$app->getSession()->setFlash('danger', [
                'type' => 'warning',
                'message' => 'No Data found!',
                'title' => 'Analysis Report',
            ]);
        }else{
            $columns = array_keys($data[0]);
            array_splice($columns,2);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => $columns
            ],
        ]);
        return $this->render('nominal-admissions-analysis', [
            'intake'=>$intake,
            'dataProvider'=> $dataProvider,
            'columns' => $columns,
        ]);
    }

    /**
     * Lists all AdmittedStudent models.
     *
     * @return string
     */
    public function actionAdmittedList(): string
    {
        $searchModel = new AdmittedStudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('admitted-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
