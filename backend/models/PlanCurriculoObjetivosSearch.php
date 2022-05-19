<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanCurriculoObjetivos;

/**
 * PlanCurriculoObjetivosSearch represents the model behind the search form of `backend\models\PlanCurriculoObjetivos`.
 */
class PlanCurriculoObjetivosSearch extends PlanCurriculoObjetivos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'distribucion_id'], 'integer'],
            [['codigo_ministerio', 'descripcion', 'tipo_objetivo'], 'safe'],
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
    public function search($params, $id)
    {
        $query = PlanCurriculoObjetivos::find()
                ->where(['distribucion_id' => $id]);

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
            'distribucion_id' => $this->distribucion_id,
        ]);

        $query->andFilterWhere(['ilike', 'codigo_ministerio', $this->codigo_ministerio])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'tipo_objetivo', $this->tipo_objetivo]);

        return $dataProvider;
    }
}
