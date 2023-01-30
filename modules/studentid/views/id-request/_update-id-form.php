<?php


use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentId */
/* @var $form kartik\form\ActiveForm */
?>

<div class="student-id-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md">
            <?= $form->field($model, 'student_id_serial_no')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'barcode')->textInput(['readonly' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <?= $form->field($model, 'issuance_date')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'valid_from')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'valid_to')->textInput(['readonly' => true]) ?>
        </div>
    </div>


    <?= $form->field($model, 'id_status')->widget(Select2::class, [
        'data' => $model->idStudentStatus(),
        'options' => [
            'placeholder' => 'Select id status',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>


    <?= $form->field($model, 'id_remarks')->textarea(['rows' => 5]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
