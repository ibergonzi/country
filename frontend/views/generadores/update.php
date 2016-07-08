<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Generadores */

$this->title = 'Modificar generador';
$this->params['breadcrumbs'][] = ['label' => 'Generadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de generador', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="generadores-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
