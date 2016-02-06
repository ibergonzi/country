<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Personas');
$this->params['breadcrumbs'][] = $this->title;

use svay\FaceDetector;

set_time_limit(90); 
$detector = new FaceDetector('detection.dat');
$detector->faceDetect('cuac.jpg');
//$detector->cropFaceToJpeg('img/petu.jpg');
$detector->toJpeg();
die;

?>
<div class="persona-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php echo $this->render('_searchfec', ['model' => $searchModel]); ?>
    

    <?php 
		/*
		echo Collapse::widget([
		'items'=>[
				[
				'label'=> 'Buscar por rango de fechas',
				'content'=>$this->render('_searchfec', ['model' => $searchModel])
				]
			]
		]);
		*/
    ?>

    
<?php //Pjax::begin(['id' => 'grilla', 'timeout' => false ,'clientOptions' => ['method' => 'POST'] ]); ?>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

           
            ['attribute'=>'id',
				'options'=>['style'=>'width:55px'], 
            ],
            'dni',
            'apellido',
            'nombre',
            'nombre2',
            ['attribute'=>'fecnac','format'=>['date'],
             'filter' => DateControl::widget([
				'model'=>$searchModel,
				'attribute'=>'fecnac',
				'type' =>DateControl::FORMAT_DATE,
                'options' => [
					'pluginEvents'=>[ 'clearDate'=>'function(e) { $.pjax.reload({container:"#grilla"});}'],	
					
					],
                
            ])
            ],
            // 'created_by',
            //'created_at',
            ['attribute'=>'created_at','format'=>['datetime']],
            ['attribute'=>'updated_at',
             'format'=>['datetime'],
            
             'filter' => DateControl::widget([
				'model'=>$searchModel,
				'attribute'=>'updated_at',
				'type' =>DateControl::FORMAT_DATE,
                'options' => [
					'pluginEvents'=>[ 'clearDate'=>'function(e) { $.pjax.reload({container:"#grilla"});}'						],	

					],
                
				])
				
            ],
            // 'updated_by',
           

            ['class' => 'yii\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Alta de persona'),]),

             'headerOptions'=>['style'=>'text-align:center'],   
             'options'=>['style'=>'width:70px'],         
            ],
        ],
    ]); ?>
<?php //Pjax::end(); ?>
</div>
