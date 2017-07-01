<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AccesosConceptos;

/**
 * AccesosConceptosSearch represents the model behind the search form about `\frontend\models\AccesosConceptos`.
 */
class AccesosConceptosSearch extends AccesosConceptos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'req_tarjeta', 'req_seguro','req_seguro_vehic','req_licencia'], 'integer'],
            [['concepto','estado'], 'safe'],
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
        $query = AccesosConceptos::find();

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
            'req_tarjeta' => $this->req_tarjeta,
            'req_seguro' => $this->req_seguro,
            'req_seguro_vehic' => $this->req_seguro_vehic,
            'req_licencia' => $this->req_licencia,                        
        ]);

        $query->andFilterWhere(['like', 'concepto', $this->concepto]);

        return $dataProvider;
    }
}
