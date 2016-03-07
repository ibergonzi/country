<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Mensajes */

$this->title = 'Mensaje';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mensaje'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mensajes-create">



    <?= $this->render('_formajax', [
        'model' => $model,
		'modelNameOrigen' => $modelNameOrigen,
		'modelIDOrigen' => $modelIDOrigen,         
    ]) ?>

</div>
