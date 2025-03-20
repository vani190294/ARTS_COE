<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'Faculty Student Allocation';

$this->params['breadcrumbs'][] = 'Curriculum';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['fs-index']];
$this->params['breadcrumbs'][] = 'Allocate';
?>
<div class="elective-stu-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>

<div class="box box-success">
<div class="box-body">
    <?php 
    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();

    if($subject_name=='')
    {
        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();
        
        if($subject_name=='')
        {
            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();

            if($subject_name=='')
            {
                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();
            }
        }
    }
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px; text-align: center;">
        Regulation:<b> <?= $regulationyear;?></b>
        Degree Type:<b> <?= $checksemdata['degree_type'];?> </b>
        Department: <b><?= $checksemdata['dept_code'];?></b>
        <br> <br>
        Subject Code: <b><?= $checkelective['subject_code'];?></b>
        Subject Name: <b><?= $subject_name;?>  </b>
        Semester: <b><?= $checkelective['semester'];?>  </b>
         Faculty Name: <b><?= $faculty_name;?>
        </b>
    </div>


    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
    	<?php $sectionid=1; $checkedcout=0;
        if(!empty($deptdata))
        {
        
        foreach ($deptdata as $key => $batchvalue) 
        {
            $batch_map_id=$key;
            ?>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <h1 style="text-align:center;">Department : <?= $deptdata[$batch_map_id]['dept_code'];?></h1>
            <?php
            
            $depdata= $deptdata[$batch_map_id]['reg_num']; 
            //print_r($depdata); exit;    
        	foreach ($depdata as $seckey => $value) 
        	{

        		$regnums=$depdata[$seckey]; 
        		$checname='section'.$sectionid;
        		$checkstu='sectionstud'.$sectionid;
        		?>
        		<div class="col-xs-12 col-sm-12 col-lg-12">
        			<div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">
        				<b>Section <?= $seckey;?></b><br>
        			</div>
        			<div class="col-xs-12 col-sm-12 col-lg-12">
        				<div class="col-xs-12 col-sm-12 col-lg-12">
        				<label><input type="checkbox" id="<?= $checname;?>" onclick="checkallelectstu1(<?= $sectionid;?>);"> All</label>
        				</div>
        				<?php 
    				    	for ($i=0; $i <count($regnums) ; $i++) 
    				    	{ 
    				    		$checked='';
    				    		$elective_nominal= Yii::$app->db->createCommand("SELECT register_number FROM cur_elective_faculty_student WHERE cur_ef_id='".$cur_ef_id."' AND register_number='".$regnums[$i]['register_number']."'")->queryScalar();
    				    		if($elective_nominal!='')
    				    		{
    				    			$checked='checked';
    				    			$checkedcout++;
    				    		}
    				    		?>
    				    	 	<div class="col-xs-2 col-sm-2 col-lg-2">
    				    	 		<label>
    				    	 		<input type="checkbox" name="register_number[]" class="<?= $checkstu;?>" onclick="checkelectstucount1(<?= $sectionid;?>)" value="<?= $regnums[$i]['register_number'];?>" <?= $checked;?>>
    				    	 		<?= $regnums[$i]['register_number'];?>
    				    	 		</label>
    				    	 	</div>
    				    	<?php
    				    	} 
    				    	?>
        			</div>
        		</div>

        		<?php $sectionid++;
        	}
            ?>
            </div>
            <?php
    	}
        }else{
            echo "<div style='color:red; text-align:center;'>No Register Number Found For Allocate </div>";
        }
    	?>
    </div>

    <?php 
        if(!empty($deptdata))
        {?>
     <div class="col-xs-12 col-sm-12 col-lg-12"><br><br>
        <div class="col-xs-9 col-sm-9 col-lg-9" style="text-align: right;"><br> <b>Total Selected Student Count: </b><input type="text" id="totalselectedstu" readonly="readonly"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3" <?php if($checkedcout==0){?> style="display: none;"<?php }?> id="saveelectstudent">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                </div>
            </div>
    </div>
<?php }?>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>