<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMecV2MallaMateria;

/**
 * ScholarisMecV2MallaMateriaSearch represents the model behind the search form of `backend\models\ScholarisMecV2MallaMateria`.
 */
class ScholarisMecV2MallaMateriaSearch extends ScholarisMecV2MallaMateria
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'asignatura_id', 'area_id','orden'], 'integer'],
            [['codigo','tipo'], 'safe'],
            [['imprime', 'es_cuantitativa', 'promedia'], 'boolean'],
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
        $query = ScholarisMecV2MallaMateria::find();

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
            'asignatura_id' => $this->asignatura_id,
            'area_id' => $this->area_id,
            'imprime' => $this->imprime,
            'es_cuantitativa' => $this->es_cuantitativa,
            'tipo' => $this->tipo,
            'orden' => $this->orden,
            'promedia' => $this->promedia,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo]);

        return $dataProvider;
    }
}
