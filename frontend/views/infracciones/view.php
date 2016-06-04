<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Infracciones;



/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */

$this->title = 'Detalle de infracción';
$this->params['breadcrumbs'][] = ['label' => 'Infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');

$this->registerCss('
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
<div class="infracciones-view">

    <h3><?= Html::encode($this->title) ?></h3>

	<?php
		if (!$pdf) {
			echo '<p>';			
			if ($model->estado==Infracciones::ESTADO_ACTIVO ) {	
				if ($model->multa_total > 0) {	
					if (\Yii::$app->user->can('altaModificarMulta')) {							
						echo Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).'&nbsp;'; 
					}
				} else {
					if (\Yii::$app->user->can('altaModificarInfrac')) {							
						echo Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).'&nbsp;'; 
					}					
				}
				if (\Yii::$app->user->can('borrarInfrac')) { 					
					echo Html::a('Eliminar', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]).'&nbsp;';
				}
			}
			if (\Yii::$app->user->can('exportarListaInfrac')) { 			
				echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $model->id], [
					'class' => 'btn btn-default',//'target'=>'_blank',
					]);	
			}
			echo '</p>';					
		}				
    ?>


	<div class='container'>
		
		<div class='row'>

			<div class="col-md-6">

				<?php
					$imgClass=($pdf)?'pull-right':'img-thumbnail pull-right';
					$sinImg=Yii::$app->urlManager->createUrl('images/sinmulta.jpg');
					if (!empty($model->foto)) {
						$imgFile=Yii::$app->urlManager->createUrl('images/multas/'.$model->foto);
						$imgFoto=Html::img($imgFile,['class'=>$imgClass,'onerror'=>"this.src='$sinImg'"]);
					} else {
						$imgFoto=Html::img($sinImg, ['class'=>$imgClass]);
					}
				
					echo DetailView::widget([
					'model' => $model,
					'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],					
					'attributes' => [
						'id',
						'nro_acta',	
						'fecha:date',
						'hora:time',											
						'id_uf',
						'concepto.concepto',						
						'id_vehiculo',
						'vehiculo.patente',
						'vehiculo.marca',
						'vehiculo.modelo',
						'vehiculo.color',
						'id_persona',
						'persona.apellido',
						'persona.nombre',
						'persona.nro_doc',
						'lugar',
						'id_informante',
						'informante.apellido',
						'informante.nombre',
						'descripcion',
						[
							'attribute' => 'notificado',
							'value' => Infracciones::getSiNo($model->notificado)
						],	
						'fecha_verif',
						[
							'attribute' => 'verificado',
							'value' => Infracciones::getSiNo($model->verificado)
						],	
						'multaUnidad.unidad',
						'multa_fec_reinc:date',
						'multa_monto',
						'multa_pers_cant',
						'multa_pers_monto',
						'multa_pers_total',
						'multa_total',
						'userCreatedBy.username',
						'created_at:datetime',
						'userUpdatedBy.username',
						'updated_at:datetime',
						[
							'label' => 'Estado',
							'value' => Infracciones::getEstados($model->estado),
							'visible'=>($pdf)?false:true,
						],	
						[
							'attribute'=>'motivo_baja',
							'visible'=>($pdf)?false:true,							
						],
						//'motivo_baja',
						[
							'label'=>'Foto',
							'value'=>$imgFoto,
							'format'=>'raw',
							'visible'=>$pdf,							
						],
					],
				]) ?>
				
			</div>
			<div class="col-md-6">
				<?php
				if (!$pdf) {
					echo $imgFoto;
				}
				?>

			</div>	
		</div>
	</div>

</div>
