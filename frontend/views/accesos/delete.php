<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Deshabilitar acceso ID: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accesos'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
