<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisTomaAsisteciaDetalle;

/**
 * ScholarisTomaAsisteciaDetalleSearch represents the model behind the search form of `backend\models\ScholarisTomaAsisteciaDetalle`.
 */
class ScholarisTomaAsisteciaDetalleSearch extends ScholarisTomaAsisteciaDetalle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toma_id', 'alumno_id'], 'integer'],
            [['asiste', 'atraso', 'atraso_justificado', 'falta', 'falta_justificada'], 'boolean'],
            [['atraso_observacion_justificacion', 'falta_observacion_justificacion', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
        $query = ScholarisTomaAsisteciaDetalle::find();

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
            'toma_id' => $this->toma_id,
            'alumno_id' => $this->alumno_id,
            'asiste' => $this->asiste,
            'atraso' => $this->atraso,
            'atraso_justificado' => $this->atraso_justificado,
            'falta' => $this->falta,
            'falta_justificada' => $this->falta_justificada,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'atraso_observacion_justificacion', $this->atraso_observacion_justificacion])
            ->andFilterWhere(['ilike', 'falta_observacion_justificacion', $this->falta_observacion_justificacion])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
