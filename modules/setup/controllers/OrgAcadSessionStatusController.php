<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgAcadSessionStatus;
use app\models\search\OrgAcadSessionStatusSearch;

/**
 * OrgAcadSessionStatusController implements the CRUD actions for OrgAcadSessionStatus model.
 */
class OrgAcadSessionStatusController extends Controller
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
     * Lists all OrgAcadSessionStatus models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcadSessionStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcadSessionStatus model.
     * @param int $acad_session_status_id Acad Session Status ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_status_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_status_id),
        ]);
    }

    /**
     * Creates a new OrgAcadSessionStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcadSessionStatus();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Academic Session Status Created!');
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
     * Updates an existing OrgAcadSessionStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_status_id Acad Session Status ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_status_id)
    {
        $model = $this->findModel($acad_session_status_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Academic Session Status Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcadSessionStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_status_id Acad Session Status ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_status_id)
    {
        $this->findModel($acad_session_status_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcadSessionStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_status_id Acad Session Status ID
     * @return OrgAcadSessionStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_status_id)
    {
        if (($model = OrgAcadSessionStatus::findOne(['acad_session_status_id' => $acad_session_status_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
