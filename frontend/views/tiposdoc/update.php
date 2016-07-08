<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Tiposdoc */

$this->title = 'Modificar tipo de documento';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de documento', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de tipo de documento', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="tiposdoc-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
