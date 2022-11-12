<?php

namespace app\modules\studentRecords\controllers;

use app\models\SmNameChangeApproval;
use app\models\search\SmNameChangeApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                return $this->redirect(['view', 'name_change_approval_id' => $model->name_change_approval_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
