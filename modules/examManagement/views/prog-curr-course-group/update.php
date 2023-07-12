<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCourses */

$this->title = 'Update Course Prerequisite';// . $model->course_name;
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Group', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-course-prerequisite-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'course_group_name')->textInput() ?>
            <?= $form->field($model, 'course_group_desc')->textInput() ?>
            <?= $form->field($model, 'course_group_type')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            

</div>
</div>
</div>
