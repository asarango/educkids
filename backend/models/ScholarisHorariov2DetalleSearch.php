<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisHorariov2Detalle;

/**
 * ScholarisHorariov2DetalleSearch represents the model behind the search form of `backend\models\ScholarisHorariov2Detalle`.
 */
class ScholarisHorariov2DetalleSearch extends ScholarisHorariov2Detalle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cabecera_id', 'hora_id', 'dia_id'], 'integer'],
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
    public function search($params, $cabecera)
    {
        $query = ScholarisHorariov2Detalle::find()
                ->where(['cabecera_id' => $cabecera]);

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
            'cabecera_id' => $this->cabecera_id,
            'hora_id' => $this->hora_id,
            'dia_id' => $this->dia_id,
        ]);

        return $dataProvider;
    }
}
