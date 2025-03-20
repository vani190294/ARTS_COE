<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
$this->title = 'Scrutiny Report';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
?>
<div class="scrutiny-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Yii::$app->ShowFlashMessages->showFlashes(); ?>
    <div class="box box-success">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); 
            ?>
            <div class="row">
                <div class="col-12">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?php echo $form->field($model, 'designation',['inputOptions' => ['autocomplete' => 'off']])->widget(
                            Select2::classname(),
                            [
                                'data' => $model->designationData,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'class' => 'form-control',
                                    'id'=>'designation'
                                ],
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?php echo $form->field($model, 'department')->widget(
                            Select2::classname(),
                            [
                                'data' => $model->departmentData,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'class' => 'form-control',
                                    'id'=>'department'
                                ],
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="btn-group" role="group" aria-label="Actions to be Perform">
                            <?= Html::Button('Report', ['onClick' => 'showScrutinyReport($("#designation").val(),$("#department").val());', 'class' => 'btn  btn-group-lg btn-group btn-primary', 'id' => 'showReportButton']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="row hide_export_button" id="export_button" style="display: none;">
                <div class="col-12">
                    <div class="col-lg-12 col-sm-12">
                        <?php
                        echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF", array('scrutiny-report-pdf', 'exportPDF' => 'PDF'), array('title' => 'Export to PDF', 'target' => '_blank', 'class' => 'pull-right btn btn-warning', 'style' => 'color:#fff'));
                        echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ", array('scrutiny-report-exel', 'exportPDF' => 'PDF'), array('title' => 'Export to Excel', 'class' => 'pull-right btn btn-info', 'style' => 'color:#fff'));
                        ?>
                        <div class="show_scrutiny_data">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>