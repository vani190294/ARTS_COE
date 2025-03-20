<?php

namespace app\controllers;

use Yii;
use app\models\Scrutiny;
use app\models\ScrutinySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;
use kartik\mpdf\Pdf;
use app\models\Signup;

/**
 * ScrutinyController implements the CRUD actions for Scrutiny model.
 */
class ScrutinyController extends Controller
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
     * Lists all Scrutiny models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScrutinySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Scrutiny model.
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
     * Creates a new Scrutiny model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Scrutiny();

        if ($model->load(Yii::$app->request->post())) {


            $name = $_POST['Scrutiny']['name']; 
            $email = $_POST['Scrutiny']['email'];
            $phone_no =  $_POST['Scrutiny']['phone_no'];

            if ($model->validate()) {

                $check_user = Yii::$app->db->createCommand('SELECT count(*) as count FROM user  WHERE email="' . $email . '"')->queryScalar();

                if (empty($check_user)) {

                    $created_at = new \yii\db\Expression('NOW()');
                    $model->created_at = new \yii\db\Expression('NOW()');
                    $model->created_by = Yii::$app->user->getId();
                    $model->save();

                    $userModel = new Signup();
                    $userModel->username = $email;
                    $userModel->password = $phone_no;
                    $userModel->ConfirmPassword = $phone_no;
                    $userModel->email = $email;
                    $userModel->signup();

                    $user_id_LAST = Yii::$app->db->createCommand('SELECT id FROM user order by id desc limit 1')->queryScalar();
                    $Assing = Yii::$app->db->createCommand('INSERT INTO auth_assignment (`item_name`,`user_id`,`created_at`) values ("Scrutiny Access","'.$user_id_LAST.'","'.$created_at.'" )')->execute();


                    Yii::$app->ShowFlashMessages->setMsg('Success', ' SUCCESS INSERTED AND USER ID CREATED ');
                     return $this->redirect(['index']);
                } else {

                    Yii::$app->ShowFlashMessages->setMsg('error', 'User already exist! Unable to Create User');
                    return $this->redirect(['index']);
                }

                 return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->coe_scrutiny_id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Scrutiny model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
               
                $model->email = $model->getOldAttribute('email');
                $model->phone_no = $model->getOldAttribute('phone_no');
                $model->updated_at = new \yii\db\Expression('NOW()');
                $model->updated_by = Yii::$app->user->getId();
                $model->save();

                Yii::$app->ShowFlashMessages->setMsg('Success', ' Data Has been updated Successfully!! ');
                return $this->redirect(['view', 'id' => $model->coe_scrutiny_id]);
            } else {

                Yii::$app->ShowFlashMessages->setMsg('Error', ' Data  not updated Successfully!! ');
                return $this->render('update', [
                    'model' => $model,
                    'errors' => $model->errors,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Scrutiny model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->ShowFlashMessages->setMsg('Success', ' Data Has been Deleted Successfully!! ');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Scrutiny model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Scrutiny the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Scrutiny::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionScrutinyReport()
    {
        $model = new Scrutiny();
        return $this->render('scrutiny_report', [
            'model' => $model,
        ]);
    }

    public function actionGetscrutinyreportdata()
    { 
        $department = Yii::$app->request->post('department');
        $designation = Yii::$app->request->post('designation');

        $data='';
        if (isset($department) || isset($designation) || $designation != null || $department != null) {
            $query_1 = new Query();
            $data = $query_1->select("A.*,B.dept_code,C.category_type as designation")
                ->from('coe_valuation_scrutiny A')
                ->join(' LEFT JOIN', 'cur_department B', 'A.department = B.coe_dept_id')
                ->join('LEFT JOIN', 'coe_category_type C', 'A.designation = C.coe_category_type_id')
                ->where('A.department = :department OR A.designation = :designation', [
                    ':department' => $department,
                    ':designation' => $designation,
                ])->createCommand()->queryAll();
        }

        if (!empty($data)) 
        {
            $body = $header = '';

            $title = '<h3>Scrutiny Data<h3>';
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $header .= '<table width=100% style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_blk_eudit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                            <tr>
                            <td  colspan=2>
                                <img width="100" height="100" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=5 align="center"> 
                                  <center><b><font size="6px">' . $org_name . '</font></b></center>
                                  <center> <font size="3px">' . $org_address . '</font></center>
                                  <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                             </td>
                              <td  colspan=2 align="center">  
                                <img width="100" height="100" width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                              </td>
                             </tr>
                        ';

            $header .= '</table><table width=100% style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" >

                            <tr class="table-danger">
 
                                <th align="center">S.NO</th>
                                <th align="center">Name</th>
                                <th align="center">Designation</th>
                                <th align="center">Department</th>
                                <th align="center">Phone_number</th>
                                <th align="center">Email</th>
                                <th align="center">Account Number</th>
                                <th align="center">Ifsc Code</th>
                                <th align="center">Bank Name</th>
                                <th align="center">Branch</th>
                             
                            </tr>';

            $increment_val = 1;
            foreach ($data as $sampledata) {
                $body .= '<tr>';
                $body .= '<td width="30">' . $increment_val++ . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['name']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['designation']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['dept_code']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['phone_no']) . '</td>';
                $body .= '<td align="center">' . $sampledata['email'] . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['bank_accno']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['bank_ifsc']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['bank_name']) . '</td>';
                $body .= '<td align="center">' . strtoupper($sampledata['bank_branch']) . '</td>';

                $body .= '</tr>';
            }

            $body .= '</table>';

            $send_results = $header . $body;

            if (isset($_SESSION['scrutiny_show'])) {
                unset($_SESSION['scrutiny_show']);
            }

            $_SESSION['scrutiny_show'] = $header . $body;

            return Json::encode($send_results);
        } else {
            return Json::encode(0);
        }
    }

    public function actionScrutinyReportExel()
    {
        $data = $_SESSION['scrutiny_show'];

        $filter_table_header = '/<table[^>]*>(.*?)<\/table>/s';

        $content = preg_replace($filter_table_header, '', $data, 1);

        $fileName = "scrutiny_exel_" . date("Y-m-d h:i:sa") . '.xls';

        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

    public function actionScrutinyReportPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['scrutiny_show'];
        $pdf = new Pdf([

            'mode' => Pdf::MODE_UTF8,
            'filename' => "Scrutiny data report.pdf",
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: left;  font-family:"Roboto, sans-serif"; width:100%; } 
                        
                        table td{
                            text-align: left;
                            font-size: 12px;
                            line-height: 1.5em;
                        }
                        table th{
                            text-align: left;
                            font-size: 12px;
                            line-height:1.5em;
                        }
                    }   
                ',
            'options' => ['title' => "SCRUTINY REPORT"],
            'methods' => [
                'SetHeader' => ["SCRUTINY REPORT"],
                'SetFooter' => ["Scrutiny report  :" . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
}
