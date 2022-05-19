<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMecV2Distribucion;

/**
 * ScholarisMecV2DistribucionSearch represents the model behind the search form of `backend\models\ScholarisMecV2Distribucion`.
 */
class ScholarisMecV2DistribucionSearch extends ScholarisMecV2Distribucion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'materia_id', 'curso_id'], 'integer'],
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
        $query = ScholarisMecV2Distribucion::find();

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
            'materia_id' => $this->materia_id,
            'curso_id' => $this->curso_id,
        ]);

        return $dataProvider;
    }
}
