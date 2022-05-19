<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisAsistenciaComportamientoFecuencia;

/**
 * ScholarisAsistenciaComportamientoFecuenciaSearch represents the model behind the search form of `backend\models\ScholarisAsistenciaComportamientoFecuencia`.
 */
class ScholarisAsistenciaComportamientoFecuenciaSearch extends ScholarisAsistenciaComportamientoFecuencia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'detalle_id', 'fecuencia', 'accion'], 'integer'],
            [['puntos'], 'number'],
            [['observacion', 'alerta'], 'safe'],
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
        $query = ScholarisAsistenciaComportamientoFecuencia::find()
                ->where(['detalle_id' => $id]);

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
            'detalle_id' => $this->detalle_id,
            'fecuencia' => $this->fecuencia,
            'puntos' => $this->puntos,
            'accion' => $this->accion,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'alerta', $this->alerta]);

        return $dataProvider;
    }
}
