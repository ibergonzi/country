<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Persona;


/**
 * PersonaSearch represents the model behind the search form about `frontend\models\Persona`.
 */
class PersonaSearch extends Persona
{
	public $fecdesde;
	public $fechasta;	
	public $nomCompleto;
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dni', 'created_by', 'updated_by'], 'integer'],
            [['apellido', 'nombre', 'nombre2', 'created_at', 'updated_at','fecnac','nomCompleto','foto',], 'safe'],
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
        $query = Persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                'pagination' => [
					'pageSize' => -1,
						],
        ]);

		
        $this->load($params);
        //var_dump($params);die;

		
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        
        

        $query->andFilterWhere([
            'id' => $this->id,
            'dni' => $this->dni,
            'fecnac' => $this->fecnac,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            //'updated_at' => $this->created_at,
            
        ]);
		
        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'nombre2', $this->nombre2]);
            
        if (isset($this->fecdesde) && isset($this->fechasta)) {
			$f=new \DateTime($this->fechasta);
			$f->add(new \DateInterval('P1D'));
			$query->andFilterWhere(['between', 'updated_at', $this->fecdesde, $f->format('Y-m-d')]);
			//unset($this->fecdesde);
		} else {    
            $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);
		}
		


        return $dataProvider;
    }
    

}
