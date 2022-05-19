<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMalla;

/**
 * ScholarisMallaSearch represents the model behind the search form of `backend\models\ScholarisMalla`.
 */
class ScholarisMallaSearch extends ScholarisMalla
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'periodo_id', 'section_id', 'tipo_uso'], 'integer'],
            [['codigo', 'nombre_malla'], 'safe'],
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
    public function search($params, $periodoId, $institutoId)
    {
        $query = ScholarisMalla::find()
                ->innerJoin("op_section s", "s.id = scholaris_malla.section_id")
                ->innerJoin("op_period p","p.id = s.period_id")
                ->where(['periodo_id' => $periodoId, 'institute' => $institutoId]);

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
            'periodo_id' => $this->periodo_id,
            'section_id' => $this->section_id,
            'tipo_uso' => $this->tipo_uso,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'nombre_malla', $this->nombre_malla]);

        return $dataProvider;
    }
}
