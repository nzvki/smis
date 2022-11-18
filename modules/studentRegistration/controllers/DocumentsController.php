<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\Intake;
use app\modules\studentRegistration\models\IntakeSource;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\search\DocumentsSearch;
use app\modules\studentRegistration\models\StudentCategory;
use app\modules\studentRegistration\models\SubmittedDocument;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class DocumentsController extends BaseController
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
     */
    public function actionIndex(): string
    {
        try {
            $docsSearchModel = new DocumentsSearch();
            $docsDataProvider = $docsSearchModel->search(Yii::$app->request->queryParams, [
                'submissionStatus' => true,
                'admissionStatus' => 'PRE-REGISTRATION',
            ]);

            $intakes = Intake::find()->select(['intake_code', 'intake_name'])->asArray()->all();
            $intakesList = ArrayHelper::map($intakes, 'intake_code', function ($intake) {
                return $intake['intake_name'];
            });

            $intakeSources = IntakeSource::find()->select(['source_id', 'source'])->asArray()->all();
            $intakeSourcesList = ArrayHelper::map($intakeSources, 'source_id', function ($intakeSource) {
                return $intakeSource['source'];
            });

            $programmes = Programme::find()->select(['prog_code', 'prog_full_name'])->asArray()->all();
            $programmesList = ArrayHelper::map($programmes, 'prog_code', function ($programme) {
                return $programme['prog_full_name'];
            });

            $categories = StudentCategory::find()->select(['std_category_id', 'std_category_name'])->asArray()->all();
            $categoriesList = ArrayHelper::map($categories, 'std_category_id', function ($category) {
                return $category['std_category_name'];
            });

            return $this->render('index', [
                'title' => $this->createPageTitle('students'),
                'docsSearchModel' => $docsSearchModel,
                'docsDataProvider' => $docsDataProvider,
                'intakesList' => $intakesList,
                'intakeSourcesList' => $intakeSourcesList,
                'programmesList' => $programmesList,
                'categoriesList' => $categoriesList
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @param string $admRefNo
     * @return Response|string
     * @throws ServerErrorHttpException
     */
    public function actionVerify(string $admRefNo): Response|string
    {
       try{
           $submittedDocuments = SubmittedDocument::find()->alias('sd')
               ->where(['sd.adm_refno' => $admRefNo])
               ->joinWith('requiredDocument rd')
               ->joinWith('requiredDocument.document doc')
               ->asArray()
               ->all();
           return $this->render('verify', [
               'title' => $this->createPageTitle('verify documents'),
               'admRefNo' => $admRefNo,
               'submittedDocuments' => $submittedDocuments,
               'canBeAdmitted' => $this->studentCanBeAdmitted($admRefNo)
           ]);
       }catch (Exception $ex){
           $message = $ex->getMessage();
           if(YII_ENV_DEV){
               $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
           }
           throw new ServerErrorHttpException($message, 500);
       }
    }

    /**
     * @return Response
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();

            $status = $post['status'];
            if($status !== 'APPROVED' && $status !== 'NOT_APPROVED'){
                throw new Exception('Invalid document status.');
            }

            $submittedDoc = SubmittedDocument::findOne($post['submittedDocId']);
            $submittedDoc->verify_status = $status;
            $submittedDoc->doc_comments = $post['comments'];
            if(!$submittedDoc->save()){
                if(!$submittedDoc->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($submittedDoc->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Changes not saved.');
                }
            }

            /**
             * If all mandatory documents submitted have been checked, ie have status APPROVED or NOT_APPROVED,
             * and at least one has not been approved, notify the student and update clearance status to NOT_CLEARED.
             * Otherwise, if all documents are approved then clearance status is updated to PENDING.
             * This clearance status is only updated from PENDING to CLEARED, if the admin clears the student.
             * @check method actionClearStudent
             */
            // make sure all documents have no status PENDING
            $pendingDocuments = SubmittedDocument::find()->alias('sd')
                ->where(['sd.adm_refno' => $submittedDoc->adm_refno, 'sd.verify_status' => 'PENDING'])
                ->joinWith('requiredDocument rd')
                ->joinWith('requiredDocument.document doc')
                ->andWhere(['doc.required' => true])
                ->count();

            $redirectToIndex = false;
            if((int)$pendingDocuments === 0){
                $admittedStudent = AdmittedStudent::findOne($submittedDoc->adm_refno);

                $notApprovedDocuments = SubmittedDocument::find()->alias('sd')
                    ->where(['sd.adm_refno' => $submittedDoc->adm_refno, 'sd.verify_status' => 'NOT_APPROVED'])
                    ->joinWith('requiredDocument rd')
                    ->joinWith('requiredDocument.document doc')
                    ->andWhere(['doc.required' => true])
                    ->count();

                if((int)$notApprovedDocuments > 0){
                    $admittedStudent->clearance_status = 'NOT_CLEARED'; // Enables to check for reports of students whose documents have been returned
                    $admittedStudent->doc_submission_status = false; // Enables re-upload for the student
                }else{
                    $admittedStudent->clearance_status = 'PENDING';
                }

                if($admittedStudent->save()){
                    if((int)$notApprovedDocuments > 0){
                        $redirectToIndex = true; // No more action is to be done on this student's documents until they re-upload and submit again
                        $this->sendEmailDocsReUpload($admittedStudent->surname, $admittedStudent->primary_email);
                    }
                }else{
                    if(!$admittedStudent->validate()){
                        $transaction->rollBack();
                        $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Changes not saved.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Verify documents', 'Changes saved successfully.');
            if($redirectToIndex){
                return $this->redirect(['/student-registration/documents/index']);
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionClearStudent(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();

            if(!$this->studentCanBeAdmitted($post['admRefNo'])){
                return $this->asJson(['success' => false,
                    'message' => 'This student can\'t be admitted yet. All their required documents are not fully approved.']);
            }

            $admittedStudent = AdmittedStudent::findOne($post['admRefNo']);
            $admittedStudent->admission_status = 'REGISTERED';
            $admittedStudent->clearance_status = 'CLEARED';
            if(!$admittedStudent->save()){
                if(!$admittedStudent->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Student was not admitted.');
                }
            }
            $transaction->commit();
            $this->setFlash('success', 'Verify documents', 'Student was admitted successfully.');
            return $this->redirect(['/student-registration/documents/index']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Check status of all submitted documents that are mandatory
     * @param string $admRefNo
     * @return bool True if all are APPROVED. False if any is NOT_APPROVED.
     */
    private function studentCanBeAdmitted(string $admRefNo): bool
    {
        $submittedDocuments = SubmittedDocument::find()->alias('sd')
            ->where(['sd.adm_refno' => $admRefNo])
            ->joinWith('requiredDocument rd')
            ->joinWith('requiredDocument.document doc')
            ->andWhere(['doc.required' => true])
            ->count();

        $approvedDocuments = SubmittedDocument::find()->alias('sd')
            ->where(['sd.adm_refno' => $admRefNo, 'sd.verify_status' => 'APPROVED'])
            ->joinWith('requiredDocument rd')
            ->joinWith('requiredDocument.document doc')
            ->andWhere(['doc.required' => true])
            ->count();

        if((int)$submittedDocuments === (int)$approvedDocuments){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param string $recipientName
     * @param string $recipientEmail
     * @return void
     * @throws Exception
     */
    private function sendEmailDocsReUpload(string $recipientName, string $recipientEmail): void
    {
        $emails = [
            'recipientEmail' => $recipientEmail,
            'subject' => 'REGISTRATION DOCUMENTS UPLOAD',
            'params' => [
                'recipient' => $recipientName,
            ]
        ];
        $layout = '@app/modules/studentRegistration/mail/layouts/html';
        $view = '@app/modules/studentRegistration/mail/views/reUploadDocuments';
        SmisHelper::sendEmails([$emails], $layout, $view);
    }
}