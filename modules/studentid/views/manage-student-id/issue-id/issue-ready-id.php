<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentIdDetails */
/* @var $form kartik\form\ActiveForm */

?>

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'remarks')->textarea(['rows' => 4]) ?>
    </div>

    <div class="card-footer">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord ? 'btn btn-success col-12' : 'btn btn-primary col-12'
            ]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

