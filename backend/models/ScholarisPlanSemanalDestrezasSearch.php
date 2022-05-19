<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisPlanSemanalDestrezas;

/**
 * ScholarisPlanSemanalDestrezasSearch represents the model behind the search form of `backend\models\ScholarisPlanSemanalDestrezas`.
 */
class ScholarisPlanSemanalDestrezasSearch extends ScholarisPlanSemanalDestrezas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_id', 'faculty_id', 'semana_id', 'comparte_valor'], 'integer'],
            [['concepto', 'contexto', 'pregunta_indagacion', 'enfoque', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
        $query = ScholarisPlanSemanalDestrezas::find();

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
            'curso_id' => $this->curso_id,
            'faculty_id' => $this->faculty_id,
            'semana_id' => $this->semana_id,
            'comparte_valor' => $this->comparte_valor,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'concepto', $this->concepto])
            ->andFilterWhere(['ilike', 'contexto', $this->contexto])
            ->andFilterWhere(['ilike', 'pregunta_indagacion', $this->pregunta_indagacion])
            ->andFilterWhere(['ilike', 'enfoque', $this->enfoque])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
