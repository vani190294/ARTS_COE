<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */

$this->title = 'Hall Allocates';
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
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Arrange New', ['/hall-allocate/create'], [
                'class'=>'pull-right btn btn-success', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will Redirects the page to create new Arrangement'
                ]);

                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/hall-allocate/hallpdf'], [
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
                             
                            $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
                            $data.='<tr>
                                        <td> 
                                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                        </td> 
                                        <td colspan=6 align="center"> 
                                            <center><b><font size="4px">'.$org_name.'</font></b></center>
                                            <center>'.$org_address.'</center>
                                            
                                            <center>'.$org_tagline.'</center> 
                                        </td>
                                        <td align="center">  
                                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                        </td>
                                    </tr>';
                            $data.='<tr><td style="font-size:15px" colspan="8" align="center"><b>Seating Arrangement</b></td></tr>';
                            $data.='<tr><td align="center" colspan="8"><b>Exam Date = '.$exam_date.' &nbsp;&nbsp;&nbsp;Exam Session = '.$exam_session.'</b></td></tr>';
                            $column_size = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);
                            $same_hall_name='';
                            $k=0;
                            $column=0;
                            $seat_no=1;
                            $total_halls = 0;
                            if(isset($allocated_value)){
                                foreach($allocated_value as $allocate){                                    

                                    if($allocate['row']!=$k)
                                    {                                        
                                       
                                        $data.='<tr>';                                     
                                        
                                        
                                        if($same_hall_name!=$allocate['hall_name'])
                                        { $total_halls++;
                                            $seat_no=1;
                                            $k=1;
                                            $same_hall_name = $allocate['hall_name'];
                                            $data.='<td> Hall '.$total_halls."</td>";
                                            
                                        }else{
                                            $k++;
                                            $data.='<td> &nbsp; </td>';
                                        }
                                        $data.='<td>'.$allocate['hall_name']."- R: ".$allocate['row'].'</td>'; 
                                    }                                    

                                    $data.='<td>'.$allocate['register_number'].'</td>';
                                    $column++;                                    
                                    if($column == $column_size){
                                         $data.='</tr>';
                                    }                                    
                                }
                                
                            }                                               
                            $data.='</tbody>';        
                            $data.='</table>';
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