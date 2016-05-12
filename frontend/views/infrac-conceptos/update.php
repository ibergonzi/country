<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptos */

$this->title = 'Modificar concepto de infracciones' ;
$this->params['breadcrumbs'][] = ['label' => 'Conceptos de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de concepto', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="infrac-conceptos-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
