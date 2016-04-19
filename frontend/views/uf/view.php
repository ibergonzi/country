<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Uf;

/* @var $this yii\web\View */
/* @var $model frontend\models\Uf */

$this->title = 'U.F.'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lista de U.F.', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-view">

    <h3><?= Html::encode($this->title) ?></h3>

	<?php
		echo '<p>';
		if ($model->estado != Uf::ESTADO_BAJA) {
			if (\Yii::$app->user->can('altaModificarUf')) {
				echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
			}
			if (\Yii::$app->user->can('borrarUf')) {	
				echo ' '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',]);
			}
		}  
		echo '</p>';
	?>  

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'loteo',
            'manzana',
            'superficie:decimal',

			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => Uf::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
        ],
    ]) ?>

</div>
