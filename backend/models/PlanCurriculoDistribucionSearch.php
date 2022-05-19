<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoDistribucion;

/**
 * PlanCurriculoDistribucionSearch represents the model behind the search form of `backend\models\PlanCurriculoDistribucion`.
 */
class PlanCurriculoDistribucionSearch extends PlanCurriculoDistribucion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nivel_id', 'curriculo_id', 'area_id', 'jefe_area_id'], 'integer'],
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
        $query = PlanCurriculoDistribucion::find();

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
            'nivel_id' => $this->nivel_id,
            'curriculo_id' => $this->curriculo_id,
            'area_id' => $this->area_id,
            'jefe_area_id' => $this->jefe_area_id,
        ]);

        return $dataProvider;
    }
}
