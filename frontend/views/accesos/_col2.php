<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

?>


<?php
$form = ActiveForm::begin();

foreach ($personas as $index => $persona) {
    echo $form->field($persona, "[$index]id")->label($persona->id);
}

echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';

ActiveForm::end(); 
?>



