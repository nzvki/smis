<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\Cohort;
use app\modules\studentRegistration\models\Intake;
use app\modules\studentRegistration\models\IntakeSource;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\ProgrammeCurriculum;
use app\modules\studentRegistration\models\search\DocumentsSearch;
use app\modules\studentRegistration\models\search\RegisteredStudentsSearch;
use app\modules\studentRegistration\models\SPAdmittedStudent;
use app\modules\studentRegistration\models\SPStudent;
use app\modules\studentRegistration\models\SPStudentCohortHistory;
use app\modules\studentRegistration\models\SPStudentProgCurriculum;
use app\modules\studentRegistration\models\SPSubmittedDocument;
use app\modules\studentRegistration\models\Student;
use app\modules\studentRegistration\models\StudentCategory;
use app\modules\studentRegistration\models\StudentCohortHistory;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use app\modules\studentRegistration\models\StudentStatus;
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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $admittedStudentsToSync = SPAdmittedStudent::find()->select(['adm_refno'])->where(['document_sync_status' => false])
                ->asArray()->all();

            if(count($admittedStudentsToSync) > 0){
                foreach ($admittedStudentsToSync as $admittedStudentToSync){
                    $admRefNo = $admittedStudentToSync['adm_refno'];

                    $spAdmittedStudent = SPAdmittedStudent::findOne($admRefNo);
                    $spAdmittedStudent->document_sync_status = true;
                    if(!$spAdmittedStudent->save()){
                        $errorMessage = 'Admitted student data failed to sync.';
                        if(!$spAdmittedStudent->validate()){
                            $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $admittedStudent = AdmittedStudent::findOne($admRefNo);
                    $admittedStudent->doc_submission_status = $spAdmittedStudent->doc_submission_status;
                    $admittedStudent->document_sync_status = $spAdmittedStudent->document_sync_status;
                    if(!$admittedStudent->save()){
                        $errorMessage = 'Admitted student data failed to sync.';
                        if(!$admittedStudent->validate()){
                            $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $submittedDocsCount = SubmittedDocument::find()->where(['adm_refno' => $admRefNo])->count();
                    if($submittedDocsCount > 0){
                        $deletedDocsCount = SubmittedDocument::deleteAll('adm_refno = ' . $admRefNo);
                        if($deletedDocsCount != $submittedDocsCount){
                            throw new ServerErrorHttpException('Submitted documents data failed to sync.', 500);
                        }
                    }

                    $spSubmittedDocsIds = SPSubmittedDocument::find()->select(['student_document_id'])->where(['adm_refno' => $admRefNo])
                        ->asArray()->all();
                    if(count($spSubmittedDocsIds) > 0){
                        foreach ($spSubmittedDocsIds as $spSubmittedDocId){
                            $submittedDocId = $spSubmittedDocId['student_document_id'];
                            $spSubmittedDoc = SPSubmittedDocument::findOne($submittedDocId);

                            $submittedDoc = new SubmittedDocument();
                            $submittedDoc->setAttributes($spSubmittedDoc->attributes);
                            if(!$submittedDoc->save()){
                                $errorMessage = 'Submitted documents data failed to sync.';
                                if(!$submittedDoc->validate()){
                                    $errorMessage = SmisHelper::getModelErrors($submittedDoc->getErrors());
                                }
                                throw new Exception($errorMessage);
                            }
                        }
                    }
                }
            }
            $transaction->commit();

            $docsSearchModel = new DocumentsSearch();
            $docsDataProvider = $docsSearchModel->search(Yii::$app->request->queryParams, [
                'submissionStatus' => true,
                'admissionStatus' => parent::PRE_REGISTERED_STATUS,
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
            $transaction->rollBack();
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
        $spTransaction = Yii::$app->db2->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $submittedDocId = $post['submittedDocId'];
            $status = $post['status'];

            if($status !== 'APPROVED' && $status !== 'NOT_APPROVED'){
                throw new Exception('Invalid document status.');
            }

            $submittedDoc = SubmittedDocument::findOne($submittedDocId);
            $submittedDoc->verify_status = $status;
            $submittedDoc->doc_comments = $post['comments'];
            if(!$submittedDoc->save()){
                $transaction->rollBack();
                $errorMessage = 'Document verify status not saved.';
                if(!$submittedDoc->validate()){
                    $errorMessage = SmisHelper::getModelErrors($submittedDoc->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }
                throw new Exception($errorMessage);
            }

            // Sync the submitted documents on the portal side
            $spSubmittedDoc = SPSubmittedDocument::findOne($submittedDocId);
            $spSubmittedDoc->verify_status = $submittedDoc->verify_status;
            $spSubmittedDoc->doc_comments = $submittedDoc->doc_comments;
            if(!$spSubmittedDoc->save()){
                $transaction->rollBack();
                $spTransaction->rollBack();
                $errorMessage = 'Document verify status failed to sync.';
                if(!$spSubmittedDoc->validate()){
                    $errorMessage = SmisHelper::getModelErrors($spSubmittedDoc->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }
                throw new Exception($errorMessage);
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
                $notApprovedDocuments = SubmittedDocument::find()->alias('sd')
                    ->where(['sd.adm_refno' => $submittedDoc->adm_refno, 'sd.verify_status' => 'NOT_APPROVED'])
                    ->joinWith('requiredDocument rd')
                    ->joinWith('requiredDocument.document doc')
                    ->andWhere(['doc.required' => true])
                    ->count();

                $admittedStudent = AdmittedStudent::findOne($submittedDoc->adm_refno);

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
                    $transaction->rollBack();
                    if(!$admittedStudent->validate()){
                        $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Changes not saved.');
                    }
                }

                // Sync admitted student data on the portal side
                $spAdmittedStudent = SPAdmittedStudent::findOne($submittedDoc->adm_refno);
                $spAdmittedStudent->clearance_status = $admittedStudent->clearance_status;
                $spAdmittedStudent->doc_submission_status = $admittedStudent->doc_submission_status;
                if(!$spAdmittedStudent->save()){
                    $transaction->rollBack();
                    $spTransaction->rollBack();
                    if(!$spAdmittedStudent->validate()){
                        $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Changes failed to sync.');
                    }
                }
            }

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Verify documents', 'Changes saved successfully.');
            if($redirectToIndex){
                return $this->redirect(['/student-registration/documents/index']);
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $spTransaction->rollBack();
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
        $spTransaction = Yii::$app->db2->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $admRefNo = $post['admRefNo'];
            if(!$this->studentCanBeAdmitted($admRefNo)){
                return $this->asJson(['success' => false,
                    'message' => 'This student can\'t be admitted yet. All their required documents are not fully approved.']);
            }

            $admittedStudent = AdmittedStudent::findOne($admRefNo);
            $admittedStudent->admission_status = parent::REGISTERED_STATUS;
            $admittedStudent->clearance_status = 'CLEARED';
            if(!$admittedStudent->save()){
                $transaction->rollBack();
                if(!$admittedStudent->validate()){
                    $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Student was not admitted.');
                }
            }

            // Sync the admitted student with student portal db
            $spAdmittedStudent = SPAdmittedStudent::findOne($admRefNo);
            $spAdmittedStudent->admission_status = $admittedStudent->admission_status;
            $spAdmittedStudent->clearance_status = $admittedStudent->clearance_status;
            if(!$spAdmittedStudent->save()){
                $transaction->rollBack();
                $spTransaction->rollBack();
                if(!$spAdmittedStudent->validate()){
                    $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Student admission data failed to sync.');
                }
            }

            $cohort = $this->getOpenCohort();

            $regNumber = $admittedStudent->uon_prog_code . '/' . $admittedStudent->adm_refno . '/' . $cohort['year'];

            $student = $this->createStudent($admittedStudent, $regNumber);

            $this->createStudentCohortHistory($student, $cohort['id']);

            $this->createStudentProgCurriculum($student, $admittedStudent);

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Verify documents', 'Student was registered successfully.');
            return $this->redirect(['/student-registration/documents/index']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

//    #[NoReturn]
//    public function actionTest()
//    {
//        $admittedStudent = AdmittedStudent::findOne('7108');
//
//        $cohort = $this->getOpenCohort();
//
//        $regNumber = $admittedStudent->uon_prog_code . '/' . $admittedStudent->adm_refno . '/' . $cohort['year'];
//
//        $student = $this->createStudent($admittedStudent, $regNumber);
//
//        $this->createStudentCohortHistory($student, $cohort['id']);
//
//        $this->createStudentProgCurriculum($student, $admittedStudent);
//    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "mixed", 'year' => "mixed"])]
    private function getOpenCohort(): array
    {
        /**
         * It is possible to have more than one cohort open.
         * We place a student in the cohort whose admission end date is furthest from the future.
         * The student's cohort may be changed later.
         */
        $cohort = Cohort::find()->select(['cohort_id', 'cohort_year'])->where(['cohort_status' => 'ACTIVE'])
            ->orderBy(['adm_end_date' => SORT_ASC])
            ->asArray()
            ->one();

        return [
            'id' => $cohort['cohort_id'],
            'year' => $cohort['cohort_year']
        ];
    }

    /**
     * @param AdmittedStudent $admittedStudent
     * @param string $regNumber
     * @return Student
     * @throws Exception
     */
    private function createStudent(AdmittedStudent $admittedStudent, string $regNumber): Student
    {
        $student = new Student();
        $student->student_number = $regNumber;
        $student->surname = $admittedStudent->surname;
        $student->other_names = $admittedStudent->other_names;
        $student->gender = $admittedStudent->gender;
        $student->country_code = 'KEN';
        $student->id_no = $admittedStudent->national_id;
        $student->passport_no = $admittedStudent->passport_no;
        $student->service_number = $admittedStudent->service_number;
        $student->service = $admittedStudent->service;
        $student->blood_group = $admittedStudent->blood_group;
        $student->sponsor = $admittedStudent->sponsor;
        $student->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
        $student->primary_phone_no = $admittedStudent->primary_phone_no;
        $student->alternative_phone_no = $admittedStudent->alternative_phone_no;
        $student->primary_email = $admittedStudent->primary_email;
        $student->alternative_email = $admittedStudent->alternative_email;
        $student->post_code = $admittedStudent->post_code;
        $student->post_address = $admittedStudent->post_address;
        $student->town = $admittedStudent->town;
        $student->nationality = $admittedStudent->nationality;
        $student->date_of_birth = $admittedStudent->date_of_birth;

        if(!$student->save()){
            if(!$student->validate()){
                $errorMessage = SmisHelper::getModelErrors($student->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student was not saved.');
            }
        }

        // Sync the registered student data with the student portal db
        $spStudent = new SPStudent();
        $spStudent->setAttributes($student->attributes);
        if(!$spStudent->save()){
            if(!$spStudent->validate()){
                $errorMessage = SmisHelper::getModelErrors($spStudent->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student data failed to sync.');
            }
        }

        return $student;
    }

    /**
     * @param Student $student
     * @param string $cohortId
     * @return void
     * @throws Exception
     */
    private function createStudentCohortHistory(Student $student, string $cohortId): void
    {
        $studentCohortHist = new StudentCohortHistory();
        $studentCohortHist->stud_id = $student->student_id;
        $studentCohortHist->cohort_id = $cohortId;
        $studentCohortHist->entry_date = $student->registration_date;
        $studentCohortHist->status = 'ACTIVE';
        $studentCohortHist->remark = parent::REGISTERED_STATUS;
        if(!$studentCohortHist->save()){
            if(!$studentCohortHist->validate()){
                $errorMessage = SmisHelper::getModelErrors($studentCohortHist->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student cohort history was not saved.');
            }
        }

        // Sync the registered student cohort history data with the student portal db
        $spStudentCohortHist = new SPStudentCohortHistory();
        $spStudentCohortHist->setAttributes($studentCohortHist->attributes);
        if(!$spStudentCohortHist->save()){
            if(!$spStudentCohortHist->validate()){
                $errorMessage = SmisHelper::getModelErrors($spStudentCohortHist->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student data failed to sync.');
            }
        }
    }

    /**
     * @param Student $student
     * @param AdmittedStudent $admittedStudent
     * @return void
     * @throws Exception
     */
    private function createStudentProgCurriculum(Student $student, AdmittedStudent $admittedStudent): void
    {
        $studProgCurr = new StudentProgCurriculum();
        $studProgCurr->student_id = $student->student_id;
        $studProgCurr->registration_number = $student->student_number;

        /**
         * New programme curriculums can be created.
         * Therefore, get the most recently created that is active.
         */
        $programme = Programme::find()->select(['prog_id'])->where(['prog_code' => $admittedStudent->uon_prog_code])->one();
        $progCurr = ProgrammeCurriculum::find()->select(['prog_curriculum_id'])
            ->where(['prog_id' => $programme->prog_id, 'status' => 'ACTIVE'])
            ->orderBy(['start_date' => SORT_DESC])
            ->one();
        $studProgCurr->prog_curriculum_id = $progCurr->prog_curriculum_id;

        $studProgCurr->student_category_id = $admittedStudent->student_category_id;
        $studProgCurr->adm_refno = $admittedStudent->adm_refno;

        $studentStatus = StudentStatus::find()->select(['status_id'])->where(['status' => 'ACTIVE', 'current_status' => true])->one();
        $studProgCurr->status_id = $studentStatus->status_id;

        if(!$studProgCurr->save()){
            if(!$studProgCurr->validate()){
                $errorMessage = SmisHelper::getModelErrors($studProgCurr->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student programme curriculum was not saved.');
            }
        }

        // Sync the registered student program curriculum data with the student portal db
        $spStudProgCurr = new SPStudentProgCurriculum();
        $spStudProgCurr->setAttributes($studProgCurr->attributes);
        if(!$spStudProgCurr->save()){
            if(!$spStudProgCurr->validate()){
                $errorMessage = SmisHelper::getModelErrors($spStudProgCurr->getErrors());
                throw new Exception($errorMessage);
            }else{
                throw new Exception('Student programme curriculum failed to sync.');
            }
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

        if($submittedDocuments === 0){
            return false;
        }

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