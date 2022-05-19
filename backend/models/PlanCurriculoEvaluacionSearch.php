<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoEvaluacion;

/**
 * PlanCurriculoEvaluacionSearch represents the model behind the search form of `backend\models\PlanCurriculoEvaluacion`.
 */
class PlanCurriculoEvaluacionSearch extends PlanCurriculoEvaluacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'distribucion_id'], 'integer'],
            [['codigo', 'criterio_evaluacion', 'orientacion_metodologica'], 'safe'],
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
        $query = PlanCurriculoEvaluacion::find();

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
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'criterio_evaluacion', $this->criterio_evaluacion])
            ->andFilterWhere(['ilike', 'orientacion_metodologica', $this->orientacion_metodologica]);

        return $dataProvider;
    }
}
