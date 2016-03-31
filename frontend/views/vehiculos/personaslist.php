<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Personas;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VehiculosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Personas');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="personas-index">

<?php 

		$columns=[
			'id',            
			'apellido',
            'nombre',
            'nombre2',
            [
				'attribute'=>'tipoDoc',
				'value'=>'tipoDoc.desc_tipo_doc_abr',
            ],
            //'id_tipo_doc',
            'nro_doc',
            // 'foto',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'motivo_baja',
            [
				'attribute'=>'estado',
                'value'=>function($data) {return Personas::getEstados($data->estado);},
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
