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
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idporton', 'created_by', 'updated_by'], 'integer'],
            [['texto', 'created_at', 'updated_at','descUsuario'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => [
							  'created_at' => SORT_DESC,
							  ]
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
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idporton' => $this->idporton,
            //'created_by' => $this->created_by,
            //'created_at' => $this->created_at,
            //'updated_by' => $this->updated_by,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'texto', $this->texto])
			->andFilterWhere(['like', 'user.username', $this->descUsuario]);
			
			
        if (isset($this->fecdesde) && isset($this->fechasta)) {
			$f=new \DateTime($this->fechasta);
			$f->add(new \DateInterval('P1D'));
			$query->andFilterWhere(['between', 'libro.created_at', $this->fecdesde, $f->format('Y-m-d')]);
			//unset($this->fecdesde);
		} else {    
            $query->andFilterWhere(['like', 'libro.created_at', $this->created_at]);
		}			

        return $dataProvider;
    }
}
