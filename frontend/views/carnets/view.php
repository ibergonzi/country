<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Personas;

if (!$pdf) {
	echo '<h3>Impresión de carnets</h3>';
	echo '<p>';
	echo Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Volver', ['index', 'pdf' => false], [
			'class' => 'btn btn-default',//'target'=>'_blank',	
	]).' ';		
	echo Html::a('<i class="fa fa-file-pdf-o"></i> generar FRENTE', ['index', 'pdf' => true,'lado'=>'frente'], [
			'class' => 'btn btn-danger',//'target'=>'_blank',
	]).' ';	
	echo Html::a('<i class="fa fa-file-pdf-o"></i> generar DORSO', ['index', 'pdf' => true,'lado'=>'dorso'], [
			'class' => 'btn btn-primary',//'target'=>'_blank',
	]);			
	echo '</p>';
} 

?>

<table style="width: 210mm;border-collapse: separate;border-spacing: 5mm;">
<?php
$sessPersonas=\Yii::$app->session->get('crntpersonas');
if ($sessPersonas) {
	$y=0;
	foreach ($sessPersonas as $id) {
			$y=$y+1;		
			if ($y==1) { echo '<tr>';}
			echo '<td style="border:1px solid black;overflow-y:hidden;text-align:center;height: 90mm;width: 46mm;">';
			if ($lado=='frente') {
					$p=Personas::findOne($id);
					if (!empty($p->foto)) {
						echo '<p>'.Html::img(Yii::$app->urlManager->createUrl('images/personas/'.$p->foto),
							['style'=>'width: 40mm;height:40mm;']).'</p>';
					} else {
						echo '<p>'.Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),
							['style'=>'width: 40mm;height:40mm;']).'</p>';
					}
					echo '<p>'.$p->apellido.' ('.$p->id.')</p>';
					echo '<p>'.$p->nombre.' '.$p->nombre2.'</p>';
					echo '<p>'.$p->nro_doc.'</p>';
					echo '<div style="color:white;text-align:center;padding:5px;">';
					echo '<barcode code="*'. $p->id .'*" type="C39" size="0.5" height="2.0"/>';
					echo '</div>';
			} else {
				echo '<p>colocar imagen</p>';
			}			
		
			echo '</td>';
		
			if ($y==4) {
				$y=0;
				echo '</tr>';
			}
	} // fin foreach sessPersonas
	if ($y > 0) {
		for ($i=$y+1;$i<=4;$i++) {
					echo '<td style="height: 90mm;width: 46mm;">';
					echo '</td>';
		}
		echo '</tr>';
	}
}
?>
</table>



