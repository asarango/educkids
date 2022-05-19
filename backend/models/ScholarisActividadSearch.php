<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisActividad;

/**
 * ScholarisActividadSearch represents the model behind the search form of `backend\models\ScholarisActividad`.
 */
class ScholarisActividadSearch extends ScholarisActividad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 
                'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 
                'semana_id','momento_id', 'destreza_id'], 
            'integer'],
            [['create_date', 'write_date', 'title', 'descripcion', 'archivo', 
                'descripcion_archivo', 'color', 'inicio', 'fin', 'a_peso', 'b_peso', 
                'c_peso', 'd_peso', 'calificado', 'tipo_calificacion', 
                'tareas', 'momento_detalle', 'formativa_sumativa',
                'con_nee','grado_nee','observacion_nee','respaldo_videoconferencia', 'link_aula_virtual'], 
            'safe'],
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
        $query = ScholarisActividad::find();

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
            'create_date' => $this->create_date,
            'write_date' => $this->write_date,
            'create_uid' => $this->create_uid,
            'write_uid' => $this->write_uid,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'tipo_actividad_id' => $this->tipo_actividad_id,
            'bloque_actividad_id' => $this->bloque_actividad_id,
            'paralelo_id' => $this->paralelo_id,
            'materia_id' => $this->materia_id,
            'hora_id' => $this->hora_id,
            'actividad_original' => $this->actividad_original,
            'semana_id' => $this->semana_id,
            'momento_id' => $this->momento_id,
            'destreza_id' => $this->destreza_id,
            'con_nee' => $this->con_nee,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'archivo', $this->archivo])
            ->andFilterWhere(['ilike', 'descripcion_archivo', $this->descripcion_archivo])
            ->andFilterWhere(['ilike', 'color', $this->color])
            ->andFilterWhere(['ilike', 'a_peso', $this->a_peso])
            ->andFilterWhere(['ilike', 'b_peso', $this->b_peso])
            ->andFilterWhere(['ilike', 'c_peso', $this->c_peso])
            ->andFilterWhere(['ilike', 'd_peso', $this->d_peso])
            ->andFilterWhere(['ilike', 'calificado', $this->calificado])
            ->andFilterWhere(['ilike', 'tipo_calificacion', $this->tipo_calificacion])
            ->andFilterWhere(['ilike', 'momento_detalle', $this->momento_detalle])
            ->andFilterWhere(['ilike', 'formativa_sumativa', $this->formativa_sumativa])
            ->andFilterWhere(['ilike', 'grado_nee', $this->grado_nee])
            ->andFilterWhere(['ilike', 'observacion_nee', $this->observacion_nee])
            ->andFilterWhere(['ilike', 'videoconfecia', $this->videoconfecia])
            ->andFilterWhere(['ilike', 'respaldo_videoconferencia', $this->respaldo_videoconferencia])
            ->andFilterWhere(['ilike', 'link_aula_virtual', $this->link_aula_virtual])
            ->andFilterWhere(['ilike', 'tareas', $this->tareas]);

        return $dataProvider;
    }
    
    
    public function porHijo($params, $alumno, $periodo)
    {
        $query = ScholarisActividad::find()
                ->innerJoin("scholaris_clase","scholaris_clase.id = scholaris_actividad.paralelo_id")
                ->innerjoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.clase_id = scholaris_clase.id")
                ->innerjoin("scholaris_bloque_actividad", "scholaris_bloque_actividad.id = scholaris_actividad.bloque_actividad_id")
                ->leftJoin("scholaris_calificaciones", "scholaris_calificaciones.idactividad = scholaris_actividad.id and scholaris_calificaciones.idalumno = scholaris_grupo_alumno_clase.estudiante_id")
                ->innerJoin("scholaris_tipo_actividad", "scholaris_tipo_actividad.id = scholaris_actividad.tipo_actividad_id")
                ->innerJoin("scholaris_materia", "scholaris_materia.id = scholaris_clase.idmateria")
                ->where([
                            "scholaris_clase.periodo_scholaris" => $periodo,
                            "scholaris_grupo_alumno_clase.estudiante_id" => $alumno 
                        ])
                ;

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
            'create_date' => $this->create_date,
            'write_date' => $this->write_date,
            'create_uid' => $this->create_uid,
            'write_uid' => $this->write_uid,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'tipo_actividad_id' => $this->tipo_actividad_id,
            'bloque_actividad_id' => $this->bloque_actividad_id,
            'paralelo_id' => $this->paralelo_id,
            'materia_id' => $this->materia_id,
            'hora_id' => $this->hora_id,
            'actividad_original' => $this->actividad_original,
            'semana_id' => $this->semana_id,
            'destreza_id' => $this->destreza_id,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'archivo', $this->archivo])
            ->andFilterWhere(['ilike', 'descripcion_archivo', $this->descripcion_archivo])
            ->andFilterWhere(['ilike', 'color', $this->color])
            ->andFilterWhere(['ilike', 'a_peso', $this->a_peso])
            ->andFilterWhere(['ilike', 'b_peso', $this->b_peso])
            ->andFilterWhere(['ilike', 'c_peso', $this->c_peso])
            ->andFilterWhere(['ilike', 'd_peso', $this->d_peso])
            ->andFilterWhere(['ilike', 'calificado', $this->calificado])
            ->andFilterWhere(['ilike', 'tipo_calificacion', $this->tipo_calificacion])
            ->andFilterWhere(['ilike', 'tareas', $this->tareas]);

        return $dataProvider;
    }
    
    
    public function porDestreza($params,$destrezaId)
    {
        $query = ScholarisActividad::find()
                ->where(['destreza_id' => $destrezaId]);

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
            'create_date' => $this->create_date,
            'write_date' => $this->write_date,
            'create_uid' => $this->create_uid,
            'write_uid' => $this->write_uid,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'tipo_actividad_id' => $this->tipo_actividad_id,
            'bloque_actividad_id' => $this->bloque_actividad_id,
            'paralelo_id' => $this->paralelo_id,
            'materia_id' => $this->materia_id,
            'hora_id' => $this->hora_id,
            'actividad_original' => $this->actividad_original,
            'semana_id' => $this->semana_id,
            'momento_id' => $this->momento_id,
            'destreza_id' => $this->destreza_id,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'archivo', $this->archivo])
            ->andFilterWhere(['ilike', 'descripcion_archivo', $this->descripcion_archivo])
            ->andFilterWhere(['ilike', 'color', $this->color])
            ->andFilterWhere(['ilike', 'a_peso', $this->a_peso])
            ->andFilterWhere(['ilike', 'b_peso', $this->b_peso])
            ->andFilterWhere(['ilike', 'c_peso', $this->c_peso])
            ->andFilterWhere(['ilike', 'd_peso', $this->d_peso])
            ->andFilterWhere(['ilike', 'calificado', $this->calificado])
            ->andFilterWhere(['ilike', 'tipo_calificacion', $this->tipo_calificacion])
            ->andFilterWhere(['ilike', 'momento_detalle', $this->momento_detalle])
            ->andFilterWhere(['ilike', 'formativa_sumativa', $this->formativa_sumativa])
            ->andFilterWhere(['ilike', 'tareas', $this->tareas]);

        return $dataProvider;
    }
    
}
