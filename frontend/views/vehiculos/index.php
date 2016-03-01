<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;

use frontend\models\Vehiculos;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VehiculosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vehiculos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php 
	Pjax::begin();
	//Yii::trace($searchModel);
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
				'attribute'=>'id',
					'options'=>['style'=>'width:75px',], 
				
            ],
            'patente',
			'marca',
            'modelo',
            'color',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',
            // 'motivo_baja',

            [
				'attribute'=>'estado',
                'value'=>function($data) {return Vehiculos::getEstados($data->estado);},
                'filter'=>$searchModel->estados,
            ],           

           ['class' => 'yii\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Alta de vehiculo'),]),

             'headerOptions'=>['style'=>'text-align:center'],   
             'options'=>['style'=>'width:70px;text-align:"center";'],  
			 'template' => '{view}',       
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
