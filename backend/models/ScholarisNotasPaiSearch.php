<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisNotasPai;

/**
 * ScholarisNotasPaiSearch represents the model behind the search form of `backend\models\ScholarisNotasPai`.
 */
class ScholarisNotasPaiSearch extends ScholarisNotasPai
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'alumno_id', 'usuario_crea', 'usuario_modifica'], 'integer'],
            [['alumno', 'quimestre', 'scholaris_periodo_codigo', 'creado', 'actualizado'], 'safe'],
            [['sumativa1_a', 'sumativa2_a', 'sumativa3_a', 'nota_a', 'sumativa1_b', 'sumativa2_b', 'sumativa3_b', 'nota_b', 'sumativa1_c', 'sumativa2_c', 'sumativa3_c', 'nota_c', 'sumativa1_d', 'sumativa2_d', 'sumativa3_d', 'nota_d', 'suma_total', 'final_homologado'], 'number'],
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
    public function search($params, $claseId)
    {
        $query = ScholarisNotasPai::find()->where([
            'clase_id' => $claseId
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
            'clase_id' => $this->clase_id,
            'alumno_id' => $this->alumno_id,
            'sumativa1_a' => $this->sumativa1_a,
            'sumativa2_a' => $this->sumativa2_a,
            'sumativa3_a' => $this->sumativa3_a,
            'nota_a' => $this->nota_a,
            'sumativa1_b' => $this->sumativa1_b,
            'sumativa2_b' => $this->sumativa2_b,
            'sumativa3_b' => $this->sumativa3_b,
            'nota_b' => $this->nota_b,
            'sumativa1_c' => $this->sumativa1_c,
            'sumativa2_c' => $this->sumativa2_c,
            'sumativa3_c' => $this->sumativa3_c,
            'nota_c' => $this->nota_c,
            'sumativa1_d' => $this->sumativa1_d,
            'sumativa2_d' => $this->sumativa2_d,
            'sumativa3_d' => $this->sumativa3_d,
            'nota_d' => $this->nota_d,
            'suma_total' => $this->suma_total,
            'final_homologado' => $this->final_homologado,
            'creado' => $this->creado,
            'usuario_crea' => $this->usuario_crea,
            'actualizado' => $this->actualizado,
            'usuario_modifica' => $this->usuario_modifica,
        ]);

        $query->andFilterWhere(['ilike', 'alumno', $this->alumno])
            ->andFilterWhere(['ilike', 'quimestre', $this->quimestre])
            ->andFilterWhere(['ilike', 'scholaris_periodo_codigo', $this->scholaris_periodo_codigo]);

        return $dataProvider;
    }
}
