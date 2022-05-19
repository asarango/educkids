<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMallaArea;

/**
 * ScholarisMallaAreaSearch represents the model behind the search form of `backend\models\ScholarisMallaArea`.
 */
class ScholarisMallaAreaSearch extends ScholarisMallaArea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'malla_id', 'area_id', 'total_porcentaje', 'orden'], 'integer'],
            [['se_imprime', 'promedia', 'es_cuantitativa'], 'boolean'],
            [['tipo'], 'safe'],
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
        $query = ScholarisMallaArea::find();

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
            'malla_id' => $this->malla_id,
            'area_id' => $this->area_id,
            'se_imprime' => $this->se_imprime,
            'promedia' => $this->promedia,
            'total_porcentaje' => $this->total_porcentaje,
            'es_cuantitativa' => $this->es_cuantitativa,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
