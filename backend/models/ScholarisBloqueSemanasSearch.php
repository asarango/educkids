<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisBloqueSemanas;

/**
 * ScholarisBloqueSemanasSearch represents the model behind the search form of `backend\models\ScholarisBloqueSemanas`.
 */
class ScholarisBloqueSemanasSearch extends ScholarisBloqueSemanas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bloque_id', 'semana_numero', 'estado'], 'integer'],
            [['nombre_semana', 'fecha_inicio', 'fecha_finaliza', 'fecha_limite_inicia', 'fecha_limite_tope'], 'safe'],
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
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $intitutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        
        $query = ScholarisBloqueSemanas::find()
                ->innerJoin("scholaris_bloque_actividad b", "b.id = scholaris_bloque_semanas.bloque_id")
                ->where(['b.scholaris_periodo_codigo' => $modelPeriodo->codigo, 'b.instituto_id' => $intitutoId]);

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
            'bloque_id' => $this->bloque_id,
            'semana_numero' => $this->semana_numero,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_finaliza' => $this->fecha_finaliza,
            'estado' => $this->estado,
            'fecha_limite_inicia' => $this->fecha_limite_inicia,
            'fecha_limite_tope' => $this->fecha_limite_tope,
        ]);

        $query->andFilterWhere(['ilike', 'nombre_semana', $this->nombre_semana]);

        return $dataProvider;
    }
}
