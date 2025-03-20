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

$this->title = 'Create Syllabus Matrix';
$this->params['breadcrumbs'][] = ['label' => 'Syllabi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curriculum-subject-create">
	<h1><?= Html::encode($this->title) ?></h1>
 <div class="curriculum-subject-form">

	    <div class="box box-success">
			<div class="box-body"> 

				<div class="col-xs-12 col-sm-12 col-lg-12">
			    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
			    <div>&nbsp;</div>
	    		<?php $form = ActiveForm::begin(); ?>

	    		<?php  
	    		if(empty($codatalist))
	    		{
	    		?>
		    		<div class="col-xs-12 col-sm-12 col-lg-12">

		    			
		        		 <div class="col-md-3">
	           
	                    	<?= $form->field($model, 'coe_regulation_id')->widget(
	                            Select2::classname(), [  
	                                'data' => $model->getRegulationDetails(),                      
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'coe_regulation_id',
	                                    'name' => 'coe_regulation_id',                                     
	                                    
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ]) ?>
	                	</div>


	                	 <div class="col-md-3">
	           
	                    	<?= $form->field($model, 'coe_dept_id')->widget(
	                            Select2::classname(), [  
	                                'data' => $model->getDepartmentdetails(),                      
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'coe_dept_id',
	                                    'name' => 'coe_dept_id',
	                                    'onchange'=>'getcoresubject1()'
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ])->label("Matrix to Dept.") ?>
	                	</div>


	                	 <div class="col-md-3">
	               
	                    	<?= $form->field($model, 'subject_code')->widget(
	                                Select2::classname(), [  
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'subject_id',
	                                    'name' => 'subject_code',
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ]) ?>
	                	</div>

	                	<div class="col-xs-3 col-sm-3 col-lg-3">
	                
				            <div class="form-group pull-right"><br>
				                <?= Html::submitButton('Show', ['class' => 'btn btn-primary']) ?>
				                 <?= Html::a("Cancel", Url::toRoute(['syllabus/index']), ['onClick'=>"spinner();",'class' => ' btn btn-warning']) ?>
				            </div>
				        </div>

		        	</div>

		        <?php } else {

               $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$coe_dept_id." AND coe_regulation_id=".$coe_regulation_id)->queryOne();

               $dept = Yii::$app->db->createCommand("SELECT dept_name FROM cur_department WHERE coe_dept_id=".$coe_dept_id)->queryScalar();

               $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_department WHERE coe_dept_id=".$coe_dept_id)->queryScalar();

            $colspan=$deptpso['pso_count']==0?0:$deptpso['pso_count'];

            if($degree_type=='UG')
            {
                if($colspan>0 && empty($matrixdata))
                    {?>

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
                               </table>
                          </div>

                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="12" width="70%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="<?= $colspan;?>" width="30%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=12 ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                        for ($i=1; $i <=$colspan ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        }
                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';
                                                  
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=12 ; $i++) 
                                                {
                                                    
                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3">3</option>
                                                    </select></td>';
                                                }
                                                
                                                for ($p=1; $p <=$colspan ; $p++) 
                                                {
                                                    $psoname="pso_matrix".$p.$l."[]";

                                                    $html.='<td width=5%><select class="form-control" name='.$psoname.'>
                                                    <option value="0">0</option>
                                                        <option value="1"'.$select.'>1</option>
                                                         <option value="2"'.$select1.'>2</option>
                                                          <option value="3">3</option>
                                                        </select></td>';
                                                
                                                }

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>

                 <?php } else {?>

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
                           </table>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="12" width="70%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="<?= $colspan;?>" width="30%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=12 ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                        for ($i=1; $i <=$colspan ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        }
                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                  
                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';$select2='';
                                            $coo='CO'.$l;
                                        
                                            $co_matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$coo."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryOne();
                                            // print_r($co_matrixdata); exit();     
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=12 ; $i++) 
                                                {
                                                    $select='';$select1='';$select2='';
                                                    
                                                    $select='';$select1='';$select2='';
                                                    $po='po'.$i;
                                                    //echo $co_matrix[$po]."<br>"; 
                                                    if($co_matrixdata[$po]==1){$select='selected';}
                                                    if($co_matrixdata[$po]==2){$select1='selected';}
                                                    if($co_matrixdata[$po]==3){$select2='selected';}

                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3"'.$select2.'>3</option>
                                                    </select></td>';
                                                }
                                                
                                                for ($p=1; $p <=$colspan ; $p++) 
                                                {
                                                    $psoname="pso_matrix".$p.$l."[]";

                                                    $html.='<td width=5%><select class="form-control" name='.$psoname.'>
                                                    <option value="0">0</option>
                                                        <option value="1">1</option>
                                                         <option value="2">2</option>
                                                          <option value="3">3</option>
                                                        </select></td>';
                                                
                                                }

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>

                <?php }?>

            <?php }  ?>
            
            <?php if($degree_type=='PG')
            {
                if(empty($matrixdata))
                {?>

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
                               </table>
                          </div>

                        <?php if($colspan>0)
                        {?>
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="4" width="45%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="<?= $colspan;?>" width="45%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                        for ($i=1; $i <=$colspan ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        }
                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                   

                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';
                                                  
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                                {
                                                    
                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3">3</option>
                                                    </select></td>';
                                                }
                                                
                                                for ($p=1; $p <=$colspan ; $p++) 
                                                {
                                                    $psoname="pso_matrix".$p.$l."[]";

                                                    $html.='<td width=5%><select class="form-control" name='.$psoname.'>
                                                    <option value="0">0</option>
                                                        <option value="1"'.$select.'>1</option>
                                                         <option value="2"'.$select1.'>2</option>
                                                          <option value="3">3</option>
                                                        </select></td>';
                                                
                                                }

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>
                        <?php } else {?>
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="4" width="45%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';
                                                  
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                                {
                                                    
                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3">3</option>
                                                    </select></td>';
                                                }
                                               

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>
                        <?php } ?>
                 <?php } ?>
            <?php } ?>

            <?php
             if($degree_type=='MBA')
            {
                    if($colspan>0 && empty($matrixdata))
                    {?>

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
                               </table>
                          </div>

                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="6" width="50%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="<?= $colspan;?>" width="40%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=6 ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                        for ($i=1; $i <=$colspan ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        }
                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                   

                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';
                                                  
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=6 ; $i++) 
                                                {
                                                    
                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3">3</option>
                                                    </select></td>';
                                                }
                                                
                                                for ($p=1; $p <=$colspan ; $p++) 
                                                {
                                                    $psoname="pso_matrix".$p.$l."[]";

                                                    $html.='<td width=5%><select class="form-control" name='.$psoname.'>
                                                    <option value="0">0</option>
                                                        <option value="1"'.$select.'>1</option>
                                                         <option value="2"'.$select1.'>2</option>
                                                          <option value="3">3</option>
                                                        </select></td>';
                                                
                                                }

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>

                 <?php } else {?>

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
                           </table>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <br>
                            <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;"><b><?php echo $dept;?></b></div>
                            <table border="1" width="100%">
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="6" width="50%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="<?= $colspan;?>" width="40%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                                <tr>
                                    <?php 
                                        for ($i=1; $i <=6 ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        } 

                                        for ($i=1; $i <=$colspan ; $i++) 
                                        { 
                                            echo "<th style='text-align: center;'>".$i."</td>";
                                        }
                                    ?>
                                      
                                </tr>

                                <?php $html='';

                                  
                                        for($l=1;$l<=6;$l++)
                                        {
                                            $select='';$select1='';$select2='';
                                            $coo='CO'.$l;
                                        
                                            $co_matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$coo."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryOne();
                                            // print_r($co_matrixdata); exit();     
                                            $cot='course_outcomes'.$l;
                                            $rpt='rpt'.$l;
                                            if($codatalist[$cot]!='')
                                            {
                                                $co_matrix="co_matrix[]";
                                                $html.='<tr>';
                                                $html.='<td width=10% style="text-align: center;">CO'.$l.'<input type="hidden" name='.$co_matrix.' value="CO'.$l.'"></td>';
                                                for ($i=1; $i <=6 ; $i++) 
                                                {
                                                    $select='';$select1='';$select2='';
                                                    
                                                    $select='';$select1='';$select2='';
                                                    $po='po'.$i;
                                                    //echo $co_matrix[$po]."<br>"; 
                                                    if($co_matrixdata[$po]==1){$select='selected';}
                                                    if($co_matrixdata[$po]==2){$select1='selected';}
                                                    if($co_matrixdata[$po]==3){$select2='selected';}

                                                    $html.='<td><select class="form-control"  name="po_matrix'.$l.'[]">
                                                    <option value="0">0</option>
                                                    <option value="1"'.$select.'>1</option>
                                                     <option value="2"'.$select1.'>2</option>
                                                      <option value="3"'.$select2.'>3</option>
                                                    </select></td>';
                                                }
                                                
                                                for ($p=1; $p <=$colspan ; $p++) 
                                                {
                                                    $psoname="pso_matrix".$p.$l."[]";

                                                    $html.='<td width=5%><select class="form-control" name='.$psoname.'>
                                                    <option value="0">0</option>
                                                        <option value="1">1</option>
                                                         <option value="2">2</option>
                                                          <option value="3">3</option>
                                                        </select></td>';
                                                
                                                }

                                                $html.='</tr>';
                                            }
                                        }

                                    
                                    echo $html;

                                ?>
                            </table>
                        </div>

                <?php }?>

            <?php }?> 
            <div class="col-xs-12 col-sm-12 col-lg-12">
                    
            <input type="hidden" name="subject_code" value="<?= $subject_code;?>">
            <input type="hidden" name="coe_dept_id" value="<?= $coe_dept_id;?>">
            <input type="hidden" name="coe_regulation_id" value="<?= $coe_regulation_id;?>">
            <input type="hidden" name="degree_type" value="<?= $degree_type;?>">


                <div class="form-group pull-right"><br>
                    <?= Html::submitButton('Save', ['id'=>'finishsyllabus','name'=>'finishsyllabus','class' => 'btn btn-primary']) ?>
                    
                   
                </div>
          
            </div>   

            

		        <?php }?>

		        <?php ActiveForm::end(); ?>
		    </div>
	        </div>
	     </div>
	</div>

</div>
