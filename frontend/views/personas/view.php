<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Personas;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */



$this->title = 'Detalle de Persona';

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Personas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">


				<div class="personas-view">

					<h3><?= Html::encode($this->title) ?></h3>


						<?php 
						echo '<p>';
						if ($model->estado==Personas::ESTADO_ACTIVO) {

							echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])
							.'  '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
								'class' => 'btn btn-danger',
								]).'  ';
						}
						if (isset($model->ultIngreso->id)) {
							echo Html::a('Ult.Ingreso', ['accesos/view', 
								'id' => $model->ultIngreso->id], 
								['class' => 'btn btn-default']);
						}
						echo '</p>';	
						?>
					

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
		</div>	
		
		<div class="col-md-3">

			<?php
				if (!empty($model->foto)) {
					echo Html::img(Yii::$app->urlManager->createUrl('images/personas/'.$model->foto),
						['class'=>'img-thumbnail pull-right']);
				}
				else
				{
					echo Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),
						['class'=>'img-thumbnail pull-right']);
				}
			?>

		</div>	
	</div>
</div>						
