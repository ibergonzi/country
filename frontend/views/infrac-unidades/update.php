<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracUnidades */

$this->title = 'Modificar unidad de infracciones';
$this->params['breadcrumbs'][] = ['label' => 'Unidades de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de unidad de infracciones', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="infrac-unidades-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
