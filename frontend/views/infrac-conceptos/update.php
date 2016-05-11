<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptos */

$this->title = 'Update Infrac Conceptos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Infrac Conceptos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="infrac-conceptos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
