<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisReporteNovedadesComportamiento;

/**
 * ScholarisReporteNovedadesComportamientoSearch represents the model behind the search form of `backend\models\ScholarisReporteNovedadesComportamiento`.
 */
class ScholarisReporteNovedadesComportamientoSearch extends ScholarisReporteNovedadesComportamiento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['novedad_id'], 'integer'],
            [['bloque', 'semana', 'fecha', 'hora', 'materia', 'estudiante', 'curso', 'paralelo', 'codigo', 'falta', 'observacion', 'justificacion', 'usuario'], 'safe'],
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
    public function search($params, $usuario)
    {
        $query = ScholarisReporteNovedadesComportamiento::find()
                ->where(['usuario' => $usuario]);

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
            'novedad_id' => $this->novedad_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'bloque', $this->bloque])
            ->andFilterWhere(['ilike', 'semana', $this->semana])
            ->andFilterWhere(['ilike', 'hora', $this->hora])
            ->andFilterWhere(['ilike', 'materia', $this->materia])
            ->andFilterWhere(['ilike', 'estudiante', $this->estudiante])
            ->andFilterWhere(['ilike', 'curso', $this->curso])
            ->andFilterWhere(['ilike', 'paralelo', $this->paralelo])
            ->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'falta', $this->falta])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'justificacion', $this->justificacion])
            ->andFilterWhere(['ilike', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
