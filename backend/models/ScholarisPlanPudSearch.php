<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisPlanPud;

/**
 * ScholarisPlanPudSearch represents the model behind the search form of `backend\models\ScholarisPlanPud`.
 */
class ScholarisPlanPudSearch extends ScholarisPlanPud
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'bloque_id', 'ac_responsable_dece', 'quien_revisa_id', 
                'quien_aprueba_id','pud_original', 'total_semanas', 'total_periodos'
            ], 'integer'],
            [['titulo', 'fecha_inicio', 'fecha_finalizacion', 'objetivo_unidad', 'ac_necesidad_atendida', 'ac_adaptacion_aplicada', 'bibliografia', 'observaciones', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
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
    public function search($params, $clase)
    {
        $query = ScholarisPlanPud::find()
                ->where(['clase_id' => $clase]);

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
            'bloque_id' => $this->bloque_id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_finalizacion' => $this->fecha_finalizacion,
            'ac_responsable_dece' => $this->ac_responsable_dece,
            'quien_revisa_id' => $this->quien_revisa_id,
            'quien_aprueba_id' => $this->quien_aprueba_id,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
            'pud_original' => $this->pud_original,
            'total_periodos' => $this->total_periodos,
            'total_semanas' => $this->total_semanas,
        ]);

        $query->andFilterWhere(['ilike', 'titulo', $this->titulo])
            ->andFilterWhere(['ilike', 'objetivo_unidad', $this->objetivo_unidad])
            ->andFilterWhere(['ilike', 'ac_necesidad_atendida', $this->ac_necesidad_atendida])
            ->andFilterWhere(['ilike', 'ac_adaptacion_aplicada', $this->ac_adaptacion_aplicada])
            ->andFilterWhere(['ilike', 'bibliografia', $this->bibliografia])
            ->andFilterWhere(['ilike', 'observaciones', $this->observaciones])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
    
}
