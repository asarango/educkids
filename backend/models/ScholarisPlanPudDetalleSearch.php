<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisPlanPudDetalle;

/**
 * ScholarisPlanPudDetalleSearch represents the model behind the search form of `backend\models\ScholarisPlanPudDetalle`.
 */
class ScholarisPlanPudDetalleSearch extends ScholarisPlanPudDetalle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pud_id','cantidad_periodos'], 'integer'],
            [['tipo', 'codigo', 'contenido', 'pertenece_a_codigo', 'estado'], 'safe'],
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
    public function search($params, $pudId)
    {
        $query = ScholarisPlanPudDetalle::find()
                ->where(['pud_id' => $pudId]);

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
            'pud_id' => $this->pud_id,
            'cantidad_periodos' => $this->cantidad_periodos,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'contenido', $this->contenido])
            ->andFilterWhere(['ilike', 'pertenece_a_codigo', $this->pertenece_a_codigo])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
