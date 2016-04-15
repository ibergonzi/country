<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\UfTitularidadPersonas;

/**
 * UfTitularidadPersonasSearch represents the model behind the search form about `frontend\models\UfTitularidadPersonas`.
 */
class UfTitularidadPersonasSearch extends UfTitularidadPersonas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uf_titularidad_id', 'id_persona', 'created_by', 'updated_by'], 'integer'],
            [['tipo', 'observaciones', 'created_at', 'updated_at'], 'safe'],
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
        $query = UfTitularidadPersonas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'uf_titularidad_id' => $this->uf_titularidad_id,
            'id_persona' => $this->id_persona,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
