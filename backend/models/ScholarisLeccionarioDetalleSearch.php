<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisLeccionarioDetalle;
use Yii;

/**
 * ScholarisLeccionarioDetalleSearch represents the model behind the search form of `backend\models\ScholarisLeccionarioDetalle`.
 */
class ScholarisLeccionarioDetalleSearch extends ScholarisLeccionarioDetalle {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'paralelo_id', 'clase_id', 'hora_id', 'asistencia_id'], 'integer'],
            [['fecha', 'desde', 'hasta', 'atraso', 'estado','motivio_justificacion_falta'], 'safe'],
            [['falta','justifica_falta','justifica_atraso'], 'boolean'],
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
    public function search($params, $paralelo, $fecha) {
        $query = ScholarisLeccionarioDetalle::find()
                ->where([
                    'paralelo_id' => $paralelo,
                    'fecha' => $fecha
                ])
                ->orderBy("desde");

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
            'paralelo_id' => $this->paralelo_id,
            'fecha' => $this->fecha,
            'clase_id' => $this->clase_id,
            'hora_id' => $this->hora_id,
            'asistencia_id' => $this->asistencia_id,
            'falta' => $this->falta,
            'justifica_falta' => $this->justifica_falta,
            'justifica_atraso' => $this->justifica_atraso,
        ]);

        $query->andFilterWhere(['ilike', 'desde', $this->desde])
                ->andFilterWhere(['ilike', 'hasta', $this->hasta])
                ->andFilterWhere(['ilike', 'atraso', $this->atraso])
                ->andFilterWhere(['ilike', 'motivio_justificacion_falta', $this->motivio_justificacion_falta])
                ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }

    public function registrar_clases($paralelo, $fecha) {
        $con = \Yii::$app->db;
        $query = "insert into scholaris_leccionario_detalle(paralelo_id, fecha, clase_id, hora_id, desde, hasta, asistencia_id, falta, estado) "
                . "select $paralelo,'$fecha'
                                ,c.id as clase_id
                                ,hor.id as hora_id
                                ,hor.desde
                                ,hor.hasta
                                ,a.id as asitencia_id
                                ,case
                                        when a.id is not null then false
                                        else true
                                end as falta
                                ,'pendiente' as estado
                from	scholaris_horariov2_dia dia
                                left join scholaris_horariov2_detalle d on d.dia_id = dia.id
                                left join scholaris_horariov2_horario h on h.detalle_id = d.id
                                left join scholaris_clase c on c.id = h.clase_id
                                left join scholaris_horariov2_hora hor on hor.id = d.hora_id
                                left join scholaris_asistencia_profesor a on a.clase_id = c.id
                                                                        and a.fecha = '$fecha'
                                                                        and a.hora_id = hor.id
                where	dia.numero = extract(dow from '$fecha'::date)
                                and c.paralelo_id = $paralelo
                                and c.id not in (select clase_id from scholaris_leccionario_detalle where paralelo_id = $paralelo and fecha = '$fecha')
                order by hor.numero;";
        $con->createCommand($query)->execute();
    }

    public function restar_horas($mayor, $menor) {
        $horaInicio = new \DateTime($mayor);
        $horaTermino = new \DateTime($menor);

        $interval = $horaInicio->diff($horaTermino);
        
        return $interval->format('%H horas %i minutos %s seconds');
    }
    
    
    
    
    
    public function toma_novedades($paralelo, $fecha) {
        $con = \Yii::$app->db;
        $query = "select s.id as alumno_id
                                    ,g.id as grupo_id
                                    ,s.last_name		
                                    ,s.first_name
                                    ,s.middle_name
                                    ,count(n.id) as total
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
                                    inner join scholaris_clase c on c.id = a.clase_id
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
                                    inner join op_student s on s.id = g.estudiante_id
                    where	c.paralelo_id = $paralelo
                                    and a.fecha = '$fecha'
                    group by s.id
                                    ,g.id
                                    ,s.last_name		
                                    ,s.first_name
                                    ,s.middle_name
                    order by s.last_name		
                                    ,s.first_name
                                    ,s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
        
    }
    
    

}
