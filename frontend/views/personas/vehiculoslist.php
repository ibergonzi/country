<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Vehiculos;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VehiculosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vehiculos');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vehiculos-index">

<?php 

		$columns=[
			'id',
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
             ],           


        ];

?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
     
        'columns' => $columns,
		//'condensed'=>true, 

		'layout'=>'{items}{pager}',
           
    ]); ?>


</div>
