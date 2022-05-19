<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OpPsychologicalAttention;

/**
 * OpPsychologicalAttentionSearch represents the model behind the search form of `backend\models\OpPsychologicalAttention`.
 */
class OpPsychologicalAttentionSearch extends OpPsychologicalAttention
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'attended_faculty_id', 'departament_id', 'course_id', 'create_uid', 'employee_id', 'external_derivation_id', 'student_id', 'violence_modality_id', 'attention_type_id', 'violence_type_id', 'violence_reason_id', 'attended_student_id', 'attended_parent_id', 'write_uid', 'special_need_id', 'substance_use_id', 'parallel_id'], 'integer'],
            [['create_date', 'detail', 'subject', 'agreements', 'state', 'write_date', 'date', 'persona_lidera'], 'safe'],
            [['special_attention'], 'boolean'],
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
    public function search($params, $usuario, $consultarTodos)
    {
        
        if($consultarTodos == 1){
            $query = OpPsychologicalAttention::find();
        }else{
            $query = OpPsychologicalAttention::find()
                ->innerJoin("hr_employee emp", "emp.id = op_psychological_attention.employee_id")
                ->innerJoin("resource_resource rr","rr.id = emp.resource_id")
                ->innerJoin("res_users u","u.id = rr.user_id")
                ->where(['u.login' => $usuario])
                ;
        }
        
        

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
            'op_psychological_attention.id' => $this->id,
            'attended_faculty_id' => $this->attended_faculty_id,
            'create_date' => $this->create_date,
            'departament_id' => $this->departament_id,
            'course_id' => $this->course_id,
            'op_psychological_attention.create_uid' => $this->create_uid,
            'employee_id' => $this->employee_id,
            'external_derivation_id' => $this->external_derivation_id,
            'student_id' => $this->student_id,
            'violence_modality_id' => $this->violence_modality_id,
            'attention_type_id' => $this->attention_type_id,
            'violence_type_id' => $this->violence_type_id,
            'violence_reason_id' => $this->violence_reason_id,
            'attended_student_id' => $this->attended_student_id,
            'attended_parent_id' => $this->attended_parent_id,
            'write_date' => $this->write_date,
            'date' => $this->date,
            'write_uid' => $this->write_uid,
            'special_need_id' => $this->special_need_id,
            'substance_use_id' => $this->substance_use_id,
            'parallel_id' => $this->parallel_id,
            'special_attention' => $this->special_attention,
        ]);

//        $query->andFilterWhere(['between', 'date', $this->date, $this->date]);
        
        $query->andFilterWhere(['ilike', 'detail', $this->detail])
            ->andFilterWhere(['ilike', 'subject', $this->subject])
            ->andFilterWhere(['ilike', 'agreements', $this->agreements])
            ->andFilterWhere(['ilike', 'state', $this->state])
            ->andFilterWhere(['ilike', 'persona_lidera', $this->persona_lidera]);

        return $dataProvider;
    }
}
