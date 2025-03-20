<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabus */

$this->title = 'Update Lab Components';
$this->params['breadcrumbs'][] = ['label' => 'Cur Syllabi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cur-syllabus-view">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
    <div class="box-body"> 
         <?php Yii::$app->ShowFlashMessages->showFlashes();?>
         <div>&nbsp;</div> 
         <div class="col-xs-12 col-sm-12 col-lg-12">
            <table class="table" width="100%">
                <tr>
                        <td width="15%">
                            <b><?= $codatalist['subject_code'];?></b>
                        </td>
                        <td width="70%" colspan="2">
                            <b><?= strtoupper($codatalist['subject_name']);?></b>
                            
                        </td>
                        <td width="15%">
                            <b><?= $codatalist['ltp'];?></b>
                        </td>
                    </tr>
                     <tr>
                        <td colspan="4">
                            <b>Nature of the Course: </b><?= $codatalist['subject_type']." (".$codatalist['subject_category_type'].")";?>
                        </td>
                    </tr>
               <tr>
                    <td>
                        <b>Cource Outcomes:</b>
                    </td>
                </tr>
                <?php $html='';
                if(!empty($codatalist)) 
                {
                    
                    for($l=1;$l<=6;$l++)
                    {
                        $cot='course_outcomes'.$l;
                        $rpt='rpt'.$l;
                        if($codatalist[$cot]!='')
                        {
                            $rptdta = Yii::$app->db->createCommand("SELECT category_type FROM coe_category_type WHERE coe_category_type_id=".$codatalist[$rpt])->queryScalar();
                            $html.='<tr>';
                            $html.='<td width=10%>CO'.$l.'</td>';
                            $html.='<td width=80%>'.$codatalist[$cot].'</td>';
                            $html.='<td width=10%>'.$rptdta.'</td>';
                            $html.='</tr>';
                        }
                    }
                }
                echo $html;
               
                ?>
            </table>

        </div>
        <?php $form = ActiveForm::begin(); ?>
         <input type="hidden" name="syllabus_id" id="syllabus_id" value="<?php echo $cur_syllabus_id;?>">

         <?php $cout=0;
         //print_r($labmodel); exit();
         if(count($cheklabdata)>0)
         {
            $i=1; 
           foreach ($cheklabdata as $value) 
            { 
                $cout++;
                $rpt='rpt'.$i;
                $co='cource_outcome'.$i;
                $coo='cource_outcome'.$i.'[]';
               $rptvalue= $value["rpt"];

               $fuction="getcorpt(".$i.")";?> 
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-md-6">
                <input type="hidden" name="experiment_id[]"  value="<?php echo $value["cur_labcomp_id"];?>">
                <?= $form->field($labmodel, 'experiment_title')->textarea(['Autocomplete'=>"off",'name'=>'experiment_title[]','value'=>$value['experiment_title']]) ?>
                               
            </div>
            
            <div class="col-md-3">
                
                 <?= $form->field($labmodel, 'cource_outcome')->widget(
                    Select2::classname(), [  
                        'data' => $labmodel->getCourceOutcome($cur_syllabus_id),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'multiple'=>'multiple',
                            'placeholder' => '----Select----',
                            'id' => $co,
                            'name'=>$coo,
                            'onchange'=>$fuction,
                            'value'=>explode(",", $value['cource_outcome']) 
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>             
            </div>
            <div class="col-md-3">
                <?= $form->field($labmodel, 'rpt')->textInput(['readonly'=>"readonly",'id'=>$rpt,'name'=>'rpt[]','value'=>$rptvalue]) ?>
                               
            </div>
          
       
        </div>

        <?php $i++;}?>
        <?php }
        else
        {
            $cout=1;?>

               <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-md-6">
                <input type="hidden" name="experiment_id[]">
                <?= $form->field($labmodel, 'experiment_title')->textarea(['Autocomplete'=>"off",'name'=>'experiment_title[]']) ?>
                               
            </div>
            
            <div class="col-md-3">
                <input type="hidden" name="syllabus_id" id="syllabus_id" value="<?php echo $cur_syllabus_id;?>">

                 <?= $form->field($labmodel, 'cource_outcome')->widget(
                    Select2::classname(), [  
                        'data' => $labmodel->getCourceOutcome($cur_syllabus_id),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'multiple'=>'multiple',
                            'placeholder' => '----Select----',
                            'id' => 'cource_outcome1',
                            'name'=>'cource_outcome1[]',
                            'onchange'=>'getcorpt(1);'
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>             
            </div>
            <div class="col-md-3">
                <?= $form->field($labmodel, 'rpt')->textInput(['readonly'=>"readonly",'id'=>'rpt1','name'=>'rpt[]']) ?>
                               
            </div>
          
       
        </div>

       <?php  }?>
       
        <div  id="additional_labcom"></div>

         <div class="col-xs-12 col-sm-12 col-lg-12">
                <input type="hidden" id="addlabcom" value="<?= $cout;?>">
                <?= Html::Button('+ ADD', ['id'=>'labcom','class' => 'pull-right btn btn-primary','onClick'=>'additional_labcom()']) ?>
            </div>


        <div class="col-xs-12 col-sm-12 col-lg-12">
                
            <div class="form-group pull-right"><br><br><br>
                <?= Html::submitButton('Next', ['id'=>'nextsyllabus','class' => 'btn btn-primary']) ?>
                
                <?= Html::a("Cancel", Url::toRoute(['syllabus/service-index']), ['onClick'=>"spinner();",'class' => ' btn btn-warning']) ?>
            </div>
      
        </div>    
    <?php ActiveForm::end(); ?>
    </div>
</div>

</div>


