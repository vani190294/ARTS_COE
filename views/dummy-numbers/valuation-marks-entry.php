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

$this->title = "Scrutiny Marks Entry";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
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
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         
         <div class="col-xs-12 col-sm-1 col-lg-1">
           <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year','name'=>'exam_year']); ?>

         </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'barcodeverify_exam_month', 
                            'name' => 'barcodeverify_exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 
                echo $form->field($factallModel,'scrutiny_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Scrutiny Date----',
                        'id' => 'barcodeverify_scrutiny_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label("Date"); 
            ?>      
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
              <?php 
                echo $form->field($factallModel,'scrutiny_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Scrutiny session----',
                        'id' => 'barcodeverify_scrutiny_session',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label("Session"); 
            ?>   
                     
        </div>

        <div class="col-xs-12 col-sm-3 col-lg-4">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'barcodeverify_val_faculty_all_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Assigned Faculty");  
            ?>  
        </div> 

         <input type="hidden" id="batch_id">
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-uverify']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

        <div class="row">
            <div  class="col-xs-12" id='hide_bar_code_data1' style="display: none;"> <br /><br />
                <div class="col-xs-10">
                     <div class="col-xs-12 col-sm-4 col-lg-4">
                        <label class="control-label">Enter Register Number<span style="color:red">*</span></label>
                        <input type="hidden" class="form-control" id="firstreg_no"/>
                        <input type="hidden" class="form-control" id="reg_no"/>
                        <input type="hidden" class="form-control" id="lastreg_no"/>
                        <input type="text" class="form-control" id="displayregno" readonly />
                    </div> 
                  

                </div>
                <div class="col-xs-2"> &nbsp; </div>
            </div>

            <div  class="col-xs-12" id='veify_details2020' style="display: none">
                <div  class="col-xs-12" style="text-align: center;"> 

                    <h3 style="text-align:center">Part A</h3>
                      <div style="width:5%;" class="col-xs-1"></div>
                     <?php
                    for($i=1;$i<=10;$i++)
                    {
                     ?>
                     <div  class="col-sm-1" id="part_a" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                     <label><?= $i; ?></label>
                     <input type="text" name="markverifyA[]" id="part_a_<?= $i;?>" max="2" onblur="getpartatotal()"/>
                    </div>
                  <?php }?>
                   <div  class="col-sm-1" id="row_a_subtotal" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                        <label class="col-sm-4" style="text-align:right">Total</label>
                        <input style="width: 75%;" type="text" name="part_a_total" id="part_a_total" max="20" readonly>
                    </div>

                </div>
                <div  class="col-xs-12">

                    <div  class="col-xs-12">
                      <div  class="col-xs-6">
                    <h3 style="text-align:center">Part B</h3>
                  </div>
                  <div  class="col-xs-6">
                    <h3 style="text-align:center">Part C</h3>
                  </div>
                  </div>
                     <?php
                    for($j=11;$j<=15;$j++)
                    { 
                     ?>
                     
                    <div  class="col-sm-1" id="part_b" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_b_<?= $j;?>" onblur="getpartbtotal()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" id="part_b_subtotal" style="padding-left: 0px;padding-right: 0px;"> 
                        <div>
                         <label>Total</label>
                         <input style="width: 90%;" type="text" id="part_b_total" readonly/>
                       </div>
                       
                    </div>
                 
                    
                     <?php
                    for($j=16;$j<=20;$j++)
                    { 
                     ?>
                     
                    <div  class="col-sm-1" id="part_c" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_c_<?= $j;?>" onblur="getpartctotal()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" id="part_c_subtotal" style="padding-left: 0px;padding-right: 0px;"> 
                        <div>
                         <label>Total</label>
                         <input style="width: 90%;" type="text" id="part_c_total" readonly/>
                       </div>
                       
                    </div>
                 
                
                </div>
                <div  class="col-xs-12" style="padding-top:10px;"> 
                    <div  class="col-xs-4"></div>
                    <div  class="col-xs-8"> 
                         <div  class="col-sm-12" id="parttotal" style="text-align:right"> 
                             <label class="control-label" style="text-align:right">Grand Total: </label>
                             <input type="text" class="control-label" name="park_total" id="part_grandtotal" placeholder="Grand total" readonly/>
                            </div>
                    </div>
                </div>

            </div>

            <div  class="col-xs-12" id='veify_details2021' style="display: none">
                <div  class="col-xs-12" style="text-align: center;"> 

                    <h3 style="text-align:center">Part A</h3>
                      <div style="width:5%;" class="col-xs-1"></div>
                     <?php
                    for($i=1;$i<=10;$i++)
                    {
                     ?>
                     <div  class="col-sm-1" id="part_a" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                     <label><?= $i; ?></label>
                     <input type="text" name="markverifyA[]" id="part_a_21<?= $i;?>" max="2" onblur="getpartatotal21()"/>
                    </div>
                  <?php }?>
                   <div  class="col-sm-1" id="row_a_subtotal" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                        <label class="col-sm-4" style="text-align:right">Total</label>
                        <input style="width: 75%;" type="text" name="part_a_total21" id="part_a_total21" max="20" readonly>
                    </div>

                </div>
                <div  class="col-xs-12">
                    <div  class="col-xs-12">
                      <div  class="col-xs-6">
                        <h3 style="text-align:center">Part B</h3>
                      </div>
                      <div  class="col-xs-6">
                        <h3 style="text-align:center">Part C</h3>
                      </div>
                    </div>
                     <?php
                    for($j=11;$j<=15;$j++)
                    { 
                     ?>
                     
                    <div  class="col-sm-1" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_b_2021<?= $j;?>" onblur="getpartbtotal21()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" style="padding-left: 0px;padding-right: 0px;"> 
                        <div>
                         <label>Total</label>
                         <input style="width: 90%;" type="text" id="part_b_2021total" readonly/>
                       </div>
                       
                    </div>
                 
                
                     <?php
                    for($j=16;$j<=20;$j++)
                    { 
                     ?>
                     
                    <div  class="col-sm-1" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_c_2021<?= $j;?>" onblur="getpartctotal21()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" style="padding-left: 0px;padding-right: 0px;"> 
                        <div>
                         <label>Total</label>
                         <input style="width: 90%;" type="text" id="part_c_2021total" readonly/>
                       </div>
                       
                    </div>
                 
                
                </div>
                <div  class="col-xs-12" style="padding-top:10px;"> 
                    <div  class="col-xs-4"></div>
                    <div  class="col-xs-8"> 
                         <div  class="col-sm-12" id="parttotal" style="text-align:right"> 
                             <label class="control-label" style="text-align:right">Grand Total: </label>
                             <input type="text" class="control-label" name="part_total_2021" id="part_grandtotal_2021" placeholder="Grand total" readonly/>
                            </div>
                    </div>
                </div>

            </div>
            

            <div  class="col-xs-12" id='veify_details_absent' style="display: none">
                
                
                <div  class="col-xs-12" style="padding-top:10px;"> 
                    
                    <div  class="col-sm-12" id="parttotal" style="text-align:center"> 
                             <label class="control-label" style="text-align:right; font-size: 20; font-weight: bold;color: red;">Absent</label>
                             <input type="hidden" class="control-label" name="absentstudent" id="absentstudent" readonly/>
                    </div>
                   
                </div>

            </div>

             <div  class="col-xs-12" id='show_savebutton' style="text-align:right; padding-top:10px; display: none;"> 
                 <div  class="col-xs-6"></div> 
                    <div  class="col-xs-6"> 
                        <div  class="col-xs-4" id='backdiv' style=" display: none;"> 
                            <?= Html::Button('Back',['class' => 'btn btn-success','id'=>'backsavemarks']) ?>
                        </div>
                        <div  class="col-xs-4" id='nextdiv' style=" display: none;"> 
                            <?= Html::Button('Next',['class' => 'btn btn-success','id'=>'nextsavemarks']) ?>
                        </div>
                        <div  class="col-xs-4" id='finshdiv' style=" display: none;"> 
                            <?= Html::Button('Finish',['class' => 'btn btn-success','id'=>'finishsavemarks']) ?>
                        </div>
                        
                        
                    </div>
                   
                </div>

       </div>


        <div  class="col-xs-12" style="overflow-x: auto; display: none;" id="verification_details_data">
            <div class="col-xs-12 col-sm-12 col-lg-12" style="padding-bottom:20px;" id="verification_details_pdf">
            <div class="col-xs-3 col-sm-10 col-lg-10">
            </div>    
            <div class="col-xs-3 col-sm-2 col-lg-2">
                <?php 
                   
                    echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/dummy-numbers/verifydetails-pdf'], [
                    'class'=>'pull-right btn btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                    ]);
                ?>
            </div>
            </div>
                <div id='verification_details'></div>
        </div>
   
</div>
</div>

<?php ActiveForm::end(); ?>


</div>

