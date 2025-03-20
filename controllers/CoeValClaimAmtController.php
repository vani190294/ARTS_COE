<?php

namespace app\controllers;

use Yii;
use app\models\CoeValClaimAmt;
use app\models\CoeValClaimAmtSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ValuationFacultyAllocate;
use app\models\ValuationFaculty;
use kartik\mpdf\Pdf;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\Query;
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Configuration;
use app\models\PracticalExamTimetable;
use app\models\FacultyClaim;
use app\models\Categorytype;
use app\models\QpSetting;

/**
 * CoeValClaimAmtController implements the CRUD actions for CoeValClaimAmt model.
 */
class CoeValClaimAmtController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CoeValClaimAmt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoeValClaimAmtSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeValClaimAmt model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CoeValClaimAmt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoeValClaimAmt();

        if ($model->load(Yii::$app->request->post())) {
            $userid = Yii::$app->user->getId(); 
            $model->created_by=$userid;
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->claim_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CoeValClaimAmt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $userid = Yii::$app->user->getId(); 
            $model->updated_by=$userid;
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->claim_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CoeValClaimAmt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CoeValClaimAmt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeValClaimAmt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoeValClaimAmt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPracticalclaim()
    {
        $model = new PracticalExamTimetable();
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Practical Claim');
        return $this->render('practicalclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionPrintclaimpdf()
    {
        $content=$_SESSION['practicalclaim'];
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'practicalclaim.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                        'options' => ['title' => 'PRACTICAL CLAIM'],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>['PRACTICAL CLAIM REPORT - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

    public function actionPrintclaimExcel()
    {

        $content = $_SESSION['practicalclaim'];          
        $fileName = 'practicalclaim ' . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

    public function actionRevaluationclaim()
    {
        $model = new ValuationFacultyAllocate();
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Theory Revaluation Claim');
        return $this->render('revaluationclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionValuationclaim()
    {
        $model = new ValuationFacultyAllocate();
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Theory Valuation Claim');
        return $this->render('valuationclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionRevaluationclaimpdf()
    {
        $content=$_SESSION['revaluationclaim'];
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'Valuationclaim.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                        'options' => ['title' => 'VALUATION CLAIM'],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>['VALUATION CLAIM REPORT - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

    public function actionRevaluationclaimExcel()
    {

        $content = $_SESSION['revaluationclaim'];          
        $fileName = 'Valuationclaim ' . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

    public function actionPracticalFacultyClaim()
    {
        $model = new FacultyClaim();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Faculty Practical Claim');
        return $this->render('practical_faculty_claim', [
                'model' => $model,
                
            ]);
        
        
    }
    
    public function actionPracticalFacultyClaimpdf($id) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['claimyear']; 
        $month = $_SESSION['claimmonth'];
            
        $claim_date = $_SESSION['claimdate'];
        $monthName = Categorytype::findOne($month);

        $pcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=1")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        // if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        // {
        //     Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded Reprint Contact Admin');
        //     return $this->redirect(['coe-val-claim-amt/practical-faculty-claim']);
        // }
        // else
        // {


            $head=$body='';   $header=$footer='';  $footer1='';


            $addwhere=" AND B.coe_val_faculty_id='".$val_faculty_id."'";          

             $practicaldata1 = Yii::$app->db->createCommand("SELECT A.external_examiner_name,A.subject_map_id,B.* FROM coe_prac_exam_ttable as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.external_examiner_name WHERE out_of_100!='-1' AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND B.faculty_mode='EXTERNAL' GROUP BY A.external_examiner_name")->queryAll();

            $practicaldata2 = Yii::$app->db->createCommand("SELECT A.external_examiner2 as external_examiner_name, A.subject_map_id, B.* FROM coe_prac_exam_ttable as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.external_examiner2 WHERE out_of_100!='-1' AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND B.faculty_mode='EXTERNAL' GROUP BY A.external_examiner2")->queryAll();

            $practicaldata =[];
             $practicaldata = array_merge($practicaldata1,$practicaldata2);
             
             $check_data_exists1 =$this->getUniqueclaim($practicaldata);
                //print_r($practicaldata1); exit;
                 $header=$footer='';    
                if(!empty($check_data_exists1))
                {
                    $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                    $head= '<table width="100%">
                        <tr>
                            <td align="left">
                                            <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                        </td>
                            <td colspan=2 align="center"> 
                                  <center><b><font size="5px">' . $org_name . '</font></b></center>
                                  <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                                  <center><h6><b>PRACTICAL CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                             </td>
                             
                              <td align="right">  
                                            <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                          </td>
                         </tr></table>
                         '; 
       
                    $header .= '<table width="100%">
                         <tr>
                            <td style="text-align:right;" colspan="3"><b>Exam Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                            
                        </tr> 
                        <tr>
                            <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                            
                        </tr> 
                        <tr>
                            <td width="30%">Name & Designation</td>
                            <td width="5%">:</td>
                            <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                        </tr>
                        <tr>
                            <td>Board & Institution</td>
                            <td>:</td>
                            <td>'.$faculty['faculty_board'].' & '.$faculty['college_code'].'</td>
                        </tr>
                        <tr>
                            <td>Mobile No. & Email</td>
                            <td>:</td>
                            <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                        </tr>
                        
                        <tr>
                            <td>Bank Acc.No. & IFSC</td>
                            <td>:</td>
                            <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                        </tr>
                        <tr>
                            <td>Bank & Branch</td>
                            <td>:</td>
                            <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                        </tr> </table>
                       ';

                    $header .= '<table width="100%">
                        <tr>
                            <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                            
                        </tr> ';

                    $header .= '
                        <tr style="text-align:center;">
                            
                            <th width="15%" style="border:1px solid #000;">Degree</th>
                            <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                            <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                            <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                            <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                        </tr>
                         <tbody>';

                        $s=1;
                        $totscript=$totscriptamt=$totamt=0;
                        foreach ($check_data_exists1 as $value) 
                        {        

                            $practicaldata3 = Yii::$app->db->createCommand("SELECT A.subject_map_id FROM coe_prac_exam_ttable as A JOIN coe_subjects_mapping B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects C ON C.coe_subjects_id=B.subject_id WHERE out_of_100!='-1' AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND external_examiner_name=".$value['external_examiner_name']." GROUP BY A.subject_map_id")->queryAll();

                            $practicaldata4 = Yii::$app->db->createCommand("SELECT A.subject_map_id FROM coe_prac_exam_ttable as A JOIN coe_subjects_mapping B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects C ON C.coe_subjects_id=B.subject_id WHERE out_of_100!='-1' AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND external_examiner2=".$value['external_examiner_name']." GROUP BY A.subject_map_id")->queryAll();

                            $practicaldatas =[];
                             $practicaldatas = array_merge($practicaldata3,$practicaldata4);
                             
                             $practicaldatas =$this->getUniqueclaim2($practicaldatas);

                            //print_r($practicaldatas); exit();

                            foreach ($practicaldatas as $key => $value1) 
                            { 

                                $query = "SELECT concat(degree_code,' - ',programme_shortname) as degree_code, paper_type_id, subject_code, subject_name, b.degree_type,c.paper_type_id FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_programme p ON p.coe_programme_id = a.coe_programme_id JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects f ON f.coe_subjects_id=c.subject_id  WHERE coe_subjects_mapping_id='" . $value1['subject_map_id'] . "'";
                                $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();      
                                
                              

                                $f1cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_prac_exam_ttable A WHERE out_of_100!='-1' AND external_examiner_name=".$value['external_examiner_name']." AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND subject_map_id=".$value1['subject_map_id'])->queryScalar();

                                $f2cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_prac_exam_ttable A WHERE out_of_100!='-1' AND external_examiner2=".$value['external_examiner_name']." AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND  subject_map_id=".$value1['subject_map_id'])->queryScalar();

                                                 

                                $qpamount = 0;
                                if($degreeInfo['degree_type']=='UG'  && ($degreeInfo['paper_type_id']!=123 && $degreeInfo['paper_type_id']!=11))
                                {
                                    $qpamount = $pcamount['ug_amt'];
                                }
                                else if($degreeInfo['degree_type']=='UG' && ($degreeInfo['paper_type_id']==123 || $degreeInfo['paper_type_id']==11))
                                {
                                    $pcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=10")->queryone();
                                    $qpamount = $pcamount['ug_amt'];
                                }
                                else if($degreeInfo['degree_type']=='PG' && ($degreeInfo['paper_type_id']==11 || $degreeInfo['paper_type_id']==123))
                                {
                                    $pcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=10")->queryone();
                                    $qpamount = $pcamount['pg_amt'];
                                }
                                else
                                {

                                    $qpamount = $pcamount['pg_amt'];
                                }

                                if($faculty['faculty_board']=='PHYSICS' || $faculty['faculty_board']=='CHEMISTRY')
                                {
                                    $qpamount=Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=12")->queryScalar();
                                }

                            
                                $totalscript=($f1cnt+$f2cnt); 
                                $totalscriptamt=$qpamount*$totalscript;

                           

                                $no_ofday_count=0;
                                $daycount = Yii::$app->db->createCommand("SELECT count(DISTINCT unique_prac_id) FROM coe_prac_exam_ttable WHERE external_examiner_name=".$value['external_examiner_name']." AND exam_date='".date("Y-m-d",strtotime($claim_date))."' AND out_of_100!='-1'")->queryScalar();

                               $daycount1 = Yii::$app->db->createCommand("SELECT count(DISTINCT unique_prac_id) FROM coe_prac_exam_ttable WHERE external_examiner2=".$value['external_examiner_name']." AND exam_date='".date("Y-m-d",strtotime($claim_date))."' AND out_of_100!='-1'")->queryScalar();
                               
                                $day=$daycount+$daycount1;


                                if($day>1)
                                {
                                     $no_ofday_count=$no_ofday_count+1;
                                }
                                else if($day==1 && $totalscript>35)
                                {
                                    $no_ofday_count=$no_ofday_count+1;
                                }
                                else if($day==1)
                                {
                                    $no_ofday_count=$no_ofday_count+0.5;
                                }

                                $tot_ta=0; $daysession='';
                                if($value['out_session']=='YES')
                                {
                                    $tot_ta=$no_ofday_count*$pcamount['out_session'];
                                    $daysession='(Full Day)';
                                }
                                else
                                {
                                    if($no_ofday_count<1 && $no_ofday_count!=0)
                                    {
                                        $tot_ta=$pcamount['ta_amt_half_day'];
                                        $daysession='(Half Day)';
                                    }
                                    else if($no_ofday_count>=1 && $no_ofday_count!=0)
                                    {
                                        $n = $no_ofday_count;
                                        $whole = floor($n);      // 1
                                        $fraction = $n - $whole; // .25

                                        if($fraction==0)
                                        {
                                            $tot_ta=$whole*$pcamount['ta_amt_full_day'];
                                        }
                                        else if($fraction>0)
                                        {
                                            $tot_ta=($whole*$pcamount['ta_amt_full_day'])+$pcamount['ta_amt_half_day'];
                                        }
                                        $daysession='(Full Day)';
                                    }
                                }
                            
                                $body .='<tr>';
                                //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                                $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                                $body .='<td style="border:1px solid #000;">'.$degreeInfo['subject_code'].' & '.$degreeInfo['subject_name'].'</td>';
                                $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                                $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                                $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                                $body .='</tr>';
                                                        
                                $totscript=$totscript+$totalscript;
                                $totscriptamt=$totscriptamt+$totalscriptamt;

                            }
                        }
                        //echo $totscript; exit();

                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount<br>'.$daysession.'</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';

                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                            <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                        </tr>';

                        
                        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                        if($checkclaim == 0)
                        {
                            $login_user_id=Yii::$app->user->getId();
                            Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(1,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                        }
                        else
                        {
                            $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                            Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.', tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                        }


                         $footer .='
                                <tr>
                                  <td colspan="5" style="text-align: right;">                                    
                                    Passed For Payment
                                  </td>
                                </tr>

                                <tr>
                                    <td colspan="3" style="text-align: left;">
                                    <b>Signature of Examiner<br>with Date</b>
                                  </td>
                                  <td colspan="2" style="text-align: right;"> 
                                    <br><br><br>
                                  </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                    
                                  </td>
                                  <td colspan="3" style="text-align: right;">
                                  
                                    <b>Controller Of Examinations</b> 
                                  </td>
                                </tr>
                            </tbody></table>';
                                          

                    $result=1;
                }
                else
                { 
                    $result=0;
                }   
                       
         
            $content=$head.$header.$body.$footer; 
       
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'practicalclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'PRACTICAL CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['PRACTICAL CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        //}

    }

    public function actionPracticalFacultyClaimpdf1($id) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['claimyear']; 
        $month = $_SESSION['claimmonth'];
            
        $claim_date = $_SESSION['claimdate'];
        $preday=$_SESSION['preday'];
        $nextday=$_SESSION['nextday'];

        $monthName = Categorytype::findOne($month);

        $pcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=1")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/practical-faculty-claim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';


            $addwhere=" AND B.coe_val_faculty_id='".$val_faculty_id."'";          

             $practicaldata1 = Yii::$app->db->createCommand("SELECT A.external_examiner_name,A.subject_map_id,B.* FROM coe_prac_exam_ttable as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.external_examiner_name WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND B.faculty_mode='EXTERNAL' GROUP BY A.external_examiner_name")->queryAll();

            $practicaldata2 = Yii::$app->db->createCommand("SELECT A.external_examiner2 as external_examiner_name, A.subject_map_id, B.* FROM coe_prac_exam_ttable as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.external_examiner2 WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.exam_date='".date("Y-m-d",strtotime($claim_date))."' AND B.faculty_mode='EXTERNAL' GROUP BY A.external_examiner2")->queryAll();

            $practicaldata =[];
             $practicaldata = array_merge($practicaldata1,$practicaldata2);
             
             $check_data_exists1 =$this->getUniqueclaim($practicaldata);
                //print_r($practicaldata1); exit;
                 $header=$footer='';    
                if(!empty($check_data_exists1))
                {
                    $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                    $head= '<table width="100%">
                        <tr>
                            <td align="left">
                                            <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                        </td>
                            <td colspan=2 align="center"> 
                                  <center><b><font size="5px">' . $org_name . '</font></b></center>
                                  <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                                  <center><h6><b>PRACTICAL CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                             </td>
                             
                              <td align="right">  
                                            <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                          </td>
                         </tr></table>
                         '; 
       
                    $header .= '<table width="100%">
                         <tr>
                            <td style="text-align:right;" colspan="3"><b>Exam Date:</b> '.date("d-m-Y",strtotime($preday)).'/'.date("d-m-Y",strtotime($nextday)).'</td>
                            
                        </tr> 
                        <tr>
                            <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                            
                        </tr> 
                        <tr>
                            <td width="30%">Name & Designation</td>
                            <td width="5%">:</td>
                            <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                        </tr>
                        <tr>
                            <td>Board & Institution</td>
                            <td>:</td>
                            <td>'.$faculty['faculty_board'].' & '.$faculty['college_code'].'</td>
                        </tr>
                        <tr>
                            <td>Mobile No. & Email</td>
                            <td>:</td>
                            <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                        </tr>
                        
                        <tr>
                            <td>Bank Acc.No. & IFSC</td>
                            <td>:</td>
                            <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                        </tr>
                        <tr>
                            <td>Bank & Branch</td>
                            <td>:</td>
                            <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                        </tr> </table>
                       ';

                    $header .= '<table width="100%">
                        <tr>
                            <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                            
                        </tr> ';

                    $header .= '
                        <tr style="text-align:center;">
                            
                            <th width="15%" style="border:1px solid #000;">Degree</th>
                            <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                            <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                            <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                            <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                        </tr>
                         <tbody>';

                        $s=1;
                        $totscript=$totscriptamt=$totamt=0;
                        foreach ($check_data_exists1 as $value) 
                        {         

                            $query = "SELECT degree_code, paper_type_id, subject_code, subject_name, b.degree_type,c.paper_type_id FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects f ON f.coe_subjects_id=c.subject_id  WHERE coe_subjects_mapping_id='" . $value['subject_map_id'] . "'";
                            $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();      
                            
                            $f1cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_prac_exam_ttable A WHERE A.student_map_id NOT IN (SELECT absent_student_reg FROM coe_absent_entry WHERE exam_subject_id='".$value['subject_map_id']."' AND exam_date is NULL AND exam_month='" . $month . "' AND exam_year='" . $year . "') AND external_examiner_name=".$value['external_examiner_name']." AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND (A.exam_date BETWEEN '".date("Y-m-d",strtotime($preday))."' AND '".date("Y-m-d",strtotime($nextday))."')")->queryScalar();

                            $f2cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_prac_exam_ttable A WHERE A.student_map_id NOT IN (SELECT absent_student_reg FROM coe_absent_entry WHERE exam_subject_id='".$value['subject_map_id']."' AND exam_date is NULL AND exam_month='" . $month . "' AND exam_year='" . $year . "') AND external_examiner2=".$value['external_examiner_name']." AND A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND (A.exam_date BETWEEN '".date("Y-m-d",strtotime($preday))."' AND '".date("Y-m-d",strtotime($nextday))."')")->queryScalar();                     

                            $qpamount = 0;
                            if($degreeInfo['degree_type']=='UG')
                            {
                                $qpamount = $pcamount['ug_amt'];
                            }
                            else if($degreeInfo['degree_type']=='PG' && $degreeInfo['paper_type_id']==123)
                            {
                                $pcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=10")->queryone();
                                $qpamount = $pcamount['pg_amt'];
                            }
                            else
                            {

                                $qpamount = $pcamount['pg_amt'];
                            }

                            $totalscript=($f1cnt+$f2cnt);
                            $totalscriptamt=$qpamount*$totalscript;

                            $daysession = Yii::$app->db->createCommand("SELECT count(DISTINCT exam_date) FROM coe_prac_exam_ttable WHERE external_examiner_name=".$value['external_examiner_name']."  AND (exam_date BETWEEN '".date("Y-m-d",strtotime($preday))."' AND '".date("Y-m-d",strtotime($nextday))."')")->queryScalar();
                           
                            $tot_ta=$pcamount['out_session'];

                            $body .='<tr>';
                            //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                            $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                            $body .='<td style="border:1px solid #000;">'.$degreeInfo['subject_code'].' & '.$degreeInfo['subject_name'].'</td>';
                            $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                            $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                            $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                            $body .='</tr>';
                                                    
                            $totscript=$totscript+$totalscript;
                            $totscriptamt=$totscriptamt+$totalscriptamt;
                        }


                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount<br>('.$daysession.' Days with Accom)</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';

                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                            <td style="border:1px solid #000;">'.($tot_ta+$totalscriptamt).'</td>
                        </tr>';

                        
                        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                        if($checkclaim == 0)
                        {
                            $login_user_id=Yii::$app->user->getId();
                            Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by, claim_accom) values(1,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'",1) ')->execute();
                        }
                        else
                        {
                            $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=1 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                            Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.', tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                        }


                         $footer .='
                                <tr>
                                  <td colspan="5" style="text-align: right;">                                    
                                    Passed For Payment
                                  </td>
                                </tr>

                                <tr>
                                    <td colspan="3" style="text-align: left;">
                                    <b>Signature of Examiner<br>with Date</b>
                                  </td>
                                  <td colspan="2" style="text-align: right;"> 
                                    <br><br><br>
                                  </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                    
                                  </td>
                                  <td colspan="3" style="text-align: right;">
                                  
                                    <b>Controller Of Examinations</b> 
                                  </td>
                                </tr>
                            </tbody></table>';
                                          

                    $result=1;
                }
                else
                { 
                    $result=0;
                }   
                       
         
       $content=$head.$header.$body.$footer; 
       
        $pdf = new Pdf([                   
            'mode' => Pdf::MODE_CORE,                 
            'filename' => 'practicalclaim.pdf',
            'format' => [212, 136],                
            //'format' => Pdf::FORMAT_A4,                 
            'orientation' => Pdf::ORIENT_LANDSCAPE,                 
            'destination' => Pdf::DEST_BROWSER,                 
            'content' => $content,  
                'options' => ['title' => 'PRACTICAL CLAIM FORM '],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                    'SetFooter'=>['PRACTICAL CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                ],
                
            ]);
            

            $pdf->marginLeft="4";
            $pdf->marginRight="4";
            $pdf->marginBottom="4";
            $pdf->marginFooter="4";
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
        }

    }

    protected function getUniqueclaim($claim) 
    {
      $claimdata = array();

      foreach($claim as $clm) {
        $niddle = $clm['external_examiner_name'];
        if(array_key_exists($niddle, $claimdata)) continue;
        $claimdata[$niddle] = $clm;
      }

      return $claimdata;
    }


    protected function getUniqueclaim2($claim) 
    {
      $claimdata = array();

      foreach($claim as $clm) {
        $niddle = $clm['subject_map_id'];
        if(array_key_exists($niddle, $claimdata)) continue;
        $claimdata[$niddle] = $clm;
      }

      return $claimdata;
    }

     public function actionTheoryFacultyVclaim()
    {
        $model = new ValuationFacultyAllocate();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Faculty Theory Valuation Claim');
        return $this->render('theory_faculty_valuationclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionTheoryFacultyClaimpdf($id) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $_SESSION['tvclaimdate'];
        $monthName = Categorytype::findOne($month);

        $rcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=6")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=2 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        // if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        // {
        //     Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
        //     return $this->redirect(['coe-val-claim-amt/theory-faculty-vclaim']);
        // }
        // else
        // { 
            //echo $checkclaim; exit;
            $body='';   $header=$footer='';  $footer1='';

            $addwhere=" AND A.valuation_date='".date("Y-m-d",strtotime($claim_date))."'";

            $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty_allocate as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_val_faculty_id='".$val_faculty_id."' GROUP BY A.coe_val_faculty_id,A.subject_code")->queryAll();

             $header=$footer='';    
            if(!empty($valdata))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>THEORY VALUATION CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Valuation Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                        $noofday=0;
                  
                        $reval_fn = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_faculty_allocate WHERE coe_val_faculty_id=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='FN' group by valuation_date,valuation_session")->queryAll();

                        $reval_an = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_faculty_allocate WHERE coe_val_faculty_id=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='AN' group by valuation_date,valuation_session")->queryAll();                      

                        if(count($reval_fn)==1 && count($reval_an)==1) 
                        {
                            $noofday=$noofday+1;
                        }
                        else if(count($reval_fn)==1 || count($reval_an)==1) 
                        {
                            $noofday=$noofday+0.5;
                        }

                        $tot_ta=0;
                        if($value['out_session']=='YES')
                        {
                            $tot_ta=$noofday*$rcamount['out_session'];
                        }
                        else
                        {
                            if($value['faculty_mode']=='EXTERNAL')
                            {
                                if($noofday<1 && $noofday!=0)
                                {
                                    $tot_ta=$rcamount['ta_amt_half_day'];
                                }
                                else if($noofday>=1 && $noofday!=0)
                                {
                                    $n = $noofday;
                                    $whole = floor($n);      // 1
                                    $fraction = $n - $whole; // .25

                                    if($fraction==0)
                                    {
                                        $tot_ta=$whole*($rcamount['ta_amt_full_day']);
                                    }
                                    else if($fraction>0)
                                    {
                                        $tot_ta=($whole*($rcamount['ta_amt_full_day']))+($rcamount['ta_amt_half_day']);
                                    }
                                }
                            }
                        }                 

                        $qpamount = 0;
                        if($degreeInfo['degree_type']=='UG')
                        {
                           $qpamount = $rcamount['ug_amt'];
                        }
                        else
                        {
                            $qpamount = $rcamount['pg_amt'];
                        }

                       

                        $ph_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND board IN ('PHYSICS','CHEMISTRY') AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)!='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                        if($ph_total>0)
                        {
                             $qpamount=15;
                             $totalscript =$ph_total;
                             $totalscriptamt = ($ph_total * $qpamount);
                        }
                        else
                        {
                             $tamil_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                            $other_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND board NOT IN ('PHYSICS','CHEMISTRY') AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)!='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                            $totalscript =($tamil_total)+ ($other_total);
                             $totalscriptamt = ($tamil_total * $qpamount)+ ($other_total * $qpamount);
                        }
                       

                       //$totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate WHERE (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND subject_code='".$value['subject_code']."'")->queryScalar();


                        $noofday=($noofday<1)?'Half Day':$noofday; 

                        if($loop==6)
                        {
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;
                    }

                    if($loop==7)
                    {
                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }

                    if($tot_ta>0)
                    {

                     $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                        <td style="border:1px solid #000; ">'.$totscript.'</td>
                        <td style="border:1px solid #000; text-align:right;">Script Amt</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';

                    
                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';
                         $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                    </tr>';
                    }
                    else
                    {
                         $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                        <td style="border:1px solid #000; ">'.$totscript.'</td>
                        <td style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';
                    }

                     

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=2 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(2,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                     else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=2 AND exam_month='" . $month . "' AND exam_year='" . $year . "'  AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.',  tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }


                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <br>
                                <b>Signature of Examiner<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty_allocate as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id2 WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_val_faculty_id2='".$val_faculty_id."' GROUP BY A.coe_val_faculty_id,A.subject_code")->queryAll();

                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>THEORY VALUATION CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Valuation Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0;
                    $totscript=$totscriptamt=$totamt=0;$tot_ta=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                        $noofday=0;
                  
                        $reval_fn = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_faculty_allocate WHERE coe_val_faculty_id2=".$value['coe_val_faculty_id2']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='FN' group by valuation_date,valuation_session")->queryAll();

                        $reval_an = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_faculty_allocate WHERE coe_val_faculty_id2=".$value['coe_val_faculty_id2']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='AN' group by valuation_date,valuation_session")->queryAll();                      

                        if(count($reval_fn)==1 && count($reval_an)==1) 
                        {
                            $noofday=$noofday+1;
                        }
                        else if(count($reval_fn)==1 || count($reval_an)==1) 
                        {
                            $noofday=$noofday+0.5;
                        }

                        
                        if($value['out_session']=='YES')
                        {
                            $tot_ta=$noofday*$rcamount['out_session'];
                        }
                        else
                        {
                            if($value['faculty_mode']=='EXTERNAL')
                            {
                                if($noofday<1 && $noofday!=0)
                                {
                                    $tot_ta=$rcamount['ta_amt_half_day'];
                                }
                                else if($noofday>=1 && $noofday!=0)
                                {
                                    $n = $noofday;
                                    $whole = floor($n);      // 1
                                    $fraction = $n - $whole; // .25

                                    if($fraction==0)
                                    {
                                        $tot_ta=$whole*($rcamount['ta_amt_full_day']);
                                    }
                                    else if($fraction>0)
                                    {
                                        $tot_ta=($whole*($rcamount['ta_amt_full_day']))+($rcamount['ta_amt_half_day']);
                                    }
                                }
                            }
                        }                 

                       $qpamount = 0;
                        if($degreeInfo['degree_type']=='UG')
                        {
                           $qpamount = $rcamount['ug_amt'];
                        }
                        else
                        {
                            $qpamount = $rcamount['pg_amt'];
                        }

                       

                        $ph_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND board IN ('PHYSICS','CHEMISTRY') AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)!='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                        if($ph_total>0)
                        {
                             $qpamount=15;
                             $totalscript =$ph_total;
                             $totalscriptamt = ($ph_total * $qpamount);
                        }
                        else
                        {
                             $tamil_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                            $other_total = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND (coe_val_faculty_id=".$value['coe_val_faculty_id']." OR coe_val_faculty_id2=".$value['coe_val_faculty_id'].") AND board NOT IN ('PHYSICS','CHEMISTRY') AND exam_year=".$year." AND (SUBSTRING(subject_code, 3, 3)!='TAM') AND exam_month=".$month." AND subject_code='".$value['subject_code']."'")->queryScalar();

                            $totalscript =($tamil_total)+ ($other_total);
                             $totalscriptamt = ($tamil_total * $qpamount)+ ($other_total * $qpamount);
                        }
                       

                        $noofday=($noofday<1)?'Half Day':$noofday; 

                        if($loop==6)
                        {
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;
                    }

                    if($loop==7)
                    {
                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }

                    if($tot_ta>0)
                    {

                     $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                        <td style="border:1px solid #000; ">'.$totscript.'</td>
                        <td style="border:1px solid #000; text-align:right;">Script Amt</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';

                    
                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';
                         $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                    </tr>';
                    }
                    else
                    {
                         $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                        <td style="border:1px solid #000; ">'.$totscript.'</td>
                        <td style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';
                    }

                     

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=2 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(2,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                     else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=2 AND exam_month='" . $month . "' AND exam_year='" . $year . "'  AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.',  tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }


                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <br>
                                <b>Signature of Examiner<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'theoryclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'THEORY VALUATION CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['THEORY VALUATION CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        //}

    }

     public function actionTheoryFacultyRevclaim()
    {
        $model = new ValuationFacultyAllocate();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Faculty Theory ReValuation Claim');
        return $this->render('theory_faculty_revaluationclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionTheoryFacultyRevClaimpdf($id) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $_SESSION['tvclaimdate'];
        $monthName = Categorytype::findOne($month);

        $rcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=3")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=3 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/theory-faculty-revclaim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';

            $addwhere=" AND A.valuation_date='".date("Y-m-d",strtotime($claim_date))."'";

            $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_reval_allocate as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_val_faculty_id='".$val_faculty_id."' GROUP BY A.coe_val_faculty_id,A.subject_code")->queryAll();

            $head= $header=$footer='';    
            if(!empty($valdata))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>THEORY REVALUATION CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Valuation Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                        $noofday=0;
                  
                        $reval_fn = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='FN' group by valuation_date,valuation_session")->queryAll();

                        $reval_an = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='AN' group by valuation_date,valuation_session")->queryAll();                      

                        if(count($reval_fn)==1 && count($reval_an)==1) 
                        {
                            $noofday=$noofday+1;
                        }
                        else if(count($reval_fn)==1 || count($reval_an)==1) 
                        {
                            $noofday=$noofday+0.5;
                        }

                        $tot_ta=0;
                        if($value['out_session']=='YES')
                        {
                            $tot_ta=$noofday*$rcamount['out_session'];
                        }
                        else
                        {
                            if($value['faculty_mode']=='EXTERNAL')
                            {
                                if($noofday<1 && $noofday!=0)
                                {
                                    $tot_ta=$rcamount['ta_amt_half_day'];
                                }
                                else if($noofday>=1 && $noofday!=0)
                                {
                                    $n = $noofday;
                                    $whole = floor($n);      // 1
                                    $fraction = $n - $whole; // .25

                                    if($fraction==0)
                                    {
                                        $tot_ta=$whole*($rcamount['ta_amt_full_day']);
                                    }
                                    else if($fraction>0)
                                    {
                                        $tot_ta=($whole*($rcamount['ta_amt_full_day']))+($rcamount['ta_amt_half_day']);
                                    }
                                }
                            }
                        }                 

                        $qpamount = 0;
                        if($degreeInfo['degree_type']=='UG')
                        {
                            $qpamount = $rcamount['ug_amt'];
                        }
                        else
                        {
                            $qpamount = $rcamount['pg_amt'];
                        }

                        if(count($valdata)>1)
                        {

                            $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_reval_allocate A WHERE val_faculty_all_id=".$value['val_faculty_all_id'])->queryScalar();
                        }
                        else
                        {
                            $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."'")->queryScalar();
                        }

                        $totalscriptamt=$qpamount*$totalscript;                        

                        $noofday=($noofday<1)?'Half Day':$noofday; 

                        if($loop==6)
                        {
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;
                    }

                    if($loop==7)
                    {
                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }
                    //$tot_ta=0;
                    if($tot_ta>0)
                    {
                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';
                    }

                    $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                    </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=3 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(3,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=3 AND exam_month='" . $month . "' AND exam_year='" . $year . "'  AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.',  tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }

                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <b>Signature of Examiner<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_reval_allocate as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id2 WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_val_faculty_id2='".$val_faculty_id."' GROUP BY A.coe_val_faculty_id,A.subject_code")->queryAll();

                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>THEORY REVALUATION CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Valuation Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                        $noofday=0;
                  
                        $reval_fn = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id2=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='FN' group by valuation_date,valuation_session")->queryAll();

                        $reval_an = Yii::$app->db->createCommand("SELECT DISTINCT valuation_date FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id2=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."' AND valuation_session='AN' group by valuation_date,valuation_session")->queryAll();                      

                        if(count($reval_fn)==1 && count($reval_an)==1) 
                        {
                            $noofday=$noofday+1;
                        }
                        else if(count($reval_fn)==1 || count($reval_an)==1) 
                        {
                            $noofday=$noofday+0.5;
                        }

                        $tot_ta=0;
                        if($value['out_session']=='YES')
                        {
                            $tot_ta=$noofday*$rcamount['out_session'];
                        }
                        else
                        {
                            if($value['faculty_mode']=='EXTERNAL')
                            {
                                if($noofday<1 && $noofday!=0)
                                {
                                    $tot_ta=$rcamount['ta_amt_half_day'];
                                }
                                else if($noofday>=1 && $noofday!=0)
                                {
                                    $n = $noofday;
                                    $whole = floor($n);      // 1
                                    $fraction = $n - $whole; // .25

                                    if($fraction==0)
                                    {
                                        $tot_ta=$whole*($rcamount['ta_amt_full_day']);
                                    }
                                    else if($fraction>0)
                                    {
                                        $tot_ta=($whole*($rcamount['ta_amt_full_day']))+($rcamount['ta_amt_half_day']);
                                    }
                                }
                            }
                        }                 

                        $qpamount = 0;
                        if($degreeInfo['degree_type']=='UG')
                        {
                            $qpamount = $rcamount['ug_amt'];
                        }
                        else
                        {
                            $qpamount = $rcamount['pg_amt'];
                        }

                        if(count($valdata)>1)
                        {

                            $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_reval_allocate A WHERE val_faculty_all_id=".$value['val_faculty_all_id'])->queryScalar();
                        }
                        else
                        {
                            $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_reval_allocate WHERE coe_val_faculty_id2=".$value['coe_val_faculty_id']." AND exam_year=".$year." AND exam_month=".$month." AND valuation_date='".date('Y-m-d',strtotime($claim_date))."'")->queryScalar();
                        }

                        $totalscriptamt=$qpamount*$totalscript;

                          

                        $noofday=($noofday<1)?'Half Day':$noofday; 

                        if($loop==6)
                        {
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;
                    }

                    if($loop==7)
                    {
                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }
                    //$tot_ta=0;
                    if($tot_ta>0)
                    {
                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';
                    }

                    $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                    </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=3 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(3,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=3 AND exam_month='" . $month . "' AND exam_year='" . $year . "'  AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.',  tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }

                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <b>Signature of Examiner<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'theoryrevalclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'THEORY REVALUATION CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['THEORY REVALUATION CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }

    }


    public function actionQpscrutinyclaim()
    {
        $model = new QpSetting();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting Scrutiny Claim');
        return $this->render('qp_scrutinyclaim', [
                'model' => $model,                
            ]);
        
        
    }


    public function actionQpScrutinyClaimpdf($id) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $_SESSION['tvclaimdate'];
        $monthName = Categorytype::findOne($month);

        $rcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=5")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=4 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/theory-faculty-revclaim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';

            $addwhere=" AND A.qp_scrutiny_date='".date("Y-m-d",strtotime($claim_date))."'";

            $valdata = Yii::$app->db->createCommand("SELECT A.*,B.* FROM coe_qp_setting as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.qp_scrutiny_id WHERE A.month='" . $month . "' AND A.year='" . $year . "'".$addwhere." AND A.qp_scrutiny_id='".$val_faculty_id."' GROUP BY A.subject_code")->queryAll();

             $header=$footer='';    
            if(!empty($valdata))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>QP SETTING SCRUTINY CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Scrutiny Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of QPs</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per QP (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                        $noofday=0;                                        

                        $reval_fn = Yii::$app->db->createCommand("SELECT DISTINCT qp_scrutiny_date FROM coe_qp_setting WHERE  qp_scrutiny_id='".$val_faculty_id."' AND year=".$year." AND month=".$month." AND qp_scrutiny_date='".date('Y-m-d',strtotime($claim_date))."' AND qp_scrutiny_session='FN' group by qp_scrutiny_date,qp_scrutiny_session")->queryAll();

                        $reval_an = Yii::$app->db->createCommand("SELECT DISTINCT qp_scrutiny_date FROM coe_qp_setting WHERE qp_scrutiny_id=".$val_faculty_id." AND year=".$year." AND month=".$month." AND qp_scrutiny_date='".date('Y-m-d',strtotime($claim_date))."' AND qp_scrutiny_session='AN' group by qp_scrutiny_date,qp_scrutiny_session")->queryAll();                

                        if(count($reval_fn)==1 && count($reval_an)==1) 
                        {
                            $noofday=$noofday+1;
                        }
                        else if(count($reval_fn)==1 || count($reval_an)==1) 
                        {
                            $noofday=$noofday+0.5;
                        }

                        $tot_ta=0;
                        if($value['out_session']=='YES')
                        {
                            $tot_ta=$noofday*$rcamount['out_session'];
                        }
                        else
                        {
                            if($value['faculty_mode']=='EXTERNAL')
                            {
                                if($noofday<1 && $noofday!=0)
                                {
                                    $tot_ta=$rcamount['ta_amt_half_day'];
                                }
                                else if($noofday>=1 && $noofday!=0)
                                {
                                    $n = $noofday;
                                    $whole = floor($n);      // 1
                                    $fraction = $n - $whole; // .25

                                    if($fraction==0)
                                    {
                                        $tot_ta=$whole*($rcamount['ta_amt_full_day']);
                                    }
                                    else if($fraction>0)
                                    {
                                        $tot_ta=($whole*($rcamount['ta_amt_full_day']))+($rcamount['ta_amt_half_day']);
                                    }
                                }
                            }
                        }                 

                        $qpamount = 0;
                        
                        $qpamount = $rcamount['ug_amt'];                        

                        $totalscript = Yii::$app->db->createCommand("SELECT count(qp_scrutiny_id) FROM coe_qp_setting A WHERE qp_scrutiny_id=".$val_faculty_id." AND year=".$year." AND month=".$month." AND qp_scrutiny_date='".date('Y-m-d',strtotime($claim_date))."' AND subject_code='".$value['subject_code']."'")->queryScalar();
                        

                        $totalscriptamt=$qpamount*$totalscript;

                        $noofday=($noofday<1)?'Half Day':$noofday; 
                       
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        //$body .='<td style="border:1px solid #000;">'.$value['degree_type'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;
                    }

                    if($loop==7)
                    {
                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }

                    if($tot_ta>0)
                    {
                        $body.=' <tr><td colspan="2"></td>
                            <td colspan="2" style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$tot_ta.'</td>
                        </tr>';
                    }

                    $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta+$totscriptamt).'</td>
                    </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=4 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(4,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "'.$tot_ta.'", "'.($tot_ta+$totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                         $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=4 AND exam_month='" . $month . "' AND exam_year='" . $year . "'  AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.',  tada_amt='.$tot_ta.', total_claim='.($tot_ta+$totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }


                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <b>Signature of Faculty<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $result=0;
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'theoryqpscrutinyclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'QP SETTING SCRUTINY CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['QP SETTING SCRUTINY CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }

    }

     public function actionQpsettingclaim()
    {
        $model = new QpSetting();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting Claim');
        return $this->render('qp_settingclaim', [
                'model' => $model,                
            ]);
        
        
    }

    public function actionQpSettingClaimpdf($id,$qp_setting_date) 
    {
        $val_faculty_id=$id;
        //echo "Please inform me next qp claim start by prabhakaran "; exit;
        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];

        $monthName = Categorytype::findOne($month);

        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=5 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND val_faculty_id=".$val_faculty_id." AND claim_date = '".$qp_setting_date."'")->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11 && $login_user_id!=12))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/qpsettingclaim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';

            $qpfinshed1 = new Query();           
            $qpfinshed1->select('*') ->from('coe_valuation_faculty A')
            ->join('JOIN', 'coe_qp_setting B', 'B.faculty1_id=A.coe_val_faculty_id')
            ->where(['B.year' => $year, 'B.month' => $month, 'B.qp_setting_date' => $qp_setting_date, 'B.faculty1_id'=>$val_faculty_id])->groupby('A.coe_val_faculty_id,B.subject_code');
            
            $qpfinsheddata1 = $qpfinshed1->createCommand()->queryAll();

            $qpfinshed11 = new Query();           
            $qpfinshed11->select('*') ->from('coe_valuation_faculty A')
            ->join('JOIN', 'coe_qp_setting B', 'B.faculty11_id=A.coe_val_faculty_id')
            ->where(['B.year' => $year, 'B.month' => $month, 'B.qp_setting_date' => $qp_setting_date, 'B.faculty11_id'=>$val_faculty_id])->groupby('A.coe_val_faculty_id,B.subject_code');
            
            $qpfinsheddata11 = $qpfinshed11->createCommand()->queryAll();

            $qpfinsheddata1 = array_merge($qpfinsheddata1,$qpfinsheddata11);

            $qpfinshed2 = new Query();           
            $qpfinshed2->select('*') ->from('coe_valuation_faculty A')
            ->join('JOIN', 'coe_qp_setting C', 'C.faculty2_id=A.coe_val_faculty_id')
            ->where(['C.year' => $year, 'C.month' => $month, 'C.qp_setting_date1' => $qp_setting_date, 'C.faculty2_id'=>$val_faculty_id])->groupby('A.coe_val_faculty_id,C.subject_code');

            $qpfinsheddata2 = $qpfinshed2->createCommand()->queryAll(); 

            $qpfinshed22 = new Query();           
            $qpfinshed22->select('*') ->from('coe_valuation_faculty A')
            ->join('JOIN', 'coe_qp_setting C', 'C.faculty22_id=A.coe_val_faculty_id')
            ->where(['C.year' => $year, 'C.month' => $month, 'C.qp_setting_date1' => $qp_setting_date, 'C.faculty22_id'=>$val_faculty_id])->groupby('A.coe_val_faculty_id,C.subject_code');

            $qpfinsheddata22 = $qpfinshed22->createCommand()->queryAll();

             $qpfinsheddata2 = array_merge($qpfinsheddata2,$qpfinsheddata22);

            $qpfinsheddata = array_merge($qpfinsheddata1,$qpfinsheddata2);  

            $unique_array = [];
            foreach($qpfinsheddata as $element) {
                $hash = $element['subject_code'];
                $unique_array[$hash] = $element;
            }
            $result = array_values($unique_array);
            //print_r($result); exit;
             $head=$header=$footer='';    
            if(!empty($result))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>QP SETTING CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"SKCT"; 
                $header .= '<table width="100%">
                     
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of QPs</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per QP (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                $totscript=$totscriptamt=$totamt=0;
                foreach ($result as $value) 
                {

                    $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();

                    $f1cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE  qp_setting_date = '".$qp_setting_date."' AND year=".$year." AND month=".$month." AND faculty1_id=".$val_faculty_id." AND subject_code='" . $value['subject_code'] . "'")->queryScalar();

                    $f2cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE qp_setting_date1= '".$qp_setting_date."' AND year=".$year." AND month=".$month." AND faculty2_id=".$val_faculty_id." AND subject_code='" . $value['subject_code'] . "'")->queryScalar();

                    $f11cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE  qp_setting_date = '".$qp_setting_date."' AND year=".$year." AND month=".$month." AND faculty11_id=".$val_faculty_id." AND subject_code='" . $value['subject_code'] . "'")->queryScalar();

                    $f22cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE qp_setting_date1= '".$qp_setting_date."' AND year=".$year." AND month=".$month." AND faculty22_id=".$val_faculty_id." AND subject_code='" . $value['subject_code'] . "'")->queryScalar();

                    $qpamount = Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=4")->queryScalar();

                    $totalscript=($f1cnt+$f2cnt)+($f11cnt+$f22cnt);
                    $totalscriptamt=$qpamount*$totalscript;

                    $subname=strtolower($degreeInfo['subject_name']);
                    $body .='<tr>';
                    $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                    $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                    $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                    $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                    $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                    $body .='</tr>';

                    $totscript=$totscript+$totalscript;
                    $totscriptamt=$totscriptamt+$totalscriptamt;

                }
                
                    $body.=' <tr><td colspan="2"></td>
                        <td colspan="2" style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=5 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND val_faculty_id=".$val_faculty_id." AND claim_date='".$qp_setting_date."'")->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        //$inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(5,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.$qp_setting_date.'" , "'.$totscript.'", "'.$totscriptamt.'", "0", "'.($totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                        // $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=5 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND val_faculty_id=".$val_faculty_id." AND claim_date='".$qp_setting_date."'")->queryScalar(); //exit;

                        // if($remun_id)
                        // { echo "string"; exit;
                        //      $login_user_id=Yii::$app->user->getId();
                        //     Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.', total_claim='.($totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                        // }
                       
                    }


                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <b>Signature of Faculty<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                Yii::$app->ShowFlashMessages->setMsg('Error', 'Something Error Pls Check');
                return $this->redirect(['coe-val-claim-amt/qpsettingclaim']);
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'theoryqpsettingclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'QP SETTING CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['QP SETTING CLAIM FORM - DATE '.date("Y-m-d").' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }

    }


     public function actionTheoryScrutinyclaim()
    {
        $model = new ValuationFacultyAllocate();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Theory Scrutiny Claim');
        return $this->render('theory_faculty_scrutinyclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionTheoryScrutinyClaimpdf($id) 
    {

        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $_SESSION['tvclaimdate'];
        $monthName = Categorytype::findOne($month);

        $rcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=9")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=6 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        // if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        // {
        //     Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
        //     return $this->redirect(['coe-val-claim-amt/practical-faculty-claim']);
        // }
        // else
        // {

            $body='';   $header=$footer='';  $footer1='';

            $addwhere=" AND A.scrutiny_date='".date("Y-m-d",strtotime($claim_date))."'";

            $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty_allocate as A JOIN coe_valuation_scrutiny B ON B.coe_scrutiny_id=A.coe_scrutiny_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_scrutiny_id='".$val_faculty_id."' GROUP BY A.subject_code")->queryAll();

             $header=$footer='';    
            if(!empty($valdata))
            {
                $faculty=Yii::$app->db->createCommand("SELECT A.*,C.dept_code,B.category_type as scrudesignation FROM coe_valuation_scrutiny A JOIN cur_department C ON C.coe_dept_id=A.department JOIN coe_category_type B ON B.coe_category_type_id=A.designation WHERE coe_scrutiny_id='".$val_faculty_id."'")->queryone();

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>THEORY VALUATION SCRUTINY CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Scrutiny Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">SCRUTINY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['name'].' & '.$faculty['scrudesignation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['dept_code'].' & SKCT</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">
                        
                        <th width="15%" style="border:1px solid #000;">Degree</th>
                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0; $page=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {               
                        $query = "SELECT b.degree_type,b.degree_code,d.subject_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_subjects_mapping c ON c.batch_mapping_id = a.coe_bat_deg_reg_id JOIN coe_subjects d ON d.coe_subjects_id=c.subject_id WHERE d.subject_code='" . $value['subject_code'] . "'";
                        
                        $degreeInfo = Yii::$app->db->createCommand($query)->queryOne();
                       
                       
                        $qpamount = $rcamount['ug_amt'];
                       

                        $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate WHERE coe_scrutiny_id=".$value['coe_scrutiny_id']." AND exam_year=".$year." AND exam_month=".$month." AND subject_code='" . $value['subject_code'] . "' AND scrutiny_date='".date('Y-m-d',strtotime($claim_date))."'")->queryScalar();
                       

                        $totalscriptamt=$qpamount*$totalscript;
                          

                        if($loop==8 && $page==0)
                        {
                             $page++;
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        else if($loop==10 && $page>0)
                        {
                            $loop=0;
                            $body .='</tbody></table><pagebreak />';
                            $body .='<table width="100%">';
                             $body .= '<tr style="text-align:center;">
                                        
                                        <th width="15%" style="border:1px solid #000;">Degree</th>
                                        <th width="40%" style="border:1px solid #000;">Subject Code & Name</th>                 
                                        <th width="15%" style="border:1px solid #000;">No. of Answer Scripts</th> 
                                        <th width="15%" style="border:1px solid #000;">Rate per Script (Rs.)</th>                           
                                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                                    </tr>
                                     <tbody>';
                        }
                        $subname=strtolower($degreeInfo['subject_name']);
                        $body .='<tr>';
                        $body .='<td style="border:1px solid #000;">'.$degreeInfo['degree_code'].'</td>';
                        $body .='<td style="border:1px solid #000;">'.$value['subject_code'].' & '.ucwords($subname).'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$qpamount.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;

                    }

                    if($loop==8 && $page==0)
                    {

                        $loop=0;
                        $body .='</tbody></table><pagebreak />';
                        $body .='<table width="100%"><tbody>';

                    }

                   
                    $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                        <td style="border:1px solid #000; text-align:right;">'.($totscript).'</td>
                        <td style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=6 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(6,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "0", "'.($totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=6 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.', total_claim='.($totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }


                     $footer .='
                            <tr>
                              <td colspan="5" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align: left;">
                                <br>
                                <b>Signature of Scrutiny<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: left;">
                                
                              </td>
                              <td colspan="3" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $result=0;
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'theorySCRUTINYclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'THEORY VALUATION SCRUTINY CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['THEORY VALUATION SCRUTINY CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        

    }

    public function actionConsolidateOverallClaim()
    {
        $model = new FacultyClaim();

        if(Yii::$app->request->post()) 
        {
            $year=$_POST['exam_year'];
            $month=$_POST['overall_exam_month'];
            $from_claim_date=$_POST['from_claim_date'];
            $to_claim_date=$_POST['to_claim_date'];

            $facultyid=$_POST['facultyid'];
            $facultyrenum=$_POST['facultyrenum'];
            $facultytype=$_POST['facultytype'];
            //print_r($facultytype); exit;
            $claim_type1=$_POST['claim_type1'];
            
            $claim_type='';
            if(!empty($claim_type1))
            {
                $claim_type=' AND claim_type in ('.$claim_type1.')';
            }
            //echo $claim_type; exit;
            $tot_success=0; $tot_error=0; 
            if(!empty($facultyid) && count($facultyid)>0)
            {
                
                
                $paidunqiueid='';
                 $paidunqiueid_pre = Yii::$app->db->createCommand("SELECT paid_unquine_id FROM coe_val_remunearation WHERE paid_date is NULL ")->queryScalar();
                if(!empty($paidunqiueid_pre))
                {
                   $paidunqiueid= $paidunqiueid_pre;
                }
                else
                {
                    $paidunqiueid=date('YmdHis');
                }
                //echo $paidunqiueid; exit();
                if($paidunqiueid!='')
                {
                    for($i=0;$i<count($facultyid);$i++)
                    {
                       
                        $check_data_exists = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_remunearation WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND val_faculty_id='".$facultyid[$i]."' AND claim_from_date='".date("Y-m-d",strtotime($from_claim_date))."' AND  claim_to_date='".date("Y-m-d",strtotime($to_claim_date))."' AND faculty_type=". $facultytype[$i])->queryScalar();

                        if($check_data_exists==0)
                        {
                            $qry="INSERT INTO coe_val_remunearation(val_faculty_id, exam_year, exam_month, claim_from_date, claim_to_date, total_amount,faculty_type,paid_unquine_id) VALUES('".$facultyid[$i]."', '".$year."', '".$month."', '".date("Y-m-d",strtotime($from_claim_date))."', '".date("Y-m-d",strtotime($to_claim_date))."', '".$facultyrenum[$i]."', '".$facultytype[$i]."','".$paidunqiueid."')";
                    
                            $Inserted= Yii::$app->db->createCommand($qry)->execute();
                            if($Inserted)
                            {
                                $tot_success++;
                            } 
                            else
                            {
                               $tot_error++;
                            }
                        }  
                        else
                        {
                            $tot_error++;
                        }                     
                    }

                    if($tot_success==count($facultyid))
                    {
                         $login_user_id=Yii::$app->user->getId();
                       

                        $successfuldata=  Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET paid_status=1, updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.',paid_unquine_id='.$paidunqiueid.' WHERE (paid_unquine_id="" OR paid_unquine_id is NULL) AND (claim_date BETWEEN "'.date('Y-m-d',strtotime($from_claim_date)).'" AND "'.date('Y-m-d',strtotime($to_claim_date)).'")'.$claim_type)->execute(); 

                        if($successfuldata)
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Success','Remuneration Claim Successfully Inserted');
                            return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error','Remuneration Claim NOT Successfully Inserted');
                            return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
                        }
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',$tot_error.' Remuneration Claim Not Fully Inserted! Please Check');
                        return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',' Unique id not generated! Please Check');
                        return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Claim Not Save Please Check');
                return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
            }

        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Consolidate Overall Claim');
            return $this->render('consolidateoverallclaim', [
                'model' => $model,
                
            ]);
        
        }
       
        
    }

    public function actionOverallclaimpdf()
    {
        $content=$_SESSION['overallclaim'];
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        if($_SESSION['withoutsplit']==1)
        {
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'consolidateoverallclaim.pdf',                
                    'format' => Pdf::FORMAT_LEGAL,                 
                    'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                    'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%;} 
                        
                        table td{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            padding: 5px;
                            font-size: 24px;
                            font-weight:bold;
                        }
                        table th{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            padding: 5px;
                            font-size: 24px;
                        }
                        }  ',
                        'options' => ['title' => 'Consolidate Overall Claim'],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>['CONSOLIDATE OVERALL CLAIM REPORT - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
        }
        else
        {
            $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'consolidateoverallclaim.pdf',                
                    'format' => Pdf::FORMAT_LEGAL,                 
                    'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                    'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%;} 
                        
                        table td{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            padding: 5px;
                            font-size: 24px;
                            font-weight:bold;
                        }
                        table th{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            padding: 5px;
                            font-size: 24px;
                        }
                    }   
                ',
                        'options' => ['title' => 'Consolidate Overall Claim'],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>['CONSOLIDATE OVERALL CLAIM REPORT - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
        }
                
        
    }

    
      public function actionOverallclaimexcel()
    {
        
        $content = $_SESSION['overallclaimxl'];
        $fileName = 'consolidateoverallclaim.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

     public function actionOverallclaimexcel1()
    {

        $content=$_SESSION['finalremunearation'];
        $content1=$_SESSION['claimfacultydatafinal'];
        $content2=$_SESSION['claimscrutinydatafinal'];


        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        if(!empty($content) && $_SESSION['withoutsplit']==1)
        {
            $objPHPExcel = new \PHPExcel();

            $objPHPExcel->createSheet(0); //Setting index when creating
            
            $objPHPExcel->setActiveSheetIndex(0);

            $objWorkSheet = $objPHPExcel->getActiveSheet();

            $objWorkSheet->setTitle('Remuneration');
            
            $head='Remuneration - '.$_SESSION['get_examyear'].' Examinations';

             $head1='Claim Date - '.$_SESSION['claimdate'];

             $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

            $objWorkSheet->getCell('A1')->setValue($head);
             $objWorkSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objWorkSheet->mergeCells('A1:I1');
             $objWorkSheet->getCell('A2')->setValue($head1);
             $objWorkSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objWorkSheet->mergeCells('A2:I2');
             $objWorkSheet->setCellValue('A3','S.No.');
             $objWorkSheet->setCellValue('B3','Name');
             $objWorkSheet->setCellValue('C3','Department');
             $objWorkSheet->setCellValue('D3','College Name');
             $objWorkSheet->setCellValue('E3','Account Number');
             $objWorkSheet->setCellValue('F3','IFSC');
             $objWorkSheet->setCellValue('G3','Bank');
             $objWorkSheet->setCellValue('H3','Bank Branch');
             $objWorkSheet->setCellValue('I3','Remuneration');
             $objWorkSheet->getStyle("A1:I3")->getFont()->setBold(true);

             $row = 4; $sno=1;
            foreach($content as $value)
            {
                $name=$department=$clgcode='';
                if($value['faculty_type']==1)
                {
                    $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['val_faculty_id']."'")->queryone();
                    if(!empty($faculty))
                    {
                        $clgcode=($faculty['faculty_mode']=='EXTERNAL')?$faculty['college_code']:"SKCT";
                        $name=$faculty['faculty_name'];
                        $department=$faculty['faculty_board'];
                    }
                }
                else if($value['faculty_type']==2)
                {
                    $name=$department=$clgcode=''; 
                    $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_scrutiny WHERE coe_scrutiny_id='".$value['val_faculty_id']."'")->queryone();  
                    if(!empty($faculty))
                    {                                 
                        $name=$faculty['name'];
                        $department=$faculty['department'];
                        $clgcode='SKCT';
                    }
                }
                
                if(!empty($faculty))
                {
                    $objWorkSheet->setCellValue('A'.$row,$sno);
                    $objWorkSheet->setCellValue('B'.$row,$name);
                    $objWorkSheet->setCellValue('C'.$row,$department);
                    $objWorkSheet->setCellValue('D'.$row,$clgcode);
                    $objWorkSheet->setCellValue('E'.$row,$faculty['bank_accno']);
                    $objWorkSheet->setCellValue('F'.$row,$faculty['bank_ifsc']);
                    $objWorkSheet->setCellValue('G'.$row,$faculty['bank_name']);
                    $objWorkSheet->setCellValue('H'.$row,$faculty['bank_branch']);
                    $objWorkSheet->setCellValue('I'.$row,$value['total_amount']);

                }

                $row++;
                $sno++;
            }

            $head='Remuneration - '.$_SESSION['get_examyear'].' Examinations.xlsx';

            header('Content-type: application/.xlsx');
            header('Content-Disposition: attachment; filename="'.$head.'"');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');

        }
        else if(!empty($content1) && $_SESSION['withoutsplit']==0)
        {

            $year=$_SESSION['tvclaimyear'];
            $month=$_SESSION['tvclaimmonth'];
            $from_claim_date=$_SESSION['from_claim_date'];
            $to_claim_date=$_SESSION['to_claim_date'];

            $paidstus='';
            if($_SESSION['paid']==1)
            {   
                 $paidstus=' AND (paid_status=1)';

            }
            else
            {
                 $paidstus=' AND (paid_status=0 OR paid_status is NULL OR paid_status="")';
            }

            $objPHPExcel = new \PHPExcel();

            $objPHPExcel->createSheet(0); //Setting index when creating
            
            $objPHPExcel->setActiveSheetIndex(0);

            $objWorkSheet = $objPHPExcel->getActiveSheet();

            $objWorkSheet->setTitle('Remuneration');
            
            $head='Remuneration - '.$_SESSION['get_examyear'].' Examinations';

             $head1='Claim Date - '.$_SESSION['claimdate'];

             $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
             $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
             $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
               $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
                  $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
                   $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);

            $objWorkSheet->getCell('A1')->setValue($head);
             $objWorkSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objWorkSheet->mergeCells('A1:I1');
             $objWorkSheet->getCell('A2')->setValue($head1);
             $objWorkSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objWorkSheet->mergeCells('A2:I2');
             $objWorkSheet->setCellValue('A3','S.No.');
             $objWorkSheet->setCellValue('B3','Name');
             $objWorkSheet->setCellValue('C3','Department');
             $objWorkSheet->setCellValue('D3','College Name');
             $objWorkSheet->setCellValue('E3','Account Number');
             $objWorkSheet->setCellValue('F3','IFSC');
             $objWorkSheet->setCellValue('G3','Bank');
             $objWorkSheet->setCellValue('H3','Bank Branch');

            $objWorkSheet->setCellValue('I3','QP Setting');
            $objWorkSheet->setCellValue('J3','QP Scrutiny');
            $objWorkSheet->setCellValue('K3','Practical');
            $objWorkSheet->setCellValue('L3','Theory Valuation');
            $objWorkSheet->setCellValue('M3','Theory Revaluation');
            $objWorkSheet->setCellValue('N3','Theory Valuation Scrutiny');
            $objWorkSheet->setCellValue('O3','Hall Invigilation');

             $objWorkSheet->setCellValue('P3','Remuneration');
             $objWorkSheet->getStyle("A1:P3")->getFont()->setBold(true);

             $row = 4; $sno=1;

             $totalrenum=0;
             $tqp_set_amt=$tqp_scrutiny_amt=$ttheory_scru_amt=$ttheory_reval=$ttheory_val=$tpractical=$thallinv=0;

            foreach($content1 as $value)
            {
                $name=$department=$clgcode='';
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['val_faculty_id']."'")->queryone();                               
                if(!empty($faculty))
                {   

                    $clgcode=($faculty['faculty_mode']=='EXTERNAL')?$faculty['college_code']:"SKCT";
                    $name=$faculty['faculty_name'];
                    $department=$faculty['faculty_board'];

                    $qp_set_amt = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=5 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();

                    $qp_scrutiny_amt = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=4 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();                

                    $theory_reval = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=3 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();

                    $theory_val = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=2 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();  

                    $practical = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=1 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();

                    $hallinv = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=7 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();

                    $scru_faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_scrutiny WHERE bank_accno='".$faculty['bank_accno']."'")->queryone();

                    if(!empty($scru_faculty))
                    {
                        $theory_scru_amt = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=6 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$scru_faculty['coe_scrutiny_id'].$paidstus)->queryScalar(); //exit;

                    }
                    else
                    {
                        $theory_scru_amt = 0;
                      
                    }
                     
                    $renumamount=$qp_set_amt+$qp_scrutiny_amt+$theory_scru_amt+$theory_reval+$theory_val+$practical+$hallinv;

                    $objWorkSheet->setCellValue('A'.$row,$sno);
                    $objWorkSheet->setCellValue('B'.$row,$name);
                    $objWorkSheet->setCellValue('C'.$row,$department);
                    $objWorkSheet->setCellValue('D'.$row,$clgcode);
                    $objWorkSheet->setCellValue('E'.$row,$faculty['bank_accno']);
                    $objWorkSheet->setCellValue('F'.$row,$faculty['bank_ifsc']);
                    $objWorkSheet->setCellValue('G'.$row,$faculty['bank_name']);
                    $objWorkSheet->setCellValue('H'.$row,$faculty['bank_branch']);
                    $objWorkSheet->setCellValue('I'.$row,$qp_set_amt);
                    $objWorkSheet->setCellValue('J'.$row,$qp_scrutiny_amt);
                    $objWorkSheet->setCellValue('K'.$row,$practical);
                    $objWorkSheet->setCellValue('L'.$row,$theory_val);
                    $objWorkSheet->setCellValue('M'.$row,$theory_reval);
                    $objWorkSheet->setCellValue('N'.$row,$theory_scru_amt);
                    $objWorkSheet->setCellValue('O'.$row,$hallinv);
                    $objWorkSheet->setCellValue('P'.$row,$renumamount);

                    $totalrenum=$totalrenum+$renumamount;

                     $tqp_set_amt= $tqp_set_amt+$qp_set_amt;
                     $tqp_scrutiny_amt=$tqp_scrutiny_amt+$qp_scrutiny_amt;
                     $ttheory_scru_amt= $ttheory_scru_amt+$theory_scru_amt;
                     $ttheory_reval=$ttheory_reval+$theory_reval;
                     $ttheory_val=$ttheory_val+$theory_val;
                     $tpractical= $tpractical+$practical;
                     $thallinv=$thallinv+$hallinv;   

                }

                $row++;
                $sno++;
            }

            foreach($content2 as $value)
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_scrutiny WHERE coe_scrutiny_id='".$value['val_faculty_id']."'")->queryone();

                $faculty1=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE bank_accno='".$faculty['bank_accno']."'")->queryone();

                $other_count=0;
                if(!empty($faculty1))
                {
                    $other_count = Yii::$app->db->createCommand("SELECT count(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND (claim_type<6 OR claim_type>6) AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$faculty1['coe_val_faculty_id'].$paidstus)->queryScalar();
                }

                if(empty($faculty1) || $other_count==0)
                {
                    $name=$department=$clgcode='';
                    
                    $name=$faculty['name'];
                    $department=$faculty['department'];
                    $clgcode='SKCT';

                    $theory_scru_amt = Yii::$app->db->createCommand("SELECT sum(total_claim) FROM coe_val_faculty_claim WHERE exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_type=6 AND (claim_date BETWEEN '".date('Y-m-d',strtotime($from_claim_date))."' AND '".date('Y-m-d',strtotime($to_claim_date))."') AND val_faculty_id=".$value['val_faculty_id'].$paidstus)->queryScalar();

                    $ttheory_scru_amt= $ttheory_scru_amt+$theory_scru_amt;

                    $renumamount=$theory_scru_amt;
                    $totalrenum=$totalrenum+$theory_scru_amt;

                    $objWorkSheet->setCellValue('A'.$row,$sno);
                    $objWorkSheet->setCellValue('B'.$row,$name);
                    $objWorkSheet->setCellValue('C'.$row,$department);
                    $objWorkSheet->setCellValue('D'.$row,$clgcode);
                    $objWorkSheet->setCellValue('E'.$row,$faculty['bank_accno']);
                    $objWorkSheet->setCellValue('F'.$row,$faculty['bank_ifsc']);
                    $objWorkSheet->setCellValue('G'.$row,$faculty['bank_name']);
                    $objWorkSheet->setCellValue('H'.$row,$faculty['bank_branch']);
                    $objWorkSheet->setCellValue('I'.$row,'');
                    $objWorkSheet->setCellValue('J'.$row,'');
                    $objWorkSheet->setCellValue('K'.$row,'');
                    $objWorkSheet->setCellValue('L'.$row,'');
                    $objWorkSheet->setCellValue('M'.$row,'');
                    $objWorkSheet->setCellValue('N'.$row,$theory_scru_amt);
                    $objWorkSheet->setCellValue('O'.$row,'');
                    $objWorkSheet->setCellValue('P'.$row,$renumamount);

                    $row++;
                    $sno++;
                }
            }

            $row=$row+1;
            $mergeCells='A'.$row.':'.'H'.$row;
            $objWorkSheet->getCell('A'.$row)->setValue('Total');
            $objWorkSheet->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objWorkSheet->mergeCells($mergeCells);
            $objWorkSheet->setCellValue('I'.$row,$tqp_set_amt);
            $objWorkSheet->setCellValue('J'.$row,$tqp_scrutiny_amt);
            $objWorkSheet->setCellValue('K'.$row,$tpractical);
            $objWorkSheet->setCellValue('L'.$row,$ttheory_val);
            $objWorkSheet->setCellValue('M'.$row,$ttheory_reval);
            $objWorkSheet->setCellValue('N'.$row,$ttheory_scru_amt);
            $objWorkSheet->setCellValue('O'.$row,$thallinv);
            $objWorkSheet->setCellValue('P'.$row,$totalrenum);

            $boldcell='A'.$row.':'.'P'.$row;
            $objWorkSheet->getStyle($boldcell)->getFont()->setBold(true);

            $head='Remuneration - '.$_SESSION['get_examyear'].' Examinations.xlsx';

            header('Content-type: application/.xlsx');
            header('Content-Disposition: attachment; filename="'.$head.'"');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','Excel Not Downloaded! Please Check');
            return $this->redirect(['coe-val-claim-amt/consolidate-overall-claim']);
        }

    }

    public function actionClaimconsolidate()
    {
        $model = new FacultyClaim();
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Claim Consolidate');
        return $this->render('claimconsolidate', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionCclaimpdf()
    {
        $content=$_SESSION['overallclaim'];

        $cclaim='Consolidate '.$_SESSION['claimtype'].' Claim Report';
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => $_SESSION['claimtype'].'consolidateclaim.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                        'options' => ['title' => $cclaim],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>[$cclaim.' - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

      public function actionAurclaim()
    {
        $model = new FacultyClaim();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to AUR Claim');
        return $this->render('aurclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionAurclaimpdf()
    {
        $content=$_SESSION['overallclaim'];

        $cclaim='AUR Claim Report';
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' =>'AURclaim.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                        'options' => ['title' => $cclaim],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>[$cclaim.' - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

    public function actionAurindclaimpdf($id,$ad,$cd) 
    {
        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $cd;
        $monthName = Categorytype::findOne($month);
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); 
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=7 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/aurclaim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';

           $query_3 = new Query();
            $query_3->select([ 'A.*','B.*'])->from('coe_faculty_hall_arrange A')
            ->join('JOIN','coe_valuation_faculty B','B.coe_val_faculty_id = A.aur')
            ->Where(['A.year'=>$year,'A.month'=>$month,'A.aur'=>$val_faculty_id])
            ->andWhere(['<=','A.exam_date',date("Y-m-d")])
            ->groupby('A.aur');
            //echo $query_3->createCommand()->getrawsql(); exit;
            $aur_faculty = $query_3->createCommand()->queryAll();
            //print_r($aur_faculty); exit;
             $header=$footer='';    
            if(!empty($aur_faculty))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();
                //print_r($faculty); exit();
                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>AUR CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:""; 
                $header .= '<table width="100%">
                     
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=3 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">  
                        <th width="50%" style="border:1px solid #000;">From Date / To Date</th>               
                        <th width="15%" style="border:1px solid #000;">No. of Days</th>                          
                        <th width="15%" style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $auramt=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=7")->queryOne();
            

                    $aur_date = Yii::$app->db->createCommand("SELECT DISTINCT exam_date FROM coe_faculty_hall_arrange WHERE aur=".$val_faculty_id." AND year=".$year." AND month=".$month)->queryAll();

                   $noofday=0; $n=count($aur_date)-1;;
                    $fromdate=$todate="";

                     $i=0;
                      $halfday_session=0;
                    $fullday_session=0;
                   foreach ($aur_date as $dvalue) 
                   {
                     if($i==0)
                        {
                             $fromdate=$dvalue['exam_date'];
                        }
                        if($i==$n)
                        {
                             $todate=$dvalue['exam_date'];
                        }

                      $aur_fn = Yii::$app->db->createCommand("SELECT aur FROM coe_faculty_hall_arrange WHERE aur=".$val_faculty_id." AND year=".$year." AND month=".$month." AND exam_date='".$dvalue['exam_date']."' group by exam_date,exam_session")->queryAll();

                    

                      if(count($aur_fn)==2) 
                      {
                          $noofday=$noofday+1;
                          $fullday_session=$fullday_session+1;
                      }
                      else if(count($aur_fn)==1) 
                      {
                          $noofday=$noofday+1;
                          $halfday_session=$halfday_session+1;
                      }
                      $i++;
                   }

                    $tot_ta=0;  $da_amt=$ta_amt=0; 
                    $noofdate='';
                    if($fullday_session==0)
                    {
                        $da_amt=($auramt['ta_amt_half_day']*$halfday_session);
                        $ta_amt=($auramt['ug_amt']*$halfday_session); 
                        $tot_ta=$da_amt+$ta_amt; //exit();
                        $noofdate=$halfday_session.' Half Duty';
                    }
                    else
                    {
                       
                        $da_amt=($auramt['ta_amt_half_day']*$halfday_session)+($auramt['ta_amt_full_day']*$fullday_session);

                        $ta_amt=($auramt['ug_amt']*$halfday_session)+($auramt['pg_amt']*$fullday_session); 
                        $tot_ta=$da_amt+$ta_amt; //exit();

                        $noofdate=$fullday_session.' Full Duty'.$halfday_session.' Half Duty';
                    }

                    if($faculty['out_session']=='YES')
                    {
                        //$tot_ta=$tot_ta+$auramt['out_session'];
                    }

                    $body .='<tr>';
                    $body .='<td style="border:1px solid #000;">'.$ad.'</td>';
                    $body .='<td style="border:1px solid #000;">'.$noofdate.'</td>';
                    $body .='<td style="border:1px solid #000;">'.$ta_amt.'</td>';
                    $body .='</tr>';
                   
                    $body.=' <tr><td></td>
                            <td style="border:1px solid #000; text-align:right;">TA/DA Amount</td>
                            <td style="border:1px solid #000;">'.$da_amt.'</td>
                        </tr>';
                    

                    $body.=' <tr><td></td>
                        <td style="border:1px solid #000; text-align:right;">Total</td>
                        <td style="border:1px solid #000;">'.($tot_ta).'</td>
                    </tr>';

                     $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=7 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(7,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" ,"'.$noofday.'", "0", "0", "'.$tot_ta.'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=7 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$noofday.', total_claim='.($tot_ta).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }
                   
                     $footer .='
                            <tr>
                              <td colspan="3" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td style="text-align: left;">
                                <b>Signature of AUR<br>with Date</b>
                              </td>
                              <td colspan="2" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td style="text-align: left;">
                                
                              </td>
                              <td colspan="2" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $result=0;
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'AURclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'AUR CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['AUR CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }

    }

     public function actionValuationCompareReport()
    {
        $model = new ValuationFacultyAllocate();
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Theory Valuation Compare Report');
        return $this->render('valuationcompare', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionValuationcomparepdf()
    {
        $content=$_SESSION['valuationcompare'];
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'valuationcompare.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content, 
                   
                    'cssInline' => ' @media all{
                            table{border-collapse: collapse; width:100%; } 
                            
                            table td{
                                border: 1px solid #CCC;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }
                            table th{
                               border: 1px solid #CCC;
                                text-align: center;
                            }
                            
                        }   
                    ',  
                        'options' => ['title' => 'Valuation Compare Report'],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>['Valuation Compare Report - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

    public function actionValuationcompareexcel()
    {

        $content = $_SESSION['valuationcompare'];          
        $fileName = 'valuationcompare ' . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

     public function actionClaimabstract()
    {
        $model = new FacultyClaim();

        if(Yii::$app->request->post()) 
        {
            if($_POST['paid_date']!='')
            {
                $claim_date = Yii::$app->request->post('claim_date');
                $claim_date=explode("/", $claim_date);

                $from_claim_date = $claim_date[0]; 
                $to_claim_date = $claim_date[1]; 
               // echo "test"; exit;

                $paid_unquine_id = Yii::$app->db->createCommand("SELECT paid_unquine_id FROM coe_val_remunearation WHERE claim_from_date='" . date("Y-m-d",strtotime($from_claim_date)) . "' AND paid_date is NULL")->queryScalar(); 

                $updated= Yii::$app->db->createCommand('UPDATE  coe_val_remunearation SET paid_date="'.date('Y-m-d',strtotime($_POST['paid_date'])).'" WHERE paid_unquine_id = "'.$paid_unquine_id.'"')->execute();
                
                if($updated)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success','Payment Release Date Successfully Updated');
                    return $this->redirect(['coe-val-claim-amt/claimabstract']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error','Please Check Payment Release Date Not Updated');
                    return $this->redirect(['coe-val-claim-amt/claimabstract']);
                }

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Please Choose Payment Release Date');
                return $this->redirect(['coe-val-claim-amt/claimabstract']);
            }
        }
        else
        {
             Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Claim Consolidate');
            return $this->render('abstractforclaim', [
                'model' => $model,
                
            ]);
        }        
        
    }


    public function actionAbstractclaimpdf()
    {
        $content=$_SESSION['claimabstract'];

        $cclaim='Consolidate Claim';
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
         $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => 'consolidateclaim.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                        'options' => ['title' => $cclaim],
                        'methods' => [ 
                            'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                            'SetFooter'=>[$cclaim.' - {PAGENO}'],
                        ],
                        
                    ]);
                   
                    $pdf->marginLeft="8";
                    $pdf->marginRight="8";
                    $pdf->marginBottom="8";
                    $pdf->marginFooter="4";
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    $headers = Yii::$app->response->headers;
                    $headers->add('Content-Type', 'application/pdf');
                    return $pdf->render(); 
                
        
    }

     public function actionChiefclaim()
    {
        $model = new FacultyClaim();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Chief Examiner Claim');
        return $this->render('chiefclaim', [
                'model' => $model,
                
            ]);
        
        
    }

    public function actionChiefClaimpdf($id) 
    {

        $val_faculty_id=$id;

        $year = $_SESSION['tvclaimyear']; 
        $month = $_SESSION['tvclaimmonth'];
            
        $claim_date = $_SESSION['tvclaimdate'];
        $monthName = Categorytype::findOne($month);

        $rcamount=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=11")->queryone();
       
        $result=0;$content='';

        $login_user_id=Yii::$app->user->getId(); //exit;
        
        $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=11 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

        if($checkclaim != 0 && ($login_user_id!=1 && $login_user_id!=11))
        {
            Yii::$app->ShowFlashMessages->setMsg('Warning', 'Already PDF Downloaded, For Reprint Contact Admin');
            return $this->redirect(['coe-val-claim-amt/chief-claim']);
        }
        else
        {

            $body='';   $header=$footer='';  $footer1='';

            $addwhere=" AND A.valuation_date='".date("Y-m-d",strtotime($claim_date))."'";

            $valdata = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_chief_allocate as A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "'".$addwhere." AND A.coe_val_faculty_id='".$val_faculty_id."'")->queryAll();

             $header=$footer='';    
            if(!empty($valdata))
            {
                $faculty=Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$val_faculty_id."'")->queryone();
                $clgcode=(!empty($faculty['college_code']))?$faculty['college_code']:"";

                 require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $head= '<table width="100%">
                    <tr>
                        <td align="left">
                                        <img class="img-responsive" width="50" height="50" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                        <td colspan=2 align="center"> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center>
                              <center>Affiliated to Anna University, Coimbatore, Tamil Nadu</center>                           
                              <center><h6><b>CHIEF EXAMINER CLAIM FORM '.strtoupper($monthName['category_type'])." - ".$year.'</b></h6></center>
                         </td>
                         
                          <td align="right">  
                                        <img width="50" height="50" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                      </td>
                     </tr></table>
                     '; 
                    
                $header .= '<table width="100%">
                     <tr>
                        <td style="text-align:right;" colspan="3"><b>Valuation Date:</b> '.date("d-m-Y",strtotime($claim_date)).'</td>
                        
                    </tr> 
                    <tr>
                        <th style="text-align:center;" colspan="3">FACULTY DETAILS</th>
                        
                    </tr> 
                    <tr>
                        <td width="30%">Name & Designation</td>
                        <td width="5%">:</td>
                        <td>'.$faculty['faculty_name'].' & '.$faculty['faculty_designation'].'</td>
                    </tr>
                    <tr>
                        <td>Board & Institution</td>
                        <td>:</td>
                        <td>'.$faculty['faculty_board'].' & '.$clgcode.'</td>
                    </tr>
                    <tr>
                        <td>Mobile No. & Email</td>
                        <td>:</td>
                        <td>'.$faculty['phone_no'].' & '.$faculty['email'].'</td>
                    </tr>
                    
                    <tr>
                        <td>Bank Acc.No. & IFSC</td>
                        <td>:</td>
                        <td><b>'.$faculty['bank_accno'].' & '.$faculty['bank_ifsc'].'</b></td>
                    </tr>
                    <tr>
                        <td>Bank & Branch</td>
                        <td>:</td>
                        <td>'.$faculty['bank_name'].' & '.$faculty['bank_branch'].'</td>
                    </tr> </table>
                   ';

                $header .= '<table width="100%">
                    <tr>
                        <th colspan=5 style="text-align:center;">REMUNERATION DETAILS</th>
                        
                    </tr> ';

                $header .= '
                    <tr style="text-align:center;">         
                        <th style="border:1px solid #000;">No. of Scripts</th>                          
                        <th style="border:1px solid #000;">Amount (Rs.)</th>
                    </tr>
                     <tbody>';

                    $s=1; $loop=0; $page=0;
                    $totscript=$totscriptamt=$totamt=0;
                    foreach ($valdata as $value) 
                    {       

                        if($value['subject_code']!='')
                        {
                            $explode=explode(",",$value['subject_code']);
                            for ($c=0; $c <count($explode) ; $c++) 
                            { 
                                
                                $ts=Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE subject_code='".$explode[$c]."' AND exam_year=".$year." AND exam_month=".$month.$addwhere)->queryScalar();
                                
                                $totalscript =$ts;
                            } 
                             
                        }
                        else
                        {
                            $totalscript = Yii::$app->db->createCommand("SELECT sum(total_answer_scripts) FROM coe_valuation_faculty_allocate A WHERE board='".$value['faculty_board']."' AND exam_year=".$year." AND exam_month=".$month.$addwhere)->queryScalar();
                        }

                        if($totalscript>=300 && $totalscript<=599)
                        {
                            $totalscriptamt=$rcamount['ug_amt'];
                        }
                        else if($totalscript>=600)
                        {
                            $totalscriptamt=$rcamount['pg_amt'];
                        }
                        else
                        {
                            $totalscriptamt=0;
                        }
                       

                        if($totalscript>=300 && $totalscript<=599)
                        {
                            $totalscriptamt=$rcamount['ug_amt'];
                        }
                        else if($totalscript>=600)
                        {
                            $totalscriptamt=$rcamount['pg_amt'];
                        }
                        else
                        {
                            $totalscriptamt=0;
                        }
                          
                        $body .='<tr>';
                        $body .='<td style="border:1px solid #000;">'.$totalscript.'</td>';
                        $body .='<td style="border:1px solid #000;">'.$totalscriptamt.'</td>';
                        $body .='</tr>';
                                                
                        $totscript=$totscript+$totalscript;
                        $totscriptamt=$totscriptamt+$totalscriptamt;
                    
                        $loop++;

                    }
                   
                    // $body.=' <tr><td colspan="2" style="border:1px solid #000; text-align:right;">Total Script</td>
                    //     <td style="border:1px solid #000; text-align:right;">'.($totscript).'</td>
                    //     <td style="border:1px solid #000; text-align:right;">Total</td>
                    //     <td style="border:1px solid #000;">'.($totscriptamt).'</td>
                    // </tr>';

                    
                    $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=11 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                    if($checkclaim == 0 && $totalscriptamt!=0)
                    {
                        $login_user_id=Yii::$app->user->getId();
                        $inserted = Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(11,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" , "'.$totscript.'", "'.$totscriptamt.'", "0", "'.($totscriptamt).'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                    }
                    else if($totalscriptamt!=0)
                    {
                        $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=11 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;

                        Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$totscript.', total_script_amount='.$totscriptamt.', total_claim='.($totscriptamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                    }


                     $footer .='
                            <tr>
                              <td colspan="2" style="text-align: right;">                                    
                                Passed For Payment
                              </td>
                            </tr>

                            <tr>
                                <td colspan="1" style="text-align: left;">
                                <br>
                                <b>Signature of Faculty<br>with Date</b>
                              </td>
                              <td colspan="1" style="text-align: right;"> 
                                <br><br><br>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="1" style="text-align: left;">
                                
                              </td>
                              <td colspan="1" style="text-align: right;">
                              
                                <b>Controller Of Examinations</b> 
                              </td>
                            </tr>
                        </tbody></table>';
                                      

                $result=1;
            }
            else
            { 
                $result=0;
            }   
                       
         
            $content=$head.$header.$body.$footer; 
           
            $pdf = new Pdf([                   
                'mode' => Pdf::MODE_CORE,                 
                'filename' => 'chiefclaim.pdf',
                'format' => [212, 136],                
                //'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                    'options' => ['title' => 'CHIEF EXAMINER CLAIM FORM '],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS "], 
                        'SetFooter'=>['CHIEF EXAMINER CLAIM FORM - DATE '.date("Y-m-d",strtotime($claim_date)).' PAGE {PAGENO}'],
                    ],
                    
                ]);
                

                $pdf->marginLeft="4";
                $pdf->marginRight="4";
                $pdf->marginBottom="4";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }

    }

}
