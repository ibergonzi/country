<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\TiposLicencia;

/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */

$this->title = 'Detalle de tipo de licencia';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Licencia de conducir', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-licencia-view">

    <h3><?= Html::encode($this->title) ?></h3>

	<?php
	
		echo '<p>';
		if ($model->activo==TiposLicencia::SI) {
			if (\Yii::$app->user->can('modificarParametros')) {
				echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
				echo ' '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
						'data' => [
							'confirm' => Yii::t('app', 'Esta acción inactivará el tipo de licencia, continúa?'),
							'method' => 'post',
						],				
				]);
			}
		}   
		echo '</p>'     
	
	?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'desc_licencia',
           	[
				'attribute' => 'activo', // o 'label'=>'Estado'
				'value' => TiposLicencia::getSiNo($model->activo)
			],
        ],
    ]) ?>

</div>
