<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisTablero;

/**
 * ScholarisTableroSearch represents the model behind the search form of `backend\models\ScholarisTablero`.
 */
class ScholarisTableroSearch extends ScholarisTablero
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'p1', 'p2', 'p3', 'ex1', 'p4', 'p5', 'p6', 'ex2'], 'integer'],
            [['curso', 'paralelo', 'apellido_profesor', 'nombre_profesor'], 'safe'],
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
        $query = ScholarisTablero::find();

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
            'clase_id' => $this->clase_id,
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'ex1' => $this->ex1,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'p6' => $this->p6,
            'ex2' => $this->ex2,
        ]);

        $query->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'apellido_profesor', $this->apellido_profesor])
            ->andFilterWhere(['ilike', 'nombre_profesor', $this->nombre_profesor]);

        return $dataProvider;
    }
}
