<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use frontend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\UploadedFile;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
            
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view','delete','update','changepwd'],
                        'allow' => true,
                        //'roles' => ['administrador','consejo','intendente'],
                        'roles' => ['accederUser'], 
                    ],
                 ],
            ],
        ];
    }
    
    
    
	public function actionChangepwd($id)
	{
		$user=$this->findModel($id);
		if ($user) {
			if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
				$user->generatePasswordResetToken();
			}
			if ($user->save()) {
				
				return $this->redirect(['site/reset-password','token'=>$user->password_reset_token]);				
			}
		}

	}

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
	
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
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
        // Actualiza el rol
		if (isset(Yii::$app->request->post()['rol'])) {
			$auth = Yii::$app->authManager;
		    $auth->revokeAll($id);
		    $rol=$auth->getRole(Yii::$app->request->post()['rol']);
		    $auth->assign($rol,$id);
		}

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
					$dirFotos='images/usuarios/';          
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


		/* original
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
		
            return $this->render('update', [
                'model' => $model,
                
            ]);
        }
        */
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model=$this->findModel($id);
        $model->status=User::STATUS_DELETED;
        $model->save();
        

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
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
