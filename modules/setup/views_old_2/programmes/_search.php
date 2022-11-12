<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ProgrammesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programmes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_id') ?>

    <?= $form->field($model, 'prog_code') ?>

    <?= $form->field($model, 'prog_short_name') ?>

    <?= $form->field($model, 'prog_full_name') ?>

    <?= $form->field($model, 'prog_type_id') ?>

    <?php // echo $form->field($model, 'prog_cat_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
