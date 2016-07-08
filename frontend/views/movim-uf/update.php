<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */

$this->title = 'Modificar movimiento de U.F.';
$this->params['breadcrumbs'][] = ['label' => 'Movimientos de U.F.', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de movimiento de U.F.', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="movim-uf-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
