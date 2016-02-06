<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Portones */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Portones',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Portones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="portones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
