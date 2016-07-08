<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */

$this->title = 'Update Movim Uf: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Movim Ufs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="movim-uf-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
