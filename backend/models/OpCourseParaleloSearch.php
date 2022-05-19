<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OpCourseParalelo;
use Yii;
/**
 * OpCourseParaleloSearch represents the model behind the search form of `backend\models\OpCourseParalelo`.
 */
class OpCourseParaleloSearch extends OpCourseParalelo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['id', 'create_uid', 'x_capacidad', 'write_uid', 'course_id', 'period_id', 'institute_id', 'capacidad', 'aula'], 'integer'],
            [['id', 'create_uid', 'x_capacidad', 'write_uid', 'course_id', 'period_id', 'institute_id'], 'integer'],
            [['last_date_invoice', 'create_date', 'name', 'write_date'], 'safe'],
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
    public function search($params,$periodo,$instituto)
    {
        
        $modelPeriodoOdoo = $this->toma_periodo_odoo($instituto, $periodo);
        $periodoOdoo = $modelPeriodoOdoo['id'];
                
        $query = OpCourseParalelo::find()
                ->where(['period_id' => $periodoOdoo]);

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
            'last_date_invoice' => $this->last_date_invoice,
            'create_date' => $this->create_date,
            'x_capacidad' => $this->x_capacidad,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
            'course_id' => $this->course_id,
            'period_id' => $this->period_id,
            'institute_id' => $this->institute_id,
//            'capacidad' => $this->capacidad,
//            'aula' => $this->aula,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
    
    
    
    public function toma_periodo_odoo($instituto, $peridoScholaris){
        $con = Yii::$app->db;
        $query = "select p.id
from	scholaris_op_period_periodo_scholaris sop		
		inner join op_period p on p.id = sop.op_id
where	sop.scholaris_id = $peridoScholaris
		and p.institute = $instituto;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function toma_materias_paralelo($paralelo, $periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	c.id
                               ,mat.abreviarura as materia
                               ,m.promedia
                               ,f.last_name
                               ,f.x_first_name
                    from	op_student_inscription i
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                                    inner join scholaris_materia mat on mat.id = m.materia_id
                                    inner join op_faculty f on f.id = c.idprofesor
                                    inner join scholaris_malla_area area on area.id = m.malla_area_id
                    where	i.parallel_id = $paralelo 
                                    and c.periodo_scholaris = '$periodoCodigo'
                                    and m.tipo <> 'COMPORTAMIENTO'
                    group by c.id
                             ,mat.abreviarura
                             ,m.promedia
                             ,f.last_name
                             ,f.x_first_name
                             ,area.orden
                             ,m.orden
                    order by area.orden,m.orden,c.id asc;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function toma_materias_paralelo_comportamiento($paralelo, $periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	c.id
                               ,mat.abreviarura as materia
                               ,m.promedia
                               ,f.last_name
                               ,f.x_first_name
                    from	op_student_inscription i
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                                    inner join scholaris_materia mat on mat.id = m.materia_id
                                    inner join op_faculty f on f.id = c.idprofesor
                                    inner join scholaris_malla_area area on area.id = m.malla_area_id
                    where	i.parallel_id = $paralelo 
                                    and c.periodo_scholaris = '$periodoCodigo'
                                    and m.tipo = 'COMPORTAMIENTO'
                    group by c.id
                             ,mat.abreviarura
                             ,m.promedia
                             ,f.last_name
                             ,f.x_first_name
                             ,area.orden
                             ,m.orden
                    order by area.orden,m.orden,c.id asc;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function toma_materias_totales($campo, $operador, $clase, $valor){
        $con = Yii::$app->db;
        $query = "select count($campo) as total
from	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id		
where	g.clase_id = $clase
		and $campo $operador $valor;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
}
