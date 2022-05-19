<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanificacionDesagregacionCabecera;

/**
 * PlanificacionDesagregacionCabeceraSearch represents the model behind the search form of `backend\models\PlanificacionDesagregacionCabecera`.
 */
class PlanificacionDesagregacionCabeceraSearch extends PlanificacionDesagregacionCabecera
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_materia_id', 'curriculo_mec_nivel_id', 'year_from', 'year_to'], 'integer'],
            [['code', 'comments'], 'safe'],
            [['is_active'], 'boolean'],
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
        $query = PlanificacionDesagregacionCabecera::find();

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
            'scholaris_materia_id' => $this->scholaris_materia_id,
            'curriculo_mec_nivel_id' => $this->curriculo_mec_nivel_id,
            'year_from' => $this->year_from,
            'year_to' => $this->year_to,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'comments', $this->comments]);

        return $dataProvider;
    }
}
