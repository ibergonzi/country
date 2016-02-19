<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Tiposdoc */

$this->title = Yii::t('app', 'Create Tiposdoc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tiposdocs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiposdoc-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
