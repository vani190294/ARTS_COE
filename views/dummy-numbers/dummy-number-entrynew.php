<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Valuation Mark Entry Approve";
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo $this->title; ?></h1>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div><input type="hidden" name="barcode_exam_year1" value="<?php echo $exam_year;?>"/></div>
 <?php if(empty($verify_stu_data))
    {?>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>'2023','id'=>'dummynumber_exam_year','name'=>'dummynumber_exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'dummynumber_exam_month', 
                            'name'=> 'dummynumber_exam_month',                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <!--div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 
               /* echo $form->field($factallModel,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select valuation Date----',
                        'id' => 'dummynumber_valuation_date',
                        'name' => 'dummynumber_valuation_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); */
            ?>      
                     
        </div--> 

        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'dummynumber_val_faculty_id',
                        'name'=>'dummynumber_val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('QP Code');
            ?>  
        </div> 

        <input type="hidden" id="dummy_exam_subject_code" name="exam_subject_code" />
        <input type="hidden" id="examiner_name" name="examiner_name" />
        <input type="hidden" id="scrutiny_name" name="scrutiny_name" />
         </div>
        
    
</div>
</div>

<div class="row">
    
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />

            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <span id="show_students_details1" style="display: none;">
                <?= Html::Button('Show' ,['onClick'=>"get_students_details($('#dummynumber_exam_year').val(), $('#dummynumber_exam_month').val());",'class' => 'btn btn-success' ]) ?>
                </span>
                <!--span id='get_students_data1' style="display: none;">
                <?php // Html::SubmitButton('Submit' ,['class' => 'btn btn-success' ]) ?>
                </span-->

                <span>
                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-entrynew']), ['id'=>'show_reset','onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </span>
                
            </div>           

            
        </div>
        

       
    </div>


    <div id='hide_dum_sub_data' class="row">
    <div  class="col-xs-12"> <br /><br />
        <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
                <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            
                            <th>SNO</th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                            <th><?php echo strtoupper("CIA MIN"); ?></th>
                            <th><?php echo strtoupper("CIA MAX"); ?></th>
                            <th><?php echo strtoupper("ESE MIN"); ?></th>
                            <th><?php echo strtoupper("ESE MAX"); ?></th>
                            <th><?php echo strtoupper("TOTAL MIN PASS"); ?></th>
                            <th>CREDIT POINT</th>
                        </tr>               
                    </thead> 
                    <tbody id="show_subject_details">     

                    </tbody>
                </table> 
            </div>
        <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div> 

    <div id='hide_dum_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
        <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr class="table-danger">
                
                <th>SNO</th>                
                <th>REGISTER NUMBER</th>
                <th>CIA</th>
                <th>ESE MARKS</th>
            </tr>               
            </thead> 
            <tbody id="show_dummy_numbers_new">     

            </tbody>
        </table>
        <?= Html::submitButton('Save' , ['id' =>'submit_dummy', 'class' => 'btn btn-primary']) ?>
         <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-entrynew']), ['id'=>'submit_dummyreset','onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>

   <?php }else if((!empty($verify_stu_data))){?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <?php // Html::a("Back to Approve", Url::toRoute(['dummy-numbers/dummy-number-entrynew']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        <?php  /* echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/dummy-numbers/printbarcodemarkpdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);*/
                ?>
           <?php //echo $_SESSION['get_print_dummy_mark']; ?>
        </div>

   <?php }else{?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align:center;"> No data found</div>

   <?php }?>

    <input type="hidden" id="markdiff_countval" value="<?= $mark_diff_count; ?>"/>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>