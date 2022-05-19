<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoObjetivosGenerales;

/**
 * PlanCurriculoObjetivosGeneralesSearch represents the model behind the search form of `backend\models\PlanCurriculoObjetivosGenerales`.
 */
class PlanCurriculoObjetivosGeneralesSearch extends PlanCurriculoObjetivosGenerales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'evaluacion_id'], 'integer'],
            [['codigo', 'objetivo_general'], 'safe'],
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
        $query = PlanCurriculoObjetivosGenerales::find();

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
            'evaluacion_id' => $this->evaluacion_id,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'objetivo_general', $this->objetivo_general]);

        return $dataProvider;
    }
}
