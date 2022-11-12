<?php

namespace app\modules\studentRecords\controllers;

use app\models\SmNameChange;
use app\models\search\SmNameChangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmNameChangeController implements the CRUD actions for SmNameChange model.
 */
class SmNameChangeController extends Controller
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
     * Lists all SmNameChange models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmNameChangeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmNameChange model.
     * @param int $name_change_id Name Change ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name_change_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($name_change_id),
        ]);
    }

    /**
     * Creates a new SmNameChange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmNameChange();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'name_change_id' => $model->name_change_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmNameChange model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $name_change_id Name Change ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name_change_id)
    {
        $model = $this->findModel($name_change_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'name_change_id' => $model->name_change_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmNameChange model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $name_change_id Name Change ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name_change_id)
    {
        $this->findModel($name_change_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmNameChange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $name_change_id Name Change ID
     * @return SmNameChange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name_change_id)
    {
        if (($model = SmNameChange::findOne(['name_change_id' => $name_change_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
