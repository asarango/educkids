<?php

namespace backend\models;

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
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 'semana_id', 'materia_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 
              'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 
              'semana_id', 'momento_id', 'destreza_id'], 'integer'],
            [['descripcion', 'archivo', 'descripcion_archivo',
                'momento_detalle', 'formativa_sumativa',
                'grado_nee','observacion_nee'
            ], 'string'],
            [['inicio', 'fin', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id'], 'required'],
            [['title', 'tareas', 'videoconfecia', 'respaldo_videoconferencia','link_aula_virtual'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 10],
            [['a_peso', 'b_peso', 'c_peso', 'd_peso', 'calificado'], 'string', 'max' => 5],
            [['tipo_calificacion'], 'string', 'max' => 1],
            [['con_nee'], 'boolean'],
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
            'momento_id' => 'Momento Didáctico',
            'momento_detalle' => 'Actividad de Aprendizaje',
            'destreza_id' => 'Destreza Id',
            'formativa_sumativa' => 'Tipo Formativa o Sumativa',
            'con_nee' => 'Con NEE',
            'grado_nee' => 'Grado NEE',
            'observacion_nee' => 'Observacion NEE',
            'videoconfecia' => 'Videoconfrencia',
            'link_aula_virtual' => 'Aula Virtual',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadSeguimientos()
    {
        return $this->hasMany(ScholarisActividadSeguimiento::className(), ['actividad_id' => 'id']);
    }
    
    public function getClase(){
        return $this->hasOne(ScholarisClase::className(), ['id' => 'paralelo_id']);
    }
    
    public function getScholarisActividadIndagacionDetalles()
    {
        return $this->hasMany(ScholarisActividadIndagacionDetalle::className(), ['actividad_id' => 'id']);
    }

    
    
    public function getBloques(){
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_actividad_id']);
    }
    
    
    
    public function getSemana(){
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }
    
    public function getInsumo(){
        return $this->hasOne(ScholarisTipoActividad::className(), ['id' => 'tipo_actividad_id']);
    }
    
    public function getMomento(){
        return $this->hasOne(ScholarisMomentosAcademicos::className(), ['id' => 'momento_id']);
    }
    
    public function getDestreza(){
        return $this->hasOne(ScholarisPlanPudDetalle::className(), ['id' => 'destreza_id']);
    }
    
    public function getBloque(){
        return $this->hasOne(\backend\models\ScholarisBloqueActividad::className(), ['id' => 'bloque_actividad_id']);
    }
    
}
