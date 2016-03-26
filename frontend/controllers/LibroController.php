<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Libro;
use frontend\models\LibroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use kartik\mpdf\Pdf;

/**
 * LibroController implements the CRUD actions for Libro model.
 */
class LibroController extends Controller
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
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['accederLibro'], 
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['altaLibro'], 
                    ],                    
		
                 ], // fin rules
             ], // fin access               
        ];
    }

    /**
     * Lists all Libro models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new LibroSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        //$post = Yii::$app->request->post();
        //$dataProvider = $searchModel->search($post);  

		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
 
    }
    
     /**
     * Displays a single Libro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
			'pdf'=>false            
        ]);
    }
    
    public function actionPdf($id)
    {
		
	   $r=$this->renderPartial('view', [
				'model' => $this->findModel($id),
				'pdf'=>true
			]);		
	
		$pdf = new Pdf([
			'filename'=>'Detalle Libro Guardia '.$id.'.pdf',
			'mode' => Pdf::MODE_CORE, 
			'format' => Pdf::FORMAT_A4, 
			// Pdf::ORIENT_LANDSCAPE
			'orientation' => Pdf::ORIENT_PORTRAIT, 
			//'destination' => Pdf::DEST_BROWSER, // no funciona con firefox
			'destination' => Pdf::DEST_DOWNLOAD, 
			'content' => $r,  
			'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
			// any css to be embedded if required
			//'cssInline' => '.kv-heading-1{font-size:18px}', 
			// aca pongo el estilo que uso en el view para que los detailview salgan parejitos
			'cssInline' => 'table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}',
			'options' => ['title' => 'Detalle de acceso'],
			'methods' => [ 
				'SetHeader'=>['Detalle de Acceso - Miraflores'], 
				'SetFooter'=>['{PAGENO}'],
			]
		]);
		return $pdf->render(); 		
	
    }    

    /**
     * Creates a new Libro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if (!\Yii::$app->session->get('porton')) {
			// se setea returnUrl para que funcione el goBack en portones/elegir (parecido a lo que hace login())
			//Yii::$app->user->setReturnUrl(Yii::$app->urlManager->createUrl(['libro/create']));			
			return $this->redirect(['portones/elegir','backUrl'=>'libro/create']);
		}
		
        $model = new Libro();
        $model->idporton=\Yii::$app->session->get('porton');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Libro model.
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
     * Deletes an existing Libro model.
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
     * Finds the Libro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Libro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Libro::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
