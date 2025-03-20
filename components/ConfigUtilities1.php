<?php
namespace app\components;
use app\models\Configuration;
use Yii;
use app\components\ConfigConstants;
use app\models\Batch;
use app\models\Degree;
use app\models\Programme;
use app\models\CoeBatDegReg;
use yii\helpers\ArrayHelper;

class ConfigUtilities
{
	
	public static function getConfigValue($name)
    {
        $config_value = Configuration::find(['config_desc'])->where(['config_name' => $name])->one();       
        return $config_value->config_value;
    }

    public function UpdateConfigValue($name,$value)
    {
        $updated = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();        
        $value = ucwords($value);


        $updateQuery = "UPDATE coe_configuration SET 
                        config_value=:value ,
                        updated_at=:updated ,
                        updated_by=:updateBy 
                        WHERE config_desc='".$name."'";
                        
        $updateCofig = Yii::$app->db->createCommand($updateQuery)
                        ->bindValue(':value', $value)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();

        return isset($updateCofig)&&$updateCofig!=""?1:0;
    }	

    public function getConfigDesc($name)
    {
        $config_value = Configuration::find(['config_desc'])->where(['config_name' => $name])->one();       
        return $config_value->config_desc;
    }

    public function UpdateBatchLocking($name,$locking_start,$locking_end)
    {
        $updated = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();  

        $updateStartDate = "UPDATE coe_configuration    
                        SET config_value =:locking_start, updated_at=:updated, updated_by=:updateBy WHERE config_name='".ConfigConstants::CONFIG_BATCH_LOCKING_START."' and config_desc like '%Batch Locking%'";
                        
        $updateEndDate = "UPDATE coe_configuration    
                        SET config_value =:locking_end, updated_at=:updated, updated_by=:updateBy WHERE config_name='".ConfigConstants::CONFIG_BATCH_LOCKING_END."' and config_desc like '%Batch Locking%'";

        $UpdateBatchLocking = Yii::$app->db->createCommand($updateStartDate)
                        ->bindValue(':locking_start', $locking_start)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();
       
        $UpdateBatchLocking = Yii::$app->db->createCommand($updateEndDate)
                        ->bindValue(':locking_end', $locking_end)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();

        return isset($UpdateBatchLocking)&&$UpdateBatchLocking!=""?1:0;
        

    }   


    /**
     * @return  the list of Batches
     */
    public function getBatchDetails()
    {
        $batch = Batch::find()->orderBy(['batch_name'=>SORT_ASC])->all();
        return  $batch_list = ArrayHelper::map($batch,'coe_batch_id','batch_name');
    }
    /**
     * @return  the list of Degrees
     */
    public function getDegreedetails()
    {
       
        $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a LEFT JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id LEFT JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id  order by a.coe_bat_deg_reg_id";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();        
        return ArrayHelper::map($degreeInfo,'coe_bat_deg_reg_id','degree_name');
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */

    public function getSectionnames()
    {
        
        $section_list = CoeBatDegReg::find()->max('no_of_section');        
        $stu_dropdown = "";
        if(!empty($section_list))
        {
            for ($char = 65; $char < 65+$section_list; $char++) {
               // $stu_dropdown .= "<option value='".chr($char)."' > ".chr($char)."</option>";
                $stu_dropdown[chr($char)]= chr($char);
            }
        }
        else
        {
             for ($char = 65; $char < 65+4; $char++) {
               // $stu_dropdown .= "<option value='".chr($char)."' > ".chr($char)."</option>";
                $stu_dropdown[chr($char)]= chr($char);
            }
        }
        
        return $stu_dropdown;
    }

}



?>