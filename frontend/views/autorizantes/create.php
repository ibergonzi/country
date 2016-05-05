<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizantes */

$this->title = Yii::t('app', 'Nuevo autorizante');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Autorizantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('$("#autorizantes-id_uf").focus()', yii\web\View::POS_READY);
?>
<div class="autorizantes-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
