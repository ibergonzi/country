<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosAutmanual */

$this->title = 'Crear autorización';
$this->params['breadcrumbs'][] = ['label' => 'Autorización de accesos manuales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-autmanual-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
