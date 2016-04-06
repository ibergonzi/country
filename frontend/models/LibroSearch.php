<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Libro;

/**
 * LibroSearch represents the model behind the search form about `frontend\models\Libro`.
 */
class LibroSearch extends Libro
{
	public $descUsuario;
	public $fecdesde;
	public $fechasta;	
	public $exportColumns;

	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idporton', 'created_by', 'updated_by'], 'integer'],
            [['texto', 'created_at', 'updated_at','descUsuario','exportColumns'], 'safe'],
            [['fecdesde','fechasta',],'safe'],
            [['fecdesde','fechasta',],'validaRangoFechas','skipOnEmpty' => true],            
        ];
    }

    public function validaRangoFechas($attribute, $params) 
    {
		if (empty($this->fecdesde) || empty($this->fechasta)) {
			if (empty($this->fecdesde)) {
				$this->addError('fecdesde','Debe ingresar un rango de fechas');return;
			}
			if (empty($this->fechasta)) {
				$this->addError('fechasta','Debe ingresar un rango de fechas');return;
			}
		}
		if (strtotime($this->fecdesde) > strtotime($this->fechasta)) {
			$this->addError('fechasta','Esta fecha no puede ser anterior a la otra fecha');return;
		}
	}

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Libro::find()->joinWith('userCreatedBy');

		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['libro.defaultPageSize'];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],
			'sort' => ['defaultOrder' => ['created_at' => SORT_DESC,],
					   'enableMultiSort'=>true,
					  ],            
        ]);

        // Agregado a mano, para que incluya el ordenamiento por descCliente
        $dataProvider->sort->attributes['descUsuario'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'libro.id' => $this->id,
            'idporton' => $this->idporton,
            //'created_by' => $this->created_by,
            //'created_at' => $this->fecSP2EN($this->created_at),
            //'updated_by' => $this->updated_by,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'texto', $this->texto])
			  ->andFilterWhere(['like', 'user.username', $this->descUsuario]);
		
		if (isset($params['resetFechas'])) {
			\Yii::$app->session->remove('libroFecDesde');
			\Yii::$app->session->remove('libroFecHasta');
			$this->fecdesde=null;
			$this->fechasta=null;
			unset($params['resetFechas']);
		}
		

        if (!empty($this->fecdesde) && !empty($this->fechasta)) {
			// cada vez que se envia el form con el rango de fechas se guardan las fechas en sesion
			\Yii::$app->session->set('libroFecDesde',$this->fecdesde);			
			\Yii::$app->session->set('libroFecHasta',$this->fechasta);					
			
			// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
			$f=new \DateTime($this->fechasta);
			$f->add(new \DateInterval('P1D'));
			$query->andFilterWhere(['between', 'libro.created_at', $this->fecdesde, $f->format('Y-m-d')]);
			//unset($this->fecdesde);
		} else {
			$sfd=\Yii::$app->session->get('libroFecDesde')?\Yii::$app->session->get('libroFecDesde'):'';
			$sfh=\Yii::$app->session->get('libroFecHasta')?\Yii::$app->session->get('libroFecHasta'):'';
			
			// si todavia están en sesion las variables del rango de fechas se hace el between y se elimina created_at
			if ($sfd && $sfh) {
				// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
				$f=new \DateTime(\Yii::$app->session->get('libroFecHasta'));
				$f->add(new \DateInterval('P1D'));
				$query->andFilterWhere(['between', 'libro.created_at', \Yii::$app->session->get('libroFecDesde'), 
						$f->format('Y-m-d')]);
				$this->created_at='';		
			}				
			else
			{	
				$query->andFilterWhere(['like', 'libro.created_at', $this->fecSP2EN($this->created_at)]);
			}	
		}			

        return $dataProvider;
    }
    
    public function fecSP2EN($fec) {
		if ($fec ==null) return null;
		$a=explode('/',$fec);
		$r=array_reverse($a);
		$f=implode('-',$r);		
		return $f;
    }    
}
