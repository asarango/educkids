<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoDesempeno;

/**
 * PlanCurriculoDesempenoSearch represents the model behind the search form of `backend\models\PlanCurriculoDesempeno`.
 */
class PlanCurriculoDesempenoSearch extends PlanCurriculoDesempeno
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'destreza_id'], 'integer'],
            [['codigo', 'nombre', 'tipo_destreza'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = PlanCurriculoDesempeno::find();

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
            'destreza_id' => $this->destreza_id,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'tipo_destreza', $this->tipo_destreza]);

        return $dataProvider;
    }
}
