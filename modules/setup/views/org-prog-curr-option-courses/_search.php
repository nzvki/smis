<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrOptionCoursesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-option-courses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'option_course_id') ?>

    <?= $form->field($model, 'option_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'prog_cur_id') ?>

    <?= $form->field($model, 'course_type') ?>

    <?php // echo $form->field($model, 'degree_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
