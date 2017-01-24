<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Autorizados;

/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizados */

$this->title = "Detalle de autorización";
$this->params['breadcrumbs'][] = ['label' => 'Autorizaciones (eventuales y permanentes)', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizados-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?php
       	if ($model->estado==Autorizados::ESTADO_ACTIVO) {
			if (\Yii::$app->user->can('borrarPersona')) {
				echo  Html::a('Eliminar autorización', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]) ;
			}	
		}
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
               [
				'attribute'=>'id_persona',
				'format' => 'raw',
				'value' =>Html::a($model->id_persona, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_persona
										]),
										['title' => 'Ver detalle de persona',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										])
            ],
            'persona.apellido',
			'persona.nombre',  
			'persona.nro_doc',
              [
				'attribute'=>'id_autorizante',
				'format' => 'raw',
				'value' =>Html::a($model->id_autorizante, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_autorizante
										]),
										['title' => 'Ver detalle de persona (autorizante)',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										])
            ],
            'autorizante.apellido',
			'autorizante.nombre',  
			'autorizante.nro_doc',
            'id_uf',
            'fec_desde:date',
            'fec_hasta:date',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => Autorizados::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
        ],
    ]) ?>

</div>
