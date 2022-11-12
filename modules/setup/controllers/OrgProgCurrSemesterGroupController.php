<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgProgCurrSemesterGroup;
use app\models\search\OrgProgCurrSemesterGroupSearch;

/**
 * OrgProgCurrSemesterGroupController implements the CRUD actions for OrgProgCurrSemesterGroup model.
 */
class OrgProgCurrSemesterGroupController extends Controller
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
     * Lists all OrgProgCurrSemesterGroup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrSemesterGroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrSemesterGroup model.
     * @param int $prog_curriculum_sem_group_id Prog Curriculum Sem Group ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_sem_group_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_sem_group_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrSemesterGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrSemesterGroup();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Program Curriculum Semester Group Created!');
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
     * Updates an existing OrgProgCurrSemesterGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_sem_group_id Prog Curriculum Sem Group ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_sem_group_id)
    {
        $model = $this->findModel($prog_curriculum_sem_group_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Program Curriculum Semester Group Updated!');
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrSemesterGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_sem_group_id Prog Curriculum Sem Group ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_sem_group_id)
    {
        $this->findModel($prog_curriculum_sem_group_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrSemesterGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_sem_group_id Prog Curriculum Sem Group ID
     * @return OrgProgCurrSemesterGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_sem_group_id)
    {
        if (($model = OrgProgCurrSemesterGroup::findOne(['prog_curriculum_sem_group_id' => $prog_curriculum_sem_group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
