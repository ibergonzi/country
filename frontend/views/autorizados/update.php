<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizados */

$this->title = 'Update Autorizados: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Autorizados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="autorizados-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
