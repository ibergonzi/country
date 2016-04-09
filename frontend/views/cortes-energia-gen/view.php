<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\CortesEnergiaGen;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */

$this->title = 'Novedad de generador';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = ['label' => 'Novedades de generadores', 'url' => ['cortes-energia-gen/index','idParent'=>$parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-gen-view">

    <h3><?= Html::encode($this->title . ' ' . 
		Yii::$app->formatter->asDatetime($parent->hora_desde) .
		' - '. 
		Yii::$app->formatter->asTime($parent->hora_hasta)) ?></h3>

	<?php

	if ($model->estado==CortesEnergiaGen::ESTADO_ACTIVO) {
		echo '<p>';
		if (\Yii::$app->user->can('modificarCorte')) {
			echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
		}
		if (\Yii::$app->user->can('borrarCorte')) {				
			echo ' '.Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',	]);
		}
		echo '</p>';		
	}	

	?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'id_cortes_energia',
            'generador.descripcion',
            'hora_desde:datetime',
			'hora_hasta:datetime',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => CortesEnergiaGen::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
        ],
    ]) ?>

</div>
