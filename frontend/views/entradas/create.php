<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Entradas */

$this->title = Yii::t('app', 'Create Entradas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Entradas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entradas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
