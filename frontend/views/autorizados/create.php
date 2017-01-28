<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizados */

$this->title = 'Nueva autorización';
$this->params['breadcrumbs'][] = ['label' => 'Autorizaciones (eventuales y permanentes)', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizados-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
