<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SmNameChangeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name_change_id') ?>

    <?= $form->field($model, 'request_date') ?>

    <?= $form->field($model, 'student_id') ?>

    <?= $form->field($model, 'new_surname') ?>

    <?= $form->field($model, 'new_othernames') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'document_url') ?>

    <?php // echo $form->field($model, 'current_surname') ?>

    <?php // echo $form->field($model, 'current_othernames') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
