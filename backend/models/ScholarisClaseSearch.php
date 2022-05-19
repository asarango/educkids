<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisClase;

/**
 * ScholarisClaseSearch represents the model behind the search form of `backend\models\ScholarisClase`.
 */
class ScholarisClaseSearch extends ScholarisClase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idmateria', 'idprofesor', 'idcurso', 'paralelo_id', 'promedia', 'asignado_horario', 'todos_alumnos', 'malla_materia'], 'integer'],
            [['peso'], 'number'],
            [['periodo_scholaris', 'tipo_usu_bloque','materia_curriculo_codigo','codigo_curso_curriculo'], 'safe'],
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
        $query = ScholarisClase::find()
                //->select(['scholaris_clase.id'])
                ->innerJoin("op_course","op_course.id = scholaris_clase.idcurso")
                ->where(["op_course.x_institute" => $instituto, "periodo_scholaris" => $periodo])
                ->orderBy(['id' => SORT_DESC]);

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
            'scholaris_clase.id' => $this->id,
            'idmateria' => $this->idmateria,
            'idprofesor' => $this->idprofesor,
            'idcurso' => $this->idcurso,
            'paralelo_id' => $this->paralelo_id,
            'peso' => $this->peso,
            'promedia' => $this->promedia,
            'asignado_horario' => $this->asignado_horario,
            'todos_alumnos' => $this->todos_alumnos,
            'malla_materia' => $this->malla_materia,
        ]);

        $query->andFilterWhere(['ilike', 'periodo_scholaris', $this->periodo_scholaris])
            ->andFilterWhere(['ilike', 'tipo_usu_bloque', $this->tipo_usu_bloque])
            ->andFilterWhere(['ilike', 'codigo_curso_curriculo', $this->codigo_curso_curriculo])
            ->andFilterWhere(['ilike', 'materia_curriculo_codigo', $this->materia_curriculo_codigo]);

        return $dataProvider;
    }
}
