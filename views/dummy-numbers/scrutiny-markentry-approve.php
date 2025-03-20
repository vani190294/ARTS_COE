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

$userid=Yii::$app->user->getId();

$item_name = Yii::$app->db->createCommand("SELECT item_name FROM auth_assignment WHERE  user_id='" . $userid . "'")->queryScalar();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Mark Entry Approve";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>

<div>&nbsp;</div>
 <?php if(empty($verify_stu_data))
{?>
 <h1><?php echo $this->title; ?></h1>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         <?php if($item_name=='Scrutiny Access'){

            echo $form->field($model, 'year')->hiddenInput(['id'=>'barcode_exam_year','name'=>'barcode_exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false);
             echo $form->field($model, 'month')->hiddenInput([ 'id' => 'barcode_exam_month','name'=>'barcode_exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false);
            ?>
           
            <?php }else{?>
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>2024,'id'=>'barcode_exam_year','name'=>'barcode_exam_year']) ?>

        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'barcode_exam_month',  
                            'name'=>'barcode_exam_month'
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <?php }?>
       

        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'barcode_val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 


          <input type="hidden" name="viewmark1" id="viewmark1" value="0"/>
         <div><input type="hidden" name="val_faculty_all_id" id="val_faculty_all_id"/></div>
         <div id="total_script"></div>
          <div><input type="hidden" id="markentry_count" value="0"/></div>
        
    </div>

  
</div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12" id="barcode_markentry">
        <h1 align="center" >Bar Code Mark Entry Starts here </h1>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-2 col-sm-2 col-lg-2"></div>
           <div class="col-xs-8 col-sm-8 col-lg-8" id="create_div_element1"></div>
            <div class="col-xs-2 col-sm-2 col-lg-2"></div>
        </div> 
   
</div>
</div>
<div><input type="hidden" name="barcode_exam_year1" value="<?php echo $exam_year;?>"/></div>
  <div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-5 col-sm-5 col-lg-5"></div>
            <div class="btn-group col-lg-7 col-sm-7" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show',['class' => 'btn btn-success','id'=>'show_barcode']) ?>

                <?= Html::SubmitButton('Submit',['class' => 'btn btn-success','id'=>'finish_barcode']) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/scrutiny-markentry-approve']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>    
            
        </div> 
    </div>

<?php }?>

<?php if(!empty($verify_stu_data))
{?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">
            <div><input type="hidden" name="barcode_exam_year1" value="<?php echo $exam_year;?>"/></div>
                <?= $form->field($model, 'year')->hiddenInput(['value'=>$exam_year,'name'=>'barcode_exam_year'])->label(false) ?>
                <?= $form->field($model, 'month')->hiddenInput(['value'=>$exam_month,'name'=>'barcode_exam_month'])->label(false) ?>
                <?= $form->field($factallModel,'valuation_date')->hiddenInput(['value'=>$val_date,'name'=>'valuation_date'])->label(false) ?>
                <?= $form->field($factallModel,'valuation_session')->hiddenInput(['value'=>$val_session,'name'=>'valuation_session'])->label(false) ?>
                <?= $form->field($factallModel,'coe_val_faculty_id')->hiddenInput(['value'=>$val_facl_id,'name'=>'coe_val_faculty_id'])->label(false) ?>
                 <?= $form->field($factallModel,'subject_pack_i')->hiddenInput(['value'=>$pack_no,'name'=>'subject_pack_i'])->label(false) ?>
        
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">
         
        

             <?php  $userid=Yii::$app->user->getId();


             if( $_SESSION['valuation_status']==6)
            {
                echo Html::a('Back to Approve', ['/dummy-numbers/scrutiny-markentry-approve'], [
                'class'=>'btn btn-primary', 
                ]);

                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/dummy-numbers/printbarcodemarkpdf1'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
                

            }
            ?>
          <?php 
          if($_SESSION['valuation_status']==5)
         {?>

            <!--  if loop start-->
            <?php 
          if(!empty($verify_stu_data))
         {?>

            <h1 align="center"><?php echo $this->title; ?></h1>

            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align:center;">
               <h4> <?php echo $title;?></h4>

            </div>

            <?php $scrucheck=0;
          foreach ($verify_stu_data as $value) 
          {
            $check_verify = Yii::$app->db->createCommand("SELECT booklet_sno,total_change,grand_total,part_b_total FROM coe_val_barcode_verify A JOIN coe_val_barcode_verify_details B ON B.val_barecode_id=A.val_barecode_id 
                JOIN coe_valuation_faculty_allocate H ON H.val_faculty_all_id=A.val_faculty_all_id
                 WHERE  exam_month='" . $exam_month . "' AND exam_year='" . $exam_year . "' AND A.val_faculty_all_id='" . $val_faculty_all_id . "' AND B.dummy_number='" . $value['dummy_number'] . "'")->queryone();
            
            $valuatormark = Yii::$app->db->createCommand("SELECT mark_100 FROM coe_valuator_markentry WHERE dummy_number='" . $value['dummy_number'] . "' AND val_faculty_all_id='" . $val_faculty_all_id . "'  ")->queryScalar();
            
            $valuator_mark =0;
            if(!empty($valuatormark))
            {
              $valuator_mark =$valuatormark;
            }

             ?> 
            <?php $grand_total=0;
            if($check_verify['grand_total']==0){ $grand_total=$check_verify['part_b_total'];}else{ $grand_total=$check_verify['grand_total']; }
            if($grand_total!=0)
            { 
                $valid="scrutiny_mark_".$value['dummy_number'];
                $valid1="valutor_mark_".$value['dummy_number'];
                ?>
                <div class="col-xs-12 col-sm-12 col-lg-12">  
                <div class="row">
                    <div class='col-xs-12 col-sm-12 col-lg-12'>  

                             <div class="col-sm-2 col-lg-2">
                                <label>Booklet No.:</label>
                                <div><?= $check_verify['booklet_sno'];?></div>
                              </div>
                            <div class="col-sm-3 col-lg-3"> 
                                <?= $form->field($model, 'student_map_id')->passwordInput(['class'=>'form-control','readonly'=> true,'name'=>"student_map_id[]",'value'=>$value['dummy_number']])->label('Dummy Number')  ?>
                            </div>                
                            <div class="col-sm-3 col-lg-3">  
                                <?= $form->field($model, 'dummy_number')->textInput(['class'=>'form-control', 'readonly'=> true,'id'=>$valid, 'name'=>"ese_marks[]",'value'=>$grand_total])->label('Scurtiny Entry Mark')  ?>
                            </div>
                            <div class="col-sm-4 col-lg-4"> 

                                <div class="col-sm-5 col-lg-5">  
                                    <?= $form->field($model, 'dummy_number')->textInput(['class'=>'form-control', 'readonly'=> true,'id'=>$valid1, 'value'=>$valuator_mark])->label('Valuator Entry Mark')  ?>
                                </div>
                                    
                                <div class="col-sm-7 col-lg-7"><br>                                                                

                                    <?php if($valuator_mark==$grand_total)
                                    {
                                        //echo "No Different";

                                    }
                                    else
                                    {
                                        $diff=$valuator_mark-$grand_total;
                                        echo "<b style='color:red;'>Mark Different: ".$diff."</b>";

                                        $scrucheck++;
                                    }?>
                                    
                                </div>

                            </div>
                        

                            
                    </div>   
                    <div class='col-xs-12 col-sm-3 col-lg-3'>&nbsp; </div>         
                </div>
                </div> 
        <?php }?>
         <?php }?>

          <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align:center;" id="refreshpage" >

            <?php if($scrucheck>=0)
            {?>
               <?= Html::SubmitButton('Save' ,['data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.','class' => 'btn btn-success','target'=>'_blank','id'=>'final_submit' ]) ?>    

            <?php }?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/scrutiny-markentry-approve']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        <?php }?> <!-- if loop end-->

            <?php 
            }else if( $_SESSION['valuation_status']==6){?>
                 <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x: auto;">
           <?php echo $_SESSION['get_print_barcode_mark']; ?>
        </div>
        <?php } else{
            ?>
                <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center"><b>No Data Found</b></div>
         <?php }
            ?>
    </div>
</div>

<?php }?>

<?php ActiveForm::end(); ?>


</div>

