<?php

namespace app\modules\studentid\controllers;

use app\models\SmStudentId;
use app\modules\studentid\models\IdRequestStatus;
use app\modules\studentid\models\search\StudentIdRequestSearch;
use app\modules\studentid\models\search\StudentIdSearch;
use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdDetails;
use app\modules\studentid\models\StudentIdRequest;
use app\modules\studentid\models\StudentIdStatus;
use Yii;
use yii\db\DataReader;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ManageStudentIdController implements the CRUD actions for StudentIdRequest model.
 */
class ManageStudentIdController extends Controller
{
//    public $layout = 'domain';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'issue-ready-id' => ['get', 'post'],
                    'print-bulk' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all StudentIdRequest models.
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $searchModel = new StudentIdRequestSearch();
        $dataProvider = $searchModel->searchIdRequests(Yii::$app->request->queryParams);

        $this->view->title = 'Pending ID requests';

        return $this->render('id-request/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIssue(): string
    {

        $searchModel = new StudentIdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->view->title = 'Issue printed IDs';

        return $this->render('issue-id/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string
     * @throws Exception
     */
    public function actionIssued(): string
    {

        $searchModel = new StudentIdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, StudentIdStatus::ID_ACTIVE);

        $this->view->title = 'View issue ids';

        return $this->render('issue-id/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionIssueReadyId($id): Response|string
    {
        $model = new StudentIdDetails();

        $model->student_id_serial_no = $id;
        if ($model->load(Yii::$app->request->post())) {
            $model->student_id_status = StudentIdStatus::ID_ISSUED;
            $model->status_date = new Expression('CURRENT_TIMESTAMP');

            $transaction = StudentIdDetails::getDb()->beginTransaction();

            $studentId = SmStudentId::findOne($id);
            if ($model->save()) {
                //update the overall status
                if ($studentId != null) {
                    $studentId->id_status = StudentIdStatus::ID_ACTIVE;
                    if ($studentId->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Student ID issued successfully");
                        return $this->redirect(['print-id',
                            'id' => $model->student_id_serial_no,
                        ]);
                    }
                }
                $transaction->rollBack();
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Student ID not issued successfully");
            }
        }

        $this->view->title = 'Issue student id';
        return $this->render('issue-id/issue-ready-id', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return false|string
     * @throws Exception
     */
    public function actionPrintId($id): bool|string
    {
        $model = StudentId::findOne($id);

        $requestStatus = IdRequestStatus::getStatusId(IdRequestStatus::CLOSED);
        $idRequest = StudentIdRequest::findOneByCurrProgId($model->student_prog_curr_id, $requestStatus[0]);

        $this->view->title = 'Print single ID card';
        return $this->render('print-id/print-single', [
            'model' => $model,
            'idRequest' => $idRequest
        ]);

    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException|Exception
     */
    public function actionPrintSingle(int $id): string|Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        //let us check for the data


        $model = $this->findBySql($id);

        //updated id request status
        $requestStatus = IdRequestStatus::getStatusId();
        $idRequest = $this->findModel($id, $requestStatus[0]);
        $newId = new StudentId();
        $newId->student_prog_curr_id = $model['student_prog_curr_id'];
        $newId->printing_date = new Expression('CURRENT_TIMESTAMP');
        $newId->issuance_date = new Expression('CURRENT_TIMESTAMP');
        $newId->valid_from = $model['start_date'];
        $newId->valid_to = $model['start_date'];
        $newId->barcode = $idRequest->request_id;
        $newId->id_status = StudentIdStatus::ID_READY;
        $newId->id_remarks = StudentIdStatus::ID_READY;
//@TODO check autoincrement in prod database       $newId->student_id_serial_no = 4;


        //save the data now
        $newId->validate();
        $saved = $newId->save();
        if ($saved) {
            //update request to closed
            $newStatus = IdRequestStatus::getStatusId(IdRequestStatus::CLOSED);

            $idRequest->status_id = ArrayHelper::getValue($newStatus, 0);
            $idRequest->validate();
            $saved = $idRequest->save();
        }

        if (!$saved) {
            $errors = [
                $newId->errors,
                $idRequest->errors
            ];
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', "There was an  error printing selected id: " . json_encode($errors));
            return $this->redirect(['index']);
        }
        $transaction->commit();
        Yii::$app->session->setFlash('success', "Student ID printed successfully");
        return $this->redirect(['issue']);
    }

    /**
     * @return false|string|Response
     */
    public function actionPrintBulk(): Response|bool|string
    {
        $selectedIds = Yii::$app->request->post('selection', []);
        if (count($selectedIds) < 2) {
            //redirect back to index
            Yii::$app->session->setFlash('error', "Please select at least 2 records for batch printing");
            return $this->redirect(['index']);
        }
        return json_encode($selectedIds);
    }


    /**
     * Finds the StudentIdRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|bool|DataReader
     * @throws Exception if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findBySql(int $id): DataReader|bool|array
    {
        if (($model = StudentIdRequest::findOneByPk($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested id request record does not exist.');
    }

    /**
     * Finds the StudentIdRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param int $statusId
     * @return StudentIdRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id, int $statusId): StudentIdRequest
    {
        if (($model = StudentIdRequest::findOne([
                'request_id' => $id,
                'status_id' => $statusId
            ])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested record does not exist.');
    }
}
