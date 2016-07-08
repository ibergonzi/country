<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */

$this->title = 'Crear movimiento de U.F.';
$this->params['breadcrumbs'][] = ['label' => 'MMovimientos de U.F.', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movim-uf-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
