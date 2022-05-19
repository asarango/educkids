<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisBloqueActividad;

/**
 * ScholarisBloqueActividadSearch represents the model behind the search form of `backend\models\ScholarisBloqueActividad`.
 */
class ScholarisBloqueActividadSearch extends ScholarisBloqueActividad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'write_uid', 'orden', 'dias_laborados','instituto_id'], 'integer'],
            [['name', 'create_date', 'write_date', 'quimestre', 'tipo', 'desde', 'hasta', 'scholaris_periodo_codigo', 'tipo_bloque', 'estado', 'abreviatura', 'tipo_uso', 'bloque_inicia', 'bloque_finaliza'], 'safe'],
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
    public function search($params, $periodo, $instituto)
    {
        $query = ScholarisBloqueActividad::find()
                ->where([
                            'scholaris_periodo_codigo' => $periodo,
                            'instituto_id' => $instituto
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
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'orden' => $this->orden,
            'dias_laborados' => $this->dias_laborados,
            'bloque_inicia' => $this->bloque_inicia,
            'bloque_finaliza' => $this->bloque_finaliza,
            'instituto_id' => $this->instituto_id,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'quimestre', $this->quimestre])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'scholaris_periodo_codigo', $this->scholaris_periodo_codigo])
            ->andFilterWhere(['ilike', 'tipo_bloque', $this->tipo_bloque])
            ->andFilterWhere(['ilike', 'estado', $this->estado])
            ->andFilterWhere(['ilike', 'abreviatura', $this->abreviatura])
            ->andFilterWhere(['ilike', 'tipo_uso', $this->tipo_uso]);

        return $dataProvider;
    }
}
