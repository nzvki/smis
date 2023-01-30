<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-approval-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'withdrawal_request_id')->textInput() ?>

    <?= $form->field($model, 'approver_id')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'approval_status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
