<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MovimUf;

/**
 * MovimUfSearch represents the model behind the search form about `frontend\models\MovimUf`.
 */
class MovimUfSearch extends MovimUf
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cesion', 'migracion', 'fec_vto', 'manual'], 'integer'],
            [['desc_movim_uf'], 'safe'],
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
        $query = MovimUf::find();

		//$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['REEMPLAZAR.defaultPageSize'];
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:15;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id' => SORT_DESC,],
						'enableMultiSort'=>true,            
                      ],              
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
            'cesion' => $this->cesion,
            'migracion' => $this->migracion,
            'fec_vto' => $this->fec_vto,
            'manual' => $this->manual,
        ]);

        $query->andFilterWhere(['like', 'desc_movim_uf', $this->desc_movim_uf]);

        return $dataProvider;
    }
}
