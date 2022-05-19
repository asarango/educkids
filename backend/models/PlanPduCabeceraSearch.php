<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanPduCabecera;

/**
 * PlanPduCabeceraSearch represents the model behind the search form of `backend\models\PlanPduCabecera`.
 */
class PlanPduCabeceraSearch extends PlanPduCabecera
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'asignatura_curriculo_id', 'bloque_id', 'periodos', 'coordinador_id', 'vicerrector_id', 'objetivo_por_nivel_id'], 'integer'],
            [['planificacion_titulo', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
        $query = PlanPduCabecera::find()
                ->where(['id' => $id]);

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
            'clase_id' => $this->clase_id,
            'asignatura_curriculo_id' => $this->asignatura_curriculo_id,
            'bloque_id' => $this->bloque_id,
            'periodos' => $this->periodos,
            'coordinador_id' => $this->coordinador_id,
            'vicerrector_id' => $this->vicerrector_id,
            'objetivo_por_nivel_id' => $this->objetivo_por_nivel_id,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'planificacion_titulo', $this->planificacion_titulo])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
