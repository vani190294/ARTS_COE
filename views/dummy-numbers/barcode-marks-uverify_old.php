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

use app\models\ValuationSettings;

$ValuationSettings = ValuationSettings::findOne(1);

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Bar Code Marks Verify";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>
<h1><?php echo "Bar Code Marks Verify"; ?></h1>

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
         
           <?= $form->field($model, 'year')->hiddenInput(['value'=>"2021",'id'=>'exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false); ?>

            <?= $form->field($model, 'month')->hiddenInput(['value'=>"2021",'id'=>'barcodeverify_exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false); ?>

            <input type="hidden" name="engg_graphic_subject" id="engg_graphic_subject" value="<?php echo $ValuationSettings['engg_graphic_subject'];?>"/>

        
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

        <div class="col-xs-12 col-sm-3 col-lg-3">
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

         
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-uverify']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

        <div class="row">
            <div  class="col-xs-12" id='hide_bar_code_data1'> <br /><br />
                <div class="col-xs-10">
                    
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'student_map_id')->passwordInput(['placeholder'=>'Place Cursor and Scan', 'autocomplete'=>"off",'onblur'=>'getVCerifyBarMarks1(this.id,this.value,$("#exam_year").val(),$("#barcodeverify_exam_month").val())'])->label('Scan Barcode Here') ?>
                    </div>  

                     <div><input type="hidden" name="val_barecode_id" id="val_barecode_id"/></div>

                </div>
                <div class="col-xs-2"> &nbsp; </div>
            </div>
       
            <!--div  class="col-xs-12"> 
                <div  class="col-xs-4"> 
                <h3 style="text-align:center">Part A</h3>
                 <?php
               // for($i=1;$i<=10;$i++)
                {
                 ?>
                 <div  class="col-sm-12" id="part_a" style="padding-top:10px;"> 
                 <label class="control-label col-sm-4" style="text-align:right"><?= $i; ?>: </label>
                 <input type="text" class="control-label" name="markverifyA[]" id="part_a_<?= $i;?>" max="2" value="" placeholder="Max mark 2" onblur="getpartatotal()"/>
                </div>
              <?php }?>
               
                </div>
                <div  class="col-xs-8"> 
                 <h3 style="text-align:center">Part B</h3>
                 <?php
               // for($j=11;$j<=20;$j++)
               // { 
                 ?>
                 <div  class="col-sm-12" id="part_b" style="padding-top:10px;"> 
                    
                        <div  class="col-xs-3">
                         <label class="control-label"><?= $j; ?> i : </label>
                         <input type="text" style="width:50%" class="control-label" id="part_b_<?= $j;?>_1" onblur="getpartbtotal()"/>
                       </div>
                       <div  class="col-xs-3">
                          <label class="control-label">ii : </label>
                         <input type="text" style="width:50%" class="control-label" id="part_b_<?= $j;?>_2" onblur="getpartbtotal()"/>
                       </div>
                       <div  class="col-xs-3">
                          <label class="control-label">iii : </label>
                         <input type="text" style="width:50%" class="control-label" id="part_b_<?= $j;?>_3" onblur="getpartbtotal()"/>
                       </div>
                       <div  class="col-xs-3">
                          <label class="control-label">Total : </label>
                        <input type="text" class="control-label" style="width:50%" name="markverifyB[]" id="part_b_<?= $j;?>" max="16" placeholder="Max mark 16" readonly />
                       </div>
                 </div>

                  <?php }?>
                 
                </div>
            

                <div  class="col-xs-12" style="padding-top:10px;"> 
                    <div  class="col-xs-4"> 
                        <div  class="col-sm-12" id="row_a_subtotal"> 
                         <label class="control-label col-sm-4" style="text-align:right">Part A Total: </label>
                         <input type="text" class="control-label" name="park_a_total" id="park_a_total" max="20" readonly placeholder="Click Here Get total">
                        </div>
                    </div>
                    <div  class="col-xs-8"> 
                         <div  class="col-sm-12" id="row_b_subtotal" style="text-align:right"> 
                             <label class="control-label"  style="text-align:right">Part B Total: </label>
                             <input type="text" class="control-label" name="park_b_total" id="part_b_total" max="80" readonly placeholder="Click Here Get total">
                            </div>
                    </div>
                </div>
                <div  class="col-xs-12" style="padding-top:10px;"> 
                    <div  class="col-xs-4"></div>
                    <div  class="col-xs-8"> 
                         <div  class="col-sm-12" id="parttotal" style="text-align:right"> 
                             <label class="control-label" style="text-align:right">Total: </label>
                             <input type="text" class="control-label" name="park_total" id="part_grandtotal" placeholder="Grand total" readonly/>
                            </div>
                    </div>
                </div>

                <div  class="col-xs-12" style="text-align:right; padding-top:10px;"> 
                    <div  class="col-xs-4"></div>
                    <div  class="col-xs-8"> 
                      
                         <?php //Html::Button('Save',['class' => 'btn btn-success','id'=>'verify_barcode']) ?>
                    </div>
                    <div  class="col-xs-2"></div>
                </div>

            </div-->

            <div  class="col-xs-12" id='veify_details'>
                <div  class="col-xs-12" style="text-align: center;"> 

                    <h3 style="text-align:center">Part A</h3>
                      <div style="width:5%;" class="col-xs-1"></div>
                     <?php
                    for($i=1;$i<=10;$i++)
                    {
                     ?>
                     <div  class="col-sm-1" id="part_a" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                     <label><?= $i; ?>: </label>
                     <input type="text" name="markverifyA[]" id="part_a_<?= $i;?>" max="2" onblur="getpartatotal()"/>
                    </div>
                  <?php }?>
                   <div  class="col-sm-1" id="row_a_subtotal" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                        <label class="col-sm-4" style="text-align:right">Total: </label>
                        <input style="width: 75%;" type="text" name="park_a_total" id="park_a_total" max="20" readonly>
                    </div>

                </div>
                <div  class="col-xs-12">
                    <h3 style="text-align:center">Part B</h3>
                     <?php
                    for($j=11;$j<=20;$j++)
                    { 
                     ?>
                      <?php
                    if($j==11)
                    { 
                     ?>
                          <div class="col-sm-1" style="width:5%;padding-left:0px;padding-right:0px;text-align: center;" id="part_b">
                                   <br>
                                <b>
                                    <div style="padding-top: 5px;">i </div>
                                    <div style="padding-top: 5px;">ii</div>
                                    <div style="padding-top: 5px;">iii </div>
                                    <div style="padding-top: 5px;">Total </div>
                                </b>
                            </div>

                   <?php }?>
                    <div  class="col-sm-1" id="part_b" style="text-align: center; padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label><?= $j; ?> </label>
                         <input type="text" id="part_b_<?= $j;?>_1" onblur="getpartbtotal()"/>
                       </div>
                       <div>
                         
                         <input type="text" id="part_b_<?= $j;?>_2" onblur="getpartbtotal()"/>
                       </div>
                       <div>
                         
                         <input type="text" id="part_b_<?= $j;?>_3" onblur="getpartbtotal()"/>
                       </div>
                       <div>
                        
                        <input type="text" name="markverifyB[]" id="part_b_<?= $j;?>" readonly />
                       </div>
                    </div>

                    <?php }?>

                    <div  class="col-sm-1" id="part_b_subtotal" style="padding-left: 0px;padding-right: 0px;"> 
                    
                    
                        <div>
                         <label>Sub Total </label>
                         <input style="width: 90%;" type="text" id="part_b_1_total"/>
                       </div>
                       <div>
                         
                         <input style="width: 90%;" type="text" id="part_b_2_total"/>
                       </div>
                       <div>
                         
                         <input style="width: 90%;" type="text" id="part_b_3_total"/>
                       </div>
                       <div>
                        
                        <input style="width: 90%;" type="text" id="part_b_123_total" readonly />
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

                <div  class="col-xs-12" style="text-align:right; padding-top:10px;"> 
                    <div  class="col-xs-4"></div>
                    <div  class="col-xs-8"> 
                      
                         <?= Html::Button('Save',['class' => 'btn btn-success','id'=>'verify_barcode']) ?>
                    </div>
                    <div  class="col-xs-2"></div> <!--,'data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.'-->
                </div>

            </div>

       </div>
   


</div>
</div>

<?php ActiveForm::end(); ?>


</div>

