<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */

$this->title = 'Update Tipos Licencia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipos Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipos-licencia-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
