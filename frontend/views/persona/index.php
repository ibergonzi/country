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

?>
<div class="persona-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    


    <p>
        <?= Html::a(Yii::t('app', 'Create Persona'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo Collapse::widget([
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

            'id',
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
					'pluginEvents'=>[ 'clearDate'=>'function(e) { $.pjax.reload({container:"#grilla"});}'						],	

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
           

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
