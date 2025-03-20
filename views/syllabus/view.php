<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabus */

$this->title = 'View';
$this->params['breadcrumbs'][] = ['label' => 'Syllabus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .table thead{
        border: 1px solid #221f1f !important;
    }
    .table tbody{
        border: 1px solid #221f1f !important;
    }
    .table th{
        border: 1px solid #221f1f !important;
    }
    .table td{
        border: 1px solid #221f1f !important;
    }
</style>
<div class="cur-syllabus-view">

    <h1><?= Html::encode($this->title) ?></h1>

    

    <div class="box box-success">
    <div class="box-body"> 
        <?php Yii::$app->ShowFlashMessages->showFlashes();?>
           <div  class="col-xs-12"> <br /><br />
        <?php 
        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/syllabus/syllabus-pdf'], [
            'class'=>'pull-right btn btn-block btn-primary', 
            'target'=>'_blank', 
            'data-toggle'=>'tooltip', 
            'title'=>'Will open the generated PDF file in a new window'
        ]); 
        ?>
       
        <div class="col-lg-1" > <?= Html::a('<i class="fa fa-book"></i> Back', ['/syllabus'], [
            'class'=>'btn btn-block btn-warning'
        ]); ?> </div>
        <div class="col-lg-10" align="center"><b><?= $regulation_year; ?></b></div>
        <div class="col-lg-1 pull-right" > <?= $print_pdf; ?> </div>
    </div>

        <div>&nbsp;</div> 

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <?php 
            $html='';

            $samesubjectcode = Yii::$app->db->createCommand("SELECT A.subject_code_new, A.subject_code FROM  cur_electivetodept A  WHERE A.subject_code='".$codatalist["subject_code"]."' AND A.subject_code_new!='".$codatalist["subject_code"]."'")->queryOne();               
                            
            $sysubject_code='';
            if(!empty($samesubjectcode))
            {
                $sysubject_code=$samesubjectcode['subject_code_new']." / ".$samesubjectcode['subject_code'];
            }
            else
            {
                $sysubject_code=$codatalist["subject_code"];
            }

            if(!empty($codatalist)) 
            {
                $_SESSION['Syllabussubject'] = $sysubject_code;
                $html.='<table class="table" width="100%">
                    <tr>
                        <td width="15%">
                            <b>'.$sysubject_code.'</b>
                        </td>
                        <td width="70%" colspan="2" align=center>';
                
                        $html.='<b>'.strtoupper($codatalist["subject_name"]).'</b>';
                            
                     $html.='    </td>
                        <td width="15%" align=center>
                            <b>'.$codatalist["ltp"].'</b>
                        </td>
                    </tr>
                     <tr>
                        <td colspan="4">
                            <b>Nature of the Course: </b>'.$codatalist['subject_type'].' ('.$codatalist['subject_category_type'].')
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <b>Pre-requisite(s):'.$codatalist['prerequisties'].'</b>
                        </td>
                    </tr>';

                    if($coe_dept_id==26 && !empty($codatalist['course_description']))
                    {
                         $html.='<tr>
                                <td colspan="4">
                                    <b>Course Description:</b>
                                </td>
                            </tr>';

                        $html.='<tr>
                                <td colspan="4">'.$codatalist['course_description'].'
                                </td>
                            </tr>';
                    }

                   $html.='<tr>
                        <td colspan="4">
                            <b>Course Objectives:</b>
                        </td>
                    </tr>';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=6;$l++)
                        {
                            $cot='course_objectives'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=15% align=center>'.$l.'</td>';
                                $html.='<td width=90% colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                    
                    

                       $html.=' <tr>
                            <td colspan="4">
                                <b>Course Outcomes:</b>
                            </td>
                        </tr>';
                        if(!empty($codatalist)) 
                        {
                            
                            for($l=1;$l<=6;$l++)
                            {
                                $cot='course_outcomes'.$l;
                                $rpt='rpt'.$l;
                                if($codatalist[$cot]!='')
                                {   
                                    $rptdta ='';
                                    if($codatalist[$rpt]!='')
                                    {
                                       $rptdta = Yii::$app->db->createCommand("SELECT category_type FROM coe_category_type WHERE coe_category_type_id=".$codatalist[$rpt])->queryScalar(); 
                                    }
                                    
                                    $html.='<tr>';
                                    $html.='<td width=15% align=center>CO'.$l.'</td>';
                                    $html.='<td width=75% colspan=2>'.$codatalist[$cot].'</td>';
                                    $html.='<td width=10% align=center>'.$rptdta.'</td>';
                                    $html.='</tr>';
                                }
                            }
                        }
                
                    $html.=' </table><table class="table" width="100%">';
                   
                     
                    if(!empty($codatalist['module_title1']) && $codatalist['module_title1']!=' ' && $codatalist['module_title1']!='' ) 
                    {
                         $html.='   <tr>
                            <td colspan="4">
                                <b>Course Content:</b>
                            </td>
                        </tr>';
                        for($l=1;$l<=5;$l++)
                        {
                            $module_title='module_title'.$l;
                            $module_hr='module_hr'.$l;
                            $module_content='cource_content_mod'.$l;
                            if($codatalist[$module_title]!='' && $codatalist[$module_hr]!='' && $codatalist[$module_content]!='')
                            {
                                 if($l==5)
                                {
                                     $html.=' </table> <pagebreak>
                                     <table class="table" width="100%">
                                        ';
                                }

                                

                                $html.='<tr>';
                                $html.='<td width=80% colspan=3><b>Module '.$l.': </b><br>'.$codatalist[$module_title].'</td>';
                                $html.='<td width=20%><b>'.$codatalist[$module_hr].' Hrs</b></td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td width=100% colspan=4>'.$codatalist[$module_content].'</td>';
                                $html.='</tr>';

                               
                            }
                        }
                    }
                    
                    if(!empty($colablist)) 
                    {
                      $html.='   <tr>
                            <th colspan="4">
                                <b>Lab Components:</b>
                            </th>
                        </tr>
                        <tr>
                            <th>S.No.</th>
                            <th>List of Experiments</th>
                            <th>CO Mapping</th>
                            <th>RBT</th>
                        </tr>';

                        $s=1;
                        foreach ($colablist as $value) 
                        {
                            $html.='<tr>';
                            $html.='<td width=10%>'.$s.'</td>';
                            $html.='<td width=60%>'.$value['experiment_title'].'</td>';
                            $html.='<td width=15%>'.$value['cource_outcome'].'</td>';
                            $html.='<td width=15%>'.$value['rpt'].'</td>';
                            $html.='</tr>';
                            $s++;
                        }

                    }

                    if(!empty($codatalist['text_book1']) && $codatalist['text_book1']!=' ' && $codatalist['text_book1']!='' ) 
                    {
                   
                    $html.=' <tr>
                        <td colspan="4">
                            <b>Text Book:</b>
                        </td>
                    </tr>';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=4;$l++)
                        {
                            $cot='text_book'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=10%>'.$l.'</td>';
                                $html.='<td width=90% colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }

                    }
                    
                    if(!empty($codatalist['reference_book1']) && $codatalist['reference_book1']!=' ' && $codatalist['reference_book1']!='' ) 
                    {
                   
                     $html.='<tr>
                        <td colspan="4">
                            <b>Reference Book:</b>
                        </td>
                    </tr>';
                   
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=6;$l++)
                        {
                            $cot='reference_book'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=10%>'.$l.'</td>';
                                $html.='<td width=90%  colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                   
                    }
                 
                   
                    if(!empty($codatalist['web_reference1']) && $codatalist['web_reference1']!=' ' && $codatalist['web_reference1']!='' ) 
                    {
                          $html.='  <tr>
                                <td colspan="4">
                                    <b>Web Reference:</b>
                                </td>
                            </tr>';

                        for($l=1;$l<=3;$l++)
                        {
                            $cot='web_reference'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=10%>'.$l.'</td>';
                                $html.='<td width=90% colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                   

                    $online_reference1 = str_replace(' ', '', $codatalist['online_reference1']);
                    //print_r($online_reference1); exit();
                    if(!empty($codatalist['online_reference1']) && $online_reference1!='') 
                    {
                        
                         $html.=' <tr>
                            <td colspan="4">
                                <b>Online Reference:</b>
                            </td>
                        </tr>';

                        for($l=1;$l<=2;$l++)
                        {
                            $cot='online_reference'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=10%>'.$l.'</td>';
                                $html.='<td width=90% colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }                    
                     $html.=' </table> ';
                    


                        $co_matrix_dept = Yii::$app->db->createCommand("SELECT A.coe_dept_id,B.dept_name,B.degree_type FROM cur_course_articulation_matrix A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id WHERE cur_syllabus_id=".$cur_syllabus_id." GROUP BY A.coe_dept_id")->queryAll();

                        $coe_regulation_id = Yii::$app->db->createCommand("SELECT coe_regulation_id FROM cur_syllabus WHERE cur_syllabus_id=".$cur_syllabus_id)->queryScalar();
                        //print_r($co_matrix_dept); exit;

                        if(!empty($co_matrix_dept) && $co_matrix_dept[0]['degree_type']=='UG')
                        {
                            foreach ($co_matrix_dept as $dept) 
                            {
                                
                                $co_matrix = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$cur_syllabus_id." AND coe_dept_id=".$dept['coe_dept_id'])->queryAll();

                                $colspan=$co_matrix[0]['pso_count'];

                                $html.=' 

                                <table border="1" width="100%">
                                <tr>                                    
                                      <th colspan="'.($colspan+13).'" width="10%" style="text-align: center;">'.$dept['dept_name'].'</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="12" width="75%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="'.$colspan.'" width="20%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                       
                                <tr>';
                                
                                for ($i=1; $i <=12 ; $i++) 
                                { 
                                    $html.= "<th style='text-align: center;'>".$i."</td>";
                                } 
                                for ($i=1; $i <=$colspan ; $i++) 
                                { 
                                    $html.= "<th style='text-align: center;'>".$i."</td>";
                                }
                                $html.= "</tr>";

                                if(!empty($co_matrix))
                                {
                                    foreach ($co_matrix as $value) 
                                    {
                                        $html.='<tr>';
                                        $html.='<td width=10% style="text-align: center;">'.$value['co'].'</td>';
                                        for ($i=1; $i <=12 ; $i++) 
                                        { 
                                            $po='po'.$i;
                                            $html.='<td style="text-align: center;">'.$value[$po].'</td>';
                                        }

                                        $pso_value=explode(",", $value['pso_value']);
                                        for ($p=0; $p <$colspan ; $p++) 
                                        { 
                                            $html.='<td width=5% style="text-align: center;">'.$pso_value[$p].'</td>';
                                        }
                                        
                                        $html.='</tr>';

                                    }
                                }

                                $html.=' </table><br>';
                            }
                        }

                        if(!empty($co_matrix_dept) && $co_matrix_dept[0]['degree_type']=='PG')
                        {

                            $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$co_matrix_dept[0]['coe_dept_id']." AND coe_regulation_id=".$coe_regulation_id)->queryOne();

                            foreach ($co_matrix_dept as $dept) 
                            {
                                
                                $co_matrix = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$cur_syllabus_id." AND coe_dept_id=".$dept['coe_dept_id'])->queryAll();

                                $colspan=$deptpso['pso_count'];

                                if($colspan>0)
                                {
                                    $html.=' 

                                    <table border="1" width="100%">
                                    <tr>                                    
                                          <th colspan="'.($colspan+13).'" width="10%" style="text-align: center;">'.$dept['dept_name'].'</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                         <th colspan="12" width="75%" style="text-align: center;">Programme Outcomes (PO)</th>
                                          <th colspan="'.$colspan.'" width="20%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                    </tr>
                           
                                    <tr>';
                                
                                    for ($i=1; $i <=$deptpso['po_count']; $i++) 
                                    { 
                                        $html.= "<th style='text-align: center;'>".$i."</td>";
                                    } 
                                    for ($i=1; $i <=$colspan ; $i++) 
                                    { 
                                        $html.= "<th style='text-align: center;'>".$i."</td>";
                                    }
                                    $html.= "</tr>";

                                    if(!empty($co_matrix))
                                    {
                                        foreach ($co_matrix as $value) 
                                        {
                                            $html.='<tr>';
                                            $html.='<td width=10% style="text-align: center;">'.$value['co'].'</td>';
                                            for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                            { 
                                                $po='po'.$i;
                                                $html.='<td style="text-align: center;">'.$value[$po].'</td>';
                                            }

                                            $pso_value=explode(",", $value['pso_value']);
                                            for ($p=0; $p <$colspan ; $p++) 
                                            { 
                                                $html.='<td width=5% style="text-align: center;">'.$pso_value[$p].'</td>';
                                            }
                                            
                                            $html.='</tr>';

                                        }
                                    }
                                }
                                else
                                {

                                    $html.=' 

                                    <table border="1" width="100%">
                                    <tr>                                    
                                          <th colspan="'.($deptpso['po_count']+2).'" width="10%" style="text-align: center;">'.$dept['dept_name'].'</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                         <th colspan="'.$deptpso['po_count'].'" width="75%" style="text-align: center;">Programme Outcomes (PO)</th>
                                    </tr>
                           
                                    <tr>';
                                
                                    for ($i=1; $i <=$deptpso['po_count']; $i++) 
                                    { 
                                        $html.= "<th style='text-align: center;'>".$i."</td>";
                                    } 
                                    
                                    $html.= "</tr>";

                                    if(!empty($co_matrix))
                                    {
                                        foreach ($co_matrix as $value) 
                                        {
                                            $html.='<tr>';
                                            $html.='<td width=10% style="text-align: center;">'.$value['co'].'</td>';
                                            for ($i=1; $i <=$deptpso['po_count'] ; $i++) 
                                            { 
                                                $po='po'.$i;
                                                $html.='<td style="text-align: center;">'.$value[$po].'</td>';
                                            }

                                            
                                            $html.='</tr>';

                                        }
                                    }
                                }

                                $html.=' </table><br>';
                            }
                        }

                        if(!empty($co_matrix_dept) && $co_matrix_dept[0]=='MBA')
                        {
                            foreach ($co_matrix_dept as $dept) 
                            {
                                
                                $co_matrix = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$cur_syllabus_id." AND coe_dept_id=".$dept['coe_dept_id'])->queryAll();

                                $colspan=$co_matrix[0]['pso_count'];

                                $html.=' 

                                <table border="1" width="100%">
                                <tr>                                    
                                      <th colspan="'.($colspan+13).'" width="10%" style="text-align: center;">'.$dept['dept_name'].'</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" width="10%" style="text-align: center;">Course Outcome (CO)</th>
                                     <th colspan="6" width="75%" style="text-align: center;">Programme Outcomes (PO)</th>
                                      <th colspan="'.$colspan.'" width="20%" style="text-align: center;">Programme Specific Outcomes (PSO)</th>
                                </tr>
                       
                                <tr>';
                                
                                for ($i=1; $i <=6 ; $i++) 
                                { 
                                    $html.= "<th style='text-align: center;'>".$i."</td>";
                                } 
                                for ($i=1; $i <=$colspan ; $i++) 
                                { 
                                    $html.= "<th style='text-align: center;'>".$i."</td>";
                                }
                                $html.= "</tr>";

                                if(!empty($co_matrix))
                                {
                                    foreach ($co_matrix as $value) 
                                    {
                                        $html.='<tr>';
                                        $html.='<td width=10% style="text-align: center;">'.$value['co'].'</td>';
                                        for ($i=1; $i <=6 ; $i++) 
                                        { 
                                            $po='po'.$i;
                                            $html.='<td style="text-align: center;">'.$value[$po].'</td>';
                                        }

                                        $pso_value=explode(",", $value['pso_value']);
                                        for ($p=0; $p <$colspan ; $p++) 
                                        { 
                                            $html.='<td width=5% style="text-align: center;">'.$pso_value[$p].'</td>';
                                        }
                                        
                                        $html.='</tr>';

                                    }
                                }

                                $html.=' </table><br>';
                            }
                        }
                    
                        $html.=' </table>';

                        if (isset($_SESSION['SyllabusPdf'])) {
                            unset($_SESSION['SyllabusPdf']);
                        }
                        $_SESSION['SyllabusPdf'] = $html;

                echo $html;
            }?>  
   
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?php if($codatalist['approve_status']==0) 
        {?>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-9 col-sm-9 col-lg-9"></div>
                <div class="col-xs-3 col-sm-3 col-lg-3">
                    <div class="form-group">
                        <br>
                        <?= Html::submitButton('Approve', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                    </div>
                </div>
        </div>

        <?php } ?>
    
    <?php ActiveForm::end(); ?>
</div>

</div>
