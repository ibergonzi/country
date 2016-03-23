<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Accesos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
 
        'columns' => [
            'id_acceso',
            'id_persona',
 		    [
				'attribute'=>'r_apellido',
				'value'=>'persona.apellido',
			],
			[
				'attribute'=>'r_nombre',
				'value'=>'persona.nombre',
			],
			[
				'attribute'=>'r_nombre2',
				'value'=>'persona.nombre2',
			],			
			[
				'attribute'=>'r_desc_tipo_doc',
				'value'=>'persona.tipoDoc.desc_tipo_doc_abr',
			],			
            //'persona.tipoDoc.desc_tipo_doc_abr',
            'persona.nro_doc',
            'ing_id_vehiculo',
            'ingVehiculo.patente',
            'ingVehiculo.marca',
            'ingVehiculo.modelo',
            'ingVehiculo.color',
            'ing_fecha',
            'ing_hora',
            'ing_id_porton',
 		    [
				'attribute'=>'r_ing_usuario',
				'value'=>'userIngreso.username',  
		    ],
            'egr_id_vehiculo',
            'egr_fecha',
            'egr_hora',
            'egr_id_porton',
 		    [
				'attribute'=>'r_egr_usuario',
				'value'=>'userEgreso.username',  
		    ],                
            'id_concepto',
            'motivo',
            'control',
            'id_autorizante',
            'id_uf',
            'estado',
            'motivo_baja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
