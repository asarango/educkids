<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisBloqueSemanasObservacion;

/**
 * ScholarisBloqueSemanasObservacionSearch represents the model behind the search form of `backend\models\ScholarisBloqueSemanasObservacion`.
 */
class ScholarisBloqueSemanasObservacionSearch extends ScholarisBloqueSemanasObservacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'semana_id', 'comparte_bloque', 'usuario', 'creado_por', 'actualizado_por'], 'integer'],
            [['creado_fecha', 'actualizado_fecha', 'observacion'], 'safe'],
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
    public function search($params,$usuario, $periodoCodigo)
    {
        $query = ScholarisBloqueSemanasObservacion::find()
                ->innerJoin('scholaris_bloque_semanas s', 's.id = scholaris_bloque_semanas_observacion.semana_id')
                ->innerJoin('scholaris_bloque_actividad b','s.bloque_id = b.id')
                ->where([
                    'scholaris_bloque_semanas_observacion.usuario' => $usuario,
                    'b.scholaris_periodo_codigo' => $periodoCodigo
                ]);
                

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
            'semana_id' => $this->semana_id,
            'comparte_bloque' => $this->comparte_bloque,
            'usuario' => $this->usuario,
            'creado_fecha' => $this->creado_fecha,
            'creado_por' => $this->creado_por,
            'actualizado_fecha' => $this->actualizado_fecha,
            'actualizado_por' => $this->actualizado_por,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
