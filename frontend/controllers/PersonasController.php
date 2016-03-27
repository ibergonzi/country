<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Personas;
use frontend\models\PersonasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\db\Query;
use yii\helpers\Json;

use kartik\widgets\ActiveForm;

use yii\web\UploadedFile;


/**
 * PersonasController implements the CRUD actions for Personas model.
 */
class PersonasController extends Controller
{
	

    public function behaviors()
    {
        return [
			/* 	// se anula porque no se puede eliminar desde el index, sino desde el view (se llama por GET y no por POST)
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            */
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrarPersona'], 
                    ],                
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['accederListaPersonas'], 
                    ],
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['altaModificarPersona'], 
                    ],
                    [
                        'actions' => ['create-ajax'],
                        'allow' => true,
                        'roles' => ['altaPersonaIngEgr'], 
                    ],                    
                    [ 
                        'actions' => ['apellidoslist'],
						'allow' => true,
						'roles' => ['accederIngreso','accederEgreso'],  
					],
 		
                 ], // fin rules
             ], // fin access            
            
        ];
    }
    

    /**
     * Lists all Personas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonasSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Personas model.
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
     * Creates a new Personas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
     

    public function actionCreate()
    {
        $model = new Personas();
     	$model->id_tipo_doc=96; // DNI por defecto
    	$model->estado=Personas::ESTADO_ACTIVO;      	

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
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
			    return $this->render('create', ['model' => $model,]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Personas model.
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
				return $this->redirect(['view', 'id' => $model->id]);

            } else {
			    return $this->render('update', ['model' => $model,]);
			}
        } 
        return $this->render('update', [
                'model' => $model,
           ]);
    }
    
    
    public function actionCreateAjax()
    {
        $model = new Personas();
     	$model->id_tipo_doc=96; // DNI por defecto  
     	$model->estado=Personas::ESTADO_ACTIVO;      

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
     * Deletes an existing Personas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		// delete original
        //$this->findModel($id)->delete()
        //return $this->redirect(['index']);
  
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=Personas::ESTADO_BAJA;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('delete', [
                'model' => $model,
            ]);
        }        

 
    }

    /**
     * Finds the Personas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Personas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Personas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Persona inexistente');
        }
    }
    
	// funcion utilizada para los select2, devuelve json
	public function actionApellidoslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
	
		if (!is_null($q)) {
			if (is_numeric($q)) {
				$sp='CALL personas_busca_nrosdoc(:query)' ;				
			} else {	
				$q=str_replace(' ','%',$q);
				$sp='CALL personas_busca_nombres(:query)' ;
			}
            $command = Yii::$app->db->createCommand($sp);
            $command->bindParam(":query", trim($q));
			
			$data = $command->queryAll();
			
			// el command devuelve un array de arrays con forma id=>n,text=>''
			// se recorre todo el array, se detecta el key id y con su valor se busca la persona
			// y se agrega a un nuevo array para despues ordenarlo por text y devolverlo 
			$aux=['id'=>'','text'=>''];
			foreach ($data as $cadauno) {				
				foreach($cadauno as $key=>$valor) {
					if ($key=='id') {
						$t=Personas::formateaPersonaSelect2($valor,is_numeric($q));
						$aux[]=['id'=>$valor,'text'=>$t];
					}
				}
			}
			asort($aux);
		
			$out['results'] = array_values($aux);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Personas::formateaPersonaSelect2($id,false)];
		}
		return $out;
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
