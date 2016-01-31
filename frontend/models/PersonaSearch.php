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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dni', 'created_by', 'updated_by'], 'integer'],
            [['apellido', 'nombre', 'nombre2', 'created_at', 'updated_at','fecnac'], 'safe'],
        ];
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
        ]);
//var_dump($params);die;
        $this->load($params);
//echo $this->fecnac;die;
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
            ->andFilterWhere(['like', 'nombre2', $this->nombre2])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ;

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
