<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
echo Dialog::widget();
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\SubjectsMapping */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="subjects-form">
<div class="box box-success">
<div class="box-body"> 
    
     <?php 

        $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        Yii::$app->ShowFlashMessages->showFlashes();

     ?>
<div>&nbsp;</div>
<div class="subjects-mapping-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
        
        <table class="table table-responsive-xl table-responsive table-striped">
    <tr>
        
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
        <td><?= Html::encode($model->coeBatchName->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
        <td><?= Html::encode($model->coeDegreeName->degree_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
        <td><?= Html::encode($model->coeProgrammeName->programme_code) ?></td>

        <th>
            <?php 
            
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'subject_code')->textInput(['value'=>$model->coeSubjects->subject_code]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('subject_code') ?>
                    <?= Html::encode($model->coeSubjects->subject_code) ?>
                    <?php
                }
            ?>
            

            </th>
        <th>
            
    </tr>
    <tr>
        
       
        <th>
            <?php 
			
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'subject_name')->textInput(['value'=>$model->coeSubjects->subject_name]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('subject_name') ?>
                    <?= Html::encode($model->coeSubjects->subject_name) ?>
                    <?php
                }
            ?>
            

            </th>
        <th>
			<?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'semester')->textInput(['value'=>$model->semester]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('semester') ?>
                    <?= Html::encode($model->semester) ?>
                    <?php
                }
            ?>
		</th>
        
         <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'part_no')->textInput(['value'=>$model->coeSubjects->part_no]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('part_no') ?>
                    <?= Html::encode($model->coeSubjects->part_no) ?>
                    <?php
                }
            ?>
            

         </th> 
         <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'paper_no')->textInput(['value'=>$model->paper_no]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('paper_no') ?>
                    <?= Html::encode($model->paper_no) ?>
                    <?php
                }
            ?>
            

         </th>      
    </tr>
   
    <tr>
        <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'CIA_min')->textInput(['value'=>$model->coeSubjects->CIA_min]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('CIA_min') ?>
                    <?= Html::encode($model->coeSubjects->CIA_min) ?>
                    <?php
                }
            ?>
            

         </th> 
		 <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'CIA_max')->textInput(['value'=>$model->coeSubjects->CIA_max]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('CIA_max') ?>
                    <?= Html::encode($model->coeSubjects->CIA_max) ?>
                    <?php
                }
            ?>
            

         </th> 
		 <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'ESE_min')->textInput(['value'=>$model->coeSubjects->ESE_min]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('ESE_min') ?>
                    <?= Html::encode($model->coeSubjects->ESE_min) ?>
                    <?php
                }
            ?>
            

         </th> 
		 <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'ESE_max')->textInput(['value'=>$model->coeSubjects->ESE_max]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('ESE_max') ?>
                    <?= Html::encode($model->coeSubjects->ESE_max) ?>
                    <?php
                }
            ?>
            

         </th> 
        
    </tr>
   
    <tr>
        <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'total_minimum_pass')->textInput(['value'=>$model->coeSubjects->total_minimum_pass]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('total_minimum_pass') ?>
                    <?= Html::encode($model->coeSubjects->total_minimum_pass) ?>
                    <?php
                }
            ?>
            

         </th>
		 <th>
            <?php 
                if($checkAccess=='Yes')
                {
                     ?>
                     <?= $form->field($model, 'credit_points')->textInput(['value'=>$model->coeSubjects->credit_points]) 
            ?>
                     <?php
                }
                else
                {
                    ?>
                    <?= $model->getAttributeLabel('credit_points') ?>
                    <?= Html::encode($model->coeSubjects->credit_points) ?>
                    <?php
                }
            ?>
            

         </th>
        
    </tr>
  </table>


<div style="display: none;" class="col-lg-3 col-sm-3">
<?= $form->field($model, 'batch_mapping_id')->textInput(['type'=>'hidden']) ?>
</div> 

<div style="display: none;" class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'subject_id')->textInput(['type'=>'hidden']) ?>
</div> 

<div class="col-lg-3 col-sm-3">

    <?php echo $form->field($model,'subject_type_id')->widget(
            Select2::classname(), [
                'data' => $model->getSubjectType(),
                'options' => [
                    'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE).' ----',
                    'name'=>'sub_type_val',
                ],
               'pluginOptions' => [
                   'allowClear' => true,
                ],
            ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE)); 
        ?>
</div> 

<div class="col-lg-3 col-sm-3">
    <?php echo $form->field($model,'course_type_id')->widget(
        Select2::classname(), [
            'data' => $model->getProgrammeType(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE).' ----',
                'name'=>'prgm_type_val',
            ],
           'pluginOptions' => [
               'allowClear' => true,
            ],
        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE)); 
    ?>
</div> 

<div class="col-lg-3 col-sm-3">
    <?php echo $form->field($model,'paper_type_id')->widget(
        Select2::classname(), [
            'data' => $model->getPaperType(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE).' ----',
                'name'=>'paper_type_val',
            ],
           'pluginOptions' => [
               'allowClear' => true,
            ],
        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE)); 
    ?>
</div> 
<?php 

    
    if($checkAccess == "Yes")
    {
        ?>
        <div class="col-lg-3 col-sm-3">

            <?= $form->field($model, 'subject_fee')->textInput(['placeholder'=>'Only Numbers','value'=>$model->coeSubjects->subject_fee]) ?>
            
        </div> 
        <?php
    }

?>
            <div class="col-lg-3 col-sm-3">       
    <div class="form-group"><br />
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>

</div>

</div>
</div>
</div>
