<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;

// Using the required models to get the data

use app\models\Batch;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\Regulation;

/**
 * This is the model class for table "coe_degree".
 *
 * @property integer $coe_degree_id
 * @property string $degree_code
 * @property string $degree_name
 * @property string $degree_type
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Import extends \yii\db\ActiveRecord
{
    
    public $importFileName,$file_name;
    
    public function rules()
    {
        return [
            [['importFileName','file_name'], 'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
        'importFileName' => 'Import File',
        'file_name' => 'File Name',
       
        ];
    }

    public function getExcelproperties($fileName)
    {
        
        $objReader = PHPExcel_IOFactory::createReaderForFile($fileName);
        $objReader->setLoadSheetsOnly(array(0));
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($fileName);
        $highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataColumn();
        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $getData = $objPHPExcel->setActiveSheetIndex(0)->toArray();
        unset($sheetData[1]); // Removing the headers         
        return $sheetData;         
    }


    public function studentImport($fileLocation)
    {

    }
    public function subjectImport($fileLocation)
    {
       
       $sheetData = $this->getExcelproperties($fileLocation); 
       
       if(!empty($sheetData))
       {
            $dispResults = []; 
            $totalSuccess = 0;
            $importResults = [];
            $created_by = Yii::$app->user->getId();
            $created_at = new \yii\db\Expression(date('Y-m-d'));
            
            foreach($sheetData as $k => $line)
            {    
                $line = array_map('trim', $line);                
                $line = array_filter(array_map(function($value) { return empty($value) ? NULL : $value; }, $line)); 

                $batch = Batch::findOne(['batch_name'=>$line['B']]);

                $regulation = Regulation::findOne(['regulation_year'=>$line['A']]);
                $programme = Programme::findOne(['programme_code'=>$line['C']]);
                $subjects = new Subjects();
                
                if(!empty($batch) && !empty($regulation) && !empty($programme) && !in_array(null, $line, true))
                {   
                    $subjects->subject_code = $line['D'];
                    $subjects->subject_name = $line['F'];
                    $subjects->semester = $line['E'];
                    $subjects->subject_fee = $line['S'];
                    $subjects->CIA_min = $line['I'];
                    $subjects->CIA_max = $line['J'];
                    $subjects->ESE_min = $line['K'];
                    $subjects->ESE_max = $line['L'];
                    $subjects->total_minimum_pass = $line['M'];
                    $subjects->credit_points = $line['O'];
                    $subjects->end_semester_exam_value_mark = ($line['L']+$line['J'])==$line['R']?$line['R']: ($line['L']+$line['J']);
                    $subjects->created_by = $created_by;
                    $subjects->updated_by = $created_by;
                    $subjects->created_at = $created_at;
                    $subjects->updated_at = $created_at;
                    $transaction = Yii::$app->db->beginTransaction();
                   try
                   {
                     if($subjects->save(false))
                       {                         
                         $transaction->commit();
                         $subject_mapping = new SubjectsMapping();
                         $categorytype = new Categorytype();
                         $batchMapping = BatDegReg::find()->where(['coe_programme_id'=>$programme->coe_programme_id,'coe_batch_id'=>$batch->coe_batch_id])->all();
                         $paper_type = Categorytype::find()->where(['category_type'=>$line['H']])->all();
                         $subject_type = Categorytype::find()->where(['category_type'=>$line['P']])->all();
                         $programme_type = Categorytype::find()->where(['category_type'=>$line['Q']])->all();
                         $subject_mapping->batch_mapping_id = $batchMapping->coe_bat_deg_reg_id;
                         $subject_mapping->subject_id = $subjects->coe_subject_id;                         
                         $subject_mapping->paper_no = $line['G'];
                         $subject_mapping->paper_type_id = $paper_type->coe_category_type_id;
                         $subject_mapping->subject_type_id = $subject_type->coe_category_type_id;
                         $subject_mapping->course_type_id = $programme_type->coe_category_type_id;
                         $subject_mapping->created_by = $created_by;
                         $subject_mapping->updated_by = $created_by;
                         $subject_mapping->created_at = $created_at;
                         $subject_mapping->updated_at = $created_at;
                         if($subjects->save(false)){ 
                            $transaction->commit();
                         }
                         else{ 
                            $transaction->rollback(); 
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'A', 'message' => 'Success']);
                         }
                         
                       }
                       else{ 
                                $Subjects = Subjects::findOne(['subject_code'=>$line['D']]);
                                $subject_id = $subjects->subject_id;
                                if(!empty($subject_id))
                                {
                                    $subject_mapping = new SubjectsMapping();
                                     $categorytype = new Categorytype();
                                     $batchMapping = BatDegReg::find()->where(['coe_programme_id'=>$programme->coe_programme_id,'coe_batch_id'=>$batch->coe_batch_id])->all();
                                     $paper_type = Categorytype::find()->where(['category_type'=>$line['H']])->all();
                                     $subject_type = Categorytype::find()->where(['category_type'=>$line['P']])->all();
                                     $programme_type = Categorytype::find()->where(['category_type'=>$line['Q']])->all();

                                     $subject_mapping->batch_mapping_id = $batchMapping->coe_bat_deg_reg_id;
                                     $subject_mapping->subject_id = $subjects->coe_subject_id;
                                     $subject_mapping->paper_type_id = $paper_type->coe_category_type_id;
                                     $subject_mapping->subject_type_id = $subject_type->coe_category_type_id;
                                     $subject_mapping->course_type_id = $programme_type->coe_category_type_id;
                                     $subject_mapping->paper_no = $line['G'];
                                     $subject_mapping->created_by = $created_by;
                                     $subject_mapping->updated_by = $created_by;
                                     $subject_mapping->created_at = $created_at;
                                     $subject_mapping->updated_at = $created_at;
                                     
                                     if($subjects->save(false)){ 
                                        $transaction->commit();
                                     }
                                     else{ 
                                        $transaction->rollback(); 
                                        $totalSuccess+=1;
                                        $dispResults[] = array_merge($line, ['type' => 'A', 'message' => 'Success']);
                                     }
                                 } else
                                 {
                                    $transaction->rollback();         
                                 }
                            
                       }                      
                   }
                   catch(\Exception $e)
                   {
                         $transaction->rollBack();
                         $dispResults[] = array_merge($line, ['type' => 'E', 'message' => $e->getMessage()]);
                   } 
                }
                else
                {
                    $dispResults[] = array_merge($line, 
                        ['type' => 'F', 'message' => 'Data submision is worng']);
                    Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
                }
                       
           } // Foreach Ends Here  
          /* $response = Yii::$app->response;
           $response->format= \yii\web\Response::FORMAT_JSON;
           $response->data = ['dispResults' => $dispResults,'totalSuccess'=>$totalSuccess];
           Yii::$app->response->redirect(Url::to(['import/index','response' => $response]));*/
           Yii::$app->controller->renderPartial('index');
           /*return $model->render([ 'index',
                'dispResults' => $dispResults, 
                'totalSuccess' => $totalSuccess
            ]);*/
            
       } // Not Empty of Sheet Ends Here 
       else {
                Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Resolve your Submission.");
                return Yii::$app->response->redirect(Url::to(['import/index']));
       }      
       

    } // Subject Insertion Function Ends here 
    public function examImport($fileLocation)
    {
        
    }


   
}
