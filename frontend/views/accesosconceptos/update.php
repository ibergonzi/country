<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosConceptos */

$this->title = 'Modificación concepto de acceso: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conceptos de accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de concepto de acceso', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="accesos-conceptos-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
