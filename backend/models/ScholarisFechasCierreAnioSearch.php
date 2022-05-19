<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisFechasCierreAnio;

/**
 * ScholarisFechasCierreAnioSearch represents the model behind the search form of `backend\models\ScholarisFechasCierreAnio`.
 */
class ScholarisFechasCierreAnioSearch extends ScholarisFechasCierreAnio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_periodo_id'], 'integer'],
            [['fecha', 'observacion'], 'safe'],
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
        $query = ScholarisFechasCierreAnio::find();

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
            'scholaris_periodo_id' => $this->scholaris_periodo_id,
        ]);

        $query->andFilterWhere(['ilike', 'fecha', $this->fecha])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
