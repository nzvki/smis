<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Update Required Course';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Prerequisites', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-course-prerequisite-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'prog_curr_group_requirement_id')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'prog_curriculum_course_id')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'credit_factor')->textInput(['type' => 'number']) ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            
        </div>
    </div>

</div>
