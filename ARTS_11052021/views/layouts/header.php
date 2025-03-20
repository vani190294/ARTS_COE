 <?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\LoginDetails;
/* @var $this \yii\web\View */
/* @var $content string */
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
?>
      <header class="main-header">
        <nav class="navbar navbar-static-top">
          <div class="container-fluid">
          <div class="navbar-header">
           <!--  <img style="background: white; width: 60px;" src="<?php echo Yii::getAlias('@web').'/images/skacas.png' ?>" /> -->
            <?= Html::a('<span class="logo-lg  shiny" ><span class="inner-shiny1"> <span class="inner-shiny"> '.Yii::$app->params['app_name'].'</span> </span> </span>', Yii::$app->homeUrl, ['class' => 'logo']) ?> 
            
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="navbar-collapse">
           <?php 

           $checkAccess = ConfigUtilities::RevalHasAccess(Yii::$app->user->getId()); 
           if(!empty($checkAccess))
            {
                $display_menu = [
                    'label' => "Revaluation",
                    'icon' => 'user-circle-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->can('/mark-entry/revaluation'),
                    'options' => ['class' => 'dropdown-submenu',],
                    'items' => [
        
                        [
                            'label' => "Application",
                            'icon' => 'window-maximize',
                            'url' => ['/mark-entry/revaluationentry',
                                'visible' => Yii::$app->user->can('/mark-entry/revaluation'),
                            ],
                        ],
                    ],
                ];
            }
            else
            {
                $display_menu = [];
            }
            $items = [];
            $condition = $org_email=='coe@skasc.ac.in'?1:'';
            if ($condition) {
                $items[] = [];
            } else {
                
            }
           ?>
            <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'nav navbar-nav',],
                'items' => [
                    
                    ['label' => 'Home', 'icon' => 'home', 'url' => ['/site/index']],


                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 
                        'icon' => 'cogs', 
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/configuration/create'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [

                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 'icon' => 'hand-o-right', 'url' => ['/configuration/create'],'visible' =>Yii::$app->user->can('/configuration/create'),],

                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY), 'icon' => 'hand-o-right', 'url' => ['/categories/create'], 'visible' => Yii::$app->user->can('/categories/create'),],
                            ['label' => "Institute Details", 'icon' => 'hand-o-right', 
                            'url' => ['/configuration/organisation-info'],
                            'visible' =>Yii::$app->user->can('/configuration/organisation-info'),],
                            
                        ],
                    ], 
                    
                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 
                        'icon' => 'graduation-cap', 
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/degree/index') || Yii::$app->user->can('/subjects-mapping/index'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [
                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 'icon' => 'hand-o-right', 'url' => ['/degree/index'],'visible' => Yii::$app->user->can('/degree/index'),],
                            
                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'icon' => 'hand-o-right', 'url' => ['/programme/index'],'visible' => Yii::$app->user->can('/programme/index'),],
                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH), 'icon' => 'hand-o-right', 'url' => ['/batch/index'],'visible' => Yii::$app->user->can('/batch/index'),
                                'options' => ['class' => 'dropdown-submenu', 'data-widget'=> 'tree'],
                                    'items' => [
                                        ['label' => 'New Regulation',
                                             'icon' => 'hand-o-right', 
                                             'visible' => Yii::$app->user->can('/regulation/index'),
                                             'url' => ['/regulation/index'],
                                        ],
                                        ['label' => 'Regulation Details',
                                             'icon' => 'hand-o-right', 
                                             'visible' => Yii::$app->user->can('/coe-bat-deg-reg/index'),
                                             'url' => ['/coe-bat-deg-reg/index'],
                                        ],
                                        ['label' => 'Classifications',
                                             'icon' => 'hand-o-right', 
                                             'visible' => Yii::$app->user->can('/classifications/index'),
                                             'url' => ['/classifications/index'],
                                        ],
                                        
                                    ], 
                            ],
                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 
                            'icon' => 'hand-o-right', 
                            'url' => ['/subjects-mapping/index'],
                            'visible' => Yii::$app->user->can('/subjects-mapping/index'),
                                    
                                    'options' => ['class' => 'dropdown-submenu', 'data-widget'=> 'tree'],
                                    'items' => [
                                        ['label' => 'Migrate',
                                             'icon' => 'hand-o-right',
                                             'visible' => Yii::$app->user->can('/subjects/migrate'), 
                                             'url' => ['/subjects/migrate'],
                                        ],
                                        
                                        
                                    ],                                
                            ],

                             ['label' => "Value Added -Arts", 
                            'icon' => 'hand-o-right', 
                            'url' => ['/sub/index'],
                            'visible' => Yii::$app->user->can('/sub/index'),
                                    
                                    'options' => ['class' => 'dropdown-submenu', 'data-widget'=> 'tree'],
                                    'items' => [
                                        ['label' => 'View Value Added Marks',
                                             'icon' => 'hand-o-right',
                                             'visible' => Yii::$app->user->can('/coe-value-mark-entry/create'), 
                                             'url' => ['coe-value-mark-entry/create'],
                                        ],
                                        
                                        
                                    ],                                
                            ],

                            
                            
                            [
                                'label' => "Additional Credits - ARTS", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry/additionalcredits-arts'),
                                'url' => ['/mark-entry/additionalcredits-arts'], 
                                'visible' => Yii::$app->user->can('/mark-entry/additionalcredits-arts'),
                                 'options' => ['class' => 'dropdown-submenu', 'data-widget'=> 'tree'],
                                    'items' => [
                                        [   
                                            'label' => 'Update Name',
                                             'icon' => 'hand-o-right', 
                                             'visible' => Yii::$app->user->can('/mark-entry/additionalcredits-update'), 
                                             'url' => ['/mark-entry/additionalcredits-update'],
                                        ],
                                        [   
                                            'label' => 'Additional Credits Internet Copy',
                                             'icon' => 'hand-o-right', 
                                             'visible' => Yii::$app->user->can('/mark-entry/internet-copy-additional-credits'), 
                                             'url' => ['/mark-entry/internet-copy-additional-credits'],
                                        ],
                                                    
                                    ], 
                            ],
                        ],
                    ],                  
                    


                                      
                    //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    
                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 
                        'icon' => 'id-card', 
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/student/index'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [

                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/student/index'),
                                'url' => ['/student/index'],
                            ],
                            [
                                'label' => "Transfer ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/student-category-details/index'),
                                'url' => ['/student-category-details/index'],
                            ],
                            [
                                'label' => "Transfer Certificate", 
                                'icon' => 'hand-o-right',
                                'visible' => Yii::$app->user->can('/mark-entry/transfer-certificate'),
                                'options' => ['class' => 'dropdown-submenu', 'data-widget'=> 'tree'],
                                        'items' => [
                                            [   
                                                'label' => 'Generate New',
                                                 'icon' => 'hand-o-right',
                                                 'url' => ['/mark-entry/transfer-certificate'],
                                                 'visible' => Yii::$app->user->can('/mark-entry/transfer-certificate'),
                                            ],
                                            [
                                              'label' => "Edit Information", 
                                              'icon' => 'hand-o-right',
                                              'visible' => Yii::$app->user->can('/transfer-certificates/index'),
                                              'url' => ['/transfer-certificates/index'],
                                          ],
                                                        
                                    ],
                            ],

                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Bulk Edit", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/student/bulkupdate'),
                                'url' => ['/student/bulkupdate'], 
                            ],
                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Status List", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/student/stu-status-list'),
                                'url' => ['/student/stu-status-list'], 
                            ],
                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL), 
                                'icon' => 'hand-o-right', 
                                'url' => ['/nominal/create'], 
                                'visible' => Yii::$app->user->can('/nominal/create'),
                                        
                            ],
                            [
                              'label' => 'Rejoin Eqvalent',
                               'icon' => 'hand-o-right',
                               'visible' => Yii::$app->user->can('/mark-entry/rejoin-equvalent'),
                               'url' => '#',
                               'options' => ['class' => 'dropdown-submenu',],
                               'items' => [
                                
                                        [
                                            'label' => 'Entry', 
                                            'icon' => 'hand-o-right', 
                                            'visible' => Yii::$app->user->can('/mark-entry/rejoin-equvalent'),
                                            'url' => ['/mark-entry/rejoin-equvalent']
                                        ],
                                        [
                                            'label' =>'Delete', 
                                            'icon' => 'hand-o-right', 
                                            'visible' => Yii::$app->user->can('/mark-entry/delete-eqvalent'),
                                            'url' => ['/mark-entry/delete-eqvalent'],
                                        ],
                                    ],
                            ],
                            
                            
                        ],
                    ], 

                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM), 
                        'icon' => 'calendar', 
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/exam-timetable/index'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [

                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM), 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/exam-timetable/index'),
                                'url' => ['/exam-timetable/index'],
                            ],
                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Application", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/student/student-exam-application'),
                                'url' => ['/student/student-exam-application'], 
                            ],
                            [
                                'label' => " Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Timetable", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/exam-timetable/export-exam-timetable'),
                                'url' => ['/exam-timetable/export-exam-timetable'],
                            ],
                            
                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),

                                'icon' => 'hand-o-right', 
                                'url' => ['/exam-timetable/absent'],
                                'visible' => Yii::$app->user->can('/exam-timetable/absent'),
                                        
                                'options' => ['class' => 'dropdown-submenu', 
                                        
                                        'data-widget'=> 'tree'],
                                        'items' => [

                                            ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date Wise Entry',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/exam-timetable/exam-date-wise-absent'),
                                                 'url' => ['/exam-timetable/exam-date-wise-absent'],
                                            ],
                                            ['label' => 'Hall Wise Entry',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/exam-timetable/hall-wise-absent'),
                                                 'url' => ['/exam-timetable/hall-wise-absent'],
                                            ],
                                             ['label' => 'Practical Entry',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/exam-timetable/absent'),
                                                 'url' => ['/exam-timetable/absent'],
                                            ],
                                            ['label' => 'View '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/exam-timetable/view-absent'),
                                                 'url' => ['/exam-timetable/view-absent'],
                                            ],
                                            ['label' => 'Delete '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/exam-timetable/delete-absent'),
                                                 'url' => ['/exam-timetable/delete-absent'],
                                            ],
                                ], 

                            ],

                            [   
                                'label' =>'External Score Card', 
                                'icon' => 'hand-o-right', 
                                'url' => ['/exam-timetable/external-format'],
                                'visible' => Yii::$app->user->can('/exam-timetable/external-format'),                                        
                                

                            ],
                        ],
                    ],

                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),
                        'icon' => 'font', 
                        'url' => ['/exam-timetable/absent'],
                        'visible' => Yii::$app->user->can('/exam-timetable/absent'),                                
                        'options' => [  
                                        'class' => 'dropdown-submenu', 
                                        'id'=>'get_ab_label_name',
                                        'data-widget'=> 'tree'
                            ],       

                    ],

                    [
                        'label' => "Galley", 
                        'icon' => 'user-circle-o', 
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/hall-master/index'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [

                            [
                                'label' => "Hall Master", 
                                'icon' => 'hand-o-right', 
                                'url' => ['/hall-master/index',
                                'visible' => Yii::$app->user->can('/hall-master/index'),
                            ],
                            ],

                            [
                                'label' => "Galley Arrangement", 
                                'icon' => 'hand-o-right', 
                                'url' => ['/hall-allocate/create'],
                                'visible' => Yii::$app->user->can('/hall-allocate/create'), 
                            ],
                           
                           
                            
                        ],
                    ],

                    
                    /* Revaluation Module */
                    //$display_menu,
                    
                    
                    [
                        'label' => "Marks", 
                        'icon' => 'wpforms', 
                        'url' => '#', 
                        'visible' => Yii::$app->user->can('/mark-entry/revaluation') || Yii::$app->user->can('/practical-entry/create') || Yii::$app->user->can('/mark-entry-master/view-external-markentry-arts'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [
                            [
                              'label' => "Fees Paid",
                               'icon' => 'hand-o-right',
                               'url' => '#',
                               'visible' => Yii::$app->user->can('/mark-entry-master/fees-paid') || Yii::$app->user->can('/mark-entry-master/view-fees-paid'),
                               'options' => ['class' => 'dropdown-submenu', 
                                            'data-widget'=> 'tree'],
                                            'items' => [
                                                [
                                                  'label' => 'Entry',
                                                   'icon' => 'hand-o-right',
                                                   'visible' => Yii::$app->user->can('/mark-entry-master/fees-paid'),
                                                   'url' => ['/mark-entry-master/fees-paid'],
                                                ],
                                                
                                                [
                                                  'label' => 'View',
                                                   'icon' => 'hand-o-right',
                                                   'visible' => Yii::$app->user->can('/mark-entry-master/view-fees-paid'),
                                                   'url' => ['/mark-entry-master/view-fees-paid'],
                                                ],
                                                
                                    ],
                            ],
                            [
                                'label' => "Practical Entry", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/practical-entry/create'),
                                'url' => '#',
                                'options' => ['class' => 'dropdown-submenu', 
                                            'data-widget'=> 'tree'],
                                            'items' => [
                                                ['label' => "Entry Without ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM),
                                                     'icon' => 'hand-o-right',
                                                     'visible' => Yii::$app->user->can('/practical-entry/create'),
                                                     'url' => ['/practical-entry/create'],
                                                ],
                                                
                                                ['label' => "Approve",
                                                     'icon' => 'hand-o-right',
                                                     'visible' => Yii::$app->user->can('/practical-entry/verify-and-migrate'),
                                                     'url' => ['/practical-entry/verify-and-migrate'],
                                                ],
                                                ['label' => "Re-Print",
                                                     'icon' => 'hand-o-right',
                                                     'visible' => Yii::$app->user->can('/practical-entry/re-print-sheet'),
                                                     'url' => ['/practical-entry/re-print-sheet'],
                                                ],
                                                
                                    ],
                            ],
                            [
                                'label' => "Internal", 
                                'icon' => 'hand-o-right', 
                                'visible' => (Yii::$app->user->can('/categorytype/internal-arrear-mark-entry') || Yii::$app->user->can('/mark-entry-master/view-external-markentry-arts') ),
                                'url' => ['/categorytype/internal-arrear-mark-entry'],
                                'options' => ['class' => 'dropdown-submenu', 
                                            'data-widget'=> 'tree'],
                                            'items' => [
                                               
                                        ['label' => "View Marks",
                                             'icon' => 'hand-o-right',
                                             'visible' => Yii::$app->user->can('/practical-entry/re-print-sheet') || Yii::$app->user->can('/mark-entry-master/view-external-markentry-arts'),
                                             'url' => ['/categorytype/view-internals'],
                                        ],
                                                
                                    ],
                                
                            ],
                            [
                                'label' => "External",
                                'icon' => 'hand-o-right',
                                'visible' => ( Yii::$app->user->can('/mark-entry-master/external-markentry-engg') || Yii::$app->user->can('/mark-entry-master/view-external-markentry-arts')),
                                'url' => '#', 
                                'options' => ['class' => 'dropdown-submenu','data-widget'=> 'tree'],
                                        'items' => [
                                            
                                            [
                                                'label' => "Mark Entry",
                                                'icon' => 'hand-o-right',
                                                'visible' => Yii::$app->user->can('/mark-entry-master/external-markentry-arts'),
                                                'url' => ['/mark-entry-master/external-markentry-arts'],
                                                
                                            ],
                                            [
                                                'label' => "View Marks",
                                                'icon' => 'hand-o-right',
                                                'visible' => Yii::$app->user->can('/mark-entry-master/view-external-markentry-arts'),
                                                'url' => ['/mark-entry-master/view-external-markentry-arts'],
                                            ],
                                            
                                            
                                ],
                            ],
                            [
                                'label' => "Internal Mode Entry", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry-master/internal-mode-mark-entry'),
                                'url' => '#',
                                'options' => ['class' => 'dropdown-submenu',],
                                'items' => [
                                        [
                                            'label' => "Mark Entry",
                                            'icon' => 'hand-o-right', 
                                            'visible' => Yii::$app->user->can('/mark-entry-master/internal-mode-mark-entry'),
                                            'url' => ['/mark-entry-master/internal-mode-mark-entry'],
                                        ],
                                        [
                                            'label' => "View Marks",
                                            'icon' => 'hand-o-right', 
                                            'visible' => Yii::$app->user->can('/mark-entry-master/view-internal-mode-mark-entry'),
                                            'url' => ['/mark-entry-master/view-internal-mode-mark-entry'],
                                        ],
                                ]
                            ],
                            
                            [
                                'label' => "Export CIA FORMAT", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry-master/export-cia-marks'),
                                'url' => ['/mark-entry-master/export-cia-marks'],
                                    'options' => ['class' => 'dropdown-submenu', 
                                            'data-widget'=> 'tree'],
                                            'items' => [
                                               
                                                ['label' => "Import CIA Marks",
                                                     'icon' => 'hand-o-right',
                                                     'visible' => Yii::$app->user->can('/mark-entry-master/import-cia-marks'),
                                                     'url' => ['/mark-entry-master/import-cia-marks'],
                                                ],
                                                
                                    ], 
                            ],
                            

                            ['label' => "Moderation",

                                'icon' => 'hand-o-right', 
                                'url' => ['/mark-entry/moderation'],
                                'visible' => Yii::$app->user->can('/mark-entry/moderation'),
                                        
                                        'options' => ['class' => 'dropdown-submenu', 
                                        'data-widget'=> 'tree'],
                                        'items' => [
                                            ['label' => 'View Moderation',
                                                 'icon' => 'hand-o-right', 
                                                 'url' => ['/mark-entry/viewmoderation'],
                                                 'visible' => Yii::$app->user->can('/mark-entry/viewmoderation'),
                                            ],
                                            
                                    ], 

                            ],
                            [   
                                'label' => "Verify Marks",
                                'icon' => 'hand-o-right',
                                'visible' => Yii::$app->user->can('/mark-entry-master/verify-marks'),
                                'url' => '#',
                                'options' => ['class' => 'dropdown-submenu',],
                                'items' => [
                                            
                                            [ 'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." WISE",
                                                 'icon' => 'hand-o-right', 
                                                 'url' => ['/mark-entry-master/verify-marks'],
                                                 'visible' => Yii::$app->user->can('/mark-entry-master/verify-marks'),
                                            ],
                                            [
                                            'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' Wise',
                                                 'icon' => 'hand-o-right', 
                                                 'url' => ['/mark-entry-master/verify-marks-arts'],
                                                 'visible' => Yii::$app->user->can('/mark-entry-master/verify-marks-arts'),
                                            ],
                                    ], 
                                        
                            ],
                            [
                                'label' => "Update Marks",
                                 'icon' => 'hand-o-right',
                                 'visible' => Yii::$app->user->can('/mark-entry-master/create'),
                                 'url' => ['/mark-entry-master/create'],
                            ],
                            [   'label' => "Import Marks",
                                 'icon' => 'hand-o-right',
                                 'visible' => Yii::$app->user->can('/mark-entry-master/subject-wise-import'),
                                 'url' => ['/mark-entry-master/subject-wise-import'],
                            ],
                            [
                                'label' => "Withheld", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry/withheld'),
                                'url' => ['/mark-entry/withheld'], 
                            ],
                            
                            [
                                'label' => "Revaluation", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry/revaluationentry'),
                                'url' => '#', 
                                'options' => ['class' => 'dropdown-submenu', 
                                        'data-widget'=> 'tree'],
                                        'items' => [
                                            [   'label' => 'Application',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/revaluationentry'),
                                                 'url' => ['/mark-entry/revaluationentry'],
                                            ],
                                            
                                            [   'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Wise Entry',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/revaluation-subject-entry'),
                                                 'url' => ['/mark-entry/revaluation-subject-entry'],
                                            ],
                                            [   'label' => 'View Revaluation',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/viewrevaluation'),
                                                 'url' => ['/mark-entry/viewrevaluation'],
                                            ],
                                            [   'label' => 'View Transparency',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/view-transparency'),
                                                 'url' => ['/mark-entry/view-transparency'],
                                            ],
                                            [   'label' => 'UPDATE TRANSPARENCY',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/update-transparency-dept'),
                                                 'url' => ['/mark-entry/update-transparency-dept'],
                                            ],
                                            [   'label' => 'View Transparency DEPT',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/view-transparency-dept'),
                                                 'url' => ['/mark-entry/view-transparency-dept'],
                                            ],
                                            [   'label' => 'View Transparency '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/view-transparency-subject'),
                                                 'url' => ['/mark-entry/view-transparency-subject'],
                                            ],
                                            [   'label' => 'Register Number Entry',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/revaluation'),
                                                 'url' => ['/mark-entry/revaluation'],
                                            ],
                                            [   'label' => 'Reval Fees Paid List',
                                                 'icon' => 'hand-o-right', 
                                                 'visible' => Yii::$app->user->can('/mark-entry/revalfeespaid'),
                                                 'url' => ['/mark-entry/revalfeespaid'],
                                            ],
                                            
                                        ], 
                            ],
                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Mark View", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry/studentmark-view'),
                                'url' => ['/mark-entry/studentmark-view'], 
                            ],
                            [
                                'label' => "Border Line Marks", 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/mark-entry-master/borderline-marks'),
                                'url' => ['/mark-entry-master/borderline-marks'], 
                            ],
                           
                        ],                       
                    ],

                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_USER), 
                        'icon' => 'user-secret',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('/rbac/default/index'),
                        'options' => ['class' => 'dropdown-submenu',],
                        'items' => [

                            [
                                'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_USER),
                                'icon' => 'hand-o-right', 
                                'url' => ['/rbac/user'],
                                'visible' => Yii::$app->user->can('/rbac/default/index'),
                               
                            ],

                            [
                                'label' => 'Assignments',
                                'icon' => 'hand-o-right',  
                                'url' => ['/rbac/assignment'],
                                'visible' => Yii::$app->user->can('/rbac/assignment/index'),
                            ],
                            [
                                'label' => 'Roles', 
                                'icon' => 'hand-o-right', 
                                'url' => ['/rbac/role'],
                                'visible' => Yii::$app->user->can('/rbac/role/index'),
                            ],
                            [
                                'label' => 'Permissions', 
                                'icon' => 'hand-o-right', 
                                'url' => ['/rbac/permission'],
                                'visible' => Yii::$app->user->can('/rbac/permission/index'),
                            ],
                            [
                                'label' => 'Access Url\'s', 
                                'icon' => 'hand-o-right', 
                                'visible' => Yii::$app->user->can('/rbac/route/index'),
                                'url' => ['/rbac/route'],
                            ],
                           
                           
                            
                        ],
                    ],
                   
                    
                    [
                        'label' => 'Import', 
                        'icon' => 'files-o', 
                        'url' => ['/import/index'],
                        'visible' => Yii::$app->user->can('/import/index'),
                    ],
                    [
                        'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT), 
                        'icon' => 'download', 
                        'url' => ['/reports/index'],
                        'visible' => Yii::$app->user->can('/reports/index'),
                    ],
                ],
            ]
        ) ?>

           <!-- Right Side Menu -->
           <?php if(isset(Yii::$app->user->identity->username)) { ?>
           <ul class="nav navbar-nav navbar-right">             

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo Yii::getAlias('@web').'/images/admin.png'; ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo Yii::getAlias('@web').'/images/admin.png'; ?>" class="img-circle"
                                 alt="User Image"/>
                            <p class="admin_name_display">
                                <?php  
                                    $loginDetails = LoginDetails::find()->where(['login_ip_address'=>LoginDetails::get_ip_address(),'login_user_id'=>Yii::$app->user->getId(),'login_status'=>1])->orderBy('login_detail_id DESC')->one();
                                    $last_login = !empty($loginDetails)? DATE('d-m-Y : H:i:s',strtotime($loginDetails->login_at)):"No Data";
                                    $login_ip = !empty($loginDetails)?$loginDetails->login_ip_address:"No Data ";
                                    
                                    ?>
                                <small>Last Login : <?php echo "<b>".$last_login."</b> <br />From IP: <b>".$login_ip."</b>"; ?> </small>
                            </p>
                        </li>
                       
                        <!-- Menu Footer-->
                        <li class="user-body">
                            <div class="col-xs-4 pull-right text-center">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                               
                            </div>
                            <div class="col-xs-4 pull-left text-center">
                                 <?= Html::a(
                                    'Change Password',
                                    ['/site/change-password'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                               
                            </div>
                            
                           
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                
            </ul>
            <?php } ?>
           <!-- Right Side Menu -->

          </div><!-- /.navbar-collapse -->

          
          </div><!-- /.container-fluid -->
        </nav>
      </header>
