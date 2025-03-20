<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
use app\models\StuInfo;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Internet Copy II (Arrear Grade update type1,2,3)";

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
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12"> 

       

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['name'=>'year','value'=>date('Y')]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            //'id' => 'exam_month', 
                            'name' => 'month',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
        </div>

        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

             <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
                       'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>


            <div class="form-group col-lg-2 col-sm-2"><br />
                <input type="submit" id="student_res_export" onclick="spinner();" class="btn btn-success" value="Submit">
            </div>       
        </div>

    <?php ActiveForm::end();
        
        if(isset($internet_copy) && !empty($internet_copy))
            { //echo print_r($internet_copy); exit(); ?>

    <div id="display_results_stu1">
       

        <?php  

                    $prev_value="";
                    $prev_value_br="";
                    $sn=1;
                    $sss=0;
                    foreach($internet_copy as $markdetails)
                    {
                       
                        $grade_point=0;
                        $getgradename='';
                        $result=''; 
                        $year_of_passing='';
                
                        if($markdetails['type_id']>=140 || $markdetails['type_id']<=142) //type123
                        {
                            $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$markdetails['subject_map_id']." AND student_map_id='".$markdetails['student_map_id']."'  AND year='".$markdetails['year']."'  AND month='".$markdetails['month']."' AND mark_type=28")->queryScalar();

                            $ese100 = Yii::$app->db->createCommand("SELECT B.grand_total FROM coe_dummy_number A JOIN coe_val_barcode_verify_details B ON  B.dummy_number = A.dummy_number WHERE student_map_id=".$markdetails['student_map_id']." AND subject_map_id=".$markdetails['subject_map_id']." AND year=".$markdetails['year']." AND month=".$markdetails['month'])->queryScalar();

                            $tpesemark=0;
                            $practmark=0;
                            $ese_disp=0;
                            $total=0;
                            if($markdetails['type_id']=='140') //type1
                            {
                               $ese_disp = ceil(($ese100*0.25) + ($prac_mark*0.25)); //exit;
                                $total=$markdetails['CIA']+$ese_disp;
                            }
                            else if($markdetails['type_id']=='141') //type2
                            {
                                
                                $ese_disp = ceil(($ese100*0.35) + ($prac_mark*0.15));
                                $total=$markdetails['CIA']+$ese_disp;
                            }
                            else if($markdetails['type_id']=='142') //type3
                            {
                              
                                $ese_disp = ceil(($ese100*0.15) + ($prac_mark*0.35));

                               $total=$markdetails['CIA']+$ese_disp;
                            } 
                            
                       
                            $sub_info = Yii::$app->db->createCommand('SELECT * FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE B.coe_subjects_mapping_id="'.$markdetails['subject_map_id'].'"')->queryOne();

                            $getgradename = Yii::$app->db->createCommand("SELECT grade_name FROM `coe_grade_range` WHERE '".$total."' between `min_mark` and `max_mark` and `subject_code`='".$sub_info["subject_code"]."' and semester='".$sub_info["semester"]."'")->queryScalar();


                             if(!empty($getgradename))
                            {

                                if(($total>=$sub_info["total_minimum_pass"]) && ($ese_disp>=$sub_info["ESE_min"]))
                                {
                                    if($getgradename=='O')
                                    {
                                        $grade_point=10;
                                    }
                                    else if($getgradename=='A+')
                                    {
                                        $grade_point=9;
                                    }
                                    else if($getgradename=='A')
                                    {
                                        $grade_point=8;
                                    }
                                    else if($getgradename=='B+')
                                    {
                                        $grade_point=7;
                                    }
                                    else if($getgradename=='B')
                                    {
                                        $grade_point=6;
                                    }
                                    else if($getgradename=='C')
                                    {
                                        $grade_point=5;
                                    }

                                    $result='Pass'; 
                                    $year_of_passing=$markdetails['month'].'-'.$markdetails['year'];
                                }
                                else
                                {
                                    $grade_point=0;
                                    $getgradename='U';
                                    $result='Fail'; 
                                    $year_of_passing='';
                                } 
                            }
                            else
                            {
                                if(($total>=$sub_info["total_minimum_pass"]) && ($ese_disp>=$sub_info["ESE_min"]))
                                {
                                    $getgradename = Yii::$app->db->createCommand("SELECT grade_name,grade_point FROM `coe_regulation` WHERE '".$total."' between `grade_point_from` and `grade_point_to` and `coe_batch_id`='13' ")->queryOne();
                                
                                    $grade_point=$getgradename['grade_point'];
                                    $getgradename=$getgradename['grade_name'];
                                    $result='Pass';
                                    $year_of_passing=$markdetails['month'].'-'.$markdetails['year'];
                                }
                                else
                                {
                                    $grade_point=0;
                                    $getgradename='U';
                                    $result='Fail'; 
                                    $year_of_passing='';
                                }
                            } 

                            $updated_at = date("Y-m-d H:i:s");
                            $updateBy = Yii::$app->user->getId();

                             $gruy= 'UPDATE coe_mark_entry_master SET ESE="'.$ese_disp.'",total="'.$total.'",result="'.$result.'",grade_point="'.$grade_point.'",grade_name="'.$getgradename.'",year_of_passing="'.$year_of_passing.'",updated_by="'.$updateBy.'",updated_at="'.$updated_at.'" WHERE student_map_id ="'.$markdetails["student_map_id"].'"  AND subject_map_id ="'.$markdetails["subject_map_id"].'"  AND year="'.$markdetails['year'].'" AND month="'.$markdetails['month'].'" AND mark_type=28 AND (total!="'.$total.'" OR ESE!="'.$ese_disp.'") AND result!="Absent"'; //exit;

                           $update='';// Yii::$app->db->createCommand($gruy)->execute();

                           if($update)
                            {  ///echo $gruy; exit;
                                $sss++;
                            }
                        }
                        else
                        {   
                        }

                                   

                           
                        
                    }

                    echo $sss." updated"; //exit;

                   
            } 

        ?>

    </div>

    </div>
	</div>
	</div>


