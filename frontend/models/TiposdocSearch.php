<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Tiposdoc;

/**
 * TiposdocSearch represents the model behind the search form about `frontend\models\Tiposdoc`.
 */
class TiposdocSearch extends Tiposdoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'persona_fisica'], 'integer'],
            [['desc_tipo_doc', 'desc_tipo_doc_abr'], 'safe'],
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
        $query = Tiposdoc::find();

		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['tiposdoc.defaultPageSize'];

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
            'persona_fisica' => $this->persona_fisica,
        ]);

        $query->andFilterWhere(['like', 'desc_tipo_doc', $this->desc_tipo_doc])
            ->andFilterWhere(['like', 'desc_tipo_doc_abr', $this->desc_tipo_doc_abr]);

        return $dataProvider;
    }
}
