<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Generadores */

$this->title = 'Detalle de generador';
$this->params['breadcrumbs'][] = ['label' => 'Generadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generadores-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'descripcion',
			[
				'attribute' => 'activo',
				'value' => frontend\models\Generadores::getSiNo($model->activo)
			],
        ],
    ]) ?>

</div>
