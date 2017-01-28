<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItemChild */

$this->title = 'Nueva asignación de permiso a rol';
$this->params['breadcrumbs'][] = ['label' => 'Permisos por rol', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-child-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
