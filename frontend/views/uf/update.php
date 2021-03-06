<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Uf */

$this->title = 'Modificación UF: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lista U.F.', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'U.F.'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificación';
?>
<div class="uf-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
