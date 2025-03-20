<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\StudentMapping;
use app\models\BatDegReg;
use app\models\CoeBatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\CoeAddPoints;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\CoeActivityMarks;
use yii\db\Query;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\StuInfo;
use app\models\MarkEntryMasterTemp;

$interate = 1; // Check only 1 time for Sheet Columns
foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'BATCH','B'=>'PROGRAMME','C'=>'REGISTERNUMBER','D'=>'SUBJECTCODE','E'=>'DURATION'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E']];

    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,', ');
        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original Activity Template </b> Please use the Original Sample Template from the Download Link!!");
        return Yii::$app->response->redirect(Url::to(['import/index']));
    }
    else
    {
        break;
    }
    $interate +=7;
    
}
unset($sheetData[1]);
$transaction = Yii::$app->db->beginTransaction();
$det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
foreach($sheetData as $k => $line)
{    
    $line = array_map('trim', $line);
    $model = new CoeActivityMarks();
    $batch = $line['A'];
    $programme =  $line['B'];
    $reg_num=$line['C'];
    //$section=$line['D'];
    $subject_code=$line['D'];
   if(!empty($batch) && !empty($programme) && !in_array(null, $line, true))
    {      
         $inserted_res = 0;
         $reg_num=$line['C'];
         $duration=isset($line['E'])?$this->valueReplace($line['E'], Categorytype::getCategoryId()):"";
        $student = Student::find()->where(['register_number'=>$reg_num])->one();
        $stu_mapping = StudentMapping::find()->where(['student_rel_id'=>$student->coe_student_id])->one();
        $batchMapping = CoeBatDegReg::findOne($stu_mapping->course_batch_mapping_id);
        $student_map_id = $stu_mapping->coe_student_mapping_id;
     
       // print_r( $mark);exit;
        
            if($batch && empty($reg_num))
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Already Available']);
            }
            else if(isset($line['A']) && isset($line['B']) && isset($line['C'])  && isset($line['D']))
            {
                $model->batch = $batchMapping['coe_batch_id'];
                $model->programme = $batchMapping['coe_bat_deg_reg_id'];
                $model->register_number = $student_map_id;
               // $model->section =  $section;
                $model->subject_code =  $subject_code;
                 if($duration==250)
                 {
                    $mark=10;
                 }
                else
                 {
                   $mark=20;
                 }
                $model->duration = $mark;

                $model->created_at = $created_at;
                $model->created_by = $created_by;
                $model->updated_at = $created_at;
                $model->updated_by = $created_by;
                if($model->save(false))
                {
                    
                    $totalSuccess+=1;
                    $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'No Errors Found']);    
                }
                else
                {
                    
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Save the Record Kindly remove special characters from the sheet ' ]);
                }

            }
            else
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => "Empty Values Found"]);
            }
        
         
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Oops Marks Already Available']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  

try
{
    $transaction->commit();
}
catch(\Exception $e)
{
   if($e->getCode()=='23000')
   {
       $message = "Duplicate Entry";
   }
   else if($e->getCode()=='8')
   {
       $message = "Empty Values Found";
   }
   else
   {
       $message = "Error";
   }
   $transaction->rollback(); 
   $dispResults[] = array_merge($line, ['type' => 'E',  'message' => $message]);
}


$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)];
?>