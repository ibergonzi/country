<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Portones */

$this->title = Yii::t('app', 'Create Portones');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Portones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
