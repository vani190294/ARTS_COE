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
use app\models\Revaluation;
use app\models\ExamTimetable;
use app\models\MarkEntry;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Update Transparency Details";
$year = isset($_POST['mark_year'])?$_POST['mark_year']:DATE('Y');
$month = isset($_POST['month'])?$_POST['month']:'';
$reg_number = isset($_POST['bat_map_val'])?$_POST['bat_map_val']:'';
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>$year,'name'=>'mark_year']) ?>
        </div>

        <div class="col-lg-2 col-sm-2">


            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'reval_entry_month',   
                            'name' => 'month',
                            'value'=>$month,
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) 
            ?>
            
        </div>
        <div class="col-lg-2 col-sm-2">

            <?= $form->field($markentry, 'stu_programme_id')->textInput(['name'=>'bat_map_val','value'=>$reg_number])->label('REGISTER NUMBER') ?>
        </div>
        <div class="col-lg-6 col-sm-6">
        <div class="form-group col-lg-12 col-sm-12"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['value'=>'Submit','name'=>"view_reval_btn" ,'id'=>"view_reval_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/view-transparency-dept']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>            
        </div>
    </div>
    </div>

    

<?php
    if(isset($revaluation) && !empty($revaluation))
    {

?>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-lg-10 col-sm-10">
        &nbsp;
    </div>
        <div style="align-content: center; text-align: center;" class="form-group col-lg-2 col-sm-2">
<div class="btn-group" role="group" aria-label="Actions to be Perform">
    <?= Html::submitButton('UPDATE', ['value'=>'UPDATE','name'=>"view_reval_btn_UPDATE" ,'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
</div>
</div>
</div>   
<?php
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
   
            $data.='<tr>
                        <th>SNO</th>     
                        <th>REGISTER NUMBER</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th colspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</th>
                        <th>UPDATE</th>
                        <th>REVALUATION</th>';

            
            $data.='</tr>';
            
            $old_reg_num='';
            $sn=1;
            foreach($revaluation as $revaluation1){
                $data.='<tr>';
                    if($old_reg_num!=$revaluation1['register_number'])
                    {
                        $old_reg_num = $revaluation1['register_number'];
                        $data.='<td align="left" > '.$sn.'</td>';
                        $data.='<input type="hidden" name="student_map_id'.$sn.'" value='.$revaluation1['student_map_id'].' >';
                        $data.='<input type="hidden" name="subject_map_id'.$sn.'" value='.$revaluation1['subject_map_id'].' >';
                        $data.='<input type="hidden" name="year'.$sn.'" value='.$revaluation1['year'].' >'.'<input type="hidden" name="month'.$sn.'" value='.$revaluation1['month'].' >'.'<input type="hidden" name="mark_type'.$sn.'" value='.$revaluation1['mark_type'].' >';
                        $data.='<td align="left" >'.$old_reg_num.'</td>';
                        
                    }
                    else
                    {
                        $data.='<td align="left" > '.$sn.'</td>';
                        $data.= '<td align="left" > &nbsp; </td>';
                    }     
                    
                        $data.='<input type="hidden" name="student_map_id'.$sn.'" value='.$revaluation1['student_map_id'].' >';
                        $data.='<input type="hidden" name="subject_map_id'.$sn.'" value='.$revaluation1['subject_map_id'].' >';
                        $data.='<input type="hidden" name="year'.$sn.'" value='.$revaluation1['year'].' >'.'<input type="hidden" name="month'.$sn.'" value='.$revaluation1['month'].' >'.'<input type="hidden" name="mark_type'.$sn.'" value='.$revaluation1['mark_type'].' >';
                        $checked = $revaluation1['is_checked']=='1'?'checked':'';
                        $value = $revaluation1['is_checked']=='1'?'YES':'NO';

                        $revalCheck = Revaluation::find()->where(['student_map_id'=>$revaluation1['student_map_id'],'subject_map_id'=>$revaluation1['subject_map_id'],'year'=>$revaluation1['year'],'month'=>$revaluation1['month'],'mark_type'=>$revaluation1['mark_type'],'is_transparency'=>'S','reval_status'=>'YES'])->one();

                        $reval_checked = !empty($revalCheck) && count($revalCheck)>0 ? 'checked' :'';
                        $value_reval = !empty($revalCheck) && count($revalCheck)>0 ?'YES':'NO';

                        $data.=
                            '<td align="left" >'.$revaluation1['subject_code'].'</td>
                            <td align="left"  colspan="2" >'.$revaluation1['subject_name'].'</td>
                            <td align="left" ><input type="checkbox" '.$checked.' onclick="changeThisCheckBboxVal(this.id,this.value);" name="checkbox'.$sn.'" id="checkbox'.$sn.'" value="'.$value.'" ></td>
                            
                            <td align="left" ><input type="checkbox" '.$reval_checked.' onclick="changeThisCheckBboxValRev(this.id,this.value);" name="checkbox_REVAL'.$sn.'" id="checkbox_REVAL'.$sn.'" value="'.$value_reval.'" ></td>
                        </tr>';
                    $sn++;            
                    
            }

    $data.='</tbody><input type="hidden"  name="total_subs" value='.$sn.' >';        
    $data.='</table>';
    
    $_SESSION['transparency_print'] = $data;
    echo $data;

    ?>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-lg-10 col-sm-10">
        &nbsp;
    </div>
        <div style="align-content: center; text-align: center;" class="form-group col-lg-2 col-sm-2">
<div  class="btn-group" role="group" aria-label="Actions to be Perform">
    <?= Html::submitButton('UPDATE', ['value'=>'UPDATE','name'=>"view_reval_btn_UPDATE" ,'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
</div>
</div>
</div> 
<?php
}

?>

<?php ActiveForm::end(); ?>


</div>
</div>
</div>