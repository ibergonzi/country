<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Vehiculos */

$this->title = Yii::t('app', 'Nuevo Vehiculo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehiculos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
