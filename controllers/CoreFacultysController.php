<?php

namespace app\controllers;

use Yii;
use app\models\CoreFacultys;
use app\models\CoreFacultysSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Regulation;
use yii\helpers\ArrayHelper;
use app\models\CoreFacultyList;
/**
 * CoreFacultysController implements the CRUD actions for CoreFacultys model.
 */
class CoreFacultysController extends Controller
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
     * Lists all CoreFacultys models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoreFacultysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoreFacultys model.
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
     * Creates a new CoreFacultys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoreFacultys();

        if (Yii::$app->request->post()) 
        {
            $degree_type=$_POST['CoreFacultys']['degree_type'];
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['CoreFacultys']['coe_regulation_id'];
            $semester=$_POST['CoreFacultys']['semester'];

            $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
            $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

            $no_of_section=Yii::$app->db->createCommand("SELECT no_of_section FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();

            $checkfaculty = Yii::$app->db->createCommand("SELECT cur_cf_id FROM  cur_core_facultys A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.semester=".$semester)->queryOne(); 

            $success=0;
            if(empty($checkfaculty))
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 

                $model = new CoreFacultys();  
                $model->coe_dept_id=$coe_dept_id;
                $model->degree_type=$degree_type;
                $model->coe_regulation_id=$coe_regulation_id;
                $model->semester=$semester;
                $model->no_of_section=$no_of_section;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                //print_r($model); exit;
                if($model->save(false))
                {
                    return $this->redirect(['core-register-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester,'id'=>$model->cur_cf_id,'sec'=>$no_of_section]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Something Insert Error! Please Check");
                    return $this->redirect(['create']);
                }

            }
            else if(!empty($checkfaculty))
            {
                return $this->redirect(['core-register-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester,'id'=>$checkfaculty['cur_cf_id'],'sec'=>$no_of_section]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Something Create Error! Please Check");
                return $this->redirect(['create']);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionCoreRegisterForm($degree_type,$coe_dept_id,$coe_regulation_id,$semester,$id,$sec)
    {
        $model = new CoreFacultys();
        $faculty_board='';

        if($coe_dept_id==9 || ($coe_dept_id>=15 && $coe_dept_id<=19))
        {
            $faculty_board='CSE/IT';
        }
        else
        {
            $faculty_board=Yii::$app->db->createCommand("SELECT dept_code FROM cur_department WHERE coe_dept_id='".$coe_dept_id."'")->queryScalar();
        }

        $int_faculty=Yii::$app->db->createCommand("SELECT coe_val_faculty_id,concat(faculty_name,' (',faculty_board,')') as faculty_name FROM coe_valuation_faculty WHERE faculty_mode='INTERNAL' AND faculty_board='".$faculty_board."'")->queryAll();

        $int_faculty=  ArrayHelper::map($int_faculty,'coe_val_faculty_id','faculty_name');

        $core_data = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_curriculum_subject A WHERE A.coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.approve_status=1 AND semester='".$semester."' AND A.subject_code NOT IN (SELECT subject_code FROM cur_core_faculty_list WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

        $corefromcore = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.approve_status=1 AND B.semester='".$semester."' AND A.subject_code NOT IN (SELECT subject_code FROM cur_core_faculty_list WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

        $corefromelective = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE A.coe_elective_option IN (200,202) AND B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.approve_status=1 AND B.semester='".$semester."' AND A.subject_code NOT IN (SELECT subject_code FROM cur_core_faculty_list WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

        $coresubject=array_merge($core_data,$corefromcore,$corefromelective);

        if (Yii::$app->request->post()) 
        {
            $sectionname=array('1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F');

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
           
            $success=0;
            foreach ($coresubject as $value) 
            {
                $faculty=$_POST[$value['subject_code']];
                $s=1;
                for ($i=0; $i <count($faculty) ; $i++) 
                { 
                    $model = new CoreFacultyList();      
                    $model->cur_cf_id=$id;
                    $model->coe_dept_id=$coe_dept_id;
                    $model->degree_type=$degree_type;
                    $model->coe_regulation_id=$coe_regulation_id;
                    $model->section=$sectionname[$s];
                    $model->semester=$semester;
                    $model->subject_code=$value['subject_code'];
                    $model->faculty_id=$faculty[$i];
                    $model->created_at=$created_at;
                    $model->created_by=$userid;

                    if($model->save(false))
                    {
                        $success++;
                    }

                    $s++;
                }

            }

            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('success', "Core Course Faculty successfully created");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Core Course Faculty Not successfully created! Please Check");
                return $this->render('core-register-form', [
                'model' => $model,
                'coresubject'=>$coresubject,
                'no_of_section'=>$sec,
                'int_faculty'=>$int_faculty
                ]);
            }
        }
        else
        {

            if(count($coresubject)==0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Not Found! Please Check");
                    return $this->redirect(['create']);
            }
            else
            {
                return $this->render('core-register-form', [
                'model' => $model,
                'coresubject'=>$coresubject,
                'no_of_section'=>$sec,
                'int_faculty'=>$int_faculty
                ]);
            }
            
        }
    }

    /**
     * Updates an existing CoreFacultys model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cur_cf_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CoreFacultys model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CoreFacultys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoreFacultys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoreFacultys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
