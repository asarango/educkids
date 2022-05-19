<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisAsistenciaComportamientoDetalle;

/**
 * ScholarisAsistenciaComportamientoDetalleSearch represents the model behind the search form of `backend\models\ScholarisAsistenciaComportamientoDetalle`.
 */
class ScholarisAsistenciaComportamientoDetalleSearch extends ScholarisAsistenciaComportamientoDetalle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'comportamiento_id', 'cantidad_descuento', 'total_x_unidad'], 'integer'],
            [['codigo', 'nombre', 'tipo', 'code_fj'], 'safe'],
            [['punto_descuento','limite'], 'number'],
            [['activo'], 'boolean'],
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
        $query = ScholarisAsistenciaComportamientoDetalle::find()
                ->where(['comportamiento_id' => $id]);

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
            'comportamiento_id' => $this->comportamiento_id,
            'cantidad_descuento' => $this->cantidad_descuento,
            'punto_descuento' => $this->punto_descuento,
            'total_x_unidad' => $this->total_x_unidad,
            'activo' => $this->activo,
            'limite' => $this->limite,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'code_fj', $this->code_fj]);

        return $dataProvider;
    }
}
