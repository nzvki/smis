<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SmNameChangeApprovalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-approval-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name_change_approval_id') ?>

    <?= $form->field($model, 'name_change_id') ?>

    <?= $form->field($model, 'approval_status') ?>

    <?= $form->field($model, 'remarks') ?>

    <?= $form->field($model, 'approved_by') ?>

    <?php // echo $form->field($model, 'approval_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
