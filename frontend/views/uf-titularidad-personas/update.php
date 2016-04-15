<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidadPersonas */

$this->title = 'Update Uf Titularidad Personas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uf Titularidad Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="uf-titularidad-personas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
