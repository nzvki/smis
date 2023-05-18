<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/8/2023
 * @time: 10:13 PM
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\LoginForm;
use app\models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        }
        return false;
    }

    /**
     * @return string|Response
     */
    public function actionIndex(): Response|string
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }
        return $this->render('index');
    }

    /**
     * @return string|\yii\console\Response|Response
     * @throws ServerErrorHttpException
     */
    public function actionLogin(): Response|string|\yii\console\Response
    {
        try {
            if (Yii::$app->user->isGuest) {
                $this->layout = 'login';
                return $this->render('login', [
                    'title' => $this->createPageTitle('login'),
                    'model' => new LoginForm()
                ]);
            } else {
                return Yii::$app->response->redirect(['/site/index']);
            }
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response|string|\yii\console\Response
     * @throws ServerErrorHttpException
     */
    public function actionProcessLogin(): Response|string|\yii\console\Response
    {
        try {
            $model = new LoginForm();
            if($model->load(Yii::$app->request->post())){
                if($model->validate()){
                    $user = User::findByUsername($model->username);

                    if(empty($user) || empty($user->password) || !$user->validatePassword($model->password)){
                        $this->setFlash('danger', 'Login', 'Incorrect username or password.');
                        return $this->redirect(['/site/login']);
                    }

                    if(Yii::$app->user->login($user)){
                        $user->last_login_at = SmisHelper::formatDate('now', 'Y-m-d h:i:s');
                        if(!$user->save()){
                            throw new Exception('Failed to update login time.');
                        }
                        return $this->goHome();
                    }else{
                        throw new Exception('An error occurred while trying to log in.');
                    }
                }else{
                    $this->setFlash('danger', 'Login', 'Incorrect username or password.');
                    return $this->redirect(['/site/login']);
                }
            }
            return $this->redirect(['/site/login']);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
