<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Uf;
use frontend\models\UfTitularidad;
use frontend\models\UfTitularidadPersonas;
use frontend\models\MovimUf;
use yii\data\ActiveDataProvider;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */

$this->title = 'Titularidad U.F. ' . $model->id_uf;
$this->params['breadcrumbs'][] = ['label' => 'Uf Titularidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');

$this->registerCss('
.panel-heading {
  padding: 0px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 1px;
}
');
?>
<div class="uf-titularidad-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
		<?php
			if ($model->ultima) {
				echo Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
				$mu=MovimUf::findOne($model->tipo_movim);
				if ($mu->cesion) {
					echo Html::a('Finalizar cesión', ['fin-cesion', 'uf' => $model->id_uf,'id' => $model->id], [
							'class' => 'btn btn-danger',
							'data' => [
								'confirm' => 'Procede con la finalización de la cesión?',
								'method' => 'post',
							],
						]).' ';
					
				} else {	
					echo Html::a('Nuevo movimiento', ['create', 'uf' => $model->id_uf], ['class' => 'btn btn-danger']).' ';					
				}
			}
		?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
            //'id',
            //'id_uf',
            //'tipo_movim',
            'tipoMovim.desc_movim_uf',
            'fec_desde:date',
            'fec_hasta:date',

        ],
    ]) ?>

<?php 
 		
		$query=UfTitularidadPersonas::find()->joinWith('persona')
			->where(['uf_titularidad_id'=>$model->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tipo' => SORT_DESC,],
						'enableMultiSort'=>true,            
                      ],    
        ]);					      
        
                 
        
		echo GridView::widget([
			'dataProvider' => $dataProvider,
			'condensed'=>true,
			'layout'=>'{items}',
			//opciones validas solo para el gridview de kartik
			'panel'=>[
				'type'=>GridView::TYPE_INFO,
				'heading'=>'Titularidad actual sobre U.F.'.$model->id_uf,
				//'headingOptions'=>['class'=>'panel-heading'],
				'footer'=>false,
				'before'=>false,
				'after'=>false,
			],		
			'panelHeadingTemplate'=>'{heading}',			
			'resizableColumns'=>false,					
			'columns' => [
				//'id',
				//'uf_titularidad_id',
				[
					'attribute'=>'tipo',
					'value'=>function ($model) {return UfTitularidadPersonas::getTipos($model->tipo);},
				],
				//'tipo',
				'id_persona',
				'persona.apellido',
				'persona.nombre',
				'persona.nombre2',
				'persona.tipoDoc.desc_tipo_doc_abr',
				'persona.nro_doc',
				'observaciones',
				// 'created_by',
				// 'created_at',
				// 'updated_by',
				// 'updated_at',

				//['class' => 'yii\grid\ActionColumn'],
			],
    ]); 
?>
    
    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
            'exp_telefono',
            'exp_direccion',
            'exp_localidad',
            'exp_email:email',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => UfTitularidad::getEstados($model->estado)
			],		
            'motivo_baja',
            //'ultima',
        ],
    ]) ?>    

</div>
