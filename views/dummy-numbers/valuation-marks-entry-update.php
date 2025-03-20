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

$user_id=Yii::$app->user->getId();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "SCRUTINY MARK ENTRY UPDATE";
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
<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'dummynumber_exam_year','name'=>'dummynumber_exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'update_exam_month', 
                            'name'=> 'update_exam_month',                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

         <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select----',      
                        'id'=>'dummynumber_val_faculty_id',
                        'name'=>'dummynumber_val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('QP Code(Cover Number)');
            ?>  
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'chief_examiner_name')->textInput(['id'=>'reg_no','placeholder'=>'Enter Reg No.'  ,'onfocus'=>" if (this.value==this.defaultValue) this.value = '' " ,'autocomplete'=>"off",'onblur'=>'getscrutinyentry(this.id,this.value,$("#dummynumber_exam_year").val(),$("#update_exam_month").val(),$("#dummynumber_val_faculty_id").val())'])->label('Enter Register Number') ?>
             
        </div>
         <input type="hidden" id="batch_id">
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-update']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    <div id='hide_bar_code_data' class="row">
    <div  class="col-xs-12" style="text-align: center;">
            
             <div class="col-xs-12" >
                <h3>
                <div id="show_scrutiny_entry_master">  </div></h3>
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
                     
                    <div  class="col-sm-1" id="partb2021" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_b_2021<?= $j;?>" onblur="getpartbtotal21()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" id="part_b_2021subtotal" style="padding-left: 0px;padding-right: 0px;"> 
                        <div>
                         <label>Total</label>
                         <input style="width: 90%;" type="text" id="part_b_2021total" readonly/>
                       </div>
                       
                    </div>
                 
                
                     <?php
                    for($j=16;$j<=20;$j++)
                    { 
                     ?>
                     
                    <div  class="col-sm-1" id="part_c_2021" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_c_2021<?= $j;?>" onblur="getpartctotal21()"/>
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" id="part_c_2021subtotal" style="padding-left: 0px;padding-right: 0px;"> 
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
                             
                    </div>
                   
                </div>

            </div>


            <div  class="col-xs-12" id='show_savebutton' style="text-align:right; padding-top:10px; display: none;"> 
                <div  class="col-xs-8"></div>                    
                <div  class="col-xs-4"> 
                    <?= Html::Button('Update',['class' => 'btn btn-success','id'=>'updatesavescrutinymarks']) ?>
                </div>
                    
            </div>
        
    </div> <!-- Row Closed -->
    </div>

    <div><input type="hidden" id="markdesign"/></div>
</div>

<?php ActiveForm::end(); ?>


</div>

