<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Nuevo Vehiculo';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehiculos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_formajax', [
        'model' => $model,
    ]) ?>

</div>
