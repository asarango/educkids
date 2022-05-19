<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisPlanPca;

/**
 * ScholarisPlanPcaSearch represents the model behind the search form of `backend\models\ScholarisPlanPca`.
 */
class ScholarisPlanPcaSearch extends ScholarisPlanPca
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'malla_materia_curriculo_id', 'malla_materia_institucion_id', 'curso_curriculo_id', 'curso_institucion_id', 'nivel_educativo', 'carga_horaria_semanal', 'semanas_trabajo', 'aprendizaje_imprevistos', 'total_semanas_clase', 'total_periodos', 'revisado_por', 'aprobado_por'], 'integer'],
            [['docentes', 'paralelos', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha', 'estado'], 'safe'],
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
        $query = ScholarisPlanPca::find();

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
            'malla_materia_curriculo_id' => $this->malla_materia_curriculo_id,
            'malla_materia_institucion_id' => $this->malla_materia_institucion_id,
            'curso_curriculo_id' => $this->curso_curriculo_id,
            'curso_institucion_id' => $this->curso_institucion_id,
            'nivel_educativo' => $this->nivel_educativo,
            'carga_horaria_semanal' => $this->carga_horaria_semanal,
            'semanas_trabajo' => $this->semanas_trabajo,
            'aprendizaje_imprevistos' => $this->aprendizaje_imprevistos,
            'total_semanas_clase' => $this->total_semanas_clase,
            'total_periodos' => $this->total_periodos,
            'revisado_por' => $this->revisado_por,
            'aprobado_por' => $this->aprobado_por,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'docentes', $this->docentes])
            ->andFilterWhere(['ilike', 'paralelos', $this->paralelos])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
