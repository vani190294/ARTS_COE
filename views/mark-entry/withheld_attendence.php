<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Categories;
use app\models\Categorytype;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Malpractice Attendence";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
        ]); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year']) ?>
        </div>


        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'month')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getMonth(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ---',
                        'id' => 'exam_month',
                        'class'=>'student_disable',
                        'name'=>'month',
                        'onchange'=>'getmalmeetdate();',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select  ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
        </div>

         <div class="col-lg-2 col-sm-2" id="malmeetdate" style="display: none;">
         <?php 
       
        echo $form->field($exam_model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'id' => 'exam_date1',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label('Meeting Date'); 
            ?>        
        
    </div>
    
        <div class="form-group col-lg-2 col-sm-2"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show', ['value'=>'report','name'=>"withheld_attendence" ,'id'=>"withheld_attendence",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/withheldattendence']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>
    
<?php if(empty($malpractice))
{?>
<div class="col-xs-12 col-sm-12 col-lg-12 tbl_n_submit_withheld">

        <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
           
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2" id="whattendpdf" style="display: none;">
            <?php 
                 echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/withheldattendencepdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "stu_withheld_tbl"></div>
        </div>

       
        <div class="col-xs-12 col-sm-12 col-lg-12" id="meetingsave" style="display: none;">
             <div class="col-lg-2 col-sm-2">
                <label>Meeting Date: <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" name="meeting_date" required="required"></label>
            </div>
            <div class="col-lg-2 col-sm-2">
                <label>Meeting venue: <input class="form-control" type="text" name="meeting_venue" value="COE Office" required="required"></label>
            </div>
            <div class="col-lg-3 col-sm-3">
                 <?php echo $form->field($vmodel,'faculty_name')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getfaculty(),
                    'options' => [
                        'placeholder' => '--- Select ---',
                        'id' => 'meeting_committee',
                        'name'=>'meeting_committee[]',
                        'multiple'=>'multiple',
                        //'onchange'=>'getmaldate();',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Meeting Committee');
            ?>
            </div>

             <div class="col-lg-3 col-sm-3 withheld_done_btn"><br>
               <?= Html::submitButton('Save', ['id'=>"withheld_submit_btn", 'class' => 'btn btn-group-lg btn-group btn-success','name'=>'withheld_submit_btn']) ?>

            </div>
        </div>


</div>

<?php } else {?>

<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                 echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/withheldattendencepdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>

  <?php      if(!empty($malpractice))
        {
            $monthName = Categorytype::findOne($_POST['month']);
             
            $head=$body1=$body=$header=$header1=''; 
            $sn = 1;
            require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
            $head .="<table width='100%' style='overflow-x:auto;' border='1' class='table table-striped '>";
            $head .= '<tr>
                        <td align="center" style=" border-left:0px !important; border-top:0px !important; border-right:0px !important;">
                            <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                        </td>
                        <td colspan=9 align="center" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">
                            <h3> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                            </h3>
                            <h4>
                                <center> <font size="3px">' . $org_address . '</font></center>
                                <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                            </h4>
                            <h4> LIST OF STUDENTS INVOLVED IN MALPRACTICE DURING '.strtoupper($monthName->description).' - '.$_POST['mark_year'].' EXAMINATIONS </h4>
                            <h4>ATTENDANCE SHEET</h4>';                                                           
            $head .= '         </td>
                    <td align="center" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">  
                        <img width="75" height="75" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                    </td>
                </tr>
            <tr><td colspan=5 align="left" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">
                Meeting Date: '.date('d-m-Y',strtotime($malpractice[0]['mal_meeting_date'])).'<br>
                </td>
                <td colspan=6 align="right" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">
                Venue: '.$malpractice[0]['meeting_venue'].'
            </td></tr></table> ';

             $header .= "<table width='100%' style='overflow-x:auto;'  border='1' align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Register Number</th>
                            <th>Student Name</th>
                            <th>Semester</th>
                            <th>Dept</th>
                            <th>Date / Session</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th width='10%'>Student Signature with Date</th>
                        </tr>
                        <tbody>"; 
                    
                       $sl=1;
                        
            foreach ($malpractice as  $value) 
            {
                 $body .='<tr>';
                $body .='<td>'.$sl.'</td>';
                $body .='<td>'.$value['register_number'].'</td>';
                $body .='<td>'.$value['name'].'</td>';
                $body .='<td>'.$value['semester'].'</td>';
                $body .='<td>'.$value['programme_name'].'</td>';
                $body .='<td>'.date('d-m-Y',strtotime($value['exam_date'])).' / '.$value['exam_session1'].'</td>';
                $body .='<td>'.$value['subject_code'].'</td>';
                $body .='<td>'.$value['subject_name'].'</td>';
                $body .='<td width="10%"></td>';
                $body .='</tr>';
                                        
                $sl++;

            } 
            $body .="</tbody></table><table width='100%' style='overflow-x:auto;' class='table table-striped '>";
            if($malpractice[0]['meeting_committee']!='')
            {
                $explode=explode(",", $malpractice[0]['meeting_committee']);
                $body .='<tr>';
                
                $c=1;
                for ($i=0; $i <3 ; $i++) 
                { 
                     $faculty_name = Yii::$app->db->createCommand("SELECT concat(faculty_name,' (',faculty_board,')') as faculty_name from coe_valuation_faculty where coe_val_faculty_id=".$explode[$i]."")->queryScalar();
                     $body .='<td align="center"><br><br>Committee Member '.$c.'<br><b>'.$faculty_name.'</b></td>';       
                     $c++;    
                }

                $body .='</tr>';
            }

             $content_1=$head.$header.$body."</tbody></table>";
            if (isset($_SESSION['malpracticepdf'])) {
            unset($_SESSION['malpracticepdf']);
            } 
           echo $_SESSION['malpracticepdf'] = $content_1;
            

           

        }
        else
        {
            echo "NO DATA";
        }
} ?>
<?php ActiveForm::end(); ?>

</div>
</div>
</div>