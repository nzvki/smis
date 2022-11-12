<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgCountry;
use app\models\search\OrgCountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgCountryController implements the CRUD actions for OrgCountry model.
 */
class OrgCountryController extends Controller
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
     * Lists all OrgCountry models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCountrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCountry model.
     * @param string $country_code Country Code
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($country_code)
    {
        return $this->render('view', [
            'model' => $this->findModel($country_code),
        ]);
    }

    /**
     * Creates a new OrgCountry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCountry();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Country Created!');
//                return $this->redirect(['view', 'country_code' => $model->country_code]);
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgCountry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $country_code Country Code
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($country_code)
    {
        $model = $this->findModel($country_code);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'country_code' => $model->country_code]);
            Yii::$app->getSession()->setFlash('success', $model->country_name.' Updated!');
            return $this->redirect(['index']);

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgCountry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $country_code Country Code
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($country_code)
    {
        $this->findModel($country_code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCountry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $country_code Country Code
     * @return OrgCountry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($country_code)
    {
        if (($model = OrgCountry::findOne(['country_code' => $country_code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
