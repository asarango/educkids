<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisActividadRecursos;

/**
 * ScholarisActividadRecursosSearch represents the model behind the search form of `backend\models\ScholarisActividadRecursos`.
 */
class ScholarisActividadRecursosSearch extends ScholarisActividadRecursos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'actividad_id'], 'integer'],
            [['tipo_codigo', 'nombre'], 'safe'],
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
        $query = ScholarisActividadRecursos::find();

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
            'actividad_id' => $this->actividad_id,
        ]);

        $query->andFilterWhere(['ilike', 'tipo_codigo', $this->tipo_codigo])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre]);

        return $dataProvider;
    }
}
