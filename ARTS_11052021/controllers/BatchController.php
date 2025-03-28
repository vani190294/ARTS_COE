<?php

namespace app\controllers;
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
use Yii;
use app\models\Batch;
use app\models\BatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Regulation;
use app\models\MarkEntry;
use app\models\Student;
use app\models\Programme;
use app\models\Degree;
use app\models\CoeBatDegReg;
use app\models\SubjectsMapping;
use app\models\StudentMapping;
use app\models\Configuration;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Growl;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

class BatchController extends Controller
{
    
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

   
    public function actionIndex()
    {
        $searchModel = new BatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        $model = $this->findModel($id);
        $regulation = new Regulation();  

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['view', 'id' => $model->coe_batch_id]);
        } else {
            return $this->render('update', [
                'model' => $model,'regulation' => $regulation,
            ]);
        }
    }

   
    public function actionCreate()
    {
        $model = new Batch();
        $regulation = new Regulation();
        $degree = new Degree();        
        $programme = new Programme();        
        $coebatdegreg = new CoeBatDegReg();
        if ($model->load(Yii::$app->request->post()) && $regulation->load(Yii::$app->request->post()))
        {  
            $reg_year = $regulation->regulation_year;                        
            $check_batch = Batch::find()->where(['batch_name' => $model->batch_name])->one();            
            $check_regulation = Regulation::find()->where(['regulation_year' => $regulation->regulation_year])->one();                          
            if(!empty($check_batch->batch_name)){
                Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' is Already Exists');
                return $this->render('create', [
                    'model' => $model,
                    'regulation' => $regulation,
                    'degree' => $degree,
                    'programme' => $programme,
                ]);                
            }else{
                $model->attributes;
                $model->created_at = new \yii\db\Expression('NOW()');
                $model->created_by = Yii::$app->user->getId();
                $model->updated_at = new \yii\db\Expression('NOW()');
                $model->updated_by = Yii::$app->user->getId();                
                if(isset($_POST['deg'])){
                    $model->save();                              
                    if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['name']) && isset($_POST['point'])){
                        $from = $_POST['from'];
                        $to = $_POST['to'];
                        $name = $_POST['name'];
                        $point = $_POST['point'];
                        for($i=0;$i<count($from);$i++){                                
                            $regulation = new Regulation();
                            $regulation->coe_batch_id=$model->coe_batch_id; 
                            $regulation->regulation_year=$reg_year;
                            $regulation->grade_point_from=$from[$i];
                            $regulation->grade_point_to=$to[$i];
                            $regulation->grade_name=$name[$i];
                            $regulation->grade_point=$point[$i];
                            $regulation->created_at = new \yii\db\Expression('NOW()');
                            $regulation->created_by = Yii::$app->user->getId();
                            $regulation->updated_at = new \yii\db\Expression('NOW()');
                            $regulation->updated_by = Yii::$app->user->getId();
                            $regulation->save();
                            unset($regulation);
                        } //for                            
                    }else{
                        $regulation->coe_batch_id=$model->coe_batch_id;                    
                        $regulation->regulation_year=$reg_year;
                        $regulation->created_at = new \yii\db\Expression('NOW()');
                        $regulation->created_by = Yii::$app->user->getId();                            
                        $regulation->updated_by = Yii::$app->user->getId();
                        $regulation->save();
                    }
                    $regulation = new Regulation();                    
                    if(isset($_POST['deg']) && isset($_POST['pgm']) && isset($_POST['sec'])){
                        $deg = $_POST['deg'];
                        $pgm = $_POST['pgm'];
                        $sec = $_POST['sec']; 
                        for($i=0;$i<count($deg);$i++){   
                            $section = $sec[$i]==0?1:$sec[$i];                             
                            $deg_id = Degree::find()->where(['degree_code' => $deg[$i]])->one();
                            $prgm_id = Programme::find()->where(['programme_code' => $pgm[$i]])->one();
                            $getProg = Yii::$app->db->createCommand('SELECT * FROM coe_programme WHERE programme_code like "%'.$pgm[$i].'%" ')->queryOne();

                            $coebatdegreg = new CoeBatDegReg();
                            $coebatdegreg->coe_batch_id=$model->coe_batch_id;
                            $coebatdegreg->coe_degree_id=$deg_id->coe_degree_id;
                            $coebatdegreg->coe_programme_id=$getProg['coe_programme_id'];
                            $coebatdegreg->regulation_year=$reg_year;
                            $coebatdegreg->no_of_section=$section;
                            $coebatdegreg->created_at = new \yii\db\Expression('NOW()');
                            $coebatdegreg->created_by = Yii::$app->user->getId();
                            $coebatdegreg->updated_by = Yii::$app->user->getId();
                            $coebatdegreg->save();
                            unset($coebatdegreg);
                        } //for                                               
                    } //pgm sec
                    Yii::$app->ShowFlashMessages->setMsg('Success','Record Saved Successfully!!!');
                    return $this->redirect(['create',]);  
                }else{
                        Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' cannot be assigned without '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).'!!!');
                        return $this->redirect(['create',]); 
                }                                                                                 
            } //else                      
        } else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' Creation');
            return $this->render('create', [
                'model' => $model,
                'regulation' => $regulation,
                'degree' => $degree, 
                'programme' => $programme,               
            ]);
        }        
    }

    public function actionUpdated()
    {
        $coebatdegreg = new CoeBatDegReg(); 
        $batch = Batch::find()->where(['batch_name' => $_POST['batch']])->one();
        $programme = Programme::find()->where(['programme_code' => $_POST['programme']])->one();
        //echo $batch->coe_batch_id."---".$_POST['degree']."---".$programme->coe_programme_id."---".$_POST['section'];exit;
        $exist_degree_details = Yii::$app->db->createCommand("select coe_bat_deg_reg_id from coe_bat_deg_reg where coe_batch_id='".$batch->coe_batch_id."' and coe_degree_id='".$_POST['degree']."' and coe_programme_id='".$programme->coe_programme_id."'")->queryScalar();
        if(empty($exist_degree_details)){
            $section = $_POST['section']==0?1:$_POST['section'];
            $coebatdegreg->coe_batch_id=$batch->coe_batch_id;
            $coebatdegreg->coe_degree_id=$_POST['degree'];
            $coebatdegreg->coe_programme_id=$programme->coe_programme_id;
            $coebatdegreg->no_of_section=$section;
            $coebatdegreg->created_at = new \yii\db\Expression('NOW()');
            $coebatdegreg->created_by = Yii::$app->user->getId();
            $coebatdegreg->updated_by = Yii::$app->user->getId();
            return $coebatdegreg->save()?"Updated":"No";     
        }else{
           return 0;
        }
                        
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $regulation = new Regulation();  

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['view', 'id' => $model->coe_batch_id]);
        } else {
            return $this->render('update', [
                'model' => $model,'regulation' => $regulation,
            ]);
        }
    }

   
    public function actionDelete($id)
    {
        $batch_mapping = CoeBatDegReg::find()->where(['coe_batch_id'=>$id])->one();
        $degree_name = Batch::findOne($id);
        $name_of_degree = $degree_name->batch_name;
        if(empty($batch_mapping))
        {
            $regulation = Regulation::findOne(['coe_batch_id'=>$id]);
            $regulation_del = Regulation::findModel($regulation->coe_regulation_id)->delete();
            if($regulation_del)
            {
                $this->findModel($id)->delete();
                Yii::$app->ShowFlashMessages->setMsg('Success',$name_of_degree.' Has Deleted Successfully!! ');   
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Delete ".$name_of_degree.' Technical Error ');   
            }
            
        }
        else
        {
            $SubjectsMapping = SubjectsMapping::findOne(['batch_mapping_id'=>$batch_mapping->coe_bat_deg_reg_id]); 
            $StudentMapping = StudentMapping::findOne(['course_batch_mapping_id'=>$batch_mapping->coe_bat_deg_reg_id]);
            if(empty($SubjectsMapping) && empty($StudentMapping))
            {   
                
                $batch_mapping_del = CoeBatDegReg::deleteAll(['coe_batch_id'=>$id]);                
                if($batch_mapping_del)
                {
                    $regulation = Regulation::findOne(['coe_batch_id'=>$id]);
                    $regulation_del = Regulation::deleteAll(['coe_batch_id'=>$id]);

                    if($regulation_del)
                    {
                        $this->findModel($id)->delete();
                        Yii::$app->ShowFlashMessages->setMsg('Success',$name_of_degree.' Has Deleted Successfully!! ');   
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Delete ".$name_of_degree.' Technical Error ');   
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error','Error while deleting record of '.$name_of_degree);
                }
                
            }
            else
            {
               
                Yii::$app->ShowFlashMessages->setMsg('Error',' You can not delete this <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'</b> Because already <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)."</b> are Assigned OR <b>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)."</b> are Available");
                
            }
            
        }
        
        return $this->redirect(['index']);
    }

   
    protected function findModel($id)
    {
        if (($model = Batch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionViewtable()
    {
        $degree = new Degree();
        $programme = new Programme();
        $degree_list = Degree::find()->all(); 
        $programme_list = Programme::find()->all();    
        return $programme_list;
    }
    public function actionViewWithdraw()
    {
        $model = new MarkEntry();
        $student = new Student();
        $subject = new SubjectsMapping();       
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to View Withdraw Entry');
        return $this->render('view-withdraw', ['model' => $model, 'student' => $student, 'subject' => $subject]);
    }
    public function actionRevalReport()
    {
        $model = new MarkEntry();
        $student = new Student();
        $subject = new SubjectsMapping();       
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Revaluation Report');
        return $this->render('reval-report', ['model' => $model, 'student' => $student, 'subject' => $subject]);
    }
    public function actionCourseMarksInfo()
    {
        $model = new MarkEntry();
        $student = new Student();
        $subject = new SubjectsMapping();       
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'</b> Marks Info');
        return $this->render('course-marks-info', ['model' => $model, 'student' => $student, 'subject' => $subject]);
    }
    public function actionCiaNotZero()
    {
        $model = new MarkEntry();
        $student = new Student();
        $subject = new SubjectsMapping();  
        return $this->render('cia-not-zero', ['model' => $model, 'student' => $student, 'subject' => $subject]);
    }
    public function actionRevalPrintPdf()
    {
      require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['reval_report'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' =>"REVAL RESULTS.pdf",                
                'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 14px; }td,th{border:1px solid #999; padding: 4px;}
                    }   
                ', 
                'options' => ['title' =>"REVAL RESULTS"],
                'methods' => [
                    'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                    'SetFooter' => ["REVAL RESULTS " . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();      
    }
    public function actionRevalPrintExcel()
    {
        $content = $_SESSION['reval_report'];
        $fileName = "Reval Print" . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionSubMinMaxInfoPdf(){
      require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['sub_max_min_info'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' =>strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." MIN MAX.pdf",                
                'format' => Pdf::FORMAT_A4,                
                'orientation' => Pdf::ORIENT_POTRAIT,                
                'destination' => Pdf::DEST_BROWSER,                
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 14px; }td,th{border:1px solid #999; padding: 4px;}
                    }   
                ', 
               
                'options' => ['title' =>strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." MIN MAX "],
                'methods' => [
                    'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                    'SetFooter' => [strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." MIN MAX " . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();      
    }
    public function actionExcelSubMinMaxInfoPdf()
    {
        $content = $_SESSION['sub_max_min_info'];
        $fileName = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." MIN MAX " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

    public function actionGetCiaNotZeroPdf(){
      require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['get_count_not_zero'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' =>"CIA NOT ZERO LIST.pdf",                
                'format' => Pdf::FORMAT_A4,                
                'orientation' => Pdf::ORIENT_POTRAIT,                
                'destination' => Pdf::DEST_BROWSER,                
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 14px; }td,th{border:1px solid #999; padding: 4px;}
                    }   
                ', 
               
                'options' => ['title' =>"CIA NOT ZERO LIST"],
                'methods' => [
                    'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                    'SetFooter' => ["CIA NOT ZERO LIST" . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();      
    }
    public function actionGetCiaNotZeroExcel()
    {
        $content = $_SESSION['get_count_not_zero'];
        $fileName = "CIA NOT ZERO LIST " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

}