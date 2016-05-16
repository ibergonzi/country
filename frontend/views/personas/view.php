<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Personas;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */



$this->title = 'Detalle de Persona';

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Personas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="personas-view">
	<h3><?= Html::encode($this->title) ?></h3>

	<?php 
	echo '<p>';
	if ($model->estado==Personas::ESTADO_ACTIVO) {
		if (\Yii::$app->user->can('altaModificarPersona')) {
			echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
		}
		if (\Yii::$app->user->can('borrarPersona')) {	
			echo ' '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]);
		}
	}
	if (\Yii::$app->user->can('accederConsAccesos')) {	
		if (isset($model->ultIngreso->id)) {
			echo ' '.Html::a('Ult.Ingreso', ['accesos/view', 
				'id' => $model->ultIngreso->id], 
				['class' => 'btn btn-default','title'=>'Ver último ingreso']);
		}
	}
	echo ' '.Html::a('Vehiculos',['lista-vehiculos','id_persona'=>$model->id],[
		'class'=>'btn btn-default',
		'title'=>'Vehiculos utilizados',
		'onclick'=>'$.ajax({
			type     :"POST",
			cache    : false,
			url  : $(this).attr("href"),
			success  : function(response) {
						if (response=="notFound") {return false;}
						$("#divvehiculos").html(response);
						$("#modalvehiculos").modal("show");
						}
		});
		return false;'							
	]
	);
	echo '</p>';	
	?>
	<div class='container'>
		<div class='row'>
			<div class="col-md-9">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'apellido',
						'nombre',
						'nombre2',
						//'id_tipo_doc',
						'tipoDoc.desc_tipo_doc_abr',
						'nro_doc',
						'foto',
						'userCreatedBy.username',
						'created_at:datetime',
						'userUpdatedBy.username',
						'updated_at:datetime',
						[
							'label' => 'Estado',
							'value' => Personas::getEstados($model->estado)
						],							
						//'estado',
						'motivo_baja',
					],
				]) ?>
			</div>	
			<div class="col-md-3">

				<?php
				$sinImg=Yii::$app->urlManager->createUrl('images/sinfoto.png');				
				if (!empty($model->foto)) {
					$imgFile=Yii::$app->urlManager->createUrl('images/personas/'.$model->foto);
					echo Html::img($imgFile,['class'=>'img-thumbnail pull-right','onerror'=>"this.src='$sinImg'"]);
				} else {
					echo Html::img($sinImg,	['class'=>'img-thumbnail pull-right']);
				}
				?>

			</div>	
		</div>
	</div>	
	<?php
	Modal::begin(['id'=>'modalvehiculos',
		'header'=>'<span class="btn-warning">&nbsp;Vehiculos utilizados&nbsp;</span>',
		]);
		echo '<div id="divvehiculos"></div>';
	Modal::end();	
	?>
</div>						
