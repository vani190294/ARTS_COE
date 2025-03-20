public function actionHallInvigilator()
    {
          $model = new HallAllocate();
          $exam = new ExamTimetable();
            if(Yii::$app->request->post())
            {

                $year=$_POST['HallAllocate']['year'];
                $month=$_POST['exam_month3'];
                $from_date=$_POST['exam_date3'];
                $to_date=$_POST['semester'];
                $from_date1= date("Y-m-d",strtotime($from_date));
                $to_date1=date("Y-m-d",strtotime($to_date));
                //print_r($from_date1);exit;
                $exam_details= Yii::$app->db->createCommand('select distinct A.exam_date,A.exam_year,A.exam_session,A.exam_month from coe_exam_timetable as A JOIN coe_hall_allocate as B ON B.exam_timetable_id =A.coe_exam_timetable_id where exam_month='.$month.' and exam_year='.$year.' group by B.hall_master_id,A.exam_date order By A.exam_date ')->queryAll();

                 $faculty=Yii::$app->db->createCommand('SELECT faculty_id FROM  coe_faculty_hall')->queryAll();

                $facultydata=array_column($faculty,'faculty_id'); 

                //print_r($facultydata); exit;

                 $facl_count=count($faculty); 
                 $i=0; 
                 $success=0;
                  foreach($exam_details as $value)
                  {
                     
                    
                     //print_r($facl_count);exit;
                     $hallcount= Yii::$app->db->createCommand('select count( DISTINCT A. hall_master_id) as hall  from coe_hall_allocate as A  join coe_exam_timetable as B on B.coe_exam_timetable_id=A.exam_timetable_id
                      join coe_hall_master as C on C.coe_hall_master_id=A.hall_master_id
                      where A.month='.$month.'  and A.year='.$year.' and B.exam_date ="'.$from_date1.'" between B.exam_date="'.$to_date1.'" and B.exam_session="'.$value['exam_session'].'" ')->queryOne();
                     print_r($hallcount);exit;

                      if($i==0)
                      {
                        $hall_loop=0;
                      }
                      else
                      {
                        $hall_loop= Yii::$app->db->createCommand('SELECT hall_loop  from coe_hall_invigilator Where month='.$month.'  and year='.$year.' ORDER BY hall_faculty_id DESC LIMIT 1')->queryScalar();


                        $hall_loop=$hall_loop+1; 

                        // if($hall_loop==58)
                        // {
                        //   echo $hall_loop."==".$facl_count; exit;
                        // }

                        if($hall_loop==$facl_count)
                        {
                          $hall_loop=0;
                        }


                      }
                      

                      $hallmaster= Yii::$app->db->createCommand('SELECT DISTINCT  A. hall_master_id,C.hall_name from coe_hall_allocate as A  join coe_exam_timetable as B on B.coe_exam_timetable_id=A.exam_timetable_id join coe_hall_master as C on C.coe_hall_master_id=A.hall_master_id Where A.month='.$month.'  and A.year='.$year.' and B.exam_date ="'.$value['exam_date'].'" and B.exam_session="'.$value['exam_session'].'"')->queryAll();
                     
                      
                      $updated_by = Yii::$app->user->getId();
                      $updated_at = new \yii\db\Expression('NOW()');
                      foreach($hallmaster as $value1)
                       {
                           // $check_faculty= Yii::$app->db->createCommand('SELECT faculty_id  from coe_hall_invigilator as A  Where A.month='.$month.'  and A.year='.$year)->queryScalar();

                        if($hall_loop==$facl_count)
                        {
                          $hall_loop=0;
                        }

                        $insert = Yii::$app->db->createCommand('INSERT into coe_hall_invigilator( year,month,exam_date, exam_session,faculty_id, hall_id,hall_loop,created_at, created_by,updated_by,updated_at) values("'.$value['exam_year'].'","'.$value['exam_month'].'","'.$value['exam_date'].'","'.$value['exam_session'].'","'.$facultydata[$hall_loop].'","'.$value1['hall_master_id'].'","'.$hall_loop.'","'.$updated_at.'","'.$updated_by.'","'.$updated_by.'","'.$updated_at.'") ')->execute();

                          if($insert)
                          {
                            $success++;
                          }

                          $hall_loop++;
                          
                         }
                     $i++;

                    }

                    if($success>0)
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Success', $success.' Faculty Allocateed to Hall');

                        return $this->redirect(['hall-invigilator']);
                    }


              }
              else
              {

                 Yii::$app->ShowFlashMessages->setMsg('Error', 'Exam Date is not created');

               
              }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to  HallAllocate Invigilator');
        return $this->render('hall-invigilator', [
            'model' => $model,
            'exam' =>$exam,
        ]);
    }