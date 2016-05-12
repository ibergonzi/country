<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Deshabilitar concepto ID: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conceptos de infracciones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de concepto', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
