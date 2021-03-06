<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */

$this->title = 'Modificar infracción: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Infracciones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de infracción', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infracciones-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
