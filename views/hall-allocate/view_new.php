<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\models\Categorytype;
use app\models\DummyNumbers;
use app\components\ConfigUtilities;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
$generator = new \Picqer\Barcode\BarcodeGeneratorSVG();

$this->title = 'RE-PRINT BARCODE (DUMMY NUMBER) VIEW';
$this->params['breadcrumbs'][] = ['label' => 'Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hall-allocate-view">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="box box-success">
<div class="box-body">

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-8 col-lg-8">
        </div>    
        <div class="col-xs-3 col-sm-4 col-lg-4">
            <?php 
                /*echo Html::a('<i class="fa fa-file-pdf-o"></i> Arrange New', ['/hall-allocate/create'], [
                'class'=>'pull-right btn btn-success', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will Redirects the page to create new Arrangement'
                ]);*/

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
                       
                        <?php
                        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
                             
                            $data ='<table width="100%"><tbody align="center">';                 
                            
                            $month_name = Categorytype::findOne($_POST['HallAllocate']['month']);
                            $table1 = $table2 = '';
                            
                            $column_size = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);

                            $same_packet_number='';
                            $k=0;
                            $column=0;
                            $seat_no=1;
                            $total_halls = 0;
                            

                           
                                   $print = 1; $loop=1;
                                   foreach( $allocated_value as $allocate) 
                                    {   
                                        if($same_packet_number!=$allocate['answer_packet_number'])
                                        {
                                            if($same_packet_number!='' && ($same_packet_number!=$allocate['answer_packet_number']) && $print>10)
                                            {
                                                $data.='</tbody></table><pagebreak /><table width="100%"><tbody align="center">'; 
                                           
                                                $print=1;
                                            }
                                            else if($same_packet_number!='' && ($same_packet_number!=$allocate['answer_packet_number']) && $print<10)
                                            {
                                                
                                                $data .='<tr>';
                                                $data .='<td align="center" colspan=8 style="padding-left:40px !important;vertical-align: middle !important; border:1px dotted;font-size: 24px;"><b>NEXT PACKET START &nbsp;&nbsp;&nbsp; PACKET NUMBER: '.$allocate['answer_packet_number'].' </b> </td>';
                                                $data .='</tr>';
                                                $print++;
                                            }

                                            $disp_date = date('d-m-Y',strtotime($exam_date));
                                            
                                           
                                            $getDumNm = DummyNumbers::findOne(['year'=>$allocate['exam_year'],'month'=>$allocate['exam_month'],'exam_date'=>$allocate['exam_date'],'exam_session'=>$allocate['exam_session'],'student_map_id'=>$allocate['student_map_id'],'subject_map_id'=>$allocate['subject_map_id']]);                                         

                                            if(isset($getDumNm['dummy_number']) && !empty($getDumNm['dummy_number']))
                                            {
                                                $padding_top='';
                                                if($print==10){
                                                    $padding_top=' padding-top:26px !important;';
                                                }
                                                else if($print>=7){
                                                    $padding_top=' padding-top:18px !important;';
                                                }
                                                else if($print>4){
                                                    $padding_top=' padding-top:8px !important;';
                                                }

                                                
                                                $data .='<tr>';
                                                $col_name = isset($getDumNm['dummy_number']) && !empty($getDumNm['dummy_number']) ?$getDumNm['dummy_number']:'NO BAR CODE';
                                                $code = str_pad($col_name, 15, '0', STR_PAD_LEFT); 
                                                
                                                $answer_packet_number = !empty($allocate['answer_packet_number']) ?$allocate['answer_packet_number']:'No Packet No.';

                                                $printdummy =substr($col_name, -5); 
                                                
                                                    $data .='
                                                           <td align="left" colspan=3 style="padding-left:30px !important;vertical-align: middle !important;'.$padding_top.'"> 
                                                                <p style="margin-bottom:0px !important;">'.$loop.'- '.strtoupper($month_name['description']).'-'.$_POST['HallAllocate']['year'].' '.$disp_date.'-'.$exam_session.'</p>
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$allocate['hall_name'].'</p>
                                                                
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;"><span style="font-size: 18px;"><b>'.$allocate['subject_code'].' / '.$allocate['register_number'].'</b></span></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                            </td>
                                                             <td align="left" colspan=3 style="padding-left:30px !important;vertical-align: middle !important;'.$padding_top.'"> 
                                                                <p style="margin-bottom:0px !important;">'.strtoupper($month_name['description']).'-'.$_POST['HallAllocate']['year'].' '.$disp_date.'-'.$exam_session.'</p>
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$allocate['hall_name'].'</p>
                                                                
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 30).'</p>
                                                                <p style="margin-top:0px !important;"><b>'.$allocate['subject_code'].' / '.$allocate['register_number'].'</b></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                            </td>
                                                           <td align="center" colspan=2 style="vertical-align: middle !important;'.$padding_top.'">
                                                                <p>&nbsp;</p>
                                                                <p>'.$generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 30).'</p>
                                                                <p><b>'.$allocate['subject_code'].'</b></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                             </td>
                                                                ';
                                                    $data .='</tr>';
                                                
                                                
                                                $print++;
                                            }
                                        }
                                        else
                                        {
                                            $getDumNm = DummyNumbers::findOne(['year'=>$allocate['exam_year'],'month'=>$allocate['exam_month'],'exam_date'=>$allocate['exam_date'],'exam_session'=>$allocate['exam_session'],'student_map_id'=>$allocate['student_map_id'],'subject_map_id'=>$allocate['subject_map_id']]);                                            

                                            if($print>10)
                                            {
                                                $data.='</tbody></table><pagebreak /><table width="100%"><tbody align="center">';
                                              
                                                $print=1;
                                            }

                                            if(isset($getDumNm['dummy_number']) && !empty($getDumNm['dummy_number']))
                                            {
                                                $data .='<tr>';
                                                $col_name = isset($getDumNm['dummy_number']) && !empty($getDumNm['dummy_number']) ?$getDumNm['dummy_number']:'NO BAR CODE';
                                                $code = str_pad($col_name, 15, '0', STR_PAD_LEFT); 

                                                $answer_packet_number = !empty($allocate['answer_packet_number']) ?$allocate['answer_packet_number']:'No Packet No.';

                                                $padding_top='';

                                                if($print==10){
                                                    $padding_top=' padding-top:26px !important;';
                                                }
                                                else if($print>=7){
                                                    $padding_top=' padding-top:18px !important;';
                                                }
                                                else if($print>4){
                                                    $padding_top=' padding-top:8px !important;';
                                                }

                                                $printdummy =substr($col_name, -5); 
                                                
                                                     $data .='
                                                           <td align="left" colspan=3 style="padding-left:30px !important;vertical-align: middle !important;'.$padding_top.'"> 
                                                                <p style="margin-bottom:0px !important;">'.$loop.'- '.strtoupper($month_name['description']).'-'.$_POST['HallAllocate']['year'].' '.$disp_date.'-'.$exam_session.'</p>
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$allocate['hall_name'].'</p>
                                                                
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;"><span style="font-size: 18px;"><b>'.$allocate['subject_code'].' / '.$allocate['register_number'].'</b></span></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                            </td>
                                                             <td align="left" colspan=3 style="padding-left:30px !important;vertical-align: middle !important;'.$padding_top.'"> 
                                                                <p style="margin-bottom:0px !important;">'.strtoupper($month_name['description']).'-'.$_POST['HallAllocate']['year'].' '.$disp_date.'-'.$exam_session.'</p>
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$allocate['hall_name'].'</p>
                                                                
                                                                <p style="margin-bottom:0px !important;margin-top:0px !important;">'.$generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 30).'</p>
                                                                <p style="margin-top:0px !important;"><b>'.$allocate['subject_code'].' / '.$allocate['register_number'].'</b></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                            </td>
                                                           <td align="center" colspan=2 style="vertical-align: middle !important;'.$padding_top.'">
                                                                <p>&nbsp;</p>
                                                                <p>'.$generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 30).'</p>
                                                                <p><b>'.$allocate['subject_code'].'</b></p>
                                                                Packet No.:<span style="font-size: 18px;"><b>'.$answer_packet_number.'</b></span>
                                                                &nbsp;&nbsp;&nbsp;
                                                                Booklet No.:<span style="font-size: 18px;"><b>'.$printdummy.'</b></span>
                                                             </td>
                                                                ';
                                                    $data .='</tr>';
                                               
                                                $print++;
                                                
                                            }                                                
                                        }
                                        $same_packet_number=$allocate['answer_packet_number'];

                                        $loop++;
                                    }
                                    

                              
                                                             
                            $data.='</tbody></table>'; 
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

</div>
</div> 