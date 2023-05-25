<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 1:28 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $progCode
 * @var string $level
 * @var string $year
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 */

use yii\helpers\Url;
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Filter courses
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="<?=Url::to(['/exam-management/publish-marks/courses'])?>">
                            <input hidden name="code" value="<?=$progCode?>">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <select class="custom-select form-control" id="year" name="year">
                                            <option value="">-- select --</option>
                                            <option value="2022/2023">2022/2023</option>
                                            <option value="2021/2022">2021/2022</option>
                                            <option value="2020/2021">2020/2021</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="level">Level</label>
                                        <select class="custom-select form-control" id="level" name="level">
                                            <option value="">-- select --</option>
                                            <?php foreach ($academicLevels as $level):?>
                                                <option value="<?=$level['academic_level']?>"><?=$level['academic_level_name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="semester">Semester</label>
                                        <select class="custom-select form-control" id="semester" name="semester">
                                            <option value="">-- select --</option>
                                            <?php foreach ($semesters as $semester):?>
                                                <option value="<?=$semester['semester_code']?>"><?=$semester['semester_code']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="group">Group</label>
                                        <select class="custom-select form-control" id="group" name="group">
                                            <option value="">-- select --</option>
                                            <?php foreach ($groups as $group):?>
                                                <option value="<?=$group['study_group_id']?>"><?=$group['study_group_name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="">
                                    <button type="submit" class="btn btn-success">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

