<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/24/2023
 * @time: 11:59 AM
 */

namespace app\modules\examManagement\controllers;

use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\SemesterCode;
use app\modules\examManagement\models\search\ProgrammesSearch;
use app\modules\examManagement\models\search\TimetablesSearch;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\StudyGroup;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;

class PublishMarksController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * @return string
     * @throws ServerErrorHttpException
     * @todo Filter according to users program
     */
    public function actionProgrammes(): string
    {
        try{
            $progSearchModel = new ProgrammesSearch();
            $dataProvider = $progSearchModel->search(Yii::$app->request->queryParams);

            return $this->render('programmes', [
                'title' => 'Programmes',
                'departName' => 'COMPUTER SCIENCE',
                'progDataProvider' => $dataProvider,
                'progSearchModel' => $progSearchModel
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @param string $progCode
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionFirstStageFilters(string $progCode): string
    {
        try{
            $academicLevels = AcademicLevel::find()->orderBy(['academic_level' => SORT_ASC])->asArray()->all();
//            $programme = Programme::find()->where([''])
            return $this->render('firstStageFilters', [
                'title' => 'timetables filters',
                'progCode' => $progCode,
                'academicLevels' => $academicLevels
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionCourses(): string
    {
        try{
            $get = Yii::$app->request->get();

            $year = (array_key_exists('year', $get)) ? $get['year'] : '';
            $code = (array_key_exists('code', $get)) ? $get['code'] : '';
            $level = (array_key_exists('level', $get)) ? $get['level'] : '';
            $semester = (array_key_exists('semester', $get)) ? $get['semester'] : '';
            $group = (array_key_exists('group', $get)) ? $get['group'] : '';

            $session = Yii::$app->session;
            $session['coursesFilter'] = [
                'year' => $year,
                'code' => $code,
                'level' => $level,
                'semester' => $semester,
                'group' => $group
            ];

            $marksheetId = $year . '_' . $code . '_' . $level;

            $timetablesSearchModel = new TimetablesSearch();
            $dataProvider = $timetablesSearchModel->search(Yii::$app->request->queryParams, [
                'marksheetId' => $marksheetId,
                'semester' => $semester,
                'group' => $group
            ]);
            dd($dataProvider->getModels());

            $academicLevels = AcademicLevel::find()->orderBy(['academic_level' => SORT_ASC])->asArray()->all();

            $groups = StudyGroup::find()->orderBy(['study_group_id' => SORT_ASC])
                ->where(['status' => 'ACTIVE'])
                ->asArray()->all();

            $semesters = SemesterCode::find()->orderBy(['semester_code' => SORT_ASC])->asArray()->all();

            return $this->render('courses', [
                'title' => 'Programmes',
                'progName' => 'BSC. COMPUTER SCIENCE',
                'dataProvider' => $dataProvider,
                'searchModel' => $timetablesSearchModel,
                'year' => $get['year'],
                'progCode' => $get['code'],
                'level' => $get['level'],
                'academicLevels' => $academicLevels,
                'groups' => $groups,
                'semesters' => $semesters
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionStudents()
    {

    }
}