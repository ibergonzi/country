<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Persona;
use frontend\models\PersonaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\db\Query;
use yii\helpers\Json;

use kartik\widgets\ActiveForm;

use yii\web\UploadedFile;



/**
 * PersonaController implements the CRUD actions for Persona model.
 */
class PersonaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	/*
En lenguaje PHP:

$consulta = "SELECT * FROM persona WHERE ";
$palabras = explode(" ", $_POST["frase"]);
$c = 0;
foreach ($palabras as $palabra)
{
$c++;
$consulta .= " nombre1 + ' ' + nombre2 + ' ' + apellido1 + ' ' + apellido2 LIKE '%".$palabra."%' ";
$consulta .= (count($palabras) == $c ? "": " OR ");
}
Lo que daría por resultado:

SELECT *
FROM persona
WHERE
nombre1 + ' ' + nombre2 + ' ' + apellido1 + ' ' + apellido2 LIKE '%Juan%'
OR nombre1 + ' ' + nombre2 + ' ' + apellido1 + ' ' + apellido2 LIKE '%Perez%'; 

pero es mas eficiente:
SELECT *
FROM persona
WHERE nombre1 + ' ' + nombre2 + ' ' + apellido1 + ' ' + apellido2 LIKE '%Juan%' 
UNION
SELECT *
FROM persona
WHERE nombre1 + ' ' + nombre2 + ' ' + apellido1 + ' ' + apellido2 LIKE '%Perez%'; 
 
 
	*/



	// funcion utilizada para los select2, devuelve json
	public function actionApellidoslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];


		
		if (!is_null($q)) {
			/*
			$query = new Query;
			$concat=new \yii\db\Expression("CONCAT(`apellido`, ' ',`nombre`,' ',`nombre2`)");			
			$query->select(['id', $concat . ' as text'])
				->from('personas')
				->where(['like', $concat , $q])
				->orderBy('text')
				->limit(40);
			$command = $query->createCommand();
			*/
			
			
			$sp='CALL personas_busca_nombres(:query)' ;
            $command = Yii::$app->db->createCommand($sp);
            $command->bindParam(":query", trim($q));
            
			
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => $this->formateaPersona($id)];
		}
		return $out;
	}


	public function formateaPersona($id) 
	{
		$p=Persona::find($id);
		$r=$p->apellido.' '.$p->nombre.' '.$p->nombre2.' ('.$id.')';
		return $r;
	}

    /**
     * Lists all Persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonaSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $post = Yii::$app->request->post();
        $dataProvider = $searchModel->search($post);        
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Persona model.
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
     * Creates a new Persona model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     

    public function actionCreate()
    {
        $model = new Persona();


        if ($model->load(Yii::$app->request->post())) {
			// Si vino por post se recupera el archivo
			$model->foto = UploadedFile::getInstance($model, 'foto');
            // cuando viene un archivo se debe forzar el validate y 
            // si se graba el modelo exitosamente se graba tambien el archivo			
			if ($model->validate() && $model->save()) {
				// evalua si se debe o no grabar el archivo
                if ($model->foto instanceof UploadedFile) {    
					$dirFotos='images/personas/';          
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
				return $this->redirect(['index']);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    

    public function actionCreateAjax()
    {
        $model = new Persona();

		// Al estar habilitado la validation ajax, $_POST['ajax'] viene seteado, si vino por el submit, esta variable no existe
		// Siempre se devuelve el validate
		if (isset($_POST['ajax'])) {

				Yii::$app->response->format = 'json';				
				$model->load(Yii::$app->request->post());
				return ActiveForm::validate($model);

		}
		
		// si no viene seteado $_POST['ajax'] se asume que se entro por el submit
		if ($model->load(Yii::$app->request->post()) ) {
				if ($model->save()) {

					Yii::$app->response->format = 'json';
					return [
						//'message' => 'Success!!!',
						'modelP'=>$model
					];				
				} else {

					Yii::$app->response->format = 'json';				
					return ActiveForm::validate($model);
			}
		}	

		return $this->renderAjax('createajax', [
				'model' => $model,
		 ]);

    }
 
    

    /**
     * Updates an existing Persona model.
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
           // Si vino por post, se recupera el archivo
            $model->foto = UploadedFile::getInstance($model, 'foto');

            // si ya tenia un valor y el usuario no subio ningun archivo, deja el valor original
            if ($arch !== null && $model->foto == '') {
                    $model->foto=$arch;
            }
            
            
			if ($model->validate() && $model->save()) { 
               // evalua si se debe o no grabar el archivo
               if ($model->foto instanceof UploadedFile) {    
					$dirFotos='images/personas/';          
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
				return $this->redirect(['index']);

            }
        } 
        return $this->render('update', [
                'model' => $model,
           ]);
    }
    
    

    /**
     * Deletes an existing Persona model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		$dirFotos='images/personas/'; 
		
        return $this->redirect(['index']);
    }

    /**
     * Finds the Persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
	public function resizeFoto($dirOrig,$nombreOrig,$dirDestino,$nombreDestino)
	{ 
		// valores maximos
		$width = 200;
		$height = 200;

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
