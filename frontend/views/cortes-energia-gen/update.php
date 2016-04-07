<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */

$this->title = 'Update Cortes Energia Gen: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cortes Energia Gens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cortes-energia-gen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
