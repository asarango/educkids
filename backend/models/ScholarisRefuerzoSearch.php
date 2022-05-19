<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisRefuerzo;

/**
 * ScholarisRefuerzoSearch represents the model behind the search form of `backend\models\ScholarisRefuerzo`.
 */
class ScholarisRefuerzoSearch extends ScholarisRefuerzo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'grupo_id', 'bloque_id', 'orden_calificacion'], 'integer'],
            [['promedio_normal', 'nota_refuerzo', 'nota_final'], 'number'],
            [['observacion'], 'safe'],
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
    public function search($params, $grupo)
    {
        $query = ScholarisRefuerzo::find()
                ->where(['grupo_id' => $grupo]);

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
            'grupo_id' => $this->grupo_id,
            'bloque_id' => $this->bloque_id,
            'orden_calificacion' => $this->orden_calificacion,
            'promedio_normal' => $this->promedio_normal,
            'nota_refuerzo' => $this->nota_refuerzo,
            'nota_final' => $this->nota_final,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
