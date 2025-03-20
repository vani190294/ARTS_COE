<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
if(isset($_SESSION['dataProvider']))
{
	?>
	<?= GridView::widget([
        'dataProvider' => $_SESSION['dataProvider'],
		'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

		    [
				'label' =>  'Student Name',
				'attribute' => 'name',
				'value' => 'name',
	 	    ],
		
		    [
				'label' =>  'Register Number',
				'attribute' => 'register_number',
				'value' => 'register_number',
	 	    ],
	        [
				'label' =>  'Mobile Number',
				'attribute' => 'mobile_no',
				'value' => 'mobile_no',
	 	    ],   
	  
        ],
    ]); ?>
    <?php } unset($_SESSION['dataProvider']); ?>