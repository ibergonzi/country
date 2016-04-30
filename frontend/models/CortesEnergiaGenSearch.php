<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\CortesEnergiaGen;

/**
 * CortesEnergiaGenSearch represents the model behind the search form about `frontend\models\CortesEnergiaGen`.
 */
class CortesEnergiaGenSearch extends CortesEnergiaGen
{
	public $descripcion;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_cortes_energia', 'id_generador', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['hora_desde', 'hora_hasta', 'created_at', 'updated_at', 'motivo_baja','observaciones','descripcion'], 'safe'],
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
        $query = CortesEnergiaGen::find()->joinWith('generador');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC,],
						'enableMultiSort'=>true,            
                      ],              
        ]);
        
        $dataProvider->sort->attributes['descripcion'] = [
            'asc' => ['generadores.descripcion' => SORT_ASC],
            'desc' => ['generadores.descripcion' => SORT_DESC],
        ];        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_cortes_energia' => $this->id_cortes_energia,
            'id_generador' => $this->descripcion,//$this->id_generador,
            'hora_desde' => $this->hora_desde,
            'hora_hasta' => $this->hora_hasta,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
			  ->andFilterWhere(['like', 'observaciones', $this->observaciones]);        

        return $dataProvider;
    }
}
