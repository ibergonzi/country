<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infracciones-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


	<div class='container'>
		
		<div class='row'>

			<div class="col-md-6">


				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'id_uf',
						'id_vehiculo',
						'id_persona',
						'fecha',
						'hora',
						'nro_acta',
						'lugar',
						'id_concepto',
						'id_informante',
						'descripcion',
						'notificado',
						'fecha_verif',
						'verificado',
						'foto',
						'multa_unidad',
						'multa_monto',
						'multa_pers_cant',
						'multa_pers_monto',
						'multa_pers_total',
						'multa_total',
						'created_by',
						'created_at',
						'updated_by',
						'updated_at',
						'estado',
						'motivo_baja',
					],
				]) ?>
				
			</div>
			<div class="col-md-6">
				<?php
					$sinImg=Yii::$app->urlManager->createUrl('images/sinmulta.jpg');
					if (!empty($model->foto)) {
						$imgFile=Yii::$app->urlManager->createUrl('images/multas/'.$model->foto);
						echo Html::img($imgFile,['class'=>'img-thumbnail pull-right','onerror'=>"this.src='$sinImg'"]);
					} else {
						echo Html::img(Yii::$app->urlManager->createUrl('images/sinmulta.jpg'),
							['class'=>'img-thumbnail pull-right']);
					}
				?>

			</div>	
		</div>
	</div>

</div>
