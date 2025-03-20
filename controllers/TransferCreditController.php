<?php

namespace app\controllers;

use Yii;
use app\models\CoeTransferCredit;
use app\models\CoeTransferCreditSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Subjects;
use app\models\SubjectsMapping;
use yii\db\Query;
use app\models\Student;
use app\models\StudentMapping;
/**
 * TransferCreditController implements the CRUD actions for CoeTransferCredit model.
 */
class TransferCreditController extends Controller
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
     * Lists all CoeTransferCredit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoeTransferCreditSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeTransferCredit model.
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
     * Creates a new CoeTransferCredit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoeTransferCredit();

        $student = new Student();
        if ($model->load(Yii::$app->request->post())) 
        {
            if(!empty($_POST['completed_subs']) && !empty($_POST['elec_sel_wai']))
            {         
                $stu_reg_num= $_POST['stu_reg_num'];
                $completed_subs = $_POST['completed_subs'];
                $status = 0;                
                $stu_id = $_POST['stu_id'];
                $get_sinfo = 0;
                $stuInfo = StudentMapping::findOne($_POST['stu_id']);
                $year = $model->year;
                $month = $model->month;
                $elec_sel_wai = $_POST['elec_sel_wai'];
                $newModel = new CoeTransferCredit();
                for ($i=0; $i <count($elec_sel_wai) ; $i++) 
                { 
                    if(isset($_POST['elect_wwa_'.$i]) && $_POST['elect_wwa_'.$i]=='on')
                    {
                        
                        $getSubMap = SubjectsMapping::findOne($_POST['elec_sel_wai'][$i]);
                        $coe_tc= CoeTransferCredit::find()->where(['student_map_id'=>$stu_id])->one();
                        if(empty($coe_tc))
                        {
                            //echo $completed_subs[$i]; exit;
                            $newModel = new CoeTransferCredit();
                            $newModel->student_map_id = $stu_id;
                            $newModel->removed_sub_map_id = $_POST['elec_sel_wai'][$i];
                            $newModel->subject_map_id = $completed_subs[$i];
                            $newModel->year = $year;
                            $newModel->month = $month;
                            $newModel->created_at = new \yii\db\Expression('NOW()');
                            $newModel->created_by = Yii::$app->user->getId();
                            $newModel->updated_at = new \yii\db\Expression('NOW()');
                            $newModel->updated_by = Yii::$app->user->getId();
                            
                            if(!empty($getSubMap) && $newModel->save(false))
                            {
                                $coe_student_id = $stuInfo->student_rel_id;  
                                $getSubMap1 = SubjectsMapping::findOne($completed_subs[$i]);

                                //$query = "DELETE FROM coe_nominal WHERE coe_student_id='".$coe_student_id."' AND coe_subjects_id='".$getSubMap->subject_id."' AND semester='".$_POST['seme']."' and course_batch_mapping_id='".$getSubMap->batch_mapping_id."' and section_name='".$stuInfo->section_name."'";

                                $query = "update coe_nominal SET coe_subjects_id='".$getSubMap1->subject_id."' WHERE coe_student_id='".$coe_student_id."' AND coe_subjects_id='".$getSubMap->subject_id."' AND semester='".$_POST['seme']."' and course_batch_mapping_id='".$getSubMap->batch_mapping_id."' and section_name='".$stuInfo->section_name."' ";//exit;
                                $delNominal = Yii::$app->db->createCommand($query)->execute();//exit;
                                if($delNominal)
                                {
                                    Yii::$app->ShowFlashMessages->setMsg('Success','Nominal Deleted Successfully!!!');
                                }
                                unset($newModel);
                            }
                        }
                        else
                        {
                            $newModel = CoeTransferCredit::find()->where(['student_map_id'=>$stu_id,'subject_map_id'=>$completed_subs[$i],'year'=>$year,'month'=>$month])->one();
                            if(!empty($newModel))
                            {
                                $newModel->student_map_id = $stu_id;
                                $newModel->removed_sub_map_id = $_POST['elec_sel_wai'][$i];
                                $newModel->subject_map_id = $completed_subs[$i];
                                $newModel->year = $year;
                                $newModel->month = $month;
                                $newModel->created_at = new \yii\db\Expression('NOW()');
                                $newModel->created_by = Yii::$app->user->getId();
                                $newModel->updated_at = new \yii\db\Expression('NOW()');
                                $newModel->updated_by = Yii::$app->user->getId();
                                
                                if(!empty($getSubMap) && $newModel->save(false))
                                {
                                    $coe_student_id = $stuInfo->student_rel_id;  
                                    $getSubMap1 = SubjectsMapping::findOne($completed_subs[$i]);

                                    $query = "update coe_nominal SET coe_subjects_id='".$getSubMap1->subject_id."' WHERE coe_student_id='".$coe_student_id."' AND coe_subjects_id='".$getSubMap->subject_id."' AND semester='".$_POST['seme']."' and course_batch_mapping_id='".$getSubMap->batch_mapping_id."' and section_name='".$stuInfo->section_name."' ";//exit;
                                    $delNominal = Yii::$app->db->createCommand($query)->execute();//exit;
                                    if($delNominal)
                                    {
                                        Yii::$app->ShowFlashMessages->setMsg('Success','Nominal Deleted Successfully!!!');
                                    }
                                    unset($newModel);
                                }
                                else
                                {
                                    Yii::$app->ShowFlashMessages->setMsg('Success','Someting Error! Please Check');
                                }
                            }
                        }   
                        
                    }
                }

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','OOPS.. Nothing Happens1');
                return $this->redirect(['create']);
            }
             //Yii::$app->ShowFlashMessages->setMsg('Error','OOPS.. Nothing Happens2');
            return $this->redirect(['create']);
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'student' =>$student,
            ]);
        }
    }

    /**
     * Updates an existing CoeTransferCredit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_tc_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CoeTransferCredit model.
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
     * Finds the CoeTransferCredit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeTransferCredit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoeTransferCredit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
