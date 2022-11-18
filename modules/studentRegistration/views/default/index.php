<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 */

use kartik\file\FileInput;
use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Registration
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            Uploaded Documents
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            National Id card
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            Pending
        </h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <?php
                    $fileUrl = Yii::getAlias('@app') . '\modules\studentRegistration\uploads\registration\6001\document_1\slide_4.jpg';
                    echo $fileUrl;
                ?>

                <?=
                FileInput::widget( [
                    'name'=>'docs[]',
                    'id'=>'_docs',
//                    'disabled' => true,
                    'options' => ['multiple' => true],
//                    'options' => ['accept' => 'application/pdf','multiple' => true,],
//                    'pluginOptions' => [],
//                    'disabled' => true,
                    'pluginOptions' => [
                        'previewFileType' => 'any',
                        'initialPreview'=>[
//                            $fileUrl,
                            'http://localhost:81/smis/web/uploads/registration/6001/document_1/id_card.pdf',

                            'http://localhost:81/smis/web/uploads/registration/6001/document_1/slide_4.jpg',
//                            "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/631px-FullMoon2010.jpg",
//                            "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Earth_Eastern_Hemisphere.jpg/600px-Earth_Eastern_Hemisphere.jpg"
                        ],
                        'initialPreviewAsData' => true,
//                        'initialCaption' => "The Moon and the Earth",
//                        'initialPreviewConfig' => [
//                            ['caption' => 'Moon.jpg', 'size' => '873727'],
////                            ['caption' => 'Earth.jpg', 'size' => '1287883'],
//                        ],
                        'overwriteInitial' => true,
//                        'showClose' => false,
//                        'showCancel' => false,
//                        'showBrowse' => false,
//                        'removeLabel' => false,
//                        'showCaption' => false,
//                        'showUpload' => false,
//                        'showRemove' => false,
//                        'dropZoneEnabled' => false
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

