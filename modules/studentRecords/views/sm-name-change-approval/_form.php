<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-approval-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_change_approval_id')->textInput() ?>

    <?= $form->field($model, 'name_change_id')->textInput() ?>

    <?= $form->field($model, 'approval_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approved_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approval_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
