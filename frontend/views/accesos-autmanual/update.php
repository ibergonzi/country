<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosAutmanual */

$this->title = 'Update Accesos Autmanual: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Accesos Autmanuals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accesos-autmanual-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
