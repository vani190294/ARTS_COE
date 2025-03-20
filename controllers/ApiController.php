<?php 
namespace app\controllers;
use yii\rest\ActiveController;
class ApiController extends ActiveController
{
    public $modelClass = 'models\Lease';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter' ] = [
              'class' => \yii\filters\Cors::className(),
        ];
        // here’s where I add behavior (read below)
        return $behaviors;
    }
}
?>