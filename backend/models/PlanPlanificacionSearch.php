<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanPlanificacion;

/**
 * PlanPlanificacionSearch represents the model behind the search form of `backend\models\PlanPlanificacion`.
 */
class PlanPlanificacionSearch extends PlanPlanificacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'distribucion_id', 'curso_id', 'periodo_id'], 'integer'],
            [['estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
    public function search($params,$faculty,$periodo)
    {
        $query = PlanPlanificacion::find()
                ->innerJoin("plan_curriculo_distribucion","plan_curriculo_distribucion.id = plan_planificacion.distribucion_id")
                ->where(["plan_curriculo_distribucion.jefe_area_id" => $faculty, "periodo_id" => $periodo]);

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
            'curso_id' => $this->curso_id,
            'periodo_id' => $this->periodo_id,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
