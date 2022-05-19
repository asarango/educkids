<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoDestreza;

/**
 * PlanCurriculoDestrezaSearch represents the model behind the search form of `backend\models\PlanCurriculoDestreza`.
 */
class PlanCurriculoDestrezaSearch extends PlanCurriculoDestreza
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'distribucion_id', 'bloque_id'], 'integer'],
            [['nombre'], 'safe'],
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
        $query = PlanCurriculoDestreza::find();

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
            'distribucion_id' => $this->distribucion_id,
            'bloque_id' => $this->bloque_id,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre]);

        return $dataProvider;
    }
}
