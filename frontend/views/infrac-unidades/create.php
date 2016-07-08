<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\InfracUnidades */

$this->title = 'Create Infrac Unidades';
$this->params['breadcrumbs'][] = ['label' => 'Infrac Unidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infrac-unidades-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
