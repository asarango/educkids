<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisRepPromedios;

/**
 * ScholarisRepPromediosSearch represents the model behind the search form of `backend\models\ScholarisRepPromedios`.
 */
class ScholarisRepPromediosSearch extends ScholarisRepPromedios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'usuario'], 'safe'],
            [['paralelo_id', 'alumno_id'], 'integer'],
            [['nota_promedio', 'nota_comportamiento'], 'number'],
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
    public function search($params, $paralelo, $usuario, $bloque)
    {
        
        $this->eliminaNotas($usuario, $paralelo);
        $this->insertaNotas($paralelo, $usuario, $bloque);
        
        $query = ScholarisRepPromedios::find()
                ->where([
                            'paralelo_id' => $paralelo,
                            'usuario' => $usuario                    
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
            'paralelo_id' => $this->paralelo_id,
            'alumno_id' => $this->alumno_id,
            'nota_promedio' => $this->nota_promedio,
            'nota_comportamiento' => $this->nota_comportamiento,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'usuario', $this->usuario]);

        return $dataProvider;
    }
    
    
    public function eliminaNotas($usuario, $paralelo){
        $con = Yii::$app->db;
        $query = "delete from scholaris_rep_promedios where usuario = '$usuario' and paralelo_id = $paralelo;";
        
        $con->createCommand($query)->execute();
    }


    public function insertaNotas($paralelo, $usuario, $bloque){
        $con = Yii::$app->db;
        $query = "insert into scholaris_rep_promedios(codigo, paralelo_id, alumno_id, nota_promedio, usuario)
select 	concat(student_id,current_time)
		,i.parallel_id
		,i.student_id
		,trunc(avg(r.calificacion),2) as nota
		,'$usuario'
from	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_resumen_parciales r on r.clase_id = c.id
										and r.alumno_id = i.student_id
where	i.parallel_id = $paralelo
		and inscription_state = 'M'
		and c.promedia = 1
		and r.bloque_id = $bloque
group by i.student_id, i.parallel_id
order by trunc(avg(r.calificacion),2) desc,i.student_id asc;";        
//        echo $query;
//        die();
        
        $con->createCommand($query)->execute();
    }
    
    
    
    
    
    public function searchTodos($params, $paralelo, $usuario)
    {
        
        $this->eliminaNotas($usuario, $paralelo);
        $this->insertaTodasNotas($paralelo, $usuario);
        
        $query = ScholarisRepPromedios::find()
                ->where([
                            'paralelo_id' => $paralelo,
                            'usuario' => $usuario                    
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
            'paralelo_id' => $this->paralelo_id,
            'alumno_id' => $this->alumno_id,
            'nota_promedio' => $this->nota_promedio,
            'nota_comportamiento' => $this->nota_comportamiento,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'usuario', $this->usuario]);

        return $dataProvider;
    }
    
    
    public function insertaTodasNotas($paralelo, $usuario){
        $con = Yii::$app->db;
        $query = "insert into scholaris_rep_promedios(codigo, paralelo_id, alumno_id, nota_promedio, usuario)
select 	concat(student_id,current_time)
		,i.parallel_id
		,i.student_id
		,trunc(avg(r.calificacion),2) as nota
		,'$usuario'
from	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_resumen_parciales r on r.clase_id = c.id
										and r.alumno_id = i.student_id
where	i.parallel_id = $paralelo
		and inscription_state = 'M'
		and c.promedia = 1
		and r.bloque_id = 100
group by i.student_id, i.parallel_id
order by trunc(avg(r.calificacion),2) desc,i.student_id asc;";      
            echo $query;
        die();
        
        $con->createCommand($query)->execute();
    }
}
