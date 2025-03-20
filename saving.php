public function actionSavescrutinymarks1() 
    {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('year');
        $reg_no = Yii::$app->request->post('reg_no');
        $fac_all_id = Yii::$app->request->post('fac_all_id');
        $batch_id = Yii::$app->request->post('batch_id');

        //print_r( $month );exit;

        $cvd_count = Yii::$app->db->createCommand("SELECT val_part_id FROM coe_valuation_mark_details WHERE val_faculty_all_id='" . $fac_all_id . "' AND stu_reg_no='".$reg_no."' ")->queryScalar();
        
        $get_verifyed_data = Yii::$app->db->createCommand("SELECT subject_code,subject_pack_i FROM coe_valuation_faculty_allocate WHERE val_faculty_all_id='" . $fac_all_id . "'")->queryOne();

       $result=0;

        if($cvd_count>0)
        {
           
                $partA = Yii::$app->request->post('partA');
                if(!empty($partA))
                {
                    $partA_value   = implode(',',$partA);
                }
                else
                {
                    $partA_value   = '';
                }

                $partB = Yii::$app->request->post('partB');
                if(!empty($partB))
                {
                    $partB_value   = implode(',',$partB);
                }
                else
                {
                    $partB_value   = '';
                }

                $partC = Yii::$app->request->post('partC');
                if(!empty($partC))
                {
                    $partC_value   = implode(',',$partC);
                }
                else
                {
                    $partC_value   = '';
                }

                $part_a_total = Yii::$app->request->post('part_a_total');
                $part_b_total = Yii::$app->request->post('part_b_total');
                $part_c_total = Yii::$app->request->post('part_c_total');
                $grandtotal = Yii::$app->request->post('grandtotal');

                $user_id=Yii::$app->user->getId();

                $update= Yii::$app->db->createCommand('UPDATE coe_valuation_mark_details SET part_a_mark="'.$partA_value.'", part_a_total="'.$part_a_total.'", part_b_mark="'.$partB_value.'", part_b_total="'.$part_b_total.'", grand_total="'.$grandtotal.'", part_c_mark="'.$partC_value.'", part_c_total="'.$part_c_total.'", updated_at="'.date('Y-m-d H:i:s').'", updated_by="'.$user_id.'" WHERE val_part_id ="'.$cvd_count.'"')->execute();
           
                if($update)
                {
                    
                    $result=2;
                }
                else
                {
                    $result=4;
                }
        }
        else
        {

            $check_regno = Yii::$app->db->createCommand("SELECT count(*) FROM coe_answerpack_regno WHERE answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' AND stu_reg_no='".$reg_no."'")->queryScalar();
            if($check_regno==1)
            {
                $partA = Yii::$app->request->post('partA');
                if(!empty($partA))
                {
                    $partA_value   = implode(',',$partA);
                }
                else
                {
                    $partA_value   = '';
                }

                $partB = Yii::$app->request->post('partB');
                if(!empty($partB))
                {
                    $partB_value   = implode(',',$partB);
                }
                else
                {
                    $partB_value   = '';
                }

                $partC = Yii::$app->request->post('partC');
                if(!empty($partC))
                {
                    $partC_value   = implode(',',$partC);
                }
                else
                {
                    $partC_value   = '';
                }

                $part_a_total = Yii::$app->request->post('part_a_total');
                $part_b_total = Yii::$app->request->post('part_b_total');
                $part_c_total = Yii::$app->request->post('part_c_total');
                $grandtotal = Yii::$app->request->post('grandtotal');

                $user_id=Yii::$app->user->getId();

                $insert = Yii::$app->db->createCommand('INSERT into coe_valuation_mark_details(year, month, val_faculty_all_id, stu_reg_no, part_a_mark, part_a_total, part_b_mark, part_b_total, part_c_mark, part_c_total, grand_total, created_at, created_by) values("'.$year.'","'.$month.'","'.$fac_all_id.'","'.$reg_no.'","'.$partA_value.'","'.$part_a_total.'","'.$partB_value.'","'.$part_b_total.'","'.$partC_value.'","'.$part_c_total.'","'.$grandtotal.'","'.date('Y-m-d H:i:s').'","'.$user_id.'") ')->execute();
           
                if($insert)
                {
                    $get_verifyed_data1 = Yii::$app->db->createCommand("SELECT total_answer_scripts FROM coe_valuation_faculty_allocate WHERE val_faculty_all_id='" . $fac_all_id . "'")->queryScalar(); 

                    $check_verify =Yii::$app->db->createCommand("SELECT count(*) FROM coe_valuation_mark_details WHERE val_faculty_all_id='" . $fac_all_id . "'")->queryScalar();

                    if($get_verifyed_data1==$check_verify)
                    {
                         Yii::$app->db->createCommand('UPDATE coe_valuation_faculty_allocate SET valuation_status="4" WHERE val_faculty_all_id ="'.$fac_all_id.'"')->execute();
                    }

                    $result=2;
                }
                else
                {
                    $result=4;
                }

            }
            else
            {
                 $result=3;
            }
        }
        
        if($result==2)
        {
            $firstregno = Yii::$app->db->createCommand("SELECT stu_reg_no FROM coe_answerpack_regno WHERE answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' LIMIT 1")->queryScalar(); 
            $lastregno = Yii::$app->db->createCommand("SELECT stu_reg_no FROM coe_answerpack_regno WHERE answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' ORDER BY stu_reg_no DESC LIMIT 1")->queryScalar();

            $currentid = Yii::$app->db->createCommand("SELECT answerpacket_reg_id FROM coe_answerpack_regno WHERE stu_reg_no='".$reg_no."' AND answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' LIMIT 1")->queryScalar(); 

            $nextregno = Yii::$app->db->createCommand("SELECT stu_reg_no FROM coe_answerpack_regno WHERE answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' AND answerpacket_reg_id>'".$currentid."' LIMIT 1")->queryScalar();

            $check_mark = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_mark_details WHERE val_faculty_all_id='".$fac_all_id."' AND  stu_reg_no='".$nextregno."' AND year= '" . $year. "' AND month='" . $month. "' ")->queryone();

            if(!empty($check_mark) && $lastregno !=$reg_no)
            { 
                $preid = Yii::$app->db->createCommand("SELECT answerpacket_reg_id FROM coe_answerpack_regno WHERE stu_reg_no='".$nextregno."' AND answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' LIMIT 1")->queryScalar(); 

                $prevregno = Yii::$app->db->createCommand("SELECT stu_reg_no FROM coe_answerpack_regno WHERE answer_packet_number='".$get_verifyed_data['subject_pack_i']."' AND exam_year= '" . $year. "' AND exam_month='" . $month. "' AND answerpacket_reg_id<'".$preid."' order by answerpacket_reg_id desc LIMIT 1")->queryScalar(); 

                if($check_mark['part_a_mark']!='')
                {
                    $part_a_mark=explode(",", $check_mark['part_a_mark']);
                    $part_b_mark=explode(",", $check_mark['part_b_mark']);
                    $part_c_mark=explode(",", $check_mark['part_c_mark']);
                }
                else
                {
                    $part_a_mark=$part_b_mark=$part_c_mark='';
                }
                
                $part_a_total=$check_mark['part_a_total'];        
                $part_b_total=$check_mark['part_b_total'];        
                $part_c_total=$check_mark['part_c_total'];
                $grand_total=$check_mark['grand_total'];

                $sub_map= Yii::$app->db->createCommand("SELECT subject_mapping_id as subject_map_id FROM coe_answerpack_regno WHERE exam_year='" . $year . "' AND exam_month='" . $month . "' AND stu_reg_no='" . $nextregno . "' AND answer_packet_number='" . $get_verifyed_data['subject_pack_i'] . "'")->queryOne();

                $getabsent = Yii::$app->db->createCommand("SELECT * FROM coe_absent_entry A JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.absent_student_reg JOIN coe_student C ON C.coe_student_id=B.student_rel_id WHERE C.register_number='" . $nextregno . "' AND A.exam_subject_id='" . $sub_map['subject_map_id'] . "' AND A.exam_year='" . $check_mark['year'] . "' AND A.exam_month='" . $check_mark['month'] . "' ")->queryone();

                $absent_regno='';
                if(!empty($getabsent))
                {
                    $absent_regno=$nextregno;
                }

                $data1[]=array('result' =>$result , 'absent_regno' =>$absent_regno , 'displayregno'=>$nextregno, 'firstregno'=>$firstregno, 'nextregno'=>$nextregno, 'lastregno'=>$lastregno, 'prevregno'=>$prevregno, 'parta'=>$part_a_mark, 'part_a_total'=>$part_a_total, 'partb'=>$part_b_mark, 'part_b_total'=>$part_b_total, 'partc'=>$part_c_mark, 'part_c_total'=>$part_c_total, 'grand_total'=>$grand_total);
            }
           else
           {
            
                $anspackserail=substr($get_verifyed_data['subject_pack_i'],0,3);
                $anspacknum=substr($get_verifyed_data['subject_pack_i'],3);

                 $sub_map= Yii::$app->db->createCommand("SELECT subject_mapping_id as subject_map_id FROM coe_answerpack_regno WHERE exam_year='" . $year . "' AND exam_month='" . $month . "' AND stu_reg_no='" . $nextregno . "' AND answer_packet_number='" . $get_verifyed_data['subject_pack_i'] . "'")->queryOne();

                $getabsent = Yii::$app->db->createCommand("SELECT * FROM coe_absent_entry A JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.absent_student_reg JOIN coe_student C ON C.coe_student_id=B.student_rel_id WHERE C.register_number='" . $nextregno . "' AND A.exam_subject_id='" . $sub_map['subject_map_id'] . "' AND A.exam_year='" .  $year . "' AND A.exam_month='" . $month . "' ")->queryone();

                $absent_regno='';
                if(!empty($getabsent))
                {
                    $absent_regno=$nextregno;
                }

                $data1[]=array('result' =>$result ,  'absent_regno' =>$absent_regno ,  'displayregno'=>$nextregno, 'prevregno'=>'', 'firstregno'=>$firstregno, 'nextregno'=>$nextregno, 'lastregno'=>$lastregno);
           }
            
        }
        else
        {
            $data1[]=array('result' =>$result ,'absent_regno' =>'' , 'firstregno'=>'', 'nextregno'=>'', 'lastregno'=>'');
        }

        return Json::encode($data1);
    }