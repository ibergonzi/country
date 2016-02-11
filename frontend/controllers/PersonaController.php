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

	// funcion utilizada para los select2, devuelve json
	public function actionApellidoslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)) {
			$query = new Query;
			$query->select(['id', new \yii\db\Expression("CONCAT(`apellido`, ' ',`nombre`) as text")])
				->from('personas')
				->where(['like', new \yii\db\Expression("CONCAT(`apellido`, ' ',`nombre`)"), $q])
				->limit(20);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Persona::find($id)->apellido];
		}
		return $out;
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

        if ($model->load(Yii::$app->request->post()) ) {

			if ($model->save()) {
				Yii::$app->response->format = 'json';
				return [
					//'message' => 'Success!!!',
					'modelP'=>$model
				];
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
