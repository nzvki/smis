<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-cohort-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'cohort_desc')
                ->label('Cohort Description', ['class'=>'mb-2 fw-bold'])
                ->textInput(['maxlength' => true])

            ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
