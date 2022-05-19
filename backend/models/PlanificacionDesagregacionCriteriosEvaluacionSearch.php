<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;

/**
 * PlanificacionDesagregacionCriteriosEvaluacionSearch represents the model behind the search form of `backend\models\PlanificacionDesagregacionCriteriosEvaluacion`.
 */
class PlanificacionDesagregacionCriteriosEvaluacionSearch extends PlanificacionDesagregacionCriteriosEvaluacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cabecera_id', 'criterio_evaluacion_id'], 'integer'],
            [['is_active'], 'boolean'],
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
        $query = PlanificacionDesagregacionCriteriosEvaluacion::find();

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
            'cabecera_id' => $this->cabecera_id,
            'criterio_evaluacion_id' => $this->criterio_evaluacion_id,
            'is_active' => $this->is_active,
        ]);

        return $dataProvider;
    }
}
