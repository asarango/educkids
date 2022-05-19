<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OpCourse;
use Yii;

/**
 * OpCourseSearch represents the model behind the search form of `backend\models\OpCourse`.
 */
class OpCourseSearch extends OpCourse {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'create_uid', 'write_uid', 'parent_id', 'x_template_id', 'x_institute', 'orden', 'level_id', 'section'], 'integer'],
            [['code', 'create_date', 'name', 'evaluation_type', 'write_date'], 'safe'],
            [['x_capacidad'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = OpCourse::find();

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
            'parent_id' => $this->parent_id,
            'write_date' => $this->write_date,
            'x_template_id' => $this->x_template_id,
            'x_capacidad' => $this->x_capacidad,
            'x_institute' => $this->x_institute,
            'section_moved1' => $this->section_moved1,
            'orden' => $this->orden,
            'level_id' => $this->level_id,
            'section_moved3' => $this->section_moved3,
            'section_moved5' => $this->section_moved5,
            'section_moved7' => $this->section_moved7,
            'section' => $this->section,
            'period_id' => $this->period_id,
        ]);

        $query->andFilterWhere(['ilike', 'code', $this->code])
                ->andFilterWhere(['ilike', 'name', $this->name])
                ->andFilterWhere(['ilike', 'evaluation_type', $this->evaluation_type])
                ->andFilterWhere(['ilike', 'section_moved0', $this->section_moved0])
                ->andFilterWhere(['ilike', 'abreviatura', $this->abreviatura])
                ->andFilterWhere(['ilike', 'section_moved2', $this->section_moved2])
                ->andFilterWhere(['ilike', 'section_moved4', $this->section_moved4])
                ->andFilterWhere(['ilike', 'section_moved6', $this->section_moved6])
                ->andFilterWhere(['ilike', 'section_moved8', $this->section_moved8]);

        return $dataProvider;
    }
    
    public function searchInstituto($params, $periodo) {
        $query = OpCourse::find()
                ->innerJoin("op_section","op_section.id = op_course.section")
                ->where(["op_section.period_id" => $periodo]);

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
            'parent_id' => $this->parent_id,
            'write_date' => $this->write_date,
            'x_template_id' => $this->x_template_id,
            'x_capacidad' => $this->x_capacidad,
            'x_institute' => $this->x_institute,            
            'orden' => $this->orden,
            'level_id' => $this->level_id,
            'section' => $this->section,
        ]);

        $query->andFilterWhere(['ilike', 'code', $this->code])
                ->andFilterWhere(['ilike', 'name', $this->name])
                ->andFilterWhere(['ilike', 'evaluation_type', $this->evaluation_type]);

        return $dataProvider;
    }

    public function datosCuadrosParciales($campo, $periodo) {
        
        $modelMinimo = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        
        
        $con = \Yii::$app->db;
        $query = "select cur.name as curso
		,count(l.p1) as total
from	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
		inner join op_course cur on cur.id = c.idcurso
where	c.periodo_scholaris = '$periodo'
		and $campo < $modelMinimo->valor
group by cur.name,cur.orden
order by cur.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
