<?php

namespace app\modules\studentRecords\controllers;

use Yii;
use Mpdf\Mpdf;
use app\models\Student;
use yii\web\Controller;
use app\helpers\pdfHelper;
use yii\filters\VerbFilter;
use app\models\SmNameChange;
use yii\web\NotFoundHttpException;
use app\models\SmNameChangeApproval;
use app\models\search\SmNameChangeApprovalSearch;
use app\mailer\PHPMailer;
use app\mailer\SMTP;

/**
 * SmNameChangeApprovalController implements the CRUD actions for SmNameChangeApproval model.
 */
class SmNameChangeApprovalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SmNameChangeApproval models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmNameChangeApprovalSearch();
        // dd($this->request->queryParams);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmNameChangeApproval model.
     * @param int $name_change_approval_id Name Change Approval ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name_change_approval_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($name_change_approval_id),
        ]);
    }

    /**
     * Creates a new SmNameChangeApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

        $model = new SmNameChangeApproval();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $name_change=SmNameChange::find()->where(['name_change_id' => $model->name_change_id])->one();
                $name_change->status= $model->approval_status;
                if ($name_change->save() && $name_change->status == 'APPROVED') {
                    $this->generatePdfLetter($name_change);
                }
                Yii::$app->getSession()->setFlash('success', "Approval Status for  {$name_change->current_surname} updated to: ".$model->approval_status);
                return $this->redirect(['sm-name-change/view', 'name_change_id' =>$model->name_change_id]);
            }var_dump($model->getErrors());
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function generatePdfLetter(SmNameChange $name_change)
    {
        $stylesheet =  Yii::getAlias('@app'). DS .'web' .DS .'css' . 'mpdf.css';

        $this->layout = 'main-pdf';
        $student = Student::findOne($name_change->student_id);
        $file = str_replace("/", "_", $student->student_number) . '.pdf';
        $path = Yii::getAlias('@app'). DS .'uploads' .DS .'name-change-pdfs' . DS . $file;
        $mpdf = new \Mpdf\Mpdf(['tempDir' => Yii::getAlias('@app'). DS .'temp']);
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($this->render('approval-letter', ['name_change' => $name_change]), 2);
        $mpdf->Output($path, 'F');
        // $mpdf->Output('');
    }
    public function actionDownload()
    {
        $file=Yii::$app->request->get('file');
        $path=Yii::$app->request->get('document_url');
        $root=Yii::getAlias('@app/').$path;
        if (file_exists($root)) {
            return Yii::$app->response->sendFile($root);
        } else {
            throw new \yii\web\NotFoundHttpException("{$file} is not found!");
        }
    }

    public function actionTestEmail()
    {
        // $d = new \Symfony\Component\Mime\Email();
        // dd($d);
        // Yii::$app->mailer->compose('test')
        // ->setFrom('smisadmin@uonbi.ac.ke')
        // ->setTo('lkombo@uonbi.ac.ke')
        // ->setSubject('Testing')
        // ->send();
       $this->_mailSenderHelper();


//        Yii::$app->mailer->compose('test')
//        ->setFrom('smisadmin@uonbi.ac.ke')
//        ->setTo('lkombo@uonbi.ac.ke')
//        ->setSubject('testing')
//        ->send();
    }

    /**
     * Updates an existing SmNameChangeApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $name_change_approval_id Name Change Approval ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name_change_approval_id)
    {
        $model = $this->findModel($name_change_approval_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'name_change_approval_id' => $model->name_change_approval_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmNameChangeApproval model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $name_change_approval_id Name Change Approval ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name_change_approval_id)
    {
        $this->findModel($name_change_approval_id)->delete();

        return $this->redirect(['index']);
    }

    private function _mailSenderHelper() {

        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '31272492fb9f7a';
        $phpmailer->Password = '184db5f9dd02f6';




//        $mail = new PHPMailer;
//        $mail->isSMTP(true);
//        $mail->Host = 'smtp.gmail.com';
//        $mail->Port = 587;
//        $mail->SMTPAuth = true;
//        $mail->Username = 'tcs4@uonbi.ac.ke';
//        $mail->Password = 'Locckdown#2020';
        $phpmailer->From = 'tcs4@uonbi.ac.ke';
         //$mail->From = 'tcs4@uonbi.ac.ke';
        //$mail->SMTPSecure = 'ssl';
        $phpmailer->FromName = "UON Pensions";
       // $mail->FromName = "UON Pensions";
     //   $mail->isHTML(true);
        $phpmailer->addAddress('aodanga@uonbi.ac.ke');
        //$mail->addAddress('aodanga@uonbi.ac.ke');
        // $mail->AddCC('otagecole@yahoo.com','otagecole');
        // $mail->AddCC('uonpension@uonbi.ac.ke','UON Pensions');
        $phpmailer->Subject ='Test Email Sending';
       // $mail->Subject ='Test Email Sending';
        $phpmailer->Body = 'Eureka! Eureka!';
//        $mail->Body = 'Eureka! Eureka!';
        $phpmailer->send();
//        $mail->send();
    }




    /**
     * Finds the SmNameChangeApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $name_change_approval_id Name Change Approval ID
     * @return SmNameChangeApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($name_change_approval_id)
    {
        if (($model = SmNameChangeApproval::findOne(['name_change_approval_id' => $name_change_approval_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
