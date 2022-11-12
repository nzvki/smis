<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\OrgAcademicSession;
use yii\web\NotFoundHttpException;
use app\models\search\OrgAcademicSessionSearch;

/**
 * OrgAcademicSessionController implements the CRUD actions for OrgAcademicSession model.
 */
class OrgAcademicSessionController extends Controller
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
     * Lists all OrgAcademicSession models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcademicSessionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcademicSession model.
     * @param int $acad_session_id Acad Session ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_id),
        ]);
    }

    /**
     * Creates a new OrgAcademicSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcademicSession();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Academic Session Created!');
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
     * Updates an existing OrgAcademicSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_id Acad Session ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_id)
    {
        $model = $this->findModel($acad_session_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Academic Session Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcademicSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_id Acad Session ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_id)
    {
        $this->findModel($acad_session_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcademicSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_id Acad Session ID
     * @return OrgAcademicSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_id)
    {
        if (($model = OrgAcademicSession::findOne(['acad_session_id' => $acad_session_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
