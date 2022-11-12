<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-unit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'org_unit_history_id')->textInput() ?>

    <?= $form->field($model, 'prog_curriculum_id')->textInput() ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
