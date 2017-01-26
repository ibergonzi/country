<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\AuthItem;


/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItemChild */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJs('$("#REEMPLAZARPORIDPRIMERCAMPO").focus()', yii\web\View::POS_READY);

?>

<div class="auth-item-child-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->dropDownList(AuthItem::getListaRoles()) ?>

    <?= $form->field($model, 'child')->dropDownList(AuthItem::getListaPermisos()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
