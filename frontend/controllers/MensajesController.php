<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Mensajes;
use frontend\models\MensajesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MensajesController implements the CRUD actions for Mensajes model.
 */
class MensajesController extends Controller
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

    /**
     * Lists all Mensajes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MensajesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mensajes model.
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
     * Creates a new Mensajes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mensajes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    
    public function actionCreateAjax($modelName,$modelID)
    {
		// Esta funcion sirve tanto para dar de alta un mensaje como para darlo de baja,
		// las dos acciones se hacen por el submit del formulario, la diferencia es que cuando
		// se hace un alta, el campo estado (que está hidden) viene vacío, cuando viene con el valor 1 significa
		// que ya existia un mensaje y con el submit lo tiene que dar de baja (lógica)
		
		// Esta función solo retorna 1 registro (que tiene que estar activo)
		$model=Mensajes::getMensajesByModelId($modelName,$modelID);

		// si no tiene registro activo, se inicializa $model para permitir su alta
		if (empty($model)) {
			$model = new Mensajes();			
		}							
		
		// toma los valores que vienen por submit
        if ($model->load(Yii::$app->request->post()) ) {
			$model->model=$modelName;
			$model->model_id=$modelID;			
			// Si estado es 1 significa que ya existia el mensaje y hay que darle baja lógica
			if ($model->estado==1) {
				$model->estado=0;
			}

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
                'modelNameOrigen' => $modelName,
                'modelIDOrigen' => $modelID,                               
         ]);
    }        
    

    /**
     * Updates an existing Mensajes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Mensajes model.
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
     * Finds the Mensajes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mensajes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mensajes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
