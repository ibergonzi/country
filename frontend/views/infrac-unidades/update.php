<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracUnidades */

$this->title = 'Update Infrac Unidades: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Infrac Unidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="infrac-unidades-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
