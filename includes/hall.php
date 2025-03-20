<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\FacultyHall;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'UNIQUE ID','B'=>'DEPARTMENT ','C'=>'NAME','D'=>'DESIGNATION','E'=>'FACULTY MODE','F'=>'EXPRIENCE','G'=>'PHONE','H'=>'COLLEGE NAME','I'=>'BANK ACC NO','J'=>'BANK NAME','K'=>'BANK BRANCH','L'=>'BANK IFSC','M'=>'EMAIL','N'=>'SLOT'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G'],'H'=>$line['H'],'I'=>$line['I'],'J'=>$line['J'],'K'=>$line['K'],'L'=>$line['L'],'M'=>$line['M'],'N'=>$line['N']];
 
 //print_r( $exam_columns);exit;
    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,', ');
        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Template </b> Please use the Original Sample Template from the Download Link!!");
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
foreach($sheetData as $k => $line)
{    
    $line = array_map('trim', $line);
    $model = new FacultyHall();
    $Unique = $line['A'];
    $department  =  $line['B'];
    $Name=$line['C'];
    $Designation=$line['D'];
    $facultymode=$line['E'];
    
   if(!empty($Unique) && !empty($department) && !in_array(null, $line, true))
    {      
         $inserted_res = 0;
         //print_r($line['F']);exit;

        if(isset($line['A']) && isset($line['B']) && isset($line['C'])  && isset($line['D']))
            {
                $model->uniqueid =$line['A'];
                $model->department = $line['B'];
                $model->name = $line['C'];
                $model->designation = $line['D'];
                $model->facultymode = $line['E'];
                $model->experience=$line['F'];
                $model->phone=$line['G'];
                $model->collegename=$line['H'];
                $model->bankaccno=$line['I'];
                $model->bankname=$line['J'];
                $model->bankbranch=$line['K'];
                $model->bankifsc=$line['L'];
                $model->email=$line['M'];
                $model->slot=$line['N'];
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