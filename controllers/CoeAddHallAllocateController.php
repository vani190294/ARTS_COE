<?php

namespace app\controllers;

use Yii;
use app\models\CoeAddHallAllocate;
use app\models\HallAllocate;
use app\models\CoeAddHallAllocateSearch;
use app\models\AbsentEntry;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\HallMaster;
use app\models\Student;
use app\models\SubjectsMapping;
use app\models\StudentMapping;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\models\Categories;
use app\models\Categorytype;
use app\models\CoeAddExamTimetable;
use app\models\Sub;
use app\models\CoeValueSubjects;

use yii\helpers\Url;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\ExamTimetable;
use app\models\AnswerPacket;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use yii\db\Query;

/**
 * CoeAddHallAllocateController implements the CRUD actions for CoeAddHallAllocate model.
 */
class CoeAddHallAllocateController extends Controller
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
     * Lists all CoeAddHallAllocate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoeAddHallAllocateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeAddHallAllocate model.
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
     * Creates a new CoeAddHallAllocate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new CoeAddHallAllocate();
        $hallmaster = new HallMaster();
       $categorytype = new Categorytype();
        $exam = new  CoeAddExamTimetable();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_add_hall_allocate_id]);
        } else {
            return $this->render('create', [
                'model' => $model,'hallmaster' =>$hallmaster,'categorytype' =>$categorytype,'exam'=>$exam
            ]);
        }
    }*/

   public function actionCreate()
  {        
      $model = new CoeAddHallAllocate();
      $hallmaster = new HallMaster();
      $categorytype = new Categorytype();
      $exam = new CoeAddExamTimetable();
      $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

      $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
    if ($model->load(Yii::$app->request->post()) && $exam->load(Yii::$app->request->post()) && $hallmaster->load(Yii::$app->request->post()) ) 
    {
      if($_POST['countTo']==$_POST['hallCount']){
        $exam_month = $_POST['CoeAddHallAllocate']['month'];
      
        $exam_year=$_POST['CoeAddHallAllocate']['year'];


        $namearr = explode("&",trim($_POST['hallName'],"&"));

        if($_POST['arrangement_type'] == 'Non-Subject Wise')
        {     
         
          $query_1 = new Query();
          $exam_date =  date("Y-m-d",strtotime($exam->exam_date));
          $sub_name_check = $query_1->select("a.subject_mapping_id,a.exam_type,b.description")
            ->from('coe_add_exam_timetable a')
            ->join('JOIN','coe_category_type b','a.exam_type=b.coe_category_type_id')
            ->where(['exam_date' =>$exam_date,'exam_month'=>$exam_month,'exam_session'=>$exam->exam_session])->createCommand()->queryAll(); 

            $sub_stu = [];
            $arr_sub_stu = [];
            $total_sub_map_id = 0;
            foreach($sub_name_check as $subs)
            {
              $total_sub_map_id += count($subs['subject_mapping_id']);
            if($subs['description']!="Arrear")
            {
              
                $query_2 = new Query();
                $subject_type = $query_2->select("a.description")
                  ->from('coe_category_type a')
                  ->join('JOIN','sub b','a.coe_category_type_id=b.subject_type_id')
                  ->where(['coe_sub_mapping_id' =>$subs['subject_mapping_id']])->createCommand()->queryScalar(); 

                if($subject_type!='Elective'){

                  $query_3 = new Query();
                  $subject_student = $query_3->select("DISTINCT (a.student_rel_id) as stu_id,c.coe_add_exam_timetable_id,f.register_number,c.qp_code")
                    ->from('coe_student_mapping a')
                    ->join('JOIN','coe_student f','f.coe_student_id=a.student_rel_id')
                    ->join('JOIN','sub b','a.course_batch_mapping_id=b.batch_mapping_id')
                    ->join('JOIN','coe_add_exam_timetable c','c.subject_mapping_id=b.coe_sub_mapping_id')
                    ->where(['c.subject_mapping_id' =>$subs['subject_mapping_id'],'b.coe_sub_mapping_id' =>$subs['subject_mapping_id'],'f.student_status'=>'Active','c.exam_date'=>$exam_date,'c.exam_month'=>$exam_month,'c.exam_session'=>$exam->exam_session])
                    ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->orderBy('register_number ASC')
                    ->createCommand()->queryAll(); 
                array_multisort(array_column($subject_student, 'register_number'),  SORT_ASC, $subject_student);
                  array_push($sub_stu, $subject_student);
                }else{

                  $query_3 = new Query();
                  $sub_id = $query_3->select("val_subject_id")
                    ->from('sub')
                    ->where(['coe_sub_mapping_id' =>$subs['subject_mapping_id']])->createCommand()->queryScalar();
                  $getSemester = Sub::findOne($subs['subject_mapping_id']);
                  $query_4 = new Query();
                   $query_4->select("DISTINCT(f.coe_student_id) as stu_id,d.coe_add_exam_timetable_id,f.register_number,d.qp_code")
                  ->from('coe_value_nominal a')                    
              ->join('JOIN','coe_student f','f.coe_student_id=a.coe_student_id')
                    ->join('JOIN','coe_student_mapping abcd','abcd.student_rel_id=f.coe_student_id and abcd.course_batch_mapping_id=a.course_batch_mapping_id')
                    ->join('JOIN','coe_value_subjects b','b.coe_val_sub_id=a.coe_subjects_id')
                    ->join('JOIN','sub c','c.val_subject_id=b.coe_val_sub_id')
                    //->join('JOIN','coe_exam_timetable d','d.subject_mapping_id=c.coe_subjects_mapping_id')
                    ->join('JOIN','coe_add_exam_timetable d','d.subject_mapping_id=c.coe_sub_mapping_id 
                        and c.batch_mapping_id=a.course_batch_mapping_id 
                        and c.val_subject_id=a.coe_subjects_id')
                      ///->where(['a.coe_subjects_id' =>$sub_id])->createCommand()->queryAll();
                      ->where(['d.subject_mapping_id' =>$subs['subject_mapping_id'],'a.semester'=>$getSemester->semester,'c.semester'=>$getSemester->semester,'c.coe_sub_mapping_id' =>$subs['subject_mapping_id'],'f.student_status'=>'Active','d.exam_date'=>$exam_date,'d.exam_month'=>$exam_month,'d.exam_session'=>$exam->exam_session])
                      ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                      ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                      ->orderBy('register_number ASC');
                      $query_4 ->createCommand()->getrawsql();
                    $subject_student = $query_4 ->createCommand()->queryAll();
                    //->where(['a.coe_subjects_id' =>$sub_id])->createCommand()->queryAll();
                      array_multisort(array_column($subject_student, 'register_number'),  SORT_ASC, $subject_student);
                  array_push($sub_stu, $subject_student);
                }           
                  $inarrr[]=$sub_name_check;
              }     
            }//sub_name_check foreach   
    
           // print_r($sub_stu); exit;

            //Regular Students

            $mer= array_reduce($sub_stu, 'array_merge', array());
           
             
              array_multisort(array_column($mer, 'register_number'),  SORT_ASC, $mer);
                $total_merge_student=$mer;
                $total_stu_division=count($total_merge_student)/2; // 200/2 =100           
                $count_merge_student=count($total_merge_student); //=200
                $jumbling_student=array_slice($total_merge_student,0,$total_stu_division);
                $jumbling_remaining_student=array_slice($total_merge_student,$total_stu_division,$count_merge_student);
                
                 if($_POST['seat_arrr'] == 'Straight Arrangement'){

                  for($s=0;$s<$total_stu_division;$s++)
                  {
                    if(isset($jumbling_student[$s]))
                    {
                      $z[]=$jumbling_student[$s];
                    }
                    if(isset($jumbling_remaining_student[$s]))
                    {
                      $z[]=$jumbling_remaining_student[$s];
                    } 
                  }
                }else{
                  $config_seat = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);
                  $count = $config_seat;
                  $value = 1;
                  $check_val=$config_seat/2;
                  for($s=0;$s<$total_stu_division;$s++){
                    //echo $s%$config_seat."<br />";
                    if($s%$config_seat==$check_val) 
                    {
                        if(isset($jumbling_remaining_student[$s]))
                        {
                          $z[]=$jumbling_remaining_student[$s];
                        } 
                        if(isset($jumbling_student[$s]))
                        {
                          $z[]=$jumbling_student[$s];
                        }

                        $value = "Arranged";
                      
                    }
                    else{
                      
                      if($value == "Arranged" )
                      {
                          if(isset($jumbling_remaining_student[$s]))
                          {
                            $z[]=$jumbling_remaining_student[$s];
                          } 
                          if(isset($jumbling_student[$s]))
                          {
                            $z[]=$jumbling_student[$s];
                          }
                          
                          $value = "Start Fresh";
                          
                      }
                      else if ($value == "Start Fresh") { 
                        
                        if(isset($jumbling_remaining_student[$s]))
                          {
                            $z[]=$jumbling_remaining_student[$s];
                          } 
                          if(isset($jumbling_student[$s]))
                          {
                            $z[]=$jumbling_student[$s];
                          }
                        $value = "Don't Arrange";
                      }
                      else
                      {
                        if(isset($jumbling_student[$s]))
                          {
                            $z[]=$jumbling_student[$s];
                          }
                          if(isset($jumbling_remaining_student[$s]))
                          {
                            $z[]=$jumbling_remaining_student[$s];
                          }                           
                        $value = "Don't Arrange";                            
                      }                      
                        
                    }                      
                     $count++;
                  }
                }
                
             // }//

              if(!empty($z)){
                $regular=$z;  
              }else if(empty($z)){
                $regular='';  
              }
              //Regular students
              
              //Arrear Students
              $mer_a= array_reduce($arr_sub_stu, 'array_merge', array());

                // if($total_sub_map_id == 1){
                //   $total_merge_student=$mer_a;
                //   $total_stu_division=count($total_merge_student)/2;
                //   $jumbling_student = $total_merge_student; //40
                //   $jumbling_remaining_student = array();
                //   array_filter($jumbling_remaining_student);
                //   for ($dummy=0; $dummy < count($jumbling_student); $dummy++) { 
                //     $jumbling_remaining_student[$dummy] ='';
                //   }
                  
                //   for($s=0;$s<count($total_merge_student);$s++){
                //     if(isset($jumbling_student[$s]))
                //     {
                //       $za[]=$jumbling_student[$s];
                //     }
                //     if(isset($jumbling_remaining_student[$s]))
                //     {
                //       $za[]=$jumbling_remaining_student[$s];
                //     }

                //   }

                // }else{
                  array_multisort(array_column($mer_a, 'register_number'),  SORT_ASC, $mer_a);
                  $total_merge_student=$mer_a;
                  $total_stu_division=count($total_merge_student)/2; // 200/2 =100           
                  $count_merge_student=count($total_merge_student); //=200
                  $jumbling_student=array_slice($total_merge_student,0,$total_stu_division);
                  $jumbling_remaining_student=array_slice($total_merge_student,$total_stu_division,$count_merge_student);
                  //$za=array();
                  //$za=array_filter($za);
                   if($_POST['seat_arrr'] == 'Straight Arrangement'){

                  for($s=0;$s<$total_stu_division;$s++)
                  {
                    if(isset($jumbling_student[$s]))
                    {
                      $za[]=$jumbling_student[$s];
                    }
                    if(isset($jumbling_remaining_student[$s]))
                    {
                      $za[]=$jumbling_remaining_student[$s];
                    } 
                  }
                }else{
                  $config_seat = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);
                  $count = $config_seat;
                  $value = 1;
                  $check_val=$config_seat/2;
                  for($s=0;$s<$total_stu_division;$s++){
                    //echo $s%$config_seat."<br />";
                    if($s%$config_seat==$check_val) 
                    {
                        if(isset($jumbling_remaining_student[$s]))
                        {
                          $za[]=$jumbling_remaining_student[$s];
                        } 
                        if(isset($jumbling_student[$s]))
                        {
                          $za[]=$jumbling_student[$s];
                        }

                        $value = "Arranged";
                      
                    }
                    else{
                      
                      if($value == "Arranged" )
                      {
                          if(isset($jumbling_remaining_student[$s]))
                          {
                            $za[]=$jumbling_remaining_student[$s];
                          } 
                          if(isset($jumbling_student[$s]))
                          {
                            $za[]=$jumbling_student[$s];
                          }
                          
                          $value = "Start Fresh";
                          
                      }
                      else if ($value == "Start Fresh") { 
                        
                        if(isset($jumbling_remaining_student[$s]))
                          {
                            $za[]=$jumbling_remaining_student[$s];
                          } 
                          if(isset($jumbling_student[$s]))
                          {
                            $za[]=$jumbling_student[$s];
                          }
                        $value = "Don't Arrange";
                      }
                      else
                      {
                        if(isset($jumbling_student[$s]))
                          {
                            $za[]=$jumbling_student[$s];
                          }
                          if(isset($jumbling_remaining_student[$s]))
                          {
                            $za[]=$jumbling_remaining_student[$s];
                          }                           
                        $value = "Don't Arrange";                            
                      }                      
                        
                    }                      
                     $count++;
                  }
                }
                //}//
                //array_unique($za);
                if(!empty($za)){
                  $arrear=$za;  
                }else if(empty($za)){
                  $arrear='';  
                }                    

                //Arrear students
               
              $printData12 = [];

              if(!empty($regular) && !empty($arrear)){
                $printData12 = array_merge($regular,$arrear);
              }else if(!empty($regular)){
                $printData12 = $regular;
              }else if(!empty($arrear)){
                $printData12 = $arrear;
              }        
              // echo "<pre>";
            //print_r($regular);exit;

              $hall_type_id = new Query();
              $seat_capacity = $hall_type_id->select("a.description")
                              ->from('coe_category_type a')
                              ->where(['a.coe_category_type_id' =>$_POST['HallMaster']['hall_type_id']])->createCommand()->queryScalar(); 
              
              //$seat_column = 6;
              $seat_column = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE);                
              $hall_type = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='".$hallmaster->hall_type_id."'")->queryScalar();
              $row = $hall_type/$seat_column;
              $row_system = ceil($row);
              // $namearr is Number of Halls

              $hall=0;
              $k=0;
              $rowsss=1;
              $seatsss=1;
              $id=0;
              $sql5='insert into coe_add_hall_allocate(hall_master_id,exam_timetable_id,year,month,register_number,row,row_column,seat_no,created_by,created_at,updated_by,updated_at) values'; 
              $insert_data = "";
              $all_hall_name = [];
              $row = [];
              $all_reg_num = [];
              $column = [];
              $seat_no = [];
              $created_at = date("Y-m-d H:i:s");
              $createdBy = Yii::$app->user->getId();

              for ($l=0; $l< count($namearr) ; $l++) {  // No of Students                 
                if($hall==count($namearr))
                {
                  break;
                }
                else
                {
                  if($rowsss==$row_system && $k==$seat_capacity)   
                  {
                    $rowsss=1;
                    $seatsss=1;
                    $hall++;
                    continue;
                  }
                  else{                  
                    for ($m=1; $m <= $row_system ; $m++) {                     
                        for($i=1; $i <=$seat_column ; $i++) {

                          //if(isset($mer[$id]['stu_id'])){ 
                          if(isset($printData12[$id]['stu_id']))
                            {                   
                           //  echo "hi";exit;     
                            $hall_id = HallMaster::find()->where(['hall_name'=>$namearr[$l]])->one();
                            $exam_id = $printData12[$id]['coe_add_exam_timetable_id'];
                            
                            $register_number = Student::findOne(['coe_student_id'=>$printData12[$id]['stu_id']]);
                          
                            array_push($all_hall_name, $namearr[$l]);
                            array_push($all_reg_num,$register_number->register_number);
                            array_push($row, $m);
                            array_push($column, $i);
                            array_push($seat_no, $seatsss);

                            $check_data = CoeAddHallAllocate::find()->where(['exam_timetable_id'=>$exam_id,'year'=>$model->year,'month'=>$model->month,'register_number'=>$register_number->register_number])->all();
                           
                            if(count($check_data)>0 && !empty($check_data))
                            {
                                
                            }else{
                              $check_multiple = Yii::$app->db->createCommand('SELECT * FROM coe_add_hall_allocate AS A JOIN coe_add_exam_timetable as B ON B.coe_add_exam_timetable_id=A.exam_timetable_id WHERE register_number="'.$register_number->register_number.'" AND A.month="'.$model->month.'" AND A.year="'.$model->year.'" AND B.exam_date="'.$exam_date.'" AND B.exam_session="'.$exam->exam_session.'"')->queryAll();

                              if(count($check_multiple)>0 && !empty($check_multiple))
                              {
                                  Yii::$app->ShowFlashMessages->setMsg('ERROR',"UNABLE TO ARRANGE GALLEY DUE TO MULTIPLE EXAMS FOR SINGLE STUDENT ON ".date('d-m-Y',strtotime($exam_date)) );
                                 return $this->redirect(['create']);
                              }
                              else
                              {
                                if($seatsss == $seat_capacity){
                                  $insert_data .='("'.$hall_id->coe_hall_master_id.'","'.$exam_id.'","'.$model->year.'","'.$model->month.'","'.$register_number->register_number.'","'.$m.'","'.$i.'","'.$seatsss.'","'.$createdBy.'","'.$created_at.'","'.$createdBy.'","'.$created_at.'"),';
                                  $id++;
                                  $seatsss++;
                                  break;
                                }else{
                                  $insert_data .='("'.$hall_id->coe_hall_master_id.'","'.$exam_id.'","'.$model->year.'","'.$model->month.'","'.$register_number->register_number.'","'.$m.'","'.$i.'","'.$seatsss.'","'.$createdBy.'","'.$created_at.'","'.$createdBy.'","'.$created_at.'"),';
                                  $id++; 
                                  $seatsss++;
                                }
                              } 
                            }
                            
                          }//checking student id has a value
                          else{
                          //echo "hello";exit;                            
                            $id++;
                            $seatsss++;
                          }
                        }
                    }// for row_system
   
                    $seatsss=1;
                    $rowsss++;
                    $k++;
   
                  } //else above for loop $row_system
                  
                } //else above break
               
              } //students loop
           } 

            if(!empty($insert_data))
            {
              $insert_data=substr($insert_data, 0, -1);              
              $insert_query = $sql5.$insert_data;
              // print_r($insert_query);exit;
              
           
            if(Yii::$app->db->createCommand($insert_query)->query()){
              //$max_column_count = Yii::$app->db->createCommand("select distinct(max(row_column)) from coe_hall_allocate a,coe_exam_timetable b where a.exam_timetable_id=b.coe_exam_timetable_id and b.exam_date='".$exam->exam_date."' and b.exam_session='".$exam->exam_session."' group by row")->queryScalar();

              $exam_sn = Yii::$app->db->createCommand("select distinct(a.description) from coe_category_type a,coe_add_exam_timetable b where a.coe_category_type_id=b.exam_session and b.exam_session='".$exam->exam_session."'")->queryScalar(); 
              $exam_date =  date("Y-m-d",strtotime($exam->exam_date));
              $query_allocate = new Query();
              $allocated_value = $query_allocate->select("hall_name,register_number,row,row_column,seat_no")
                              ->from('coe_add_hall_allocate a')
                              ->join('JOIN','coe_hall_master b','a.hall_master_id=b.coe_hall_master_id')
                              ->join('JOIN','coe_add_exam_timetable c','c.coe_add_exam_timetable_id=a.exam_timetable_id')
                              ->where(['c.exam_date' =>$exam_date,'c.exam_month'=>$exam_month,'c.exam_session'=>$exam->exam_session])
                              ->orderBy('hall_name,seat_no')->createCommand()->queryAll();
              

          Yii::$app->ShowFlashMessages->setMsg('Success',"Halls Allocated Successfully!!" );
          return $this->render('view', [
                'model' => $model,
                'hallmaster' => $hallmaster,
                'categorytype' => $categorytype,
                'exam' => $exam,                
                'exam_date' => $exam->exam_date,
                'exam_session' => $exam_sn,
                'allocated_value' => $allocated_value,
            ]);

        }
        else{
          $hallmaster = new HallMaster();
            $categorytype = new Categorytype();
            $exam = new CoeAddExamTimetable();
          Yii::$app->ShowFlashMessages->setMsg('error',"Un Known Error" );
           return $this->render('create', [
                'model' => $model,'hallmaster' => $hallmaster,'categorytype' => $categorytype,'exam' => $exam,
            ]);
        }
      }
      else{
          $hallmaster = new HallMaster();
          $categorytype = new Categorytype();
          $exam = new CoeAddExamTimetable();
          Yii::$app->ShowFlashMessages->setMsg('error',"Un Known Error" );
            return $this->redirect(['create']);
        }
      }//equal hall count if condition
      else{
        $hallmaster = new HallMaster();
        $categorytype = new Categorytype();
        $exam = new CoeAddExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('error',"Halls are not sufficient to assign" );
            return $this->redirect(['create']);
      }
    } else {
      Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Galley Arrangement');
            return $this->render('create', [
                'model' => $model,'hallmaster' => $hallmaster,'categorytype' => $categorytype,'exam' => $exam,
            ]);
        }
    }

  public function actionDeletehallarrangement()
    {
       $exam_date =  date("Y-m-d",strtotime($_POST['date']));
      $month =  Yii::$app->request->post('month');
      $year =  Yii::$app->request->post('year');
      $entered_marks = array();
      $subject_query = new Query();
      $check_subject = $subject_query->select("subject_mapping_id,coe_add_exam_timetable_id")
                              ->from('coe_add_exam_timetable')                              
                              ->where(['exam_date' =>$exam_date,'exam_session'=>$_POST['session'],'exam_year'=>$year,'exam_month'=>$month])->createCommand()->queryAll();
      
      $absent_query = new Query();
      $check_absent = $absent_query->select('*')
                              ->from('coe_add_absent_entry')                              
                              ->where(['exam_date' =>$exam_date,'exam_session'=>$_POST['session'],'exam_year'=>$year,'exam_month'=>$month])->createCommand()->queryAll();

      if(count($check_subject)>0){
        foreach($check_subject as $sub){
          $mark_query = new Query();
          $check_mark = $mark_query->select('*')
                        ->from('coe_value_mark_entry')
                        ->where(['subject_map_id'=>$sub['subject_mapping_id'],'year'=>$_POST['year'],'month'=>$_POST['month']])->createCommand()->queryAll();
          
          if(count($check_mark)>0 || count($check_absent)>0){            
            return 0;           
          
          }else{            
            foreach($check_subject as $exm_id){
              $delete_halls = Yii::$app->db->createCommand("delete from coe_add_hall_allocate where exam_timetable_id='".$exm_id['coe_add_exam_timetable_id']."'")->execute();
            }
            return 1;            
          }

        }
        
      }

      
    }


    /**
     * Updates an existing CoeAddHallAllocate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_add_hall_allocate_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CoeAddHallAllocate model.
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
     * Finds the CoeAddHallAllocate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeAddHallAllocate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoeAddHallAllocate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
      public function actionHallpdf(){
      require(Yii::getAlias('@webroot/includes/use_institute_info.php'));   

        $content = $_SESSION['hall_arrange'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Application.pdf',                
                'format' => Pdf::FORMAT_A4,                
                'orientation' => Pdf::ORIENT_PORTRAIT,                
                'destination' => Pdf::DEST_BROWSER,                
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }td{border:1px solid #999}
                    }   
                ', 
                'options' => ['title' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Application'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();        
        unset($_SESSION['hall_arrange']);
    }
 public function actionGalleyReport()
    {
      $model = new CoeAddHallAllocate();
      $hallmaster = new HallMaster();
      $categorytype = new Categorytype();
      $exam = new CoeAddExamTimetable();
      $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
      if ($model->load(Yii::$app->request->post())) 
        {
          $exam_date =  date("Y-m-d",strtotime($_POST['CoeAddExamTimetable']['exam_date']));
          $query = new Query();
          $query->select('A.name, concat(`K`.`degree_name`,`L`.`programme_code`) as degree_name, M.batch_name, B.register_number,B.seat_no, C.exam_date, C.qp_code, H.category_type as exam_session, D.hall_name, B.row, B.row_column, B.year, E.category_type as month,G.subject_code')
                  ->from('coe_student as A')  
                  ->join('JOIN','coe_student_mapping as I','I.student_rel_id = A.coe_student_id')
                  ->join('JOIN','coe_bat_deg_reg as J','J.coe_bat_deg_reg_id=I.course_batch_mapping_id')
                  ->join('JOIN','coe_degree as K','K.coe_degree_id=J.coe_degree_id')
                  ->join('JOIN','coe_programme as L','L.coe_programme_id=J.coe_programme_id')
                  ->join('JOIN','coe_batch as M','M.coe_batch_id=J.coe_batch_id')                 
                  ->join('JOIN','coe_add_hall_allocate as B','B.register_number=A.register_number')
                  ->join('JOIN','coe_add_exam_timetable as C','C.coe_add_exam_timetable_id=B.exam_timetable_id')
                  ->join('JOIN','coe_hall_master as D','D.coe_hall_master_id=B.hall_master_id')
                  ->join('JOIN','coe_category_type as E','E.coe_category_type_id=B.month')
                  ->join('JOIN','sub as F','F.coe_sub_mapping_id=C.subject_mapping_id')
                  ->join('JOIN','coe_value_subjects as G','G.coe_val_sub_id=F.val_subject_id')
                  ->join('JOIN','coe_category_type as H','H.coe_category_type_id=C.exam_session')
                  //->groupBy('B.register_number')
                  ->orderBy('D.hall_name,B.seat_no');

          $query->where(['B.year'=>$model->year,'B.month'=>$model->month,'C.exam_date'=>$exam_date ,'C.exam_session'=>$_POST['CoeAddExamTimetable']['exam_session']])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['A.student_status'=>'Active']);

          $get_data = $query->createCommand()->queryAll();
        
        if(!empty($get_data))
        {
            return $this->render('galley-report', [
                'model' => $model,'exam' => $exam,'get_data'=>$get_data,
            ]);
        }
        else
        {
           Yii::$app->ShowFlashMessages->setMsg('error',"No Data Found" );
           return $this->redirect(['galley-report']);
        }

        
      }
      else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Galley Arrangement '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT));
            return $this->render('galley-report', [
                'model' => $model,'exam' => $exam,
            ]);
      }

    }


    public function actionAttendanceSheet()
    {

       $model = new CoeAddHallAllocate();
      $hallmaster = new HallMaster();
      $categorytype = new Categorytype();
      $exam = new CoeAddExamTimetable();
      if ($model->load(Yii::$app->request->post())) 
        {
          $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
          $prac_ticla = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Theory%'")->queryScalar();

            $exam_type_g = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%arrear%'")->queryScalar();
           $exam_date = date('Y-m-d',strtotime($_POST['CoeAddExamTimetable']['exam_date'])); 
           $exam_session = $_POST['CoeAddExamTimetable']['exam_session']; 
           $print_for = 'Normal';

          
         
              $query = new Query();
              $query->select('A.name,B.exam_timetable_id,B.hall_master_id,B.register_number,B.seat_no, C.exam_date,H.category_type as exam_session,D.hall_name,B.year, E.category_type as month,G.subject_code,G.subject_name')
                      ->from('coe_student as A')                    
                      ->join('JOIN','coe_add_hall_allocate as B','B.register_number=A.register_number')
                      ->join('JOIN','coe_add_exam_timetable as C','C.coe_add_exam_timetable_id=B.exam_timetable_id')
                      ->join('JOIN','coe_hall_master as D','D.coe_hall_master_id=B.hall_master_id')
                      ->join('JOIN','coe_category_type as E','E.coe_category_type_id=B.month')
                      ->join('JOIN','sub as F','F.coe_sub_mapping_id=C.subject_mapping_id')
                      ->join('JOIN','coe_student_mapping as stu','stu.course_batch_mapping_id=F.batch_mapping_id and stu.student_rel_id=A.coe_student_id')
                      ->join('JOIN','coe_value_subjects as G','G.coe_val_sub_id=F.val_subject_id')
                      ->join('JOIN','coe_category_type as H','H.coe_category_type_id=C.exam_session')
                      //->groupBy('B.register_number')
                      ->groupBy('C.exam_date, B.hall_master_id,B.register_number')
                      ->orderBy('D.hall_name,B.seat_no');

            $query->where(['B.year'=>$model->year,'B.month'=>$model->month,'C.exam_date'=>$exam_date,'C.exam_session'=>$exam_session])
                  ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                  ->andWhere(['A.student_status'=>'Active']); 
          
          $get_data = $query->createCommand()->queryAll();
        
        if(!empty($get_data))
        {
            return $this->render('attendance-sheet', [
                'model' => $model,'get_data'=>$get_data,'exam' => $exam,
                'print_for' => $print_for,
            ]);
        }
        else
        {
           Yii::$app->ShowFlashMessages->setMsg('error',"No Data Found" );
           return $this->redirect(['attendance-sheet']);
        }

        
      }
      else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Attendance Sheet');
            return $this->render('attendance-sheet', [
                'model' => $model,'exam' => $exam,
            ]);
      }

    }

    public function actionQpdistribution(){
    $model = new CoeAddHallAllocate();
      $hallmaster = new HallMaster();
      $categorytype = new Categorytype();
      $exam = new CoeAddExamTimetable();
      
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Distribution');
        return $this->render('qpdistribution', [
            'model' => $model,'exam' => $exam,
        ]);
        
    }

    public function actionQpcnt(){

      $hall_id = [];
      $hall_array = [];
      $qp_array = [];
      $exam_id = [];
      $total_qp_count = 0;
      $total_stu = 0;
      $exam_date =  date("Y-m-d",strtotime($_POST['date']));
      $exam_year = Yii::$app->request->post('exam_year');
      $exam_month = Yii::$app->request->post('exam_month');
      
      $getQpPract = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Practical%'")->queryScalar();
      $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

      $qp_code_exam = Yii::$app->db->createCommand('select * from coe_add_exam_timetable as A JOIN sub as B ON B.coe_sub_mapping_id =A.subject_mapping_id where subject_type_id!='.$getQpPract.' and exam_date="'.$exam_date.'" and exam_session='.$_POST['session'].' and exam_month='.$exam_month.' and exam_year='.$exam_year.' ')->queryAll();

       $mark_query = new Query();
       $qp_code_list = $mark_query->select('coe_add_exam_timetable_id,qp_code')
            ->from('coe_add_exam_timetable as A')
            ->join('JOIN','sub B','B.coe_sub_mapping_id=A.subject_mapping_id')
            ->where(['exam_date'=>$exam_date,'exam_year'=>$exam_year,'exam_month'=>$exam_month,'exam_session'=>$_POST['session']])->andWhere(['<>','paper_type_id',$getQpPract])->createCommand()->queryAll();

      foreach($qp_code_list as $qp_hall)
      {        
        array_push($qp_array,$qp_hall['qp_code']);
        array_push($exam_id, $qp_hall['coe_add_exam_timetable_id']);
        
        $hall_name = Yii::$app->db->createCommand("select distinct(a.hall_master_id),b.hall_name from coe_add_hall_allocate a,coe_hall_master b where a.hall_master_id=b.coe_hall_master_id and a.exam_timetable_id='".$qp_hall['coe_add_exam_timetable_id']."' group by hall_master_id,exam_timetable_id")->queryAll();

        foreach($hall_name as $hl_nme){
          if(!in_array($hl_nme['hall_name'], $hall_array))
          {
            array_push($hall_array, $hl_nme['hall_name']);
            array_push($hall_id, $hl_nme['hall_master_id']);
          }                
        }
      }     
      $qp_array = array_unique($qp_array);
      if(count($qp_code_list)>0){
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        /* 
        *   Already Defined Variables from the above included file
        *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
        *   use these variables for application
        *   use $file_content_available=="Yes" for Content Status of the Organisation
        */
        $qp_table = '';
        $col_merge = count($qp_array)+2;
        if($file_content_available!="Yes")
        {
            return $qp_table=0;
        }
        $qp_table .= '<table style="overflow: auto" id="checkAllFeat1" class="table table- table-responsive table-striped" align="center">';
        $qp_table.='<tr>
                <td colspan=2> 
                    <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan="'.($col_merge-4).'" align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td  colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
      $exam_sess_for = Categorytype::findOne($_POST['session']);
      $exam_date_for =  date("d-m-Y",strtotime($_POST['date']));
      $qp_table.='<tr>
      <td style="font-size:15px" colspan="'.($col_merge).'" align="center"><b>Question Paper Distribution List for '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date : '.$exam_date_for.'  Session : '.$exam_sess_for->description.' </b></td>
      </tr>
      <tr><th>Hall Name</th>';

        $qp_codes_list='';
        $exam_ids='';
        sort($qp_array);
        
        for($q=0;$q<count($qp_array);$q++){
          $qp_table.='<th>'.$qp_array[$q].'</th>';
          $qp_codes_list .="'".$qp_array[$q]."', ";
        }
        
        $qp_codes_list =trim($qp_codes_list,", ");
        $qp_table.= '<th>Total</th></tr>';

        if(count($qp_code_exam)>0)
        {
          for ($i=0; $i <count($qp_code_exam) ; $i++) 
          { 
              $exam_ids[]= $qp_code_exam[$i]['coe_add_exam_timetable_id'];
          }
        }
        sort($hall_array);
        for($hc=0;$hc<count($hall_array);$hc++)
        {
          $qp_table.='<tr><td>'.$hall_array[$hc].'</td>';
          $hallId = HallMaster::find()->where(['hall_name'=>$hall_array[$hc]])->one();
          for($qc=0;$qc<count($qp_array);$qc++)
          {
           $qp_exam_list = '';
           $qp_code_exam = CoeAddExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$_POST['session'],'qp_code'=>$qp_array[$qc],'exam_month'=>$exam_month,'exam_year'=>$exam_year])->all();
           
            for($q_1=0;$q_1<count($qp_code_exam);$q_1++)
            {
              $qp_exam_list .="'".$qp_code_exam[$q_1]->coe_add_exam_timetable_id."', ";
            }
            $qp_exam_list = trim($qp_exam_list,', ');
            $qp_count = Yii::$app->db->createCommand("select count(A.register_number) from coe_add_hall_allocate as A JOIN coe_student as B ON B.register_number=A.register_number JOIN coe_student_mapping as C ON C.student_rel_id=B.coe_student_id where exam_timetable_id IN (".$qp_exam_list.") and status_category_type_id NOT IN('".$det_disc_type."') and hall_master_id='".$hallId->coe_hall_master_id."'")->queryScalar();

            $disp_qp = $qp_count==0?"-":$qp_count;
            $qp_table.='<td>'.$disp_qp.'</td>';
            $total_qp_count+=$qp_count;
          }
         // echo $total_qp_count;exit;
          $qp_table.='<td>'.$total_qp_count.'</td></tr>';
          $total_qp_count=0;
        }
        $qp_table.='<tr><td>Total</td>';
        
        $exam_date =  date("Y-m-d",strtotime($_POST['date']));
        $qp_stu_count = Yii::$app->db->createCommand("select count(A.register_number) as count from coe_add_hall_allocate as A JOIN coe_add_exam_timetable as B ON B.coe_add_exam_timetable_id=A.exam_timetable_id JOIN coe_student as C ON C.register_number=A.register_number JOIN coe_student_mapping as D ON D.student_rel_id=C.coe_student_id where B.exam_date='".$exam_date."' and B.exam_session ='".$_POST['session']."' and exam_month='".$exam_month."' and status_category_type_id NOT IN('".$det_disc_type."') and exam_year='".$exam_year."'  group by B.qp_code")->queryAll();
         //print_r($qp_stu_count);exit;


        $s=count($qp_stu_count);
        $total_stu = 0;
        if(empty($qp_stu_count) || count($qp_stu_count)==0)
        {
          return $qp_table=0;
        }
        $spans = 10;
        foreach($qp_stu_count as $qp_stu){          
          $qp_table.='<td>'.$qp_stu['count'].'</td>';
          $spans--;
          $total_stu+=$qp_stu['count'];
        }
        $total_stu = $total_stu==0?'-':$total_stu;
        $qp_table.='<td>'.$total_stu.'</td></tr></table>';   
        if(isset($_SESSION['questionpaper_print'])){ unset($_SESSION['questionpaper_print']);}     
        $_SESSION['questionpaper_print']=$qp_table;
        return $qp_table;

      }else{
        
        return $qp_table = 0;
      }

    }    

    public function actionPrintQpPdf(){
        
      require(Yii::getAlias('@webroot/includes/use_institute_info.php'));  

      $content = $_SESSION['questionpaper_print'];
      //unset($_SESSION['student_application_date']);
          $pdf = new Pdf([
             
              'mode' => Pdf::MODE_CORE, 
              
              'filename' => 'QP Data.pdf',                
              'format' => Pdf::FORMAT_A4,                 
              'orientation' => Pdf::ORIENT_LANDSCAPE,                 
              'destination' => Pdf::DEST_BROWSER,                 
              'content' => $content,  
              'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
              'cssInline' => ' @media all{
                      table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }td,th{border:1px solid #999}
                  }   
              ', 
              'options' => ['title' => 'QP Data'],
              'methods' => [ 
                  'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                  'SetFooter'=>['Question Paper Distribution'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
              ]
          ]);

      Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
      $headers = Yii::$app->response->headers;
      $headers->add('Content-Type', 'application/pdf');
      return $pdf->render(); 
    }


    public function actionHallTicket()
    { 
        $model = new MarkEntry();  
        $student = new Student();
        $Practical = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Theory%'")->queryScalar();
        $exam_month = Yii::$app->request->post('exam_month');

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        if (isset($_POST['get_hall_tickets'])) 
        {
            $query = new Query();
            $query = new  Query();
            $query->select('J.hall_name,A.name,I.register_number,G.semester,H.subject_code,H.subject_name, A.dob, A.gender, D.degree_code, D.degree_name,E.programme_name,F.exam_date,F.exam_month ,I.year,J.hall_name,K.category_type, L.category_type as exam_session,I.seat_no,')
                            ->from('coe_student as A')                    
                            ->join('JOIN','coe_student_mapping as B','B.student_rel_id=A.coe_student_id ')
                            ->join('JOIN','coe_bat_deg_reg as C','C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                            ->join('JOIN','coe_degree as D','D.coe_degree_id=C.coe_degree_id')
                            ->join('JOIN','coe_programme as E','E.coe_programme_id=C.coe_programme_id')
                            ->join('JOIN','sub as G','G.batch_mapping_id=C.coe_bat_deg_reg_id')
                            ->join('JOIN','coe_value_subjects H','H.coe_val_sub_id=G.val_subject_id')
                            ->join('JOIN','coe_add_exam_timetable F','F.subject_mapping_id=G.coe_sub_mapping_id')
                            ->join('JOIN','coe_category_type K','K.coe_category_type_id=F.exam_month')
                            ->join('JOIN','coe_add_hall_allocate I','I.exam_timetable_id=F.coe_add_exam_timetable_id and I.register_number=A.register_number')
                            ->join('JOIN','coe_hall_master J','J.coe_hall_master_id=I.hall_master_id')
                            ->join('JOIN','coe_category_type L','L.coe_category_type_id=F.exam_session');
                          
            $query->Where(['F.exam_year' => $_POST['mark_year'],'F.exam_month'=> $_POST['exam_month_add'], 'A.student_status'=>'Active' ,'C.coe_batch_id'=>$_POST['bat_val']])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type]); 
          
           
            $query->orderBy('I.register_number,subject_code');
            $query->orderBy('I.register_number,exam_date');

          $print_halls = $query->createCommand()->queryAll();
          //print_r( $print_halls);exit;

         // $semester_number = ConfigUtilities::semCaluclation($_POST['mark_year'],$exam_month);
          $query1 = new Query();
            $query1 = new  Query();
            $query1->select('A.name,A.register_number,G.semester, H.subject_code,H.subject_name, A.dob, A.gender, D.degree_code, D.degree_name,E.programme_name,F.exam_date, F.exam_year as year,K.category_type, L.category_type as exam_session')
              ->from('coe_student as A')                    
              ->join('JOIN','coe_student_mapping as B','B.student_rel_id=A.coe_student_id ')
              ->join('JOIN','coe_bat_deg_reg as C','C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
              ->join('JOIN','coe_degree as D','D.coe_degree_id=C.coe_degree_id')
              ->join('JOIN','coe_programme as E','E.coe_programme_id=C.coe_programme_id')
              ->join('JOIN','sub as G','G.batch_mapping_id=C.coe_bat_deg_reg_id')
              ->join('JOIN','coe_value_subjects H','H.coe_val_sub_id=G.val_subject_id')
              ->join('JOIN','coe_add_exam_timetable F','F.subject_mapping_id=G.coe_sub_mapping_id')
              ->join('JOIN','coe_category_type K','K.coe_category_type_id=F.exam_month')
              ->join('JOIN','coe_category_type L','L.coe_category_type_id=F.exam_session');
                          
            $query1->Where(['F.exam_year' => $_POST['mark_year'],
              'F.exam_month'=>$_POST['exam_month_add'], 'A.student_status'=>'Active','C.coe_batch_id'=>$_POST['bat_val'] ])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
         
            $query1->groupBy('A.register_number,subject_code');
            $query1->orderBy('A.register_number,subject_code,exam_date');

            $print_halls_pract = $query1->createCommand()->queryAll();
            if(!empty($print_halls_pract))
            {
              $print_halls = array_merge($print_halls,$print_halls_pract);
              array_multisort(array_column($print_halls, 'register_number'),  SORT_ASC, $print_halls);
            }

            $query2 = new Query();
            $query2 = new  Query();
            $query2->select('A.name,A.register_number,G.semester, H.subject_code,H.subject_name, A.dob, A.gender, D.degree_code, D.degree_name,E.programme_name,F.exam_date, F.exam_year as year,K.category_type, L.category_type as exam_session')
              ->from('coe_student as A')                    
              ->join('JOIN','coe_student_mapping as B','B.student_rel_id=A.coe_student_id ')
              ->join('JOIN','coe_bat_deg_reg as C','C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
              ->join('JOIN','coe_degree as D','D.coe_degree_id=C.coe_degree_id')
              ->join('JOIN','coe_programme as E','E.coe_programme_id=C.coe_programme_id')
              ->join('JOIN','sub as G','G.batch_mapping_id=C.coe_bat_deg_reg_id')
              ->join('JOIN','coe_value_subjects H','H.coe_val_sub_id=G.val_subject_id')
              ->join('JOIN','coe_add_exam_timetable F','F.subject_mapping_id=G.coe_sub_mapping_id')
              ->join('JOIN','coe_category_type K','K.coe_category_type_id=F.exam_month')
              ->join('JOIN','coe_category_type L','L.coe_category_type_id=F.exam_session');
              //->join('JOIN','coe_mark_entry_master M','M.subject_map_id=G.coe_subjects_mapping_id and M.student_map_id=B.coe_student_mapping_id');
            $query2->Where(['F.exam_year' => $_POST['mark_year'],'F.exam_month'=> 
              $_POST['exam_month_add'], 'A.student_status'=>'Active','C.coe_batch_id'=>$_POST['bat_val'] ])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
           
           // ->andWhere(['<','G.semester',$semester_number]) 
            //->andWhere(['NOT LIKE','M.result','Pass']) 
          
            $query1->groupBy('A.register_number,subject_code');
            $query2->orderBy('A.register_number,exam_date');

            $print_halls_pract_arr = $query2->createCommand()->queryAll();
            if(!empty($print_halls_pract_arr))
            {
              $print_halls = array_merge($print_halls,$print_halls_pract_arr);
              array_multisort(array_column($print_halls, 'register_number'),  SORT_ASC, $print_halls);
            }
            $print_halls = array_map("unserialize", array_unique(array_map("serialize", $print_halls)));
            
            if(!empty($print_halls))
            {
                return $this->render('hall-ticket', [
                    'model' => $model,'student' => $student,'print_halls'=>$print_halls,
                ]);
            }
            else
            {
               Yii::$app->ShowFlashMessages->setMsg('error',"No Data Found" );
               return $this->redirect(['hall-ticket']);
            }
            
          
        }
        else {
              Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Hall Ticket Printing');
              return $this->render('hall-ticket', [
                  'model' => $model,'student' => $student,
              ]);
        }
    }

    public function actionExcelHallTicket()
    {
        $content = $_SESSION['hall_ticket_print'];
        
        $fileName ='Hall-Ticket-Printing-'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionPrintHallTickets()
    {
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
       
          $content = $_SESSION['hall_ticket_print'];
         
        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => 'Hall Ticket Printing.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: left;  font-family:"Roboto, sans-serif";  border: 1px solid #000; width:100%; } 
                        
                        table td{.
                           white-space: nowrap;
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 12px;
                            line-height: 1.5em;
                        }
                        table th{
                          white-space: nowrap;
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 12px;
                            line-height:1.5em;
                        }
                    }   
                ',
                'options' => ['title' => 'Hall Ticket Printing'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".strtoupper($org_name)], 
                    'SetFooter'=>['Hall Ticket Printing PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
         
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionHallticketexport()
    {
        $model = new MarkEntry();
        $galley = new HallAllocate();
        $models  = new CoeAddHallAllocate();
        $exam = new CoeAddExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Hallticket Export');
        return $this->render('hallticketexport', [
            'model' => $model, 'galley' => $galley,
            'models' =>$models,
            'exam' => $exam, 
        ]);
    }
    public function actionAddHall()
    {
        
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        
        $stu_elective = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Elective%'")->queryScalar();

        $practical_check = Yii::$app->request->post('check_val');
        $year = Yii::$app->request->post('year');
        $batch_id = Yii::$app->request->post('batch_id');
        $semester = Yii::$app->request->post('semester');
        if(isset($semester) && !empty($semester))
        {
            $semester = $semester+1;
        }
        $month = Yii::$app->request->post('month');
        $table = "";
        $a="400";
        $pract="Practical";
        $sn = 1;
        if(isset($batch_id) && !empty($batch_id))
        {
            $bath_name = Batch::findOne($batch_id);
            $batch_name_id = $bath_name->batch_name;
        }
        if($practical_check==1)
        {
            $add_in_theQuery = isset($batch_id) && !empty($batch_id) ? " and A.coe_batch_id='".$batch_id."' and A.coe_batch_id='".$batch_id."' and batch_name='".$batch_name_id."' ":'';

            $add_in_theQuerySem = isset($semester) && !empty($semester) ? " and G.semester='".$semester."' ":'';

            $query_2 = "SELECT L.batch_name,D.register_number,D.name,D.dob,B.degree_code,C.programme_name,G.semester,F.subject_code,F.subject_name,$a as exam_date, $a as category_type, $a as hall_name ,$a as row, $a as row_column,$a as seat_no  FROM coe_bat_deg_reg as A JOIN coe_degree as B ON A.coe_degree_id=B.coe_degree_id JOIN coe_programme C ON A.coe_programme_id=C.coe_programme_id JOIN coe_student_mapping E ON  A.coe_bat_deg_reg_id=E.course_batch_mapping_id JOIN coe_student D ON E.student_rel_id=D.coe_student_id JOIN sub G ON A.coe_bat_deg_reg_id=G.batch_mapping_id JOIN coe_value_subjects F ON G.val_subject_id=F.coe_val_sub_id JOIN coe_value_nominal as I ON I.coe_subjects_id=F.coe_val_sub_id AND I.coe_student_id=D.coe_student_id and G.val_subject_id=I.coe_subjects_id and I.course_batch_mapping_id=G.batch_mapping_id JOIN coe_category_type H ON H.coe_category_type_id=G.paper_type_id JOIN coe_batch L ON A.coe_batch_id=L.coe_batch_id WHERE H.description LIKE '%$pract%' AND G.subject_type_id='".$stu_elective."' AND status_category_type_id not in('".$det_disc_type."' ) $add_in_theQuery $add_in_theQuerySem group by D.register_number,F.subject_code ORDER BY G.semester,D.register_number";
            $practical_elective = Yii::$app->db->createCommand($query_2)->queryAll();

            $query_3 = "SELECT L.batch_name,D.register_number,D.name,D.dob,B.degree_code,C.programme_name,G.semester,F.subject_code,F.subject_name,$a  as exam_date, $a  as category_type, $a  as hall_name ,$a  as row, $a  as row_column,$a  as seat_no  FROM coe_bat_deg_reg as A JOIN coe_degree as B ON A.coe_degree_id=B.coe_degree_id JOIN coe_programme C ON A.coe_programme_id=C.coe_programme_id JOIN coe_student_mapping E ON  A.coe_bat_deg_reg_id=E.course_batch_mapping_id JOIN coe_student D ON E.student_rel_id=D.coe_student_id JOIN  sub G ON A.coe_bat_deg_reg_id=G.batch_mapping_id JOIN coe_value_subjects F ON G.val_subject_id=F.coe_val_sub_id JOIN coe_category_type H ON H.coe_category_type_id=G.paper_type_id JOIN coe_batch L ON A.coe_batch_id=L.coe_batch_id WHERE H.description LIKE '%$pract%' AND G.subject_type_id!='".$stu_elective."' AND status_category_type_id not in('".$det_disc_type."' ) $add_in_theQuery $add_in_theQuerySem group by D.register_number,F.subject_code ORDER BY G.semester,D.register_number";
            $practical_common = Yii::$app->db->createCommand($query_3)->queryAll();
            if(!empty($practical_elective))
            {
                if(!empty($practical_common))
                {
                    $practical_common = array_merge($practical_common,$practical_elective);
                }
            }
            $query_4 = "SELECT L.batch_name,D.register_number,D.name,D.dob,B.degree_code,C.programme_name,G.semester,F.subject_code,F.subject_name,$a  as exam_date, $a  as category_type, $a  as hall_name ,$a  as row, $a  as row_column,$a  as seat_no  FROM coe_bat_deg_reg as A JOIN coe_degree as B ON A.coe_degree_id=B.coe_degree_id JOIN coe_programme C ON A.coe_programme_id=C.coe_programme_id JOIN coe_student_mapping E ON  A.coe_bat_deg_reg_id=E.course_batch_mapping_id JOIN coe_student D ON E.student_rel_id=D.coe_student_id JOIN sub G ON A.coe_bat_deg_reg_id=G.batch_mapping_id and E.course_batch_mapping_id=G.batch_mapping_id JOIN coe_value_subjects F ON G.val_subject_id=F.coe_val_sub_id JOIN coe_category_type H ON H.coe_category_type_id=G.paper_type_id JOIN coe_batch L ON A.coe_batch_id=L.coe_batch_id JOIN coe_value_mark_entry as M ON M.student_map_id=E.coe_student_mapping_id and M.subject_map_id=G.coe_subjects_mapping_id WHERE H.description LIKE '%$pract%' AND status_category_type_id not in('".$det_disc_type."' ) $add_in_theQuery $add_in_theQuerySem group by D.register_number,F.subject_code ORDER BY G.semester,D.register_number";
            $prac_arrear = Yii::$app->db->createCommand($query_4)->queryAll();

            if(!empty($prac_arrear))
            {
                if(!empty($practical_common))
                {
                    $practical_common = array_merge($practical_common,$prac_arrear);
                }
            }
            $subject = $practical_common;
            $subject = array_map("unserialize", array_unique(array_map("serialize", $subject)));

        }
        else
        {
            $examsIdsMer = array_filter(['']);
            $examSIds = CoeAddExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month])->all();
            foreach ($examSIds as $key => $exams) 
            {
               $examsIdsMer[$exams['coe_add_exam_timetable_id']]=$exams['coe_add_exam_timetable_id'];
            }
            $examsIdsMer = array_filter($examsIdsMer);
            $query = new Query();
            $query->select('L.batch_name,D.register_number,D.name,D.dob,B.degree_code,C.programme_name,G.semester,F.subject_code,F.subject_name,H.exam_date,K.category_type,I.hall_name,J.row,J.row_column,J.seat_no')
                ->from('coe_bat_deg_reg A')
                ->join('JOIN', 'coe_degree B', 'A.coe_degree_id=B.coe_degree_id')
                ->join('JOIN', 'coe_programme C', 'A.coe_programme_id=C.coe_programme_id')
                ->join('JOIN', 'coe_student_mapping E', 'E.course_batch_mapping_id=A.coe_bat_deg_reg_id')
                ->join('JOIN', 'coe_student D', 'D.coe_student_id=E.student_rel_id')
                ->join('JOIN', 'sub G', 'G.batch_mapping_id=E.course_batch_mapping_id and G.batch_mapping_id=A.coe_bat_deg_reg_id')
                ->join('JOIN', 'coe_value_subjects F', 'F.coe_val_sub_id=G.val_subject_id')
                ->join('JOIN', 'coe_add_exam_timetable H', 'H.subject_mapping_id=G.coe_sub_mapping_id')
                ->join('JOIN', 'coe_add_hall_allocate J', 'J.exam_timetable_id=H.coe_add_exam_timetable_id and J.register_number=D.register_number and J.year=H.exam_year and J.month=H.exam_month')
                ->join('JOIN', 'coe_hall_master I', 'I.coe_hall_master_id=J.hall_master_id')
                ->join('JOIN', 'coe_category_type K', 'K.coe_category_type_id=H.exam_session')
                ->join('JOIN', 'coe_batch L', 'A.coe_batch_id=L.coe_batch_id')
                ->where(['J.year' => $year,'J.month' => $month,'H.exam_year'=> $year,'H.exam_month'=>$month,'L.coe_batch_id'=>$batch_id,'batch_name'=>$batch_name_id]);
                /*if(isset($batch_id) && !empty($batch_id))
                {
                    $query->where(['L.coe_batch_id'=>$batch_id,'A.coe_batch_id'=>$batch_id,'batch_name'=>$batch_name_id]);
                }*/
            $query->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['<>', 'G.subject_type_id', $stu_elective])
                ->andWhere(['IN', 'coe_add_exam_timetable_id', $examsIdsMer])
                ->andWhere(['IN', 'exam_timetable_id', $examsIdsMer])
                ->groupBy('D.register_number,F.subject_code')
                ->orderBy('G.semester,D.register_number,H.exam_date');
            $subject = $query->createCommand()->queryAll();

            $query_1 = new Query();
            $query_1->select('L.batch_name,D.register_number,D.name,D.dob,B.degree_code,C.programme_name,G.semester,F.subject_code,F.subject_name,H.exam_date,K.category_type,I.hall_name,J.row,J.row_column,J.seat_no')
                ->from('coe_bat_deg_reg A')
                ->join('JOIN', 'coe_degree B', 'A.coe_degree_id=B.coe_degree_id')
                ->join('JOIN', 'coe_programme C', 'A.coe_programme_id=C.coe_programme_id')
                ->join('JOIN', 'coe_batch L', 'A.coe_batch_id=L.coe_batch_id')
                ->join('JOIN', 'coe_student_mapping E', 'A.coe_bat_deg_reg_id=E.course_batch_mapping_id')
                ->join('JOIN', 'coe_student D', 'E.student_rel_id=D.coe_student_id')
                ->join('JOIN', 'sub G', 'A.coe_bat_deg_reg_id=G.batch_mapping_id')
                ->join('JOIN', 'coe_value_subjects F', 'G.val_subject_id=F.coe_val_sub_id')
                ->join('JOIN', 'coe_value_nominal N', 'N.coe_subjects_id=F.coe_val_sub_id AND N.coe_student_id=D.coe_student_id and N.course_batch_mapping_id=E.course_batch_mapping_id and N.semester=G.semester and N.course_batch_mapping_id=G.batch_mapping_id')
                ->join('JOIN', 'coe_add_exam_timetable H', 'H.subject_mapping_id=G.coe_sub_mapping_id')
                ->join('JOIN', 'coe_add_hall_allocate J', 'J.exam_timetable_id=H.coe_add_exam_timetable_id and D.register_number=J.register_number and J.year=H.exam_year and J.month=H.exam_month')
                ->join('JOIN', 'coe_hall_master I', 'I.coe_hall_master_id=J.hall_master_id')
                ->join('JOIN', 'coe_category_type K', 'K.coe_category_type_id=H.exam_session')
                ->where(['J.year' => $year, 'J.month' => $month,'H.exam_year'=> $year,'H.exam_month'=>$month,'L.coe_batch_id'=>$batch_id,'batch_name'=>$batch_name_id]);
               /* if(isset($batch_id) && !empty($batch_id))
                {
                    $query->where(['L.coe_batch_id'=>$batch_id,'A.coe_batch_id'=>$batch_id,'batch_name'=>$batch_name_id]);
                }*/
                $query->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['=', 'G.subject_type_id', $stu_elective])
                ->andWhere(['IN', 'coe_add_exam_timetable_id', $examsIdsMer])
                ->andWhere(['IN', 'exam_timetable_id', $examsIdsMer])
                ->groupBy('D.register_number,F.subject_code')
                ->orderBy('G.semester,D.register_number,H.exam_date');
            $subject_elec = $query_1->createCommand()->queryAll();
           
            if(!empty($subject_elec))
            {
                $subject = array_merge($subject,$subject_elec);
            }
        }
        $subject = array_map("unserialize", array_unique(array_map("serialize", $subject)));
        if (count($subject) > 0) 
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $table = '<table width="100%" ><tbody align="center">';
            
            $table .= '
                        <tr>
                            <td><b> S.NO </b></td>
                            <td><b> Batch </b></td>
                            <td><b> Register number </b></td>
                            <td><b> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . ' Name </b></td>
                            <td><b> DOB </b></td>
                            <td><b> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' </b></td>
                            <td><b> Semester </b></td>
                            <td><b> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Code </b></td>
                            <td><b> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Name </b></td>
                            <td><b> Exam date </b></td>
                            <td><b> Session </b></td>
                            <td><b> Hallname </b></td>
                            
                            <td><b> Seat no </b></td>
                        </tr>';
            foreach ($subject as $subject1) 
            {
                $dergreee_code = strstr($subject1['degree_code'], "MBATRISEM") ? "MBA" : $subject1['degree_code'];
                $exam_date = $subject1['exam_date']=='400' ? '-' : date('d-M-Y',strtotime($subject1['exam_date']));
                $hall_name = $subject1['hall_name']=='400' ? '-' : $subject1['hall_name'];
                $seat_no = $subject1['seat_no']=='400' ? '-' : $subject1['seat_no'];
                $category_type = $subject1['category_type']=='400' ? '-' : $subject1['category_type'];
                $table .= '
                    <tr>
                        <td> ' . $sn . ' </td>
                        <td> ' . $subject1['batch_name'] . ' </td>
                        <td> ' . $subject1['register_number'] . ' </td>
                        <td> ' . $subject1['name'] . ' </td>
                        <td> ' . $subject1['dob'] . ' </td>
                        <td> ' . $dergreee_code . ' ' . $subject1['programme_name'] . ' </td>
                        <td> ' . $subject1['semester'] . ' </td>
                        <td> ' . $subject1['subject_code'] . ' </td>
                        <td> ' . $subject1['subject_name'] . ' </td>
                        <td> ' . $exam_date. ' </td>
                        <td> ' . $category_type . ' </td>
                        <td> ' . $hall_name . ' </td>
                        <td> ' . $seat_no . ' </td>
                    </tr>';
                $sn++;
            }
            $table .= '</table>';
        } else {
            $table .= 0;
        }
        if (isset($_SESSION['hallticket_export_print'])) {
            unset($_SESSION['hallticket_export_print']);
        }
        $_SESSION['hallticket_export_print'] = $table;
        return $table;
    }
    public function actionHallticketexportPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['hallticket_export_print'];
        //unset($_SESSION['student_application_date']);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'Hall Ticket Export.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 15px; }
                    }   
                ',
            'options' => ['title' => 'Hallticket export Information'],
            'methods' => [
                'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                'SetFooter' => ['Hallticket ' . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
            ]
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    public function actionExcelHallticketexport()
    {
        
            $content = $_SESSION['hallticket_export_print'];
            
        $fileName = "Hallticket export Information " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionAddAnswerPackets()
    {
        $model = new AnswerPacket();
        $examTimetable = new ExamTimetable();
        $absentModel = new AbsentEntry();
        $coeaddexamTimetable = new CoeAddExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Answer Covers Alloted');
        return $this->render('add-answer-packets', [
            'model' => $model,
            'examTimetable' => $examTimetable,
            'absentModel' =>$absentModel,
            'coeaddexamTimetable' =>$coeaddexamTimetable, 
        ]);
    }

    public function actionAddAnswerScriptsPdf()
    {
      require(Yii::getAlias('@webroot/includes/use_institute_info.php'));   

        $content = $_SESSION['get_answer_packet'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' =>'Answer Script.pdf',                
                'format' => Pdf::FORMAT_A4,                
                'orientation' => Pdf::ORIENT_LANDSCAPE,                
                'destination' => Pdf::DEST_BROWSER,                
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse:collapse; border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 14px; }td,th{border:1px solid #999; padding: 4px;}
                        
                    }   
                ', 
               
                'options' => ['title' =>"Answer Script"],
                'methods' => [
                    'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                    'SetFooter' => [ "Answer Script " . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();        
        unset($_SESSION['get_answer_packet']);
    }
    public function actionAddAnswerScriptsExcel()
    {
        $content = $_SESSION['get_answer_packet'];
        $fileName = "Answer Script " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


     public function actionAddPrintRegisterNumbers()
    {
        $model = new AnswerPacket();
        $examTimetable = new ExamTimetable();
        $absentModel = new AbsentEntry();
        $coeaddexamTimetable = new CoeAddExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Answer Covers Register Number');
        return $this->render('add-print-register-numbers', [
            'model' => $model,
            'examTimetable' => $examTimetable,
            'absentModel' =>$absentModel,
            'coeaddexamTimetable'=>$coeaddexamTimetable ,
        ]);
    }

    public function actionAddPrintRegisterNumbersPdf(){
      require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['get_print_reg'];        
            $pdf = new Pdf([               
                'mode' => Pdf::MODE_CORE,                
                'filename' =>'Register Number Covers.pdf',                
                'format' => Pdf::FORMAT_A4,                
                'orientation' => Pdf::ORIENT_LANDSCAPE,                
                'destination' => Pdf::DEST_BROWSER,                
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{
                          border-collapse:collapse; 
                          border: none; 
                          font-family:"Roboto, sans-serif"; 
                          width:100%; 
                          font-size: 18px; 
                        }td,th{border:1px solid #999; padding: 4px;}
                    }   
                ', 
                'options' => ['title' =>"Register Number Covers"],                
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();        
        unset($_SESSION['get_print_reg']);
    }
    public function actionAddPrintRegisterNumbersExcel()
    {
        $content = $_SESSION['get_print_reg'];
        $fileName = "Register Numbers " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionAddAnswerVal()
    {
        $model = new AnswerPacket();
        $examTimetable = new ExamTimetable();
        $absentModel = new AbsentEntry();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Answer Covers Alloted');
        return $this->render('add-answer-val', [
            'model' => $model,
            'examTimetable' => $examTimetable,
            'absentModel' =>$absentModel,
        ]);
    }

}
