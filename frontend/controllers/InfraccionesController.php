<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Infracciones;
use frontend\models\InfraccionesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

/**
 * InfraccionesController implements the CRUD actions for Infracciones model.
 */
class InfraccionesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Infracciones models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InfraccionesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Infracciones model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Infracciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Infracciones();

        if ($model->load(Yii::$app->request->post())) {
			$model->fecha=$model->hora;			
			$ic=$model->concepto;
			if ($ic->es_multa) {
				$model->multa_unidad=$ic->multa_unidad; 
				if ($ic->multa_reincidencia) {
					$model->multa_monto=$this->calculaReinc($model,$ic,true);
				} else {
					$model->multa_monto=$ic->multa_precio;
				}
				$model->multa_pers_monto=$ic->multa_personas_precio;
				$model->multa_pers_total=($model->multa_pers_cant > 0)?$model->multa_pers_cant*$ic->multa_personas_precio:0;
				$model->multa_total=$model->multa_monto + $model->multa_pers_total;
				$model->fecha_verif=null;
				$model->verificado=true;				
			} else {
				$model->multa_unidad=null;
				$model->multa_monto=0;
				$model->multa_pers_monto=0;
				$model->multa_pers_total=0;
				$model->multa_total=0;
				if ($ic->dias_verif <= 0) {
					$model->fecha_verif=null;
					$model->verificado=true;
				} else {
					$model->fecha_verif=date('Y-m-d',strtotime('+' . $ic->dias_verif . ' days', strtotime($model->fecha)));
					$model->verificado=false;					
				} 
			}
			
			$model->foto = UploadedFile::getInstance($model, 'foto');
			if ($model->validate() && $model->save()) {	
				// evalua si se debe o no grabar el archivo
                if ($model->foto instanceof UploadedFile) {    
					$dirFotos='images/multas/';          
					$archOrig=$model->foto->baseName . '.' . $model->foto->extension;
					$archResize=$model->id.'.'.$model->foto->extension;
                    $model->foto->saveAs($dirFotos . $archOrig);
                    $this->resizeFoto($dirFotos, $archOrig, $dirFotos, $archResize);            
                    // sobreescribo el campo foto con el string de la foto toqueteada
                    $model->foto=$archResize;
                    $model->update();
                    //se elimina el archivo uploaded
                    unlink($dirFotos . $archOrig);
                }
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
        return $this->render('create', ['model' => $model,]);
     }

    /**
     * Updates an existing Infracciones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       // antes de recuperar los valores del post, se guarda el valor que tenia $model->archivo
        // esto se hace porque UploadedFile::getInstance sobreescribe el modelo y si en el formulario
        // no se eligio ningun archivo, se borra el que tenia antes de grabar
        $arch=$model->foto; 

        if ($model->load(Yii::$app->request->post())) {
			$model->fecha=$model->hora;			
			$ic=$model->concepto;
			if ($ic->es_multa) {
				$model->multa_unidad=$ic->multa_unidad; 
				if ($ic->multa_reincidencia) {
					$model->multa_monto=$this->calculaReinc($model,$ic,false);
				} else {
					$model->multa_monto=$ic->multa_precio;
				}
				$model->multa_pers_monto=$ic->multa_personas_precio;
				$model->multa_pers_total=($model->multa_pers_cant > 0)?$model->multa_pers_cant*$ic->multa_personas_precio:0;
				$model->multa_total=$model->multa_monto + $model->multa_pers_total;
				$model->fecha_verif=null;
				$model->verificado=true;				
			} else {
				$model->multa_unidad=null;
				$model->multa_monto=0;
				$model->multa_pers_monto=0;
				$model->multa_pers_total=0;
				$model->multa_total=0;
				if ($ic->dias_verif <= 0) {
					$model->fecha_verif=null;
					$model->verificado=true;
				} else {
					$model->fecha_verif=date('Y-m-d',strtotime('+' . $ic->dias_verif . ' days', strtotime($model->fecha)));
					$model->verificado=false;					
				} 
			}
			
			$model->foto = UploadedFile::getInstance($model, 'foto');
			
            // si ya tenia un valor y el usuario no subio ningun archivo, deja el valor original
            if ($arch !== null && $model->foto == '') {
                    $model->foto=$arch;
            }
            			
			
			if ($model->validate() && $model->save()) {	
				// evalua si se debe o no grabar el archivo
                if ($model->foto instanceof UploadedFile) {    
					$dirFotos='images/multas/';          
					$archOrig=$model->foto->baseName . '.' . $model->foto->extension;
					$archResize=$model->id.'.'.$model->foto->extension;
                    $model->foto->saveAs($dirFotos . $archOrig);
                    $this->resizeFoto($dirFotos, $archOrig, $dirFotos, $archResize);            
                    // sobreescribo el campo foto con el string de la foto toqueteada
                    $model->foto=$archResize;
                    $model->update();
                    //se elimina el archivo uploaded
                    unlink($dirFotos . $archOrig);
                }
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
        return $this->render('update', ['model' => $model,]);
    }
    
     private function calculaReinc($model,$ic,$alta) 
     {
		// si por error se coloca un valor que es menor que 1 dia, se devuelve la multa sin reincidencias 
		if ($ic->multa_reinc_dias <= 1) { return $ic->multa_precio; }
		
		// Se calcula la fecha desde la cual se cuentan las reincidencias (desde la fecha de la multa - multa_reinc_dias)
		$fecAtrasUnix=strtotime('-' . $ic->multa_reinc_dias . ' days', strtotime($model->fecha));
		$fecAtras=date('Y-m-d',$fecAtrasUnix);
		$fecMulta=date('Y-m-d',strtotime($model->fecha));
		
		// cuenta todas las multas para la unidad funcional desde la fecha calculada
		$cantMultas=Infracciones::find()->where([
				'id_uf'=>$model->id_uf,
				'estado'=>Infracciones::ESTADO_ACTIVO,
				'id_concepto'=>$model->id_concepto
				])
				->andWhere(['between','fecha',$fecAtras,$fecMulta])->count();
				
		// se suma tambien la que se está grabando (si es modif.no es necesario porque ya está incluido en el select)
		if ($alta) {$cantMultas=$cantMultas + 1;}
		
		$monto=$ic->multa_precio+ ($ic->multa_precio * (($cantMultas-1)*$ic->multa_reinc_porc) / 100);
	 
		return $monto;
	 }    

    /**
     * Deletes an existing Infracciones model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Infracciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Infracciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Infracciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
	public function resizeFoto($dirOrig,$nombreOrig,$dirDestino,$nombreDestino)
	{ 
		// valores maximos
		$width = 400;
		$height = 400;

		list($width_orig, $height_orig) = getimagesize($dirOrig.$nombreOrig);

		$ratio_orig = $width_orig/$height_orig;

		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}

		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($dirOrig.$nombreOrig);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);		
		
		imagejpeg($image_p, $dirDestino.$nombreDestino, 100);
	}           
}
