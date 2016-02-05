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

public function actionApellidoslist($q = null, $id = null) {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
    if (!is_null($q)) {
        $query = new Query;
        $query->select(['id', new \yii\db\Expression("CONCAT(`apellido`, ' ',`nombre`) as text")])
            ->from('personas')
            ->where(['like', 'apellido', $q])
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


/* Para usar con typeahead
	public function actionBuscar($q=null) {
		$query = new Query;
		
		$query->select('apellido')
			->from('personas')
			->where('apellido LIKE "%' . $q .'%"')
			->orderBy('apellido');
		$command = $query->createCommand();
		$data = $command->queryAll();
		$out = [];
		foreach ($data as $d) {
			$out[] = ['value' => $d['apellido']];
		}
		echo Json::encode($out);
	}
	
	public function actionPre() {
		$query = new Query;
		
		$query->select('apellido')
			->from('personas')
			->limit(10)
			->orderBy('apellido');
		$command = $query->createCommand();
		$data = $command->queryAll();
		$out = [];
		foreach ($data as $d) {
			$out[] = ['value' => $d['apellido']];
		}
		echo Json::encode($out);
	}	
*/

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
     
	/*	
    public function actionCreate()
    {
        $model = new Persona();

        if ($model->load(Yii::$app->request->post())) {
			//echo $model->fecnac;die;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    */
    public function actionCreate()
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
    
    /*
    public function actionCreateAjax()
    {
        $model = new Persona();

        if ($model->load(Yii::$app->request->post())) {
			//echo $model->fecnac;die;
			if ($model->save()) {
				Yii::$app->response->format = 'json';
				return [
					'id' => $model->id,
					'apellido'=>$model->apellido,
					'nombre'=>$model->nombre,
				];
			}
        } else {
            return $this->renderAjax('createAjax', [
                'model' => $model,
            ]);
        }
    }
    */

	public function actionCreateAjax()
	{
		$model = new Persona();
		$request = \Yii::$app->getRequest();
		if ($request->isPost && $model->load($request->post())) {
			Yii::$app->response->format = 'json';
			return ['success' => $model->save()];
		}
		return $this->renderAjax('createajax', [
			'model' => $model,
		]);
	}


    
	public function actionValidateAjax()
	{
		$model = new Persona();
		
		$request = \Yii::$app->getRequest();
		if ($request->isPost && $model->load($request->post())) {
			Yii::$app->response->format = 'json';
			$arrayErrores=ActiveForm::validate($model);
			if (!empty($arrayErrores)) {
				return $arrayErrores;
			}
			else
			{	
				echo 'cuac';die;
				$model->save();
				return ['success'=>true];
			}	
		}
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

        if ($model->load(Yii::$app->request->post())) {
			//echo $model->fecnac;die;
			if ($model->save()) { 
            return $this->redirect(['view', 'id' => $model->id]);
		} else {	
            return $this->render('update', [
                'model' => $model,
            ]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
}
