<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad".
 *
 * @property int $id
 * @property string $create_date Created on
 * @property string $write_date Last Updated on
 * @property int $create_uid Created by
 * @property int $write_uid Last Updated by
 * @property string $title Nombre
 * @property string $descripcion Descripción
 * @property resource $archivo Archivo
 * @property string $descripcion_archivo Descripción del Archivo
 * @property string $color Color
 * @property string $inicio Inicio
 * @property string $fin Fin
 * @property int $tipo_actividad_id Tipo de Actividad
 * @property int $bloque_actividad_id Bloque-Actividad
 * @property string $a_peso A Peso
 * @property string $b_peso B Peso
 * @property string $c_peso C Peso
 * @property string $d_peso D Peso
 * @property int $paralelo_id Paralelo
 * @property int $materia_id Materia
 * @property string $calificado calificado
 * @property string $tipo_calificacion
 * @property string $tareas
 * @property int $hora_id
 * @property int $actividad_original
 * @property int $semana_id
 *
 * @property ScholarisActividadIndagacionDetalle[] $scholarisActividadIndagacionDetalles
 * @property ScholarisActividadSeguimiento[] $scholarisActividadSeguimientos
 */
class ScholarisActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date', 'write_date', 'inicio', 'fin'], 'safe'],
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 'semana_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 'semana_id'], 'integer'],
            [['descripcion', 'archivo', 'descripcion_archivo'], 'string'],
            [['inicio', 'fin', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id','hora_id'], 'required'],
            [['title', 'tareas'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 10],
            [['a_peso', 'b_peso', 'c_peso', 'd_peso', 'calificado'], 'string', 'max' => 5],
            [['tipo_calificacion'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_date' => 'Create Date',
            'write_date' => 'Write Date',
            'create_uid' => 'Create Uid',
            'write_uid' => 'Write Uid',
            'title' => 'Title',
            'descripcion' => 'Descripcion',
            'archivo' => 'Archivo',
            'descripcion_archivo' => 'Descripcion Archivo',
            'color' => 'Color',
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'tipo_actividad_id' => 'Tipo Actividad ID',
            'bloque_actividad_id' => 'Bloque Actividad ID',
            'a_peso' => 'A Peso',
            'b_peso' => 'B Peso',
            'c_peso' => 'C Peso',
            'd_peso' => 'D Peso',
            'paralelo_id' => 'Paralelo ID',
            'materia_id' => 'Materia ID',
            'calificado' => 'Calificado',
            'tipo_calificacion' => 'Tipo Calificacion',
            'tareas' => 'Tareas',
            'hora_id' => 'Hora ID',
            'actividad_original' => 'Actividad Original',
            'semana_id' => 'Semana ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadIndagacionDetalles()
    {
        return $this->hasMany(ScholarisActividadIndagacionDetalle::className(), ['actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadSeguimientos()
    {
        return $this->hasMany(ScholarisActividadSeguimiento::className(), ['actividad_id' => 'id']);
    }
    
    public function getInsumo(){
        return $this->hasOne(\backend\models\ScholarisTipoActividad::className(), ['id' => 'tipo_actividad_id']);
    }
    
    public function getClase(){
        return $this->hasOne(\backend\models\ScholarisClase::className(), ['id' => 'paralelo_id']);
    }
    
    public function getBloque(){
        return $this->hasOne(\backend\models\ScholarisBloqueActividad::className(), ['id' => 'bloque_actividad_id']);
    }
}
