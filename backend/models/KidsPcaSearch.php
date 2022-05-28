<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KidsPca;

/**
 * KidsPcaSearch represents the model behind the search form of `backend\models\KidsPca`.
 */
class KidsPcaSearch extends KidsPca
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ism_area_materia_id', 'carga_horaria_semanal', 'numero_semanas_trabajo', 'imprevistos'], 'integer'],
            [['objetivos', 'observaciones', 'bibliografia', 'estado', 'created_at', 'created', 'updated_at', 'updated'], 'safe'],
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
        $query = KidsPca::find();

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
            'ism_area_materia_id' => $this->ism_area_materia_id,
            'carga_horaria_semanal' => $this->carga_horaria_semanal,
            'numero_semanas_trabajo' => $this->numero_semanas_trabajo,
            'imprevistos' => $this->imprevistos,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'objetivos', $this->objetivos])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones])
            ->andFilterWhere(['ilike', 'bibliografia', $this->bibliografia])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'created', $this->created])
            ->andFilterWhere(['ilike', 'updated', $this->updated]);

        return $dataProvider;
    }
}
