<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\AutorizadosHorarios;

/* @var $this yii\web\View */
/* @var $model frontend\models\AutorizadosHorarios */

// Achica los gridview
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


$this->title = "Detalle de Horario de autorización";
$this->params['breadcrumbs'][] = ['label' => 'Autorizaciones (eventuales y permanentes)', 
	'url' => ['autorizados/index']];
$this->params['breadcrumbs'][] = ['label' => 'Horarios de autorización', 
	'url' => ['autorizados-horarios/index','idParent'=>$parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizados-horarios-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?php
       	if ($model->estado==AutorizadosHorarios::ESTADO_ACTIVO) {
			if (\Yii::$app->user->can('borrarAutorizados')) {
				echo  Html::a('Eliminar día', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]) ;
			}	
		}
        ?>
    </p>
    
    <?php
    echo DetailView::widget([
        'model' => $parent,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
			[
				'attribute'=>'id_persona',
				'value'=>'('.$parent->id_persona.') '.$parent->persona->apellido.' '.$parent->persona->nombre,
            ],
			[
				'attribute'=>'id_autorizante',
				'value'=>'('.$parent->id_autorizante.') '.$parent->autorizante->apellido.' '.
					$parent->autorizante->nombre.' - U.F.:'.$parent->id_uf,  
            ],
            
            'fec_desde:date',
            'fec_hasta:date',
        ],
    ]);
 
     ?>
    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],            
        'attributes' => [
 			[
				'label' => 'Día',
				'value' => AutorizadosHorarios::getDias($model->dia)
			],		
            'hora_desde',
            'hora_hasta',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => AutorizadosHorarios::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
        ],
    ]) ?>

</div>
