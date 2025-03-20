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
use app\models\ValuationFacultyAllocate;
echo Dialog::widget();
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

 
$this->title="Valuation Faculty Details";

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


<?php ActiveForm::end(); ?>

<?php
    if(isset($factall_list) && !empty($factall_list))
    {
?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="padding-bottom:20px;">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
               
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/hall-allocate/valuationfacultydetails-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>

            <?php 
               
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Excel', ['/hall-allocate/valuationfacultydetails-excel'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated Excel file in a new window'
                ]);
            ?>
        </div>
    </div>

<?php $data_header=$data_header1=$data_footer=$data='';
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

    $data_header1.='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" border="0" ><tbody align="center">
                    <tr>
                <td colspan=2 > 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=5 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr></tbody></table>';

    $data_header ='<table id="checkAllFeet" class="table table-responsive testth" align="center" border="1" ><tbody align="center">';                 
    
            $data.='<tr>
                        <th style="border-top: 1px solid #000 !impartant;">S.No.</th>     
                        <th style="border-top: 1px solid #000 !impartant;">Name</th>
                        <th style="border-top: 1px solid #000 !impartant;">Designation</th>
                        <th style="border-top: 1px solid #000 !impartant;">Board</th>
                        <th style="border-top: 1px solid #000 !impartant;">Faculty Mode</th>
                        <th style="border-top: 1px solid #000 !impartant;">Experience</th>
                         <th style="border-top: 1px solid #000 !impartant;">Bank Acc No.</th>
                        <th style="border-top: 1px solid #000 !impartant;">Bank IFSC</th>
                        <th style="border-top: 1px solid #000 !impartant;">Bank Name</th>
                        <th style="border-top: 1px solid #000 !impartant;">Bank Branch</th>
                        <th style="border-top: 1px solid #000 !impartant;">Phone Number</th>
                        <th style="border-top: 1px solid #000 !impartant;">Email</th>
                        <th style="border-top: 1px solid #000 !impartant;">College Name</th>
                       </tr>';
          
            
            $sn=1;
            foreach($factall_list as $factall_list1)
            {
                $board=Yii::$app->db->createCommand('select category_type from coe_category_type where coe_category_type_id='.$factall_list1['faculty_board'])->queryScalar();;
                $data.='<tr>';
                
                $data.='<td align="left" >'.$sn.'</td>';
                $data.='<td align="left" >'.$factall_list1['faculty_name'].'</td>';
                $data.='<td align="left" >'.$factall_list1['faculty_designation'].'</td>';
               $data.='<td align="left" >'.$board.'</td>';
               $data.='<td align="left" >'.$factall_list1['faculty_mode'].'</td>';
               $data.='<td align="left" >'.$factall_list1['faculty_experience'].'</td>';
               $data.='<td align="left" >'.$factall_list1['bank_accno'].'</td>';
               $data.='<td align="left" >'.$factall_list1['bank_ifsc'].'</td>';
               $data.='<td align="left" >'.$factall_list1['bank_name'].'</td>';
               $data.='<td align="left" >'.$factall_list1['bank_branch'].'</td>';
               $data.='<td align="left" >'.$factall_list1['phone_no'].'</td>';
                $data.='<td align="left" >'.$factall_list1['email'].'</td>';
               $data.='<td align="left" >'.$factall_list1['college_code'].'</td>';
                $data.='</tr>';

             $sn++;        
            }

    $data_footer.='</tbody></table>';  
    if(isset($_SESSION['valuationfacultydetails'])){ unset($_SESSION['valuationfacultydetails']);}
    $_SESSION['valuationfacultydetails'] = $data_header1.$data_header.$data.$data_footer;
    
    echo $data_header.$data.$data_footer;


    }
?>

</div>
</div>
</div>