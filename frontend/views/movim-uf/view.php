<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */

$this->title = 'Detalle de movimiento de U.F.';
$this->params['breadcrumbs'][] = ['label' => 'Movimientos de U.F.', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movim-uf-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'desc_movim_uf',
			[
				'attribute' => 'cesion',
				'value' => frontend\models\MovimUf::getSiNo($model->cesion)
			],
			[
				'attribute' => 'migracion',
				'value' => frontend\models\MovimUf::getSiNo($model->migracion)
			],
			[
				'attribute' => 'fec_vto',
				'value' => frontend\models\MovimUf::getSiNo($model->fec_vto)
			],
			[
				'attribute' => 'manual',
				'value' => frontend\models\MovimUf::getSiNo($model->manual)
			],
        ],
    ]) ?>

</div>
