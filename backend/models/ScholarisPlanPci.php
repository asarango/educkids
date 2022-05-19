<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pci".
 *
 * @property int $id
 * @property int $subnivel_id
 * @property int $materia_curriculo_id
 * @property string $materia_curriculo_nombre
 * @property string $materia_curriculo_color
 * @property string $tipo_materia
 *
 * @property ScholarisPlanPciEvaluacion[] $scholarisPlanPciEvaluacions
 */
class ScholarisPlanPci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subnivel_id', 'materia_curriculo_id', 'materia_curriculo_nombre', 'materia_curriculo_color', 'tipo_materia'], 'required'],
            [['subnivel_id', 'materia_curriculo_id'], 'default', 'value' => null],
            [['subnivel_id', 'materia_curriculo_id','periodo_id'], 'integer'],
            [['materia_curriculo_nombre'], 'string', 'max' => 150],
            [['materia_curriculo_color', 'tipo_materia'], 'string', 'max' => 30],
            [['subnivel_codigo', 'materia_curriculo_codigo'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subnivel_id' => 'Subnivel ID',
            'materia_curriculo_id' => 'Materia Curriculo ID',
            'materia_curriculo_nombre' => 'Materia Curriculo Nombre',
            'materia_curriculo_color' => 'Materia Curriculo Color',
            'tipo_materia' => 'Tipo Materia',
            'subnivel_codigo' => 'Codigo Subnivel',
            'materia_curriculo_codigo' => 'Codigo materia curriculo',
            'periodo_id' => 'Periodo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPlanPciEvaluacions()
    {
        return $this->hasMany(ScholarisPlanPciEvaluacion::className(), ['pci_id' => 'id']);
    }
    
    public function getPeriodo(){
        return $this->hasOne(ScholarisPeriodo::className(),['id' => 'periodo_id']);
    }
    
    
    /**
     * Sentencias para planificaciones
     */
    public function consulta_materias_curriculo($subnivel){
        $con = Yii::$app->db1;
        $query = "select a.subnivel_id
		,m.id
		,mat.nombre
		,mat.color
		,mat.tipo
		,m.tipo_materia
                ,s.codigo
                ,mat.codigo as materia_codigo
from	gen_malla_materia m
		inner join gen_malla_area a on a.id = m.malla_area_id
		inner join gen_asignaturas mat on mat.id = m.materia_id 
                inner join gen_subnivel s on s.id = a.subnivel_id 
where	a.subnivel_id = $subnivel "
                . "and mat.tipo_asignatura = 'TRONCO';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function consulta_materias_curriculo_optativas($subnivel){
        $con = Yii::$app->db1;
        $query = "select a.subnivel_id
		,m.id
		,mat.nombre
		,mat.color
		,mat.tipo
		,m.tipo_materia
                ,s.codigo
                ,mat.codigo as materia_codigo
from	gen_malla_materia m
		inner join gen_malla_area a on a.id = m.malla_area_id
		inner join gen_asignaturas mat on mat.id = m.materia_id 
                inner join gen_subnivel s on s.id = a.subnivel_id 
where	a.subnivel_id = $subnivel "
                . "and mat.tipo_asignatura = 'OPTATIVA';";
        
//        echo $query;
//        die();
//        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
}
