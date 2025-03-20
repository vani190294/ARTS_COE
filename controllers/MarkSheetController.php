<?php
namespace app\controllers;
use Yii;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\db\Query;
use yii\helpers\Json;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigUtilities;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\models\Student;
error_reporting(0);

class MarkSheetController extends Controller
{

	public function actionSemMarkStatement()
    {
        $model = new MarkEntry();
        $student = new Student();
        
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $det_transfer= Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Transfer%'")->queryScalar();


        if (isset($_POST['get_marks']) && Yii::$app->request->post() && !empty($_POST['bat_map_val'])) 
        {            
            
            $count_of_subs = $_POST['count_of_subs']=='' || empty($_POST['count_of_subs'])?23:$_POST['count_of_subs'];
            $mark_statement_type = $_POST['deg_credit_type'];
            $with_umis=$_POST['with_umis'];
            
            $batch_map_id=$_POST['bat_map_val'];
            $year=$_POST['MarkEntry']['year'];
            $month=$_POST['MarkEntry']['month'];
            $batch_id=$_POST['bat_val'];
            $from_reg=$_POST['from_reg'];
            $to_reg= $_POST['to_reg'];

            $print_date= $_POST['print_date'];

            $query = new  Query();
            $query->select('G.paper_no, F.subject_map_id, F.student_map_id, H.subject_code,  A.name, A.register_number, G.semester, H.subject_name,A.dob,A.gender, H.credit_points, H.ESE_min,H.ESE_max, H.CIA_max,H.CIA_min, H.end_semester_exam_value_mark as sub_total_marks, B.course_batch_mapping_id,K.description as month, F.month as add_month , C.regulation_year,F.year, F.ESE,F.CIA, F.total,F.result,F.withheld,F.grade_name, F.grade_point, D.degree_code,D.degree_name,E.programme_name,F.year_of_passing, sum(H.credit_points) as cumulative, part_no, I.batch_name, D.degree_type, E.programme_code, A.UMISnumber, D.degree_total_semesters')
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id ')
                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                ->join('JOIN', 'coe_programme as E', 'E.coe_programme_id=C.coe_programme_id')
                ->join('JOIN', 'coe_batch as I', 'I.coe_batch_id=C.coe_batch_id')
                ->join('JOIN', 'coe_mark_entry_master as F', 'F.student_map_id=B.coe_student_mapping_id')
                ->join('JOIN', 'coe_category_type as K', 'K.coe_category_type_id=F.month')
                ->join('JOIN', 'coe_subjects_mapping as G', 'G.coe_subjects_mapping_id=F.subject_map_id')
                ->join('JOIN', 'coe_subjects H', 'H.coe_subjects_id=G.subject_id');
            $query->Where(['B.course_batch_mapping_id' => $batch_map_id, "C.coe_batch_id" => $batch_id, 'F.year' => $year, 'F.month' => $month, 'A.student_status' => 'Active'])->andWhere(['<>', 'G.course_type_id', 231])->andWhere(['<>', 'G.course_type_id', 232])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['between', "A.register_number", $from_reg, $to_reg]);
            $query->groupBy('F.student_map_id')
                ->orderBy('A.register_number');
               
            $mark_statement = $query->createCommand()->queryAll();

            //$_SESSION['degree_info'] = $deg_info;

            if (!empty($mark_statement)) 
            {        
                require(Yii::getAlias('@webroot')."/views/mark-sheet/sem-mark-statement-pdf.php");

            } 
            else 
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No data Found ");
                return $this->redirect(['mark-sheet/sem-mark-statement']);
            }
        } else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Semester Mark Statement');
            return $this->render('sem-mark-statement', [
                'model' => $model,
                'student' => $student,
            ]);
        }
    }

}