<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use Exception;
use Yii;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class BaseController extends Controller
{
    const REGISTERED_STATUS = 'REGISTERED';

    const PRE_REGISTERED_STATUS = 'PRE-REGISTRATION';

    const ACADEMIC_PROGRESS_STATUS_REGISTERED = 'REGISTERED';

    const ACADEMIC_PROGRESS_ACTIVE = 'ACTIVE';

    const STUDENT_SEMESTER_SESSION_STATUS_REPORTED = 'REPORTED';

    /**
     * Setup controllers with initial data
     * @return void
     * @throws ServerErrorHttpException
     */
    public function init(): void
    {
        try{
            parent::init();

        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function setFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->setFlash('new', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function addFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->addFlash('added', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * Create the page title
     * @param string $title
     * @return string full page title
     */
    protected function createPageTitle(string $title): string
    {
        return Yii::$app->params['sitename'] . ' - ' . $title;
    }
}