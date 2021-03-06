<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#tiposlicencia-desc_licencia").focus()', yii\web\View::POS_READY);

?>

<div class="tipos-licencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'desc_licencia')->textInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
