<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgProgLevel;
use app\models\search\OrgProgLevelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgLevelController implements the CRUD actions for OrgProgLevel model.
 */
class OrgProgLevelController extends Controller
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
     * Lists all OrgProgLevel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgLevelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgLevel model.
     * @param int $programme_level_id Programme Level ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($programme_level_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($programme_level_id),
        ]);
    }

    /**
     * Creates a new OrgProgLevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgLevel();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
              //  Yii::$app->getSession()->setFlash('success', $model->programme_level_name.' Created!');
               // return $this->redirect(['index']);
                return $this->redirect(['view', 'programme_level_id' => $model->programme_level_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgLevel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $programme_level_id Programme Level ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($programme_level_id)
    {
        $model = $this->findModel($programme_level_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->programme_level_name.' Updated!');
            return $this->redirect(['index']);
          //  return $this->redirect(['view', 'programme_level_id' => $model->programme_level_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgLevel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $programme_level_id Programme Level ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($programme_level_id)
    {
        $this->findModel($programme_level_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgLevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $programme_level_id Programme Level ID
     * @return OrgProgLevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($programme_level_id)
    {
        if (($model = OrgProgLevel::findOne(['programme_level_id' => $programme_level_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
