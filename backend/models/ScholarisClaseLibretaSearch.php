<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisClaseLibreta;

/**
 * ScholarisClaseLibretaSearch represents the model behind the search form of `backend\models\ScholarisClaseLibreta`.
 */
class ScholarisClaseLibretaSearch extends ScholarisClaseLibreta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'grupo_id'], 'integer'],
            [['p1', 'p2', 'p3', 'pr1', 'pr180', 'ex1', 'ex120', 'q1', 'p4', 'p5', 'p6', 'pr2', 'pr280', 'ex2', 'ex220', 'q2', 'final_ano_normal', 'mejora_q1', 'mejora_q2', 'final_con_mejora', 'supletorio', 'remedial', 'gracia', 'final_total'], 'number'],
            [['estado'], 'safe'],
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
        $query = ScholarisClaseLibreta::find();

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
            'grupo_id' => $this->grupo_id,
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'pr1' => $this->pr1,
            'pr180' => $this->pr180,
            'ex1' => $this->ex1,
            'ex120' => $this->ex120,
            'q1' => $this->q1,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'p6' => $this->p6,
            'pr2' => $this->pr2,
            'pr280' => $this->pr280,
            'ex2' => $this->ex2,
            'ex220' => $this->ex220,
            'q2' => $this->q2,
            'final_ano_normal' => $this->final_ano_normal,
            'mejora_q1' => $this->mejora_q1,
            'mejora_q2' => $this->mejora_q2,
            'final_con_mejora' => $this->final_con_mejora,
            'supletorio' => $this->supletorio,
            'remedial' => $this->remedial,
            'gracia' => $this->gracia,
            'final_total' => $this->final_total,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
    
    
    public function searchClase($params, $clase)
    {
        $query = ScholarisClaseLibreta::find()
                ->innerJoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
                ->innerJoin("op_student","op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where(["scholaris_grupo_alumno_clase.clase_id" => $clase])
                ->orderBy("op_student.last_name","op_student.first_name");

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
            'grupo_id' => $this->grupo_id,
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'pr1' => $this->pr1,
            'pr180' => $this->pr180,
            'ex1' => $this->ex1,
            'ex120' => $this->ex120,
            'q1' => $this->q1,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'p6' => $this->p6,
            'pr2' => $this->pr2,
            'pr280' => $this->pr280,
            'ex2' => $this->ex2,
            'ex220' => $this->ex220,
            'q2' => $this->q2,
            'final_ano_normal' => $this->final_ano_normal,
            'mejora_q1' => $this->mejora_q1,
            'mejora_q2' => $this->mejora_q2,
            'final_con_mejora' => $this->final_con_mejora,
            'supletorio' => $this->supletorio,
            'remedial' => $this->remedial,
            'gracia' => $this->gracia,
            'final_total' => $this->final_total,
        ]);

        $query->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
