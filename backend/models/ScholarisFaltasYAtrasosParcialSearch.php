<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisFaltasYAtrasosParcial;

/**
 * ScholarisFaltasYAtrasosParcialSearch represents the model behind the search form of `backend\models\ScholarisFaltasYAtrasosParcial`.
 */
class ScholarisFaltasYAtrasosParcialSearch extends ScholarisFaltasYAtrasosParcial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'alumno_id', 'bloque_id', 'atrasos', 'faltas_justificadas', 'faltas_injustificadas'], 'integer'],
            [['observacion'], 'safe'],
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
        $query = ScholarisFaltasYAtrasosParcial::find();

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
            'alumno_id' => $this->alumno_id,
            'bloque_id' => $this->bloque_id,
            'atrasos' => $this->atrasos,
            'faltas_justificadas' => $this->faltas_justificadas,
            'faltas_injustificadas' => $this->faltas_injustificadas,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
