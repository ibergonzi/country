<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TitularidadVista */

$this->title = 'Create Titularidad Vista';
$this->params['breadcrumbs'][] = ['label' => 'Titularidad Vistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="titularidad-vista-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
