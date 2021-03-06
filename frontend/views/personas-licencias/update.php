<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonasLicencias */

$this->title = 'Update Personas Licencias: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Personas Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="personas-licencias-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
