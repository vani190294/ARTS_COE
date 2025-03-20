<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\db\Query;
/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */

$this->title = 'Hall Allocates Internal Examinations';
$this->params['breadcrumbs'][] = ['label' => 'Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hall-allocate-view">


    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-8 col-lg-8">
        </div>    
        <div class="col-xs-3 col-sm-4 col-lg-4">
            <?php 

                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/hall-allocate-int/hallpdf'], [
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
        <div class="panel box box-primary">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-lg-1">
                            &nbsp;
                        </div>
                    <div class="col-md-10">
                        <!-- <h4 class="padding box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDegree">Seating Arrangement </a>
                        </h4> -->

                       
                        <?php
                        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 

                        $array= array('1'=>'CIA 1','2'=>'CIA 2','3'=>'CIA 3','5'=>'MODEL','4'=>'COMPONENT');  
                        $cia= $array[$internal_number];
                         $exam_sn = Yii::$app->db->createCommand("select distinct(a.description) from coe_category_type a where a.coe_category_type_id='".$exam_session."'")->queryScalar(); 

                            $head ='<table id="checkAllFeet" border="0" width="100%" class="table table-responsive table-striped" align="center" style="font-family:Roboto, sans-serif;font-size: 11px;margin-bottom: 0px !important;" ><tbody align="center">';                 
                            $head.='<tr>
                                        <td> 
                                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                        </td> 
                                        <td colspan=6 align="center"> 
                                            <center><b><font size="4px">'.$org_name.'</font></b></center>
                                            <center>'.$org_address.'</center>                                            
                                            <center>'.$org_tagline.'</center>
                                            <center><b>INTERNAL EXAMINATIONS '.$cia.' - GALLEY ARRANGEMENT</b></center> 
                                            <center><b> Date of Examinations: '.date('d-m-Y',strtotime($exam_date)).' - '.$exam_sn.' </b> <br>Time Slot: <b>'.$time_slot.'</b></center>
                                        </td>
                                        <td align="center">  
                                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                        </td>
                                    </tr></tbody></table>';

                            $footer ='<table id="checkAllFeet" border="0" width="100%" class="table table-responsive table-striped" align="center" style="font-family:Roboto, sans-serif;font-size: 11px;margin-bottom: 0px !important;" ><tbody align="center">
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: left; margin-right: 5px;"  height="100px" colspan="5">
                          </td>
                          <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="5">
                            <br><br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr></tbody></table>';
                                                       

                            $data=$tmp_sub='';
                            $i=0;
                            if(isset($allocated_value))
                            {
                                $n=count($allocated_value);
                                foreach($allocated_value as $allocate)
                                {                      
                                    $degree_code='';              
                                    if($allocate['degree_code']=='MBABISEM')
                                    {
                                        $degree_code=$allocate['programme_name']; 
                                    }
                                    else
                                    {
                                        $degree_code=$allocate['degree_code'].' '.$allocate['programme_name'];
                                    }

                                    $data.=$head.'<table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif;font-size: 10px; padding:0px !important;" border="1"><tbody>';
                                    $data.='<tr><td align="center" style="border-top:1px solid #000 !important;" colspan=3><h4><b>'.$degree_code.'</b><h4></td></tr>'; 
                                    $data.='<tr><td align="center" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>Hall Name</b></td>
                                            <td align="center" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>Register Number</b></td>
                                            <td align="center" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>Student Count</b></td></tr>';

                                    $query_h = new Query();
                                    $query_h->select("DISTINCT (coe_hall_master_id), hall_name")
                                                      ->from('coe_hall_allocate_int a')
                                                      ->join('JOIN','coe_hall_master b','a.hall_master_id=b.coe_hall_master_id')
                                                      ->join('JOIN','coe_exam_timetable_int c','c.coe_exam_timetable_id=a.exam_timetable_id')
                                                      ->join('JOIN','coe_subjects_mapping d','d.coe_subjects_mapping_id=c.subject_mapping_id')     
                                                      ->where(['c.exam_date' =>$exam_date,'c.exam_session'=>$exam_session,'c.internal_number'=>$internal_number,'d.batch_mapping_id'=>$allocate['course_batch_mapping_id']])
                                                      ->orderBy('hall_name');
                                    //echo $query_h->createCommand()->getrawsql(); exit();

                                    $allocated_h = $query_h->createCommand()->queryAll();
                                    foreach($allocated_h as $hall)
                                    {
                                        

                                        $data.='<tr><td align="center" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>'.$hall['hall_name'].'</b></td>';
                                        
                                        $query_r = new Query();
                                        $query_r->select(["DISTINCT (a.register_number)","coe_student_id"])
                                                          ->from('coe_hall_allocate_int a')
                                                          ->join('JOIN','coe_hall_master b','a.hall_master_id=b.coe_hall_master_id')
                                                          ->join('JOIN','coe_exam_timetable_int c','c.coe_exam_timetable_id=a.exam_timetable_id')
                                                          ->join('JOIN','coe_subjects_mapping d','d.coe_subjects_mapping_id=c.subject_mapping_id') 
                                                          ->join('JOIN','coe_student_mapping e','e.course_batch_mapping_id=d.batch_mapping_id')
                                                          ->join('JOIN','coe_student f','f.coe_student_id=e.student_rel_id and f.register_number=a.register_number')    
                                                          ->where(['c.exam_date' =>$exam_date,'c.exam_session'=>$exam_session,'c.internal_number'=>$internal_number,'d.batch_mapping_id'=>$allocate['course_batch_mapping_id'], 'b.coe_hall_master_id'=>$hall['coe_hall_master_id']])
                                                          ->orderBy('a.register_number');
                                        //echo $query_r->createCommand()->getrawsql(); exit();

                                        $allocated_r = $query_r->createCommand()->queryAll();

                                       $regcount=0;
                                        $register_number1='';
                                        foreach($allocated_r as $regno)
                                        {
                                            $register_number1.=$regno['register_number'].', ';
                                            $i++;
                                            $regcount=$regcount+1;
                                        }

                                        $register_number1=rtrim($register_number1,', ');

                                        $data.='<td align="left" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>'.$register_number1.'</b></td>
                                            <td align="left" style="border-top:1px solid #000 !important; border-bottom:1px solid #000 !important;"><b>'.$regcount.'</b></td>
                                        </tr>';
                                    }

                                    $data.='</tbody></table>';

                                    if($i<$n-1)
                                    {
                                        $data.='<pagebreak>';
                                    }

                                    $i++;
                                }
                                
                            } 

                            
                            
                            echo $data;

                            if(isset($_SESSION['hall_arrange'])){ unset($_SESSION['hall_arrange']);}
                             $_SESSION['hall_arrange'] = $data;
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-lg-1">
                            &nbsp;
                        </div>
                </div>
            </div>
        </div>
    </div>

</div> 