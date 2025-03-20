<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

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
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                            
                        <!-- <h4 class="padding box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDegree">Seating Arrangement </a>
                        </h4> -->

                       
                        <?php //echo $hall_types; exit;
                        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
                        $data='';
                        if($hall_types==56)
                        {
                            $head ='<table id="checkAllFeet" border="0" width="100%" class="table table-responsive table-striped" align="center" style="font-family:Roboto, sans-serif;font-size: 14px;margin-bottom: 0px !important;" ><tbody align="center" >';                 
                            $head.='<tr>
                                        <td> 
                                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                        </td> 
                                        <td colspan=6 align="center"> 
                                            <center><b><font size="4px">'.$org_name.'</font></b></center>
                                            <center>'.$org_tagline.'</center> 
                                            <center>'.$org_address.'</center>
                                            <center><b>INTERNAL EXAMINATIONS - GALLEY ARRANGEMENT</b></center>
                                             <center><b> Date of Examinations: '.date('d-m-Y',strtotime($exam_date)).' - '.$exam_session.' &nbsp; Internal Exam: '.$internal_number.'</b> <br>Time Slot: <b>'.$time_slot.'</b></center>
                                        </td>
                                        <td align="center">  
                                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                        </td>

                                    </tr></tbody></table>';     

                            
                            $data='';
                            $column_size = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);
                            $same_hall_name='';
                            $k=0;
                            $column=0;
                            $seat_no=1;
                            $total_halls = 0;
                            $sub_code=[];
                            $student_enroll=[];
                            $tmp_sub=''; $tempreg=0; $tempseat=0;


                                $hallstucount = Yii::$app->db->createCommand("SELECT count(hall_master_id) FROM coe_hall_allocate_int A JOIN coe_exam_timetable_int B ON B.coe_exam_timetable_id=A.exam_timetable_id WHERE A.hall_master_id='".$allocated_value[0]['hall_master_id']."' AND  B.exam_date='".$allocated_value[0]['exam_date']."' AND  B.exam_session='".$allocated_value[0]['exam_session']."'")->queryScalar(); 


                                if($hallstucount==60)
                                {
                                    $fontsize='font-size: 14px; font-weight:bold;';
                                }
                                else
                                {
                                    $fontsize='font-size: 14px; font-weight:bold;';
                                }

                                $data=$head.'<table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif; padding:0px !important; border-right:1px !important; '.$fontsize.'" border="1"><tbody><tr>'; 

                                $loop=1;
                                foreach($allocated_value as $allocate)
                                {         

                                    $nextrecard=0;

                                    if($allocate['row']!=$k)
                                    {  
                                        if($same_hall_name!=$allocate['hall_name'])
                                        {                                                 

                                            $hallstucount = Yii::$app->db->createCommand("SELECT count(hall_master_id) FROM coe_hall_allocate_int A JOIN coe_exam_timetable_int B ON B.coe_exam_timetable_id=A.exam_timetable_id WHERE A.hall_master_id='".$allocate['hall_master_id']."' AND  B.exam_date='".$allocate['exam_date']."' AND  B.exam_session='".$allocate['exam_session']."'")->queryScalar(); 


                                            if($hallstucount==60)
                                            {
                                                $fontsize='font-size: 14px; font-weight:bold;';
                                            }
                                            else
                                            {
                                                $fontsize='font-size: 14px; font-weight:bold;';
                                            }

                                            if($seat_no!=1)
                                            {
                                                $sub_code1 =''; $tot_sub=0;
                                                foreach($sub_code as $enroll_sub)
                                                {
                                                    $stud_data= array_column($student_enroll, $enroll_sub);
                                                    
                                                    if(count($stud_data)>0)
                                                    {
                                                        $sub_code1.='
                                                        <td class="tabletd" colspan="8" align="left">'.$enroll_sub.'</td>
                                                        <td class="tabletd" colspan="2" align="left">'.count($stud_data).'</td></tr><tr>';
                                                        $tot_sub=$tot_sub+count($stud_data);
                                                    }
                                                }

                                                $data.='</tr></tbody></table><pagebreak>
                                                <table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif; padding:0px !important; border-right:1px !important; '.$fontsize.'" border="1"><tbody>'; 
                                                $data.='<tr>';
                                                $data.='
                                                <td class="tabletd" colspan="8" align="left">QP CODES</td>
                                                <td class="tabletd" colspan="2" align="left">TOTAL COUNT</td>';
                                                $data.='</tr><tr>';
                                                $data.=$sub_code1;
                                                $data.='
                                                <td class="tabletd" colspan="8" align="left">TOTAL</td>
                                                <td class="tabletd" colspan="2" align="left">'.$tot_sub.'</td>';
                                                $data.='</tr><tr>';
                                                $data.='
                                                <td class="tabletd" colspan="8" align="left">REGISTER NUMBER OF THE ENROLLED STUDENT</td>
                                                <td colspan="2" align="left">TOTAL ABSENTS</td>';
                                                $data.='</tr><tr><td class="tabletd" align="left" colspan="8">';
                                                $sloop=0;
                                                foreach($sub_code as $enroll_sub)
                                                {
                                                    $stud_data= array_column($student_enroll, $enroll_sub);
                                                    $stud='';
                                                    if(count($stud_data)>0)
                                                    {
                                                        $ro=0;
                                                        foreach($stud_data as $stud__list)
                                                        {
                                                            if($ro==0)
                                                            {
                                                                $stud=$stud__list;
                                                            }
                                                            else
                                                            {
                                                                $stud.=", ".$stud__list;
                                                            }
                                                            $ro++;
                                                        }
                                                        if($sloop>0)
                                                        {
                                                            $data.= "<br>";
                                                        }
                                                        $data.= "<b><u>".$enroll_sub.":</u> </b>".$stud."<br>";
                                                    }

                                                    $sloop++;
                                                }

                                                $data.='</td><td colspan="2"></td></tr>';
                                                
                                                 $data.='<tr>
                                                 <td class="tabletd" colspan="8" align="left">R-Register Number / Q-Question Paper Code</td>
                                                 <td class="tabletd" colspan="2" align="left">TOTAL </td></tr>';
                                              
                                                $data.='<tr>';
                                                
                                                 $data.='
                                                 <td class="tabletd" colspan="4" align="left" style="height: 70px; vertical-align:bottom;border-top: 0px "><br><br>NAME & SIGNATURE OF THE HALL SUPERINTENDENT</td>
                                                <td class="tabletd" colspan="6" align="right" style="height: 70px; vertical-align:bottom;border-top: 0px "><br><br>NAME & SIGNATURE OF THE CHIEF SUPERINTENDENT / COE</td>';
                                                $data.='</tr></tbody></table><pagebreak />';

                                                $data.=$head.'<table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif; padding:0px !important; border-right:1px !important; '.$fontsize.'" border="1"><tbody><tr>';

                                            }     
                                            $total_halls++;
                                            
                                            $k=1;
                                            $same_hall_name = $allocate['hall_name'];
                                            $data.='
                                            <td class="tabletd" colspan="10" align="center" style="border-top:1px solid #000 !important;"><h4><b>EXAMINATION HALL NAME: &nbsp; '.$allocate['hall_name'].'</b><h4></td></tr><tr>'; 
                                            $seat_no=1;
                                            $student_enroll=[];
                                            
                                        }else{
                                            $k++;
                                        }
                                        
                                        $data.='<tr>';  
                                    }         

                                    $checkseat_no = Yii::$app->db->createCommand("SELECT count(hall_master_id) FROM coe_hall_allocate_int A JOIN coe_exam_timetable_int B ON B.coe_exam_timetable_id=A.exam_timetable_id WHERE A.seat_no='".$allocate['seat_no']."' AND A.hall_master_id='".$allocate['hall_master_id']."' AND  B.exam_date='".$allocate['exam_date']."' AND  B.exam_session='".$allocate['exam_session']."'")->queryScalar(); 

                                    if($tempseat==$allocate['seat_no'])
                                    {
                                        if($checkseat_no==1)
                                        {
                                            $data.='<td colspan=2 class="tabletd1" align="center" style="border-right:1px solid #000 !important;" >R: '.$allocate['register_number'].'<br>Q:'.$allocate['subject_code'].'<br>'.$allocate['seat_no'].'</td>';
                                        }
                                        else
                                        {
                                            $data.='<td class="tabletd1" align="center" style="border-left:0px !important; border-right:1px solid #000 !important;" >R: '.$allocate['register_number'].'<br>Q:'.$allocate['subject_code'].'<br>'.$allocate['seat_no'].'</td>';
                                        }
                                        
                                    }
                                    else 
                                    {
                                        if($checkseat_no==1)
                                        {
                                            $data.='<td colspan=2 class="tabletd1" align="center" style="border-right:1px solid #000 !important;" >R: '.$allocate['register_number'].'<br>Q:'.$allocate['subject_code'].'<br>'.$allocate['seat_no'].'</td>';
                                        }
                                        else
                                        {
                                            $data.='<td class="tabletd1" align="center" style="border-right:0px !important;" >R: '.$allocate['register_number'].'<br>Q:'.$allocate['subject_code'].'<br>'.$allocate['seat_no'].'</td>';
                                        }
                                        
                                            $column++; 
                                        
                                        
                                    }

                                    $student_enroll[]=array($allocate['subject_code']=>$allocate['register_number']);
                                    $seat_no++;
                                                                       
                                    if($column == $column_size)
                                    {
                                        if($tempseat==$allocate['seat_no'])
                                        {
                                            $data.='</tr>';
                                            $column=0;
                                        }
                                    }  
                                    if(!in_array($allocate['subject_code'], $sub_code))
                                    {
                                        $sub_code[]=$allocate['subject_code'];    
                                    }        
                                    $tmp_sub=$allocate['subject_code'];

                                    $tempreg=$allocate['register_number']; 
                                    $tempseat=$allocate['seat_no'];

                                    $loop++;
                                }

                                if(count($sub_code)>0)
                                {
                                    $sub_code1 =''; $tot_sub=0;
                                    foreach($sub_code as $enroll_sub)
                                    {
                                        $stud_data= array_column($student_enroll, $enroll_sub);
                                                        
                                        if(count($stud_data)>0)
                                         {
                                            $sub_code1.='
                                            <td class="tabletd" colspan="8" align="left">'.$enroll_sub.'</td>
                                            <td class="tabletd" colspan="2" align="left">'.count($stud_data).'</td></tr><tr>';
                                            $tot_sub=$tot_sub+count($stud_data);
                                        }
                                    }
                                    
                                    $data.='</tr></tbody></table><pagebreak>
                                            <table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif; padding:0px !important; border-right:1px !important; '.$fontsize.'" border="1"><tbody>'; 
                                    $data.='<tr>';
                                    $data.='
                                    <td class="tabletd" colspan="8" align="left" style="border-top: 1px solid #000 !important; ">QP CODES</td>
                                    <td class="tabletd" colspan="2" align="left" style="border-top: 1px solid #000 !important; ">TOTAL COUNT</td>';
                                    $data.='</tr><tr>';
                                    $data.=$sub_code1;                   
                                    $data.='
                                    <td class="tabletd" colspan="8" align="left">TOTAL</td>
                                    <td class="tabletd" colspan="2" align="left">'.$tot_sub.'</td>';
                                    $data.='</tr><tr>';
                                    $data.='
                                    <td class="tabletd" colspan="8" align="left">REGISTER NUMBER OF THE ENROLLED STUDENT</td>
                                    <td class="tabletd" colspan="2" align="left">TOTAL ABSENTS</td>';
                                    $data.='</tr><tr>
                                    <td class="tabletd" align="left" colspan="8" >';
                                    $sloop=0;
                                    foreach($sub_code as $enroll_sub)
                                    {
                                        $stud_data= array_column($student_enroll, $enroll_sub);
                                        $stud='';
                                        if(count($stud_data)>0)
                                        {
                                            $ro=0;
                                            foreach($stud_data as $stud__list)
                                            {
                                                if($ro==0)
                                                {
                                                    $stud=$stud__list;
                                                }
                                                else
                                                {
                                                    $stud.=", ".$stud__list;
                                                }
                                                $ro++;
                                            }
                                            if($sloop>0)
                                            {
                                                $data.= "<br>";
                                            }
                                            $data.= "<b><u>".$enroll_sub.":</u> </b>".$stud."<br>";
                                        }

                                        $sloop++;
                                    }

                                    $data.='</td><td colspan="2"></td></tr>';
                                                        
                                    $data.='<tr>
                                    <td class="tabletd" colspan="8" align="left">R-Register Number / Q-Question Paper Code</td>
                                    <td class="tabletd" colspan="2" align="left">TOTAL </td></tr>';
                                                      
                                    $data.='<tr>';
                                                        
                                    $data.='
                                    <td class="tabletd" colspan="4" align="left" style="height: 70px;  vertical-align:bottom;border-top: 0px "><br><br>NAME & SIGNATURE OF THE HALL SUPERINTENDENT</td>
                                    <td class="tabletd" colspan="6" align="right" style="height: 70px; vertical-align:bottom;border-top: 0px "><br><br>NAME & SIGNATURE OF THE CHIEF SUPERINTENDENT / COE </td>';
                                }
                                $data.='</tbody></table>';
                        }
                        else
                        {
                             
                            $head ='<table id="checkAllFeet" border="0" width="100%" class="table table-responsive table-striped" align="center" style="font-family:Roboto, sans-serif;font-size: 11px;margin-bottom: 0px !important;" ><tbody align="center">';                 
                            $head.='<tr>
                                        <td> 
                                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                        </td> 
                                        <td colspan=3 align="center"> 
                                            <center><b><font size="4px">'.$org_name.'</font></b></center>
                                            <center>'.$org_address.'</center>
                                            
                                            <center>'.$org_tagline.'</center> 
                                        </td>
                                        <td align="center">  
                                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                        </td>
                                    </tr>';
                            $head.='<tr><td colspan="5" align="center"><b>INTERNAL EXAMINATIONS - GALLEY ARRANGEMENT</b></td></tr>';

                            $head.='<tr><td align="center" colspan="5"><b> Date of Examinations: '.date('d-m-Y',strtotime($exam_date)).' - '.$exam_session.' &nbsp; Internal Exam: '.$internal_number.'</b> <br>Time Slot: <b>'.$time_slot.'</b></td></tr></tbody></table>';
                            
                            $head1=$head.'<table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif;font-size: 10px;padding:0px !important;" border="1"><tbody><tr>';

                            $data=$head.'<table id="checkAllFeet" width="100%" class="table table-responsive table-striped" align="center"  style="font-family:Roboto, sans-serif;font-size: 10px; padding:0px !important;" border="1"><tbody><tr>'; 
                           
                            $column_size = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);
                            $same_hall_name='';
                            $k=0;
                            $column=0;
                            $seat_no=1;
                            $total_halls = 0;
                            $sub_code=[];
                            $student_enroll=[];
                            $tmp_sub='';
                            if(isset($allocated_value)){
                                foreach($allocated_value as $allocate)
                                {                                    

                                    if($allocate['row']!=$k)
                                    {  
                                        if($same_hall_name!=$allocate['hall_name'])
                                        { 

                                            if($seat_no!=1)
                                            {
                                                 $sub_code1 =''; $tot_sub=0;
                                                 foreach($sub_code as $enroll_sub)
                                                {
                                                    $stud_data= array_column($student_enroll, $enroll_sub);
                                                    
                                                    if(count($stud_data)>0)
                                                    {
                                                        $sub_code1.='<td colspan="4" align="left"><h5>'.$enroll_sub.'</h5></td><td colspan="1" align="left"><h5>'.count($stud_data).'</h4></td></tr><tr>';
                                                        $tot_sub=$tot_sub+count($stud_data);
                                                    }
                                                }
                                                $data.='<tr>';
                                                $data.='<td colspan="4" align="left"><h5>QP CODES</h5></td><td colspan="1" align="left"><h5>TOTAL COUNT</h5></td>';
                                                $data.='</tr><tr>';
                                                $data.=$sub_code1;
                                                $data.='<td colspan="4" align="left"><h5>TOTAL</h5></td><td colspan="1" align="left"><h5>'.$tot_sub.'</h4></td>';
                                                $data.='</tr><tr>';
                                                $data.='<td colspan="4" align="left"><h5>REGISTER NUMBER OF THE ENROLLED STUDENT</h5></td><td colspan="1" align="left"><h5>TOTAL ABSENTS</h5></td>';
                                                $data.='</tr><tr><td align="left" colspan="4" style="font-size: 9px;">';
                                                
                                                foreach($sub_code as $enroll_sub)
                                                {
                                                    $stud_data= array_column($student_enroll, $enroll_sub);
                                                    $stud='';
                                                    if(count($stud_data)>0)
                                                    {
                                                        $ro=0;
                                                        foreach($stud_data as $stud__list)
                                                        {
                                                            if($ro==0)
                                                            {
                                                                $stud=$stud__list;
                                                            }
                                                            else
                                                            {
                                                                $stud.=", ".$stud__list;
                                                            }
                                                            $ro++;
                                                        }
                                                        $data.= "<b>".$enroll_sub.": </b>".$stud."<br>";
                                                    }
                                                }

                                                $data.='</td><td colspan="1"></td></tr>';
                                                
                                                 $data.='<tr><td colspan="4" align="left"><h5>R-Register Number / Q-Question Paper Code</h5></td><td colspan="1" align="left"><h5>TOTAL </h5></td></tr>';
                                              
                                                $data.='<tr>';
                                                
                                                 $data.='<td  colspan="3" align="left" style="height: 70px; vertical-align:bottom;border-top: 0px "><h6>NAME & SIGNATURE OF THE HALL SUPERINTENDENT</h6></td>
                                                    <td colspan="2" align="right" style="height: 70px; vertical-align:bottom;border-top: 0px "><h6>NAME & SIGNATURE OF THE CHIEF SUPERINTENDENT / COE </h6></td>';
                                                $data.='</tr></tbody></table><pagebreak />';

                                                $data.=$head1;

                                            }     
                                            $total_halls++;
                                            
                                            $k=1;
                                            $same_hall_name = $allocate['hall_name'];
                                            $data.='<td colspan="5" align="center" style="border-top:1px solid #000 !important;"><h4><b>EXAMINATION HALL NAME: &nbsp; '.$allocate['hall_name'].'</b><h4></td></tr><tr>'; 
                                            $seat_no=1;
                                            $student_enroll=[];
                                            
                                        }else{
                                            $k++;
                                        }
                                        
                                        $data.='<tr>';  
                                    }                                    

                                    $data.='<td width="20%" align="center">R: '.$allocate['register_number'].'<br>Q:'.$allocate['subject_code'].'<br><h5><b>'.$seat_no.'</b></h5></td>';
                                     $student_enroll[]=array($allocate['subject_code']=>$allocate['register_number']);
                                    $seat_no++;
                                    $column++;                                    
                                    if($column == $column_size){
                                         $data.='</tr>';
                                    }  
                                    if(!in_array($allocate['subject_code'], $sub_code))
                                    {
                                        $sub_code[]=$allocate['subject_code'];    
                                    }        
                                    $tmp_sub=$allocate['subject_code'];
                                }
                                
                            } 

                            if(count($sub_code)>0)
                            {
                                $sub_code1 =''; $tot_sub=0;
                                foreach($sub_code as $enroll_sub)
                                {
                                    $stud_data= array_column($student_enroll, $enroll_sub);
                                                    
                                    if(count($stud_data)>0)
                                     {
                                        $sub_code1.='<td colspan="4" align="left"><h5>'.$enroll_sub.'</h5></td><td colspan="1" align="left"><h5>'.count($stud_data).'</h4></td></tr><tr>';
                                        $tot_sub=$tot_sub+count($stud_data);
                                    }
                                }
                            $data.='<tr>';
                            $data.='<td colspan="4" align="left" style="border-top: 1px solid #000 !important; "><h5>QP CODES</h5></td>
                            <td colspan="1" align="left" style="border-top: 1px solid #000 !important; "><h5>TOTAL COUNT</h5></td>';
                            $data.='</tr><tr>';
                            $data.=$sub_code1;                   
                            $data.='<td colspan="4" align="left"><h5>TOTAL</h5></td><td colspan="1" align="left"><h5>'.$tot_sub.'</h4></td>';
                            $data.='</tr><tr>';
                            $data.='<td colspan="4" align="left"><h5>REGISTER NUMBER OF THE ENROLLED STUDENT</h5></td><td colspan="1" align="left"><h5>TOTAL ABSENTS</h5></td>';
                            $data.='</tr><tr><td align="left" colspan="4" style="font-size: 9px;">';

                            foreach($sub_code as $enroll_sub)
                            {
                                $stud_data= array_column($student_enroll, $enroll_sub);
                                $stud='';
                                if(count($stud_data)>0)
                                {
                                    $ro=0;
                                    foreach($stud_data as $stud__list)
                                    {
                                        if($ro==0)
                                        {
                                            $stud=$stud__list;
                                        }
                                        else
                                        {
                                            $stud.=", ".$stud__list;
                                        }
                                        $ro++;
                                    }
                                    $data.= "<b>".$enroll_sub.": </b>".$stud."<br>";
                                }
                            }

                            $data.='</td><td colspan="1"></td></tr>';
                                                
                            $data.='<tr><td colspan="4" align="left"><h5>R-Register Number / Q-Question Paper Code</h5></td><td colspan="1" align="left"><h5>TOTAL </h5></td></tr>';
                                              
                            $data.='<tr>';
                                                
                            $data.='<td  colspan="3" align="left" style="height: 70px;  vertical-align:bottom;border-top: 0px "><h6>NAME & SIGNATURE OF THE HALL SUPERINTENDENT</h6></td>
                                <td colspan="2" align="right" style="height: 70px; vertical-align:bottom;border-top: 0px "><h6>NAME & SIGNATURE OF THE CHIEF SUPERINTENDENT / COE </h6></td>';
                            }
                            $data.='</tbody></table>';
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