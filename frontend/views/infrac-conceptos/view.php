<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\InfracConceptos;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptos */

$this->title = 'Detalle de concepto';
$this->params['breadcrumbs'][] = ['label' => 'Conceptos de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');
?>
<div class="infrac-conceptos-view">

    <h3><?= Html::encode($this->title.' (Infracciones)') ?></h3>

      
	<?php
	
		echo '<p>';
		if ($model->estado==InfracConceptos::ESTADO_ACTIVO) {
			if (\Yii::$app->user->can('modificarParametros')) {
				echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
				echo ' '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]);
			}
		}   
		echo '</p>'     
	
	?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'concepto',
			[
				'attribute' => 'es_multa',
				'value' => InfracConceptos::getSiNo($model->es_multa)
			],	            
            'dias_verif',
            'multa_unidad',
            'multa_precio:decimal',
			[
				'attribute' => 'multa_reincidencia',
				'value' => InfracConceptos::getSiNo($model->multa_reincidencia)
			],	             
            'multa_reinc_porc:decimal',
            'multa_reinc_dias',
			[
				'attribute' => 'multa_personas',
				'value' => InfracConceptos::getSiNo($model->multa_personas)
			],	            
            'multa_personas_precio:decimal',
            'userCreatedBy.username',
            'created_at:datetime',
            'userUpdatedBy.username',
            'updated_at:datetime',
			[
				'attribute' => 'estado', // o 'label'=>'Estado'
				'value' => InfracConceptos::getEstados($model->estado)
			],	
            'motivo_baja',
        ],
    ]) ?>

</div>
