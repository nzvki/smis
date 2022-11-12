<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgAcademicSessionSemester;
use app\models\search\OrgAcademicSessionSemesterSearch;

/**
 * OrgAcademicSessionSemesterController implements the CRUD actions for OrgAcademicSessionSemester model.
 */
class OrgAcademicSessionSemesterController extends Controller
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
     * Lists all OrgAcademicSessionSemester models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcademicSessionSemesterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcademicSessionSemester model.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_semester_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_semester_id),
        ]);
    }

    /**
     * Creates a new OrgAcademicSessionSemester model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcademicSessionSemester();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Academic Session Semester Created!');
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgAcademicSessionSemester model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_semester_id)
    {
        $model = $this->findModel($acad_session_semester_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $saved = $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Academic Session Semester Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcademicSessionSemester model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_semester_id)
    {
        $this->findModel($acad_session_semester_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcademicSessionSemester model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return OrgAcademicSessionSemester the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_semester_id)
    {
        if (($model = OrgAcademicSessionSemester::findOne(['acad_session_semester_id' => $acad_session_semester_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
