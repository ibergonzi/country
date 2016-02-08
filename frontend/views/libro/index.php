<?php

use yii\helpers\Html;
use yii\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Libro de guardia');
$this->params['breadcrumbs'][] = $this->title;
		
?>
<div class="libro-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php 
			echo Collapse::widget([
			'items'=>[
					[
					'label'=> 'Buscar por rango de fechas',
					'content'=>$this->render('_searchfec', ['model' => $searchModel])
					]
				]
			]);
    ?>
	
	<?php Pjax::begin(['id' => 'grilla', 'timeout' => false ,'clientOptions' => ['method' => 'POST'] ]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           ['attribute'=>'idporton',
				'options'=>['style'=>'width:55px',], 
				
            ],        
            //'created_by',
           [
                'attribute'=>'descUsuario',
                'value'=>'userCreatedBy.username',  
                'options'=>['style'=>'width:140px',],  
           ],                   
 
            //'created_at',
            ['attribute'=>'created_at',
             'options'=>['style'=>'width:160px',],             
             'format'=>['datetime'],
            
             'filter' => DateControl::widget([
				'model'=>$searchModel,
				'attribute'=>'created_at',
				'type' =>DateControl::FORMAT_DATE,
                'options' => [
					'pluginEvents'=>[ 'clearDate'=>'function(e) { $.pjax.reload({container:"#grilla"});}'						],	

					],
                
				])
				
            ],            
            'texto',
            // 'updated_by',
            // 'updated_at',

           ['class' => 'yii\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Agregar texto'),]),

             'headerOptions'=>['style'=>'text-align:center'],   
             'options'=>['style'=>'width:70px'],         
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?>
</div>
