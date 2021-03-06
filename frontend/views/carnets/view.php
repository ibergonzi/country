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
			//echo '<td style="border:1px solid black;overflow-y:hidden;text-align:center;height: 90mm;width: 46mm;">';
			
			if ($lado=='frente') {
				echo '<td style="border:1px solid black;text-align:center;height: 100mm !important;width: 33% !important;">';				
				
				$p=Personas::findOne($id);
				if (!empty($p->foto)) {
					echo '<p>'.Html::img(Yii::$app->urlManager->createUrl('images/personas/'.$p->foto),
						//['style'=>'width: 40mm;height:40mm;']).'</p>';
						['style'=>'height:40mm;']).'</p>';							
				} else {
					echo '<p>'.Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),
						//['style'=>'width: 40mm;height:40mm;']).'</p>';
						['style'=>'height:40mm;']).'</p>';
				}
				echo '<p>'.$p->apellido.' ('.$p->id.')</p>';
				echo '<p>'.$p->nombre.' '.$p->nombre2.'</p>';
				echo '<p>D:'.$p->nro_doc.'</p>';
				echo '<div style="color:white;text-align:center;padding:5px;">';
				//echo '<barcode code="*'. $p->id .'*" type="C39" size="0.5" height="2.0"/>';
				//echo '<barcode code="*'. $p->id .'*" type="C39"/>';
				echo '<barcode code="'. $p->id .'" type="C128A" text="1"/>';

				echo '</div>';
			} else {
				echo '<td style="overflow-y:hidden;text-align:center;height: 100mm !important;width: 33% !important;">';				

				echo '<p>'.Html::img(Yii::$app->urlManager->createUrl('images/dorso.png')
							//['style'=>'width: 40mm;height:auto;']);
							//['style'=>'height:auto;']
							).'</p>';							
			}			
		
			echo '</td>';
		
			//if ($y==4) {
			if ($y==3) {				
				$y=0;
				echo '</tr>';
			}
	} // fin foreach sessPersonas
	if ($y > 0) {
		//for ($i=$y+1;$i<=4;$i++) {
		for ($i=$y+1;$i<=3;$i++) {			
					//echo '<td style="height: 90mm;width: 46mm;">';
					echo '<td style="height: 100mm !important;width: 33% !important;">';					
					echo '</td>';
		}
		echo '</tr>';
	}
}
?>
</table>



