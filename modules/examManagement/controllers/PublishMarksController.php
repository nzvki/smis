<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/24/2023
 * @time: 11:59 AM
 */

namespace app\modules\examManagement\controllers;

use app\models\Employee;
use app\modules\examManagement\models\search\ProgrammesSearch;
use app\modules\examManagement\models\search\TimetablesSearch;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\ProgCurrSemesterGroup;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\StudyGroup;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Response;
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

            $employee = Employee::find()->select(['dept_code'])->where(['payroll_number' => Yii::$app->user->identity->username])
                ->asArray()->one();

            return $this->render('programmes', [
                'title' => 'Programmes',
                'departName' => $employee['dept_code'],
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
     * @param string $progCurrId
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionFirstStageFilters(string $progCode, string $progCurrId): string
    {
        try{
            $academicLevels = AcademicLevel::find()->orderBy(['academic_level' => SORT_ASC])->asArray()->all();
            return $this->render('firstStageFilters', [
                'title' => 'timetables filters',
                'progCode' => $progCode,
                'progCurrId' => $progCurrId,
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
     * @return Response
     */
    public function actionActiveCourseFilters(): Response
    {
        try{
            $session = Yii::$app->session;
            return $this->asJson(['success' => true, 'courseFilters' => $session['coursesFilter']]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
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
            $progCurrId = (array_key_exists('curr-id', $get)) ? $get['curr-id'] : '';
            $level = (array_key_exists('level', $get)) ? $get['level'] : '';
            $semesterCode = (array_key_exists('semester-code', $get)) ? $get['semester-code'] : '';
            $groupId = (array_key_exists('group-id', $get)) ? $get['group-id'] : '';

            $session = Yii::$app->session;
            $session['coursesFilter'] = [
                'year' => $year,
                'code' => $code,
                'progCurrId' => $progCurrId,
                'level' => $level,
                'semesterCode' => $semesterCode,
                'groupId' => $groupId
            ];

            $marksheetId = $year . '_' . $code . '_' . $level;

            $timetablesSearchModel = new TimetablesSearch();
            $dataProvider = $timetablesSearchModel->search(Yii::$app->request->queryParams, [
                'marksheetId' => $marksheetId,
                'semesterCode' => $semesterCode,
                'groupId' => $groupId
            ]);

            $academicLevels = AcademicLevel::find()->orderBy(['academic_level' => SORT_ASC])->asArray()->all();

            $groups = StudyGroup::find()->orderBy(['study_group_id' => SORT_ASC])
                ->where(['status' => 'ACTIVE'])
                ->asArray()->all();

            $semesterGroups = ProgCurrSemesterGroup::find()->alias('psg')
                ->select([
                    'psg.prog_curriculum_sem_group_id',
                    'psg.prog_curriculum_semester_id'
                ])
                ->where(['psg.programme_level' => $level])
                ->joinWith(['progCurrSemester ps' => function(ActiveQuery $q){
                    $q->select([
                        'ps.prog_curriculum_semester_id',
                        'ps.acad_session_semester_id',
                        'prog_curriculum_id'
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['ps.prog_curriculum_id' => $progCurrId])
                ->joinWith(['progCurrSemester.academicSessionSemester ass' => function(ActiveQuery $q){
                    $q->select([
                        'ass.acad_session_semester_id',
                        'ass.acad_session_id',
                        'ass.semester_code',
                        'ass.acad_session_semester_desc'
                    ]);
                }], true, 'INNER JOIN')
                ->joinWith(['progCurrSemester.academicSessionSemester.academicSession acs' => function(ActiveQuery $q){
                    $q->select([
                        'acs.acad_session_id',
                        'acs.acad_session_name'
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['acs.acad_session_name' => $year])
                ->asArray()
                ->all();

            $semesters = [];
            $semester = [];
            foreach ($semesterGroups as $semesterGroup){
                $acadSessSem = $semesterGroup['progCurrSemester']['academicSessionSemester'];

                $semester['code'] = $acadSessSem['semester_code'];
                $semester['description'] = $acadSessSem['acad_session_semester_desc'];

                $semesters[] = $semester;
            }

            $programme = Programme::find()->select(['prog_full_name', 'prog_short_name'])->where(['prog_code' => $code])->asArray()->one();

            return $this->render('courses', [
                'title' => 'courses',
                'progName' => $programme['prog_short_name'],
                'dataProvider' => $dataProvider,
                'searchModel' => $timetablesSearchModel,
                'year' => $year,
                'progCode' => $code,
                'progCurrId' => $progCurrId,
                'level' => $level,
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