<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AutorizadosHorarios */

$this->title = 'Update Autorizados Horarios: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Autorizados Horarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="autorizados-horarios-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
