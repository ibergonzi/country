<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\AuthItem;


/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItemChild */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#authitemchild-parent").focus()', yii\web\View::POS_READY);

?>

<div class="auth-item-child-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->dropDownList(AuthItem::getListaRoles(),['prompt'=>'']) ?>

    <?= $form->field($model, 'child')->dropDownList(AuthItem::getListaPermisos(),['prompt'=>'']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Aceptar' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
