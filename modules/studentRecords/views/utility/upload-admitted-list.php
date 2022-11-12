<?php

use app\models\generated\AdmittedStudent;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Admitted Students';
$this->params['breadcrumbs'][] = $this->title;

$ind = \app\models\Intakes::find()->all();
$src = \app\models\IntakeSource::findOne(1);
$intakes = \yii\helpers\ArrayHelper::map($ind,'intake_code','intake_name');

?>
<div class="admitted-student-index row">
    <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-md-6">
                <?=
                Html::dropDownList('intake',null,$intakes,['id'=>'intake','class'=>'form-select form-select-lg mb-3','prompt'=>'--* Choose Intake'])
                ?>
            </div>
            <div class="col-md-6">
                <?=Html::a('Download Template',['download-template'],['class'=>'btn btn-primary btn-lg float-end']) ?>
            </div>
            </div>
            <div class="col-md-6">
                <h4><?=$src->source?></h4>
                <?=Html::hiddenInput('source',$src->source_id)?>
            </div>
            <div class="col-6 col-sm-12">
                <?php
                echo FileInput::widget([
                    'name' => 'admit_list',
                    'options'=>[
                        'accept' => '.csv, 
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                        application/vnd.ms-excel',
                        'required'=>true,
                    ],
                    'pluginOptions' => [
                        'uploadUrl' => Url::to(['upload-admitted-list']),
                        'uploadExtraData' => [
                            'album_id' => 20,
                            'cat_id' => 'Nature'
                        ],
//                        'showPreview' => false,
//                        'showCaption' => true,
//                        'showRemove' => true,
//                        'showUpload' => false
                    ],
                    'pluginEvents' => [
                        'filepreajax'=> '(e)=> {
                            if(!$("#intake").val().length){
                                e.preventDefault(); 
                                $("#intake").focus();
                                $("button.fileinput-cancel-button").click();
                                alert("Choose an Intake!");
                            }
                        }',
                        'fileuploaded'=> '(e)=> {
                            alert("Uploaded Successfully")
                        }',
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
