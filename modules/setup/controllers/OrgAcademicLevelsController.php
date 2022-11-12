<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\OrgAcademicLevels;
use yii\web\NotFoundHttpException;
use app\models\search\OrgAcademicLevelsSearch;

/**
 * OrgAcademicLevelsController implements the CRUD actions for OrgAcademicLevels model.
 */
class OrgAcademicLevelsController extends Controller
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
     * Lists all OrgAcademicLevels models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcademicLevelsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcademicLevels model.
     * @param int $academic_level_id Academic Level ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($academic_level_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($academic_level_id),
        ]);
    }

    /**
     * Creates a new OrgAcademicLevels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcademicLevels();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Academic Level Created!');
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
     * Updates an existing OrgAcademicLevels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $academic_level_id Academic Level ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($academic_level_id)
    {
        $model = $this->findModel($academic_level_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Academic Level Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcademicLevels model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $academic_level_id Academic Level ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($academic_level_id)
    {
        $this->findModel($academic_level_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcademicLevels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $academic_level_id Academic Level ID
     * @return OrgAcademicLevels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($academic_level_id)
    {
        if (($model = OrgAcademicLevels::findOne(['academic_level_id' => $academic_level_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
