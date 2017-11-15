<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */

$this->title = 'Modificar Tipo de Licencia de conducir';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Licencia de conducir', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="tipos-licencia-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
