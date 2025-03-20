<?php

namespace app\controllers;

use Yii;
use app\models\AdditionalCourseRejoin;
use app\models\AdditionalCourseRejoinSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AdditionalCourseRejoinList;
/**
 * AdditionalCourseRejoinController implements the CRUD actions for AdditionalCourseRejoin model.
 */
class AdditionalCourseRejoinController extends Controller
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
     * Lists all AdditionalCourseRejoin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdditionalCourseRejoinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdditionalCourseRejoin model.
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
     * Creates a new AdditionalCourseRejoin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdditionalCourseRejoin();

        if (Yii::$app->request->post()) 
        {

            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['AdditionalCourseRejoin']['coe_regulation_id'];
            $semester=$_POST['AdditionalCourseRejoin']['semester'];
            $subject_code=$_POST['rejoin_subject_code'];
            $register_number=$_POST['rejoin_register_number'];
            $student_status=$_POST['AdditionalCourseRejoin']['student_status'];
            $batch_map_id=$_POST['batch_map_id'];

            //print_r($register_number); exit;

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

            $Success=0;
            for ($s=0; $s <count($register_number) ; $s++) 
            { 
                $checksubjectsall = Yii::$app->db->createCommand("SELECT * FROM cur_additional_course_rejoin WHERE batch_map_id='".$batch_map_id."' AND semester='".$semester."' AND register_number='".$register_number[$s]."'")->queryAll();

                if(empty($checksubjectsall) && $batch_map_id!='')
                {
                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId(); 

                    $model = new AdditionalCourseRejoin();      
                    $model->batch_map_id=$batch_map_id; 
                    $model->coe_dept_id=$coe_dept_id;
                    $model->degree_type='UG';
                    $model->coe_batch_id=$coe_batch_id; 
                    $model->coe_regulation_id=$coe_regulation_id;
                    $model->semester=$semester;
                    $model->student_status=$student_status;
                    $model->register_number=$register_number[$s];
                    $model->subject_code=implode(",", $subject_code);
                    $model->created_at=$created_at;
                    $model->created_by=$userid;

                    if($model->save(false))
                    {
                        $cur_acrj_id=$model->cur_acrj_id;
                        for ($i=0; $i <count($subject_code) ; $i++) 
                        { 
                            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM coe_subjects WHERE subject_code='".$subject_code[$i]."'")->queryScalar();

                            $model1 = new AdditionalCourseRejoinList();  
                            $model1->cur_acrj_id=$cur_acrj_id;     
                            $model1->batch_map_id=$batch_map_id; 
                            $model1->coe_dept_id=$coe_dept_id;
                            $model1->degree_type='UG';
                            $model1->semester=$semester;
                            $model1->student_status=$student_status;
                            $model1->coe_batch_id=$coe_batch_id; 
                            $model1->coe_regulation_id=$coe_regulation_id;
                            $model1->register_number=$register_number[$s];
                            $model1->semester=$semester;
                            $model1->subject_code=$subject_code[$i];
                            $model1->subject_name=$subject_name;
                            $model1->created_at=$created_at;
                            $model1->created_by=$userid;
                            if($model1->save(false))
                            {
                                $Success++;
                            }
                        }                        
                       
                    }

                }
            }

            if($Success>0)
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Additional Course Rejoin Registration Inserted Successfully..");
                        return $this->redirect(['index']);           
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Inserted or Insert Error! Please Check");
                return $this->redirect(['create']);
            }

            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdditionalCourseRejoin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cur_acrj_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdditionalCourseRejoin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {

        Yii::$app->db->createCommand('DELETE FROM cur_additional_course_rejoin_list WHERE cur_acrj_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_additional_course_rejoin WHERE cur_acrj_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        

        return $this->redirect(['index']);
    }

    public function actionApprove($id)
    {
        $updated_at = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();

        $updated=Yii::$app->db->createCommand('UPDATE cur_additional_course_rejoin_list SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_acrj_id="' . $id . '"')->execute();

        $updated1= Yii::$app->db->createCommand('UPDATE cur_additional_course_rejoin SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_acrj_id="' . $id . '"')->execute();

        Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the AdditionalCourseRejoin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdditionalCourseRejoin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdditionalCourseRejoin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
