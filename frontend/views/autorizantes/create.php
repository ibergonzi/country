<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizantes */

$this->title = Yii::t('app', 'Create Autorizantes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Autorizantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizantes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
