<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Update Level Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
//$this->params['breadcrumbs'][] = ['label' => 'Course Prerequisites', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-course-prerequisite-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'prog_curriculum_id')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'min_courses_taken')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'prog_study_level')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'min_pass_courses')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'pass_type')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'gpa_choice')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'gpa_courses')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'gpa_weight')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'pass_result')->textInput()?>
            <?= $form->field($model, 'fail_result')->textInput() ?>
            <?= $form->field($model, 'pass_recommendation')->textInput() ?>
            <?= $form->field($model, 'fail_recommendation')->textInput() ?>
            <?= $form->field($model, 'incomplete_result')->textInput() ?>
            <?= $form->field($model, 'incomplete_recommendation')->textInput() ?>


            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            
        </div>
    </div>

</div>

