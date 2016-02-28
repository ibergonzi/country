<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Accesos;
use frontend\models\AccesosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\Model;

use frontend\models\Personas;
use yii\data\ArrayDataProvider;



/**
 * AccesosController implements the CRUD actions for Accesos model.
 */
class AccesosController extends Controller
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
     * Lists all Accesos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccesosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accesos model.
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
     * Creates a new Accesos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accesos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    

    public function actionIngreso($nueva=null)
    {
		/*
		\Yii::$app->session->remove('personas');
			
		$sess=\Yii::$app->session['personas'];
		$sess[]=9;
		$sess[]=10;
		$sess[]=11;
		\Yii::$app->session['personas']=$sess;
		
		die();
		*/
		
		
        $model = new Accesos();

		// Se crea el array vacio que va a contener Personas
		$personas=[];
		
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get('personas');	
		
		if (isset($nueva)) {
			$sess[]=$nueva;
			\Yii::$app->session['personas']=$sess;			
		}
		
        if ($sess)  {
			// La session personas solo contiene los IDs, se recorre el array y se completa $personas con el objeto Personas
			foreach ($sess as $p) {
				$personas[]=Personas::findOne($p);
			}
			$dataProvider = new ArrayDataProvider([
				'allModels'=>$personas
			]);			
		} else {
			// dataProvider vacio
			$dataProvider = new ArrayDataProvider([
				'allModels'=>[ ['id'=>'', 'apellido'=>'', 'nombre'=>''], ]
			]);					
		}

		if (isset($nueva)) {
			return $this->renderAjax('ingreso', [
				//'model' => $searchModel,
				'model' => $model,
				'dataProvider'=>$dataProvider,
			]);       				
			
		} else {
				
			return $this->render('ingreso', [
				//'model' => $searchModel,
				'model' => $model,
				'dataProvider'=>$dataProvider,
			]);        
		}
        
		/*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('ingreso', [
                'model' => $model,
            ]);
        }
        */
    }

    

    /**
     * Updates an existing Accesos model.
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
     * Deletes an existing Accesos model.
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
     * Finds the Accesos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accesos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accesos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
