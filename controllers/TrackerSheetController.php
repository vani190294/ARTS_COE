<?php

namespace app\controllers;

use Yii;
use app\models\TrackerSheet;
use app\models\TrackerSheetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * CoeTrackerSheetController implements the CRUD actions for CoeTrackerSheet model.
 */
class TrackerSheetController extends Controller
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
     * Lists all CoeTrackerSheet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrackerSheetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeTrackerSheet model.
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
     * Creates a new CoeTrackerSheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
 
public function actionCreate()
 {
     $model = new TrackerSheet();           

     if ($model->load(Yii::$app->request->post())) 
     {
        $date = date('Y-m-d', strtotime(str_replace('-','/', $_POST['date']))); 
        $model->date = $date;
        $model->updated_by = Yii::$app->user->getId();
        $model->created_by = Yii::$app->user->getId();
        $status_check = 0;
        
        if( $model->save(false))
        {
             return $this->redirect(['view', 'id' => $model->coe_ts_id]);   
        
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','UNABLE TO INSERT DETAILS');
        }

      } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }   
    } 

    /**
     * Updates an existing CoeTrackerSheet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

public function actionUpdate($id)
 {
    $model = $this->findModel($id);   
    
    if ($model->load(Yii::$app->request->post())) 
    {
        $date = date('Y-m-d', strtotime(str_replace('-','/', $_POST['date']))); 
        $model->date = $date;
        $model->attributes = $_POST['TrackerSheet'];
        $model->coe_ts_id =$model->coe_ts_id;
       
        if($model->save(false))
        {
            
            Yii::$app->ShowFlashMessages->setMsg('SUCCESS','SUCCESSFULLY UPDATED');
            return $this->redirect([
                'view', 'id' => $model->coe_ts_id]);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Update the  Details');

             return $this->render('update', [
            'model' => $model,
            
            ]);
        }     
   
    
    } 
    else 
    {
        return $this->render('update', [
            'model' => $model,
            
      ]);      
    }
}

    /**
     * Deletes an existing CoeTrackerSheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
  /*  public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/
    public function actionDeleteTask($id)
    {
        $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        if($checkAccess=='Yes')
        {
            $model = $this->findModel($id);
            $this->findModel($id)->delete();
            Yii::$app->ShowFlashMessages->setMsg('Success','Record Deleted Successfully!!!');
            return $this->redirect(['index']);
        }
        else
        {
            $lockUser = Yii::$app->db->createCommand('UPDATE user SET status="12" WHERE id="'.Yii::$app->user->getId().'"')->execute();
            $created_by = $updated_by = Yii::$app->user->getId();
            $created_at = $updated_at = date("Y-m-d H:i:s");
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $filename =  Yii::getAlias("@webroot").'/access_logs/log_'.date("j.n.Y").'.txt';
            $content  = "User Name: ".Yii::$app->user->getUsername().' - '.date("F j, Y, g:i a").PHP_EOL.
                        "Accessed URLS: ".$url.PHP_EOL.
                        "----------------------------------------------------------------".PHP_EOL;

            //print_r(parse_url($url)); // This will returns the parts of the URL
            
            $removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
            $image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).parse_url($url, PHP_URL_PORT).$removed_path.'images/notfound.png'; 
            
            $image_path = Yii::getAlias("@web").'/images/notfound.png'; 

            if(file_put_contents($filename, $content.PHP_EOL , FILE_APPEND | LOCK_EX))
            {   
                $file_content = file_get_contents($filename, true);
                echo "<div style='width:1000px;  text-align: center; margin: 0 auto;'><img src='".$image_path."' alt='not found' height='600' width='900' align='center' /></div>";
                
            }
            unset($_SESSION);
            session_destroy();
            Yii::$app->ShowFlashMessages->setMsg('Error','OOOPS You are not allowed!!! Your Account is Locked!!!');
            return $this->redirect(['site/index']);            
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the CoeTrackerSheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeTrackerSheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrackerSheet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
