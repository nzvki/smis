<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration;

use Yii;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        Yii::configure(Yii::$app, require __DIR__ . '/config/web.php');

        $this->layout = 'main';

        $this->controllerNamespace = 'app\modules\studentRegistration\controllers';

        $handler = $this->get('errorHandler');

        Yii::$app->set('errorHandler', $handler);

        $handler->register();
    }
}
