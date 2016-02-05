<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Entradas;

/**
 * EntradasSearch represents the model behind the search form about `frontend\models\Entradas`.
 */
class EntradasSearch extends Entradas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idpersonas_fk', 'idvehiculos_fk'], 'integer'],
            [['motivo'], 'safe'],
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
        $query = Entradas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idpersonas_fk' => $this->idpersonas_fk,
            'idvehiculos_fk' => $this->idvehiculos_fk,
        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo]);

        return $dataProvider;
    }
}
