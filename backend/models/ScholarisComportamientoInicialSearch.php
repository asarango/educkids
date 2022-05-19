<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisComportamientoInicial;

/**
 * ScholarisComportamientoInicialSearch represents the model behind the search form of `backend\models\ScholarisComportamientoInicial`.
 */
class ScholarisComportamientoInicialSearch extends ScholarisComportamientoInicial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'inscription_id', 'faculty_id'], 'integer'],
            [['q1', 'q2', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
        $query = ScholarisComportamientoInicial::find();

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
            'inscription_id' => $this->inscription_id,
            'faculty_id' => $this->faculty_id,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'q1', $this->q1])
            ->andFilterWhere(['ilike', 'q2', $this->q2])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
