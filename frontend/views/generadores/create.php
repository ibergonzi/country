<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Generadores */

$this->title = 'Crear Generador';
$this->params['breadcrumbs'][] = ['label' => 'Generadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generadores-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
