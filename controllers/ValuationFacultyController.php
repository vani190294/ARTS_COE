<?php

namespace app\controllers;

use Yii;
use app\models\ValuationFaculty;
use app\models\ValuationFacultySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\Categorytype;
use yii\db\Query;
use app\models\Signup;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * ValuationFacultyController implements the CRUD actions for ValuationFaculty model.
 */
class ValuationFacultyController extends Controller
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
     * Lists all ValuationFaculty models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ValuationFacultySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ValuationFaculty model.
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
     * Creates a new ValuationFaculty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ValuationFaculty();

        if(Yii::$app->request->post()) 
        {
            $email=trim($_POST['ValuationFaculty']['email']," ");
            $phone_no=trim($_POST['ValuationFaculty']['phone_no']," ");

            $model = new ValuationFaculty();
            $model->faculty_name = $_POST['ValuationFaculty']['faculty_name'];
            $model->faculty_designation =$_POST['faculty_designation'];
            $model->faculty_board = $_POST['faculty_board'];
            $model->faculty_mode =  $_POST['faculty_mode'];
            $model->faculty_experience =  $_POST['ValuationFaculty']['faculty_experience'];
            if($_POST['ValuationFaculty']['bank_accno']!='')
            {
                $model->bank_accno =  $_POST['ValuationFaculty']['bank_accno'];
                $model->bank_name =  $_POST['ValuationFaculty']['bank_name'];
                $model->bank_branch =  $_POST['ValuationFaculty']['bank_branch'];
                $model->bank_ifsc =  $_POST['ValuationFaculty']['bank_ifsc'];
            }
            
            $model->phone_no = $phone_no;
            $model->email = $email;
            $model->out_session = $_POST['out_session'];
            $model->college_code = $_POST['ValuationFaculty']['college_code'];
            $model->created_by = Yii::$app->user->getId();
            $model->created_at = new \yii\db\Expression('NOW()');

            if($model->save())
            {
               
                $check_user =Yii::$app->db->createCommand('SELECT * FROM user WHERE email="'.$email.'"')->queryOne();
                if(empty($check_user))
                {
                    $userModel = new Signup();
                    $userModel->username = $email;
                    $userModel->password = $phone_no;
                    $userModel->ConfirmPassword = $phone_no;
                    $userModel->email = $email;
                    $userModel->signup();
                    $created = strtotime(ConfigUtilities::getCreatedTime());
                    $user_id_LAST = Yii::$app->db->createCommand('SELECT id FROM user  WHERE email="'.$email.'"')->queryScalar();
                    $Assing = Yii::$app->db->createCommand('INSERT INTO auth_assignment (`item_name`,`user_id`,`created_at`) values ("ValuatorAccess","'.$user_id_LAST.'","'.$created.'" )')->execute();

                    if($Assing)
                    {
                         Yii::$app->ShowFlashMessages->setMsg('SUCCESS', "SUCCESS INSERTED AND USER ID CREATED");
                        
                    }
                    else
                    {
                         Yii::$app->ShowFlashMessages->setMsg('Error', "Access Rights issue please check");
                    }
                }
                else
                {       
                     Yii::$app->ShowFlashMessages->setMsg('WARNING', "SUCCESS INSERTED but User already exist! Unable to Create User");
                } 
                return $this->redirect(['view', 'id' => $model->coe_val_faculty_id]);
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Error', "Valuation Faculty Not Created Please Check");
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ValuationFaculty model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
     public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()) 
        {

            $model->faculty_name = $_POST['ValuationFaculty']['faculty_name'];
            $model->faculty_designation =$_POST['faculty_designation'];
            $model->faculty_board = $_POST['faculty_board'];
            $model->faculty_mode =  $_POST['faculty_mode'];
            $model->faculty_experience =  $_POST['ValuationFaculty']['faculty_experience'];
            if($_POST['ValuationFaculty']['bank_accno']!='')
            {
                $model->bank_accno =  $_POST['ValuationFaculty']['bank_accno'];
                $model->bank_name =  $_POST['ValuationFaculty']['bank_name'];
                $model->bank_branch =  $_POST['ValuationFaculty']['bank_branch'];
                $model->bank_ifsc =  $_POST['ValuationFaculty']['bank_ifsc'];
            }
            $model->out_session = $_POST['out_session'];
            $model->college_code = $_POST['ValuationFaculty']['college_code'];
            $model->updated_by = Yii::$app->user->getId();
            $model->updated_at = new \yii\db\Expression('NOW()');

            if($model->save(false))
            {
                 return $this->redirect(['view', 'id' => $model->coe_val_faculty_id]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Valuation Faculty Not Updated Please Check");
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ValuationFaculty model.
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
     * Finds the ValuationFaculty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ValuationFaculty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ValuationFaculty::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
