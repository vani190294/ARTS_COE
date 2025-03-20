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

$this->title ='View Syllabi';
$this->params['breadcrumbs'][] = ['label' => 'Cur Syllabi', 'url' => ['index']];
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
        <div>&nbsp;</div> 
        <?php if(!empty($codatalist)) 
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
                     <tr>
                        <td colspan="4">
                            <b>Nature of the Course: </b><?= $codatalist['subject_type']." (".$codatalist['subject_category_type'].")";?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <b>Pre-requisite(s):</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <b>Cource Objectives:</b>
                        </td>
                    </tr>
                    <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=6;$l++)
                        {
                            $cot='course_objectives'.$l;
                            if($codatalist[$cot]!='')
                            {                            
                                $html.='<tr>';
                                $html.='<td width=10%>'.$l.'</td>';
                                $html.='<td width=90% colspan="3">'.$codatalist[$cot].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                    
                    echo $html;

                    ?>
                   <tr>
                        <td colspan="4">
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
                                $html.='<td width=10%  colspan="2">'.$rptdta.'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                    
                    echo $html;?>

                    <tr>
                        <td colspan="4">
                            <b>Cource Content:</b>
                        </td>
                    </tr>

                      <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=5;$l++)
                        {
                            $module_title='module_title'.$l;
                            $module_hr='module_hr'.$l;
                            $module_content='cource_content_mod'.$l;
                            if($codatalist[$module_title]!='' && $codatalist[$module_hr]!='' && $codatalist[$module_content]!='')
                            {
                                $html.='<tr>';
                                $html.='<td width=80% colspan=2><b>Module Title: </b><br>'.$codatalist[$module_title].'</td>';
                                $html.='<td width=20% colspan=2><b>'.$codatalist[$module_hr].' Hrs</b></td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td width=100% colspan=4><b>Module Content: </b><br>'.$codatalist[$module_content].'</td>';
                                $html.='</tr>';
                            }
                        }
                    }
                    
                    echo $html;?>

                    <?php $html='';
                    if(!empty($colablist)) 
                    {?>

                        <tr>
                            <th colspan="4">
                                <b>Lab Components:</b>
                            </th>
                        </tr>
                        <tr>
                            <th>S.No.</th>
                            <th>List of Experiments</th>
                            <th>CO Mapping</th>
                            <th>RBT</th>
                        </tr>

                    <?php
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
                    
                    echo $html;
                    ?>

                    <tr>
                        <td colspan="4">
                            <b>Text Book:</b>
                        </td>
                    </tr>
                    <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=3;$l++)
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
                    
                    echo $html;

                    ?>

                    <tr>
                        <td colspan="4">
                            <b>Reference Book:</b>
                        </td>
                    </tr>
                    <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
                        for($l=1;$l<=3;$l++)
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
                    
                    echo $html;

                    ?>

                    <tr>
                        <td colspan="4">
                            <b>Web Reference:</b>
                        </td>
                    </tr>
                    <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
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
                    
                    echo $html;

                    ?>

                    <tr>
                        <td colspan="4">
                            <b>Online Reference:</b>
                        </td>
                    </tr>
                    <?php $html='';
                    if(!empty($codatalist)) 
                    {
                        
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
                    
                    echo $html;

                    ?>

                </table>

            </div> 
        
            
        <?php }?>  
   
    </div>
</div>

</div>


